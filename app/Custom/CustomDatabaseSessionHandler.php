<?php

namespace App\Custom;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\Auth;

class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    protected function addUserInformation(&$payload)
    {
        if ($this->container->bound(Guard::class)) {
            if (Auth::check()) {
                $payload['user_id'] = $this->userId();
                $payload['sites'] = 'user';
            }
            if (Auth::guard('custom')->check()) {
                $user = Auth::guard('custom')->user();
                $payload['user_id'] = $user->id;
                $payload['sites'] = 'admin';
            }
        }
        return $this;
    }
}
