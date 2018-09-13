<?php

namespace App\Transformers;

use App\Models\UserMetaModel;
use Flugg\Responder\Transformers\Transformer;

/**
 * @OA\Schema(
 *     schema="UserMetaModel",
 *     @OA\Property(property="id",type="integer"),
 *     @OA\Property(property="user_id",type="integer"),
 *     @OA\Property(property="gender",type="string",ref="#/components/schemas/UserGender"),
 *     @OA\Property(property="phone",type="string"),
 *     @OA\Property(property="city",type="string"),
 *     @OA\Property(property="address",type="string"),
 *     @OA\Property(property="birth_date",type="string",format="datetime"),
 *     @OA\Property(property="created_at",type="string",format="datetime"),
 *     @OA\Property(property="updated_at",type="string",format="datetime")
 * )
 */
/**
 * Class UserMetaModelTransformer
 *
 * @package App\Transformers
 */
class UserMetaModelTransformer extends Transformer
{
    /**
     * @var string[]
     */
    protected $relations = [];

    /**
     * @var array
     */
    protected $load = [];

    /**
     * @param  UserMetaModel $userMetaModel
     * @return array
     */
    public function transform(UserMetaModel $userMetaModel) : array
    {
        return $userMetaModel->toArray();
    }
}
