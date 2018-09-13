<?php
namespace App\Tests\Integration;

use App\Mail\AuthenticationLoginMail;
use App\Models\UserModel;
use App\Services\EmailService;
use App\Tests\TestCase;
use Illuminate\Support\Facades\Mail;

/**
 * Class ServiceEmailServiceTest
 *
 * @package App\Tests\Integration
 */
class ServiceEmailServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testEmailServiceErrors()
    {
        Mail::fake();
        $user = factory(UserModel::class)->create();
        $service = new EmailService();
        $mailable = new AuthenticationLoginMail($user);

        try {
            $service->send($mailable);
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'EMAIL_SERVICE_NO_EMAIL');
        }

        try {
            $service->setTo('');
            ;
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'EMAIL_SERVICE_NO_EMAIL');
        }

        try {
            $service->setCc('');
            ;
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'EMAIL_SERVICE_NO_CC');
        }

        try {
            $service->setBcc('');
            ;
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'EMAIL_SERVICE_NO_BCC');
        }
    }

    public function testSetToMethod()
    {
        Mail::fake();
        $service = new EmailService();
        $service->reset();
        $current_to = $service->getTo();
        $service->setTo('aa@bb.com');

        $this->assertTrue($current_to !== $service->getTo());
        $this->assertTrue('aa@bb.com' === $service->getTo());
    }

    public function testSetCcMethod()
    {
        Mail::fake();
        $service = new EmailService();
        $service->reset();
        $current_to = $service->getCc();
        $service->setCc('aa@bb.com');

        $this->assertTrue($current_to !== $service->getCc());
        $this->assertTrue('aa@bb.com' === $service->getCc());
    }

    public function testSetBccMethod()
    {
        Mail::fake();
        $service = new EmailService();
        $service->reset();
        $current_to = $service->getBcc();
        $service->setBcc('aa@bb.com');

        $this->assertTrue($current_to !== $service->getBcc());
        $this->assertTrue('aa@bb.com' === $service->getBcc());
    }

    public function testSendMethod()
    {
        Mail::fake();
        $user = factory(UserModel::class)->create();
        $service = new EmailService();
        $service->reset();
        $mailable = new AuthenticationLoginMail($user);

        try {
            $service->setTo($user->email)
                ->setCc('cc@aa.com')
                ->setBcc('bcc@aa.com')
                ->send($mailable);
        } catch (\Exception $e) {
        }

        Mail::assertQueued(AuthenticationLoginMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                $mail->hasCc('cc@aa.com') &&
                $mail->hasBcc('bcc@aa.com');
        });
    }

    public function testResetMethod()
    {
        $service = new EmailService();
        $service->setTo('aa@aa.com')
            ->setCc('cc@aa.com')
            ->setBcc('bcc@aa.com')
            ->reset();

        $this->assertNull($service->getTo());
        $this->assertNull($service->getCc());
        $this->assertNull($service->getBcc());
    }
}
