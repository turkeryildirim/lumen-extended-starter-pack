<?php

namespace App\Providers;

/**
 * Class EmailServiceProvider
 *
 * @package App\Providers
 */
class EmailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function registerIlluminateMailer()
    {
        parent::registerIlluminateMailer();
        $this->app->alias(\Illuminate\Contracts\Mail\Mailer::class, 'mailer');
    }
}
