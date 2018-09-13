<?php

namespace App\Services;

use App\Jobs\MailDispatcher;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * Class EmailService
 *
 * @package App\Services
 */
class EmailService
{
    /**
     * @var null
     */
    protected $to;
    /**
     * @var null
     */
    protected $cc;
    /**
     * @var null
     */
    protected $bcc;

    /**
     * @return \App\Services\EmailService
     */
    public function reset() : EmailService
    {
        $this->bcc = null;
        $this->cc = null;
        $this->to = null;
        return $this;
    }

    /**
     * @param $bcc
     * @return \App\Services\EmailService
     * @throws \Exception
     */
    public function setBcc($bcc) : EmailService
    {
        if (empty($bcc)) {
            throw new \Exception("EMAIL_SERVICE_NO_BCC");
        }
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @param $cc
     * @return \App\Services\EmailService
     * @throws \Exception
     */
    public function setCc($cc) : EmailService
    {
        if (empty($cc)) {
            throw new \Exception("EMAIL_SERVICE_NO_CC");
        }
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param $to
     * @return \App\Services\EmailService
     * @throws \Exception
     */
    public function setTo($to) : EmailService
    {
        if (empty($to)) {
            throw new \Exception("EMAIL_SERVICE_NO_EMAIL");
        }
        $this->to = $to;
        return $this;
    }

    /**
     * @param \Illuminate\Mail\Mailable $mailable
     * @param bool                      $now
     * @return mixed
     * @throws \Exception
     */
    public function send(Mailable $mailable, $now = false)
    {
        if (empty($this->to)) {
            throw new \Exception("EMAIL_SERVICE_NO_EMAIL");
        }

        $mail = Mail::to($this->to);
        if (!empty($this->bcc)) {
            $mail->bcc($this->bcc);
            $mailable->bcc($this->bcc);
        }
        if (!empty($this->cc)) {
            $mailable->cc($this->cc);
        }

        //dispatch(new MailDispatcher($mail, $now));

        $result = ($now === true) ? $mail->sendNow($mailable) : $mail->send($mailable);

        return $result;
    }

    /**
     * @return null|string|array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @return null|string|array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @return null|string|array
     */
    public function getTo()
    {
        return $this->to;
    }
}
