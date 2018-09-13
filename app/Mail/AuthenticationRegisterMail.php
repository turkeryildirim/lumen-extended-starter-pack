<?php

namespace App\Mail;

/**
 * Class AuthenticationRegisterMail
 *
 * @package App\Mail
 */
class AuthenticationRegisterMail extends BaseMail
{
    /**
     * @return \App\Mail\AuthenticationRegisterMail|mixed
     */
    public function build()
    {
        return $this->subject('You have registered to the site')
            ->view('emails.default')
            ->with([
                'title' => 'You have registered to the site.',
                'content' => "Hello {$this->actionModel->first_name} {$this->actionModel->last_name},<br>"
                    . "You have registered."
            ]);
    }
}
