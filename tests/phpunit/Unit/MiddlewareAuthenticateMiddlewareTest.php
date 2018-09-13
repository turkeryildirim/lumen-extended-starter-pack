<?php
namespace App\Tests\Unit;

use App\Http\Middleware\AuthenticateMiddleware;
use App\Tests\TestCase;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;

/**
 * Class MiddlewareAuthenticateMiddlewareTest
 *
 * @package App\Tests\Unit
 */
class MiddlewareAuthenticateMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testAuthenticateMiddleware401Code()
    {
        $request = Request::create('/', 'POST', ['a' => '']);
        $middleware = new AuthenticateMiddleware($this->app->make('Illuminate\Contracts\Auth\Factory'));
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals(401, $result->getStatusCode());
    }

    public function testAuthenticateMiddleware403Code()
    {
        $request = Request::create('/api/user/create', 'POST', ['a' => '']);
        $request->headers->set('Authorization', 'xxxxx');
        $middleware = new AuthenticateMiddleware($this->app->make('Illuminate\Contracts\Auth\Factory'));
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals(403, $result->getStatusCode());
    }

    public function testAuthenticateMiddleware200Code()
    {
        $user = new GenericUser([]);
        $this->actingAs($user);
        $request = Request::create('/api/user/create', 'POST', ['a' => '']);
        $request->headers->set('Authorization', 'xxxxx');
        $middleware = new AuthenticateMiddleware($this->app->make('Illuminate\Contracts\Auth\Factory'));
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals('OK', $result);
    }
}
