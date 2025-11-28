<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $frontend = env('FRONTEND_URL', 'https://gkpawiligar.org');
            return "{$frontend}/reset-password?token={$token}&email={$user->email}";
        });
    }
}
