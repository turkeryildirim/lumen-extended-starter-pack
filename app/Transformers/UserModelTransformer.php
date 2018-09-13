<?php

namespace App\Transformers;

use App\Models\UserModel;
use Flugg\Responder\Transformers\Transformer;

/**
 * @OA\Schema(
 *     schema="UserModel",
 *     @OA\Property(property="id",type="integer"),
 *     @OA\Property(property="first_name",type="string"),
 *     @OA\Property(property="last_name",type="string"),
 *     @OA\Property(property="email",type="string",format="email"),
 *     @OA\Property(property="password",type="string",format="password"),
 *     @OA\Property(property="role",type="string",ref="#/components/schemas/UserRole"),
 *     @OA\Property(property="api_token",type="string"),
 *     @OA\Property(property="status",type="boolean",ref="#/components/schemas/UserStatus"),
 *     @OA\Property(property="created_at",type="string",format="datetime"),
 *     @OA\Property(property="updated_at",type="string",format="datetime")
 * )
 */
/**
 * Class UserModelTransformer
 *
 * @package App\Transformers
 */
class UserModelTransformer extends Transformer
{
    /**
     * @var string[]
     */
    protected $relations = [
        'userMeta' => UserMetaModelTransformer::class
    ];

    /**
     * @var array
     */
    protected $load = [];

    /**
     * @param  UserModel $userModel
     * @return array
     */
    public function transform(UserModel $userModel) : array
    {
        return [
            'id' => $userModel->id,
            'first_name' => $userModel->first_name,
            'last_name' => $userModel->last_name,
            'email' => $userModel->email,
            'role' => $userModel->role,
            'authorization' => $userModel->api_token,
            'status' => $userModel->status,
            'created_at' => (string)$userModel->created_at,
            'updated_at' => (string)$userModel->updated_at,
        ];
    }
}
