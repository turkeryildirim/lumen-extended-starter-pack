<?php

namespace App\Mail;

use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseMail
 *
 * @package App\Mail
 */
abstract class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    public $actionModel;

    /**
     * @var UserModel
     */
    public $actionUser;

    /**
     * BaseMail constructor.
     *
     * @param $actionModel
     * @param UserModel|null $actionUser
     */
    public function __construct($actionModel, UserModel $actionUser = null)
    {
        $this->actionModel = $actionModel;
        $this->actionUser = (empty($actionUser)) ? Auth::user() : $actionUser;
    }

    /**
     * @return mixed
     */
    abstract public function build();
}
