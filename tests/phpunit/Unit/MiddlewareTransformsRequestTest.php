<?php
namespace App\Tests\Unit;

use App\Http\Middleware\TransformsRequest;
use App\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class MiddlewareTransformsRequestTest
 *
 * @package App\Tests\Unit
 */
class MiddlewareTransformsRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testLowerAgeAndAddBeer()
    {
        $middleware = new TransformsRequest;
        $request = new Request(
            [
                'name' => 'Damian',
                'beers' => 4,
            ],
            ['age' => 28]
        );
        $middleware->handle($request, function (Request $request) {
            $this->assertEquals('Damian', $request->get('name'));
            $this->assertEquals(28, $request->get('age'));
            $this->assertEquals(4, $request->get('beers'));
        });
    }
    public function testAjaxLowerAgeAndAddBeer()
    {
        $middleware = new TransformsRequest;
        $request = new Request(
            [
                'name' => 'Damian',
                'beers' => 4,
                'test' => ['a' => 16]
            ],
            [],
            [],
            [],
            [],
            ['CONTENT_TYPE' => '/json'],
            json_encode(['age' => 28])
        );
        $middleware->handle($request, function (Request $request) {
            $this->assertEquals('Damian', $request->input('name'));
            $this->assertEquals(28, $request->input('age'));
            $this->assertEquals(4, $request->input('beers'));
        });
    }
}
