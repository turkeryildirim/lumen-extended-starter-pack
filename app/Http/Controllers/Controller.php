<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="My API",
 *     version="1.0.0",
 *     title="My API Docs",
 *     termsOfService="http://localhost.io/terms/",
 *     @OA\Contact(
 *         email="apiteam@localhost.io"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
 * @OA\ExternalDocumentation(
 *     description="Find out more about My API",
 *     url="http://localhost.io"
 * )
 */

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    /**
     * @param mixed|null $data
     * @param callable|string|\Flugg\Responder\Transformers\Transformer|null $transformer
     * @param string|null $resourceKey
     * @param int|null $status
     * @param array|null $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess(
        $data = null,
        $transformer = null,
        string $resourceKey = null,
        $status = null,
        array $headers = []
    ) {
        return responder()->success($data, $transformer, $resourceKey)
            ->respond($status, $headers);
    }

    /**
     * @param mixed|null $errorCode
     * @param string|null $message
     * @param int|null $status
     * @param array|null $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondError(
        $errorCode = null,
        string $message = null,
        $status = null,
        array $headers = []
    ) {
        return responder()->error($errorCode, $message)
            ->respond($status, $headers);
    }
}
