<?php

namespace App\Models;

use App\Transformers\UserModelTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class UserModel
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $api_token
 * @property string $activation_code
 * @property \Carbon\Carbon $activation_date
 * @property \Carbon\Carbon $last_login_date
 * @property bool $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @package App\Models
 */
class UserModel extends BaseModel implements Transformable
{
    use Notifiable, Authorizable;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'activation_code'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'last_login_date' => 'datetime',
        'activation_date' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'role',
        'password',
        'first_name',
        'last_name',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userMeta()
    {
        return $this->hasOne(UserMetaModel::class, 'user_id');
    }

    /**
     * @return callable|\Flugg\Responder\Transformers\Transformer|null|string
     */
    public function transformer()
    {
        return UserModelTransformer::class;
    }

    /**
     * @param string $token
     * @return mixed
     */
    public static function findByAuthToken(string $token)
    {
        return parent::findBy('api_token', $token)->first();
    }
}
