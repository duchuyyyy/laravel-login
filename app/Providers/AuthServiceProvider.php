<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Custom\CustomDatabaseSessionHandler;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('custom', function ($app, $name, array $config) {
            $userProvider = $app['auth']->createUserProvider($config['provider']);
            $sessionManager = $app->make(SessionManager::class);
            $session = new SessionGuard($name, $userProvider, $sessionManager->driver());
            return $session;
        });


        Session::resolved(function ($session) {
            $session->extend('customdriver', function ($app) {
                $table = $app['config']['session.table'];
                $lifetime = $app['config']['session.lifetime'];
                $connection = $app['db']->connection($app['config']['session.connection']);
                return new CustomDatabaseSessionHandler($connection, $table, $lifetime, $app);
            });
        });
    }
}
