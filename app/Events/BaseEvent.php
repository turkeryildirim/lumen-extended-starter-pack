<?php

namespace App\Events;

use App\Models\UserModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

/**
 * Class Event
 * @package App\Events
 */
abstract class BaseEvent
{
    use SerializesModels;

    /**
     * @var
     */
    public $actionModel;

    /**
     * @var UserModel
     */
    public $actionUser;

    /**
     * @var
     */
    public $type;

    /**
     * BaseEvent constructor.
     *
     * @param $actionModel
     * @param string $type
     * @param UserModel|null $actionUser
     */
    public function __construct($actionModel, string $type, UserModel $actionUser = null)
    {
        $this->actionModel = $actionModel;
        $this->actionUser = (empty($actionUser)) ? Auth::user() : $actionUser;
        $this->type = $type;
    }
}
