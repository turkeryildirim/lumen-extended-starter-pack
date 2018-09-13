<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * @param \Exception $e
     * @throws \Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($e instanceof HttpResponseException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $e = new HttpException($status, 'HTTP_METHOD_NOT_ALLOWED', $e);
        } elseif ($e instanceof NotFoundHttpException) {
            $status = Response::HTTP_NOT_FOUND;
            $e = new HttpException($status, 'HTTP_NOT_FOUND', $e);
        } elseif ($e instanceof ModelNotFoundException) {
            $status = Response::HTTP_BAD_REQUEST;
            $e = new HttpException($status, 'HTTP_NOT_FOUND', $e);
        } elseif ($e instanceof AuthorizationException) {
            $status = Response::HTTP_FORBIDDEN;
            $e = new HttpException($status, 'HTTP_FORBIDDEN', $e);
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $e = new HttpException($status, 'HTTP_UNPROCESSABLE_ENTITY', $e);
        } elseif ($e) {
            $e = new HttpException($status, 'HTTP_INTERNAL_SERVER_ERROR', $e);
        }

        $message = $e->getMessage();
        $code = $e->getCode();
        if ($e->getPrevious() !== null && env('APP_DEBUG')) {
            $message .= (!empty($e->getPrevious()->getMessage())) ? ' - '. $e->getPrevious()->getMessage() : '';
            $code = (!empty($e->getPrevious()->getCode())) ? $e->getPrevious()->getCode() : $code;
        }

        return response()->json([
            'success' => false,
            'status' => $status,
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ], $status);
    }
}
