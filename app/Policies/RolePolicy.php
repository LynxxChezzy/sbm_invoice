<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return Auth::user()->hasRole('Administrator');
    }

    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return Auth::user()->hasRole('Administrator');
    }

    public function update(User $user)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        return false;
    }

    public function delete(User $user)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        return false;
    }
}
