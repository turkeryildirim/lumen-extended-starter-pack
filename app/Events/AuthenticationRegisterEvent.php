<?php

namespace App\Events;

/**
 * Class AuthenticationRegisterEvent
 * @package App\Events
 */
class AuthenticationRegisterEvent extends BaseEvent
{
    /**
     * AuthenticationRegisterEvent constructor.
     *
     * @param \App\Models\UserModel $user
     */
    public function __construct(\App\Models\UserModel $user)
    {
        parent::__construct($user, "all");
    }
}
