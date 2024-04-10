<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Custom\CustomDatabaseSessionHandler;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Session\DatabaseSessionHandler;
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
