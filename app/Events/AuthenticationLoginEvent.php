<?php

namespace App\Events;

/**
 * Class AuthenticationLoginEvent
 * @package App\Events
 */
class AuthenticationLoginEvent extends BaseEvent
{
    /**
     * AuthenticationLoginEvent constructor.
     *
     * @param \App\Models\UserModel $user
     */
    public function __construct(\App\Models\UserModel $user)
    {
        parent::__construct($user, "email");
    }
}
