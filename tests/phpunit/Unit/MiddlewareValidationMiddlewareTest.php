<?php
namespace App\Tests\Unit;

use App\Http\Middleware\ValidationMiddleware;
use App\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class MiddlewareValidationMiddlewareTest
 *
 * @package App\Tests\Unit
 */
class MiddlewareValidationMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testValidationMiddlewareSuccess()
    {
        $request = Request::create('/', 'POST', ['email' => 'test@test.com']);
        $middleware = new ValidationMiddleware();
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals('OK', $result);
    }
}
