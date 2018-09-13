<?php

namespace App\Models;

use App\Transformers\UserMetaModelTransformer;
use Flugg\Responder\Contracts\Transformable;

/**
 * Class UserMetaModel
 *
 * @property int $id
 * @property int $user_id
 * @property string $gender
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property string $birth_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @package App\Models
 */
class UserMetaModel extends BaseModel implements Transformable
{

    /**
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'birth_date' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gender',
        'phone',
        'city',
        'address',
        'birth_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * @return callable|\Flugg\Responder\Transformers\Transformer|null|string
     */
    public function transformer()
    {
        return UserMetaModelTransformer::class;
    }
}
