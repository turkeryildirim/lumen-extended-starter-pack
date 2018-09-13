<?php
namespace App\Tests\Unit;

use App\Http\Middleware\ConvertEmptyStringsToNullMiddleware;
use App\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class MiddlewareConvertEmptyStringsToNullMiddlewareTest
 *
 * @package App\Tests\Unit
 */
class MiddlewareConvertEmptyStringsToNullMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConvertEmptyStringsToNullMiddleware()
    {
        $request = Request::create('/', 'POST', ['a' => '']);
        $middleware = new ConvertEmptyStringsToNullMiddleware();
        $result = $middleware->handle($request, function () {
            return 'OK';
        });

        $this->assertEquals('OK', $result);
        $this->assertNull($request->get('a'));
    }
}
