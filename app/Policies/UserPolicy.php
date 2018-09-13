<?php

namespace App\Policies;

use App\Constants\UserRoleConstant;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 *
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\UserModel $currentUser
     * @param                       $ability
     * @return bool
     */
    public function before(UserModel $currentUser, $ability)
    {
        if ($currentUser->role === UserRoleConstant::ADMIN && $ability != 'delete') {
            return true;
        }
    }

    /**
     * @param \App\Models\UserModel $currentUser
     * @return bool
     */
    public function get(UserModel $currentUser)
    {
        return false;
    }

    /**
     * @param \App\Models\UserModel $currentUser
     * @param \App\Models\UserModel $userModel
     * @return bool
     */
    public function show(UserModel $currentUser, UserModel $userModel)
    {
        if ($currentUser->id === $userModel->id) {
            return true;
        }

        return false;
    }

    /**
     * @param \App\Models\UserModel $currentUser
     * @return bool
     */
    public function create(UserModel $currentUser)
    {
        return false;
    }

    /**
     * @param \App\Models\UserModel $currentUser
     * @param int                   $id
     * @return bool
     */
    public function update(UserModel $currentUser, int $id)
    {
        if ($currentUser->id == $id) {
            return true;
        }

        return false;
    }

    /**
     * @param \App\Models\UserModel $currentUser
     * @param int                   $id
     * @return bool
     */
    public function delete(UserModel $currentUser, int $id)
    {
        if ($currentUser->role == UserRoleConstant::ADMIN && $currentUser->id != $id) {
            return true;
        }

        return false;
    }
}
