<?php

namespace App\Http\Controllers;

use App\Constants\UserRoleConstant;
use App\Events\AuthenticationLoginEvent;
use App\Events\AuthenticationRegisterEvent;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

/**
 * Class AuthenticationController
 *
 * @package App\Http\Controllers
 */
class AuthenticationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"auth"},
     *     summary="Login",
     *     description="Gets user API token.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Returns API token with given user email and password",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="email",type="string",format="email"),
     *              @OA\Property(property="password",type="string")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="authorization",type="string")
     *         ),
     *     ),
     *     @OA\Response(response=401,description="Access denied"),
     *     @OA\Response(response=403,description="Inactive account"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!$user = UserModel::findWhere($request->only("email"))->first()) {
            return $this->respondError(101, 'WRONG_EMAIL', 404);
        }
        if (!Hash::check($request->get("password"), $user->password)) {
            return $this->respondError(102, 'WRONG_PASSWORD', 401);
        }
        if ($user->status !== true) {
            return $this->respondError(103, 'INACTIVE_ACCOUNT', 403);
        }

        $user->last_login_date = Carbon::now();
        $user->api_token = create_authorization_token($user);
        $user->save();

        Event::dispatch(new AuthenticationLoginEvent($user));

        return $this->respondSuccess(['authorization' => $user->api_token]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/forgot-password",
     *     tags={"auth"},
     *     summary="Forgot password",
     *     description="Sends an email to reset user password.",
     *     operationId="forgotPassword",
     *     @OA\RequestBody(
     *         description="user email",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="email",type="string",format="email"),
     *         ),
     *     ),
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\Response(response=403,description="Inactive account"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function forgotPassword(Request $request)
    {
        if (!$user = UserModel::findWhere($request->only("email"))->first()) {
            return $this->respondError(201, 'WRONG_EMAIL', 404);
        }
        if ($user->status !== true) {
            return $this->respondError(202, 'INACTIVE_ACCOUNT', 403);
        }

        $user->activation_code = Uuid::uuid5(Uuid::NAMESPACE_OID, $user->email . time());
        $user->save();

        /**
         * @todo: event required to send reset email
         */

        return $this->respondSuccess();
    }

    /**
     * @OA\Get(
     *     path="/api/auth/reset-password",
     *     tags={"auth"},
     *     summary="Reset password",
     *     description="Resets user password and sends new password to user.",
     *     operationId="resetPassword",
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         description="Unique key to reset password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email of user",
     *         required=true,
     *         @OA\Schema(type="string",format="email")
     *     ),
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\Response(response=401,description="Access denied"),
     *     @OA\Response(response=403,description="Inactive account"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function resetPassword(Request $request)
    {
        if (!$user = UserModel::findWhere($request->only("email"))->first()) {
            return $this->respondError(301, 'WRONG_EMAIL', 404);
        }
        if ($user->activation_code != $request->get("key")) {
            return $this->respondError(302, 'INVALID_KEY', 401);
        }
        if ($user->status !== true) {
            return $this->respondError(303, 'INACTIVE_ACCOUNT', 403);
        }

        $password = str_random(8);
        $user->password = Hash::make($password);
        $user->activation_code = Uuid::uuid5(Uuid::NAMESPACE_OID, $user->email . time());
        $user->save();

        /**
         * @todo: event required to send new password email
         */

        return $this->respondSuccess();
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"auth"},
     *     summary="Register user",
     *     description="Returns registered user.",
     *     operationId="register",
     *     @OA\RequestBody(
     *         description="User details will be used to create. Email is mandatory.",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="email",type="string",format="email"),
     *              @OA\Property(property="password",type="string"),
     *              @OA\Property(property="first_name",type="string"),
     *              @OA\Property(property="last_name",type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
     *     ),
     *     @OA\Response(response=412,description="Email exists"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function register(Request $request)
    {
        if ($user = UserModel::findWhere($request->only('email'))->first()) {
            return $this->respondError(401, 'EMAIL_EXIST', 412);
        }

        $user = new UserModel();
        $user->fill($request->only(['email', 'password', 'first_name', 'last_name']));
        $password = ($request->has('password')) ? $request->get('password') : str_random(8);
        $user->password = Hash::make($password);
        $user->api_token = create_authorization_token($user);
        $user->role = UserRoleConstant::USER;
        $user->status = false;
        $user->save();

        Event::dispatch(new AuthenticationRegisterEvent($user));

        return $this->respondSuccess($user);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/activate",
     *     tags={"auth"},
     *     summary="Activate user",
     *     description="Activates user with given key and email and send welcome email.",
     *     operationId="activate",
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         description="Unique activation key",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email of user",
     *         required=true,
     *         @OA\Schema(type="string",format="email")
     *     ),
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\Response(response=401,description="Access denied"),
     *     @OA\Response(response=403,description="Inactive account"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function activate(Request $request)
    {
        if (!$user = UserModel::findWhere($request->only("email"))->first()) {
            return $this->respondError(501, 'WRONG_EMAIL', 404);
        }
        if ($user->activation_code != $request->get("key")) {
            return $this->respondError(502, 'INVALID_KEY', 401);
        }
        if ($user->status !== true) {
            return $this->respondError(503, 'INACTIVE_ACCOUNT', 403);
        }

        $user->activation_date = Carbon::now();
        $user->activation_code = Uuid::uuid5(Uuid::NAMESPACE_OID, $user->email . time());
        $user->save();

        /**
         * @todo: event required to send activated email
         */

        return $this->respondSuccess();
    }

    /**
     * @OA\Post(
     *     path="/api/auth/resend-activation",
     *     tags={"auth"},
     *     summary="Resend activation",
     *     description="Re-sends an email to activate user.",
     *     operationId="resendActivation",
     *     @OA\RequestBody(
     *         description="user email",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="email",type="string",format="email"),
     *         ),
     *     ),
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\Response(response=401,description="Access denied"),
     *     @OA\Response(response=403,description="Inactive account"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     * )
     */
    public function resendActivation(Request $request)
    {
        if (!$user = UserModel::findWhere($request->only("email"))->first()) {
            return $this->respondError(601, 'WRONG_EMAIL', 404);
        }
        if ($user->status !== true) {
            return $this->respondError(602, 'INACTIVE_ACCOUNT', 403);
        }
        if (!empty($user->activation_date)) {
            return $this->respondError(602, 'ALREADY_ACTIVE', 401);
        }

        $user->activation_code = Uuid::uuid5(Uuid::NAMESPACE_OID, $user->email . time());
        $user->save();

        /**
         * @todo: event required to send new password email
         */

        return $this->respondSuccess();
    }
}
