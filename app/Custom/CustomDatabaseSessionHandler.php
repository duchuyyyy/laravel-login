<?php

namespace App\Custom;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\DatabaseSessionHandler;

class CustomDatabaseSessionHandler extends DatabaseSessionHandler
{
    protected $role;


    protected function addUserInformation(&$payload)
    {
        if ($this->container->bound(Guard::class)) {
            $payload['user_id'] = $this->userId();
            $payload['sites'] = $this->getRoleUser();
        }

        return $this;
    }

    private function getRoleUser()
    {
        $user = $this->container->make(Guard::class)->user();

        if ($user) {
            return $user->role;
        }

        return null;
    }
}
