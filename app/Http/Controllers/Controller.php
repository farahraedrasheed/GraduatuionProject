<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    protected function authUser(): User
    {
        $user = request()->user();
        assert($user instanceof User);
        return $user;
    }
}
