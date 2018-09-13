<?php
namespace App\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Mockery;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $adminEmail;
    protected $adminPassword;
    protected $userEmail;
    protected $userPassword;

    protected $dispatchedNotifications;

    public function setUp()
    {
        parent::setUp();
/*
        if(config('database.default') == 'sqlite'){
            $db = app()->make('db');
            $db->connection()->getPdo()->exec("pragma foreign_keys=1");
        }
*/
        config(['app.env' => getenv('APP_ENV')]);
        config(['mail.driver' => getenv('MAIL_DRIVER')]);
        config(['cache.default' => getenv('CACHE_DRIVER')]);
        config(['queue.default' => getenv('QUEUE_DRIVER')]);
        config(['database.default' => getenv('DB_CONNECTION')]);

        $this->adminEmail = getenv('adminEmail');
        $this->adminPassword = getenv('adminPassword');
        $this->userEmail = getenv('userEmail');
        $this->userPassword = getenv('userPassword');

        Event::fake();
        Queue::fake();
        Notification::fake();
        Mail::fake();
    }

    /**
     * @return mixed|\Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';
        return $app;
    }

    /**
     * @param string $userRole
     * @return string|null
     */
    protected function getApiToken(string $userRole)
    {
        $user = $this->createUser($userRole);
        return $user->api_token;
    }

    /**
     * @param string $userRole
     * @return mixed
     */
    protected function createUser(string $userRole)
    {
        return factory(\App\Models\UserModel::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('123456'),
            'role' => $userRole
        ]);
    }

    /**
     * Mock the notification dispatcher so all notifications are silenced.
     *
     * @return $this
     */
    protected function withoutNotifications()
    {
        $mock = Mockery::mock(\Illuminate\Contracts\Notifications\Dispatcher::class);
        $mock->shouldReceive('send')
            ->andReturnUsing(function ($notifiable, $instance, $channels = []) {
                $this->dispatchedNotifications[] = compact(
                    'notifiable',
                    'instance',
                    'channels'
                );
            });

        $this->app->instance('events', $mock);

        return $this;
    }

    /**
     * Specify a notification that is expected to be dispatched.
     *
     * @param  mixed  $notifiable
     * @param  string $notification
     * @return $this
     */
    protected function expectsNotification($notifiable, $notification)
    {
        $this->withoutNotifications()
            ->beforeApplicationDestroyed(function () use ($notifiable, $notification) {
                foreach ($this->dispatchedNotifications as $dispatched) {
                    $notified = $dispatched['notifiable'];
                    if (($notified === $notifiable ||
                        $notified->getKey() == $notifiable->getKey()) &&
                    get_class($dispatched['instance']) === $notification
                    ) {
                        return $this;
                    }
                }
                $this->fail('The following expected notification were not dispatched: [' . $notification . ']');
            });
        return $this;
    }
}
