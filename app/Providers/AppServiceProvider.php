<?php

namespace App\Providers;

use App\Mail\Transports\GmailSmtpRelayTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Mail::extend('gmail', fn ($config) => new GmailSmtpRelayTransport($config));
    }
}
