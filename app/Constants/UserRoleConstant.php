<?php

namespace App\Constants;

/**
 * @OA\Schema(
 *     schema="UserRole",
 *     type="string",
 *     enum={"user", "admin"},
 *     default="user"
 * )
 */
/**
 * Class UserRoleConstant
 *
 * @package App\Constants
 */
class UserRoleConstant
{
    const USER = 'user';
    const ADMIN = 'admin';
}
