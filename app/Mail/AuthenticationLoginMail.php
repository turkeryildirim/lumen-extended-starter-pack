<?php

namespace App\Mail;

/**
 * Class AuthenticationLoginMail
 *
 * @package App\Mail
 */
class AuthenticationLoginMail extends BaseMail
{
    /**
     * @return \App\Mail\AuthenticationLoginMail|mixed
     */
    public function build()
    {
        return $this->subject('You have signed in to the site')
            ->view('emails.default')
            ->with([
                'title' => 'You have signed in to the site.',
                'content' => "Hello {$this->actionModel->first_name} {$this->actionModel->last_name},<br>"
                    . "You have signed in."
            ]);
    }
}
