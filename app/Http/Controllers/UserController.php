<?php

namespace App\Http\Controllers;

use App\Constants\CacheUserControllerConstants;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserController
 * @package App\Http\Controllers
 *
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"user"},
     *     summary="Get all users",
     *     description="Returns users list. Admin only",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserModel")
     *         ),
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * ),
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function get()
    {
        $this->authorize('get', UserModel::class);
        $users = Cache::remember(CacheUserControllerConstants::GET, 5, function () {
            return UserModel::getAll();
        });
        return $this->respondSuccess($users);
    }

    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     summary="Get the user",
     *     description="Returns single user with given ID. Admin only",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user to return",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * )
     */
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $id)
    {
        $user = Cache::remember(CacheUserControllerConstants::SHOW.'.'.$id, 30, function () use ($id) {
            if (!$user = UserModel::findBy('id', $id)->first()) {
                return null;
            }

            return $user;
        });
        if (empty($user)) {
            return $this->respondError(201, 'NOT_FOUND', 404);
        }

        $this->authorize('show', [$user]);

        return $this->respondSuccess($user);
    }

    /**
     * @OA\Post(
     *     path="/api/user",
     *     tags={"user"},
     *     summary="Create user",
     *     description="Returns created user. Admin only",
     *     operationId="post",
     *     @OA\RequestBody(
     *         description="Create user object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserModel")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=412,description="Email exists"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * )
     */
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', UserModel::class);

        if ($user = UserModel::findWhere(['email' => $request->get('email')])->first()) {
            return $this->respondError(301, 'EMAIL_EXIST', 412);
        }

        $user = UserModel::create($request->all());
        $password = (!empty($request->get('password'))) ? $request->get('password') : str_random(10);
        $user->password = Hash::make($password);
        $user->activation_code = str_random(15);
        $user->api_token = create_authorization_token($user);
        $user->save();

        Cache::put(CacheUserControllerConstants::SHOW.'.'.$user->id, $user, 30);
        Cache::forget(CacheUserControllerConstants::GET);

        return $this->respondSuccess($user);
    }

    /**
     * @OA\Put(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     summary="Update user",
     *     description="Returns updated user. Admin only",
     *     operationId="put",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\RequestBody(
     *         description="Update user object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserModel")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=404,description="Not found"),
     *     @OA\Response(response=422,description="Invalid input"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * )
     */
    /**
     * @param int                      $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(int $id, Request $request)
    {
        $this->authorize('update', [UserModel::class, $id]);

        if (!$user = UserModel::findWhere(['id' => $id])->first()) {
            return $this->respondError(401, 'NOT_FOUND', 404);
        }

        $user->fill($request->all());
        if (!empty($request->get('password'))) {
            $user->password = Hash::make($request->get('password'));
        }
        $user->api_token = create_authorization_token($user);
        $user->activation_code = str_random(15);
        $user->save();

        Cache::put(CacheUserControllerConstants::SHOW.'.'.$user->id, $user, 30);
        Cache::forget(CacheUserControllerConstants::GET);

        return $this->respondSuccess($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     summary="Delete user",
     *     description="Admin only",
     *     operationId="delete",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of user",
     *         required=true,
     *         @OA\Schema(type="integer",format="int64")
     *     ),
     *     @OA\Response(response=200,description="successful operation"),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=404,description="User not found"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * )
     */
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(int $id)
    {
        $this->authorize('delete', [UserModel::class, $id]);

        if (!UserModel::findWhere(['id' => $id])->first()) {
            return $this->respondError(401, 'NOT_FOUND', 404);
        }

        UserModel::deleteWhere(['id' => $id]);

        Cache::forget(CacheUserControllerConstants::SHOW.'.'.$id);
        Cache::forget(CacheUserControllerConstants::GET);

        return $this->respondSuccess();
    }

    /**
     * @OA\Get(
     *     path="/api/user/me",
     *     tags={"user"},
     *     summary="Get current user",
     *     description="Returns authenticated user.",
     *     operationId="me",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/UserModel"),
     *     ),
     *     @OA\Response(response=403,description="Unauthorized"),
     *     @OA\Response(response=500,description="Server error"),
     *     security={ {"Authorization": {}} }
     * )
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authUser()
    {
        $this->authorize('show', [Auth::user()]);
        return $this->respondSuccess(Auth::user());
    }
}
