<?php
namespace App\Tests\Unit;

use App\Http\Middleware\TrimStringsMiddleware;
use App\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class MiddlewareTrimStringsMiddlewareTest
 *
 * @package App\Tests\Unit
 */
class MiddlewareTrimStringsMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTrimStringsMiddlewareSuccess()
    {
        $request = Request::create('/', 'POST', ['a' => '  b   ']);
        $middleware = new TrimStringsMiddleware();
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals('OK', $result);
        $this->assertEquals('b', $request->get('a'));
    }

    public function testTrimStringsMiddlewareIgnore()
    {
        $request = Request::create('/', 'POST', ['password' => '  b   ']);
        $middleware = new TrimStringsMiddleware();
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals('OK', $result);
        $this->assertEquals('  b   ', $request->get('password'));
    }
}
