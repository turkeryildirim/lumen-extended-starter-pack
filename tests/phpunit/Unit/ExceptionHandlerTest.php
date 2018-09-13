<?php
namespace App\Tests\Unit;

use App\Constants\UserStatusConstant;
use App\Exceptions\Handler;
use App\Services\EmailService;
use App\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Intervention\Image\Exception\ImageException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExceptionHandlerTest
 *
 * @package App\Tests\Unit
 */
class ExceptionHandlerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testRenderMethod()
    {
        $middleware = new Handler();
        $request = Request::create('/', 'POST', ['a' => '']);
        $response = Response::create('aa', 500);
        $exception = new HttpResponseException($response);
        $result = $middleware->render($request, $exception);

        $this->assertEquals(500, $result->getStatusCode());

        $exception = new MethodNotAllowedHttpException(['GET']);
        $result = $middleware->render($request, $exception);

        $this->assertEquals(405, $result->getStatusCode());

        $exception = new NotFoundHttpException();
        $result = $middleware->render($request, $exception);

        $this->assertEquals(404, $result->getStatusCode());

        $exception = new ModelNotFoundException();
        $result = $middleware->render($request, $exception);

        $this->assertEquals(400, $result->getStatusCode());

        $exception = new AuthorizationException();
        $result = $middleware->render($request, $exception);

        $this->assertEquals(403, $result->getStatusCode());

        $exception = new ImageException();
        $result = $middleware->render($request, $exception);

        $this->assertEquals(500, $result->getStatusCode());
    }
}
