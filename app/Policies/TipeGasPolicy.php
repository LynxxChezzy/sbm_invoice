<?php

namespace App\Policies;

use App\Models\TipeGas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TipeGasPolicy
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
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Superintendent') || Auth::user()->hasRole('Supervisor');
    }

    public function view(User $user, TipeGas $tipeGas)
    {
        return true;
    }

    public function create(User $user)
    {
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Superintendent') || Auth::user()->hasRole('Supervisor');
    }

    public function update(User $user, TipeGas $tipeGas)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Supertendent')) {
            return true;
        }
        if (Auth::user()->hasRole('Supervisor')) {
            return true;
        }
        return false;
    }

    public function delete(User $user, TipeGas $tipeGas)
    {
        return true;
    }
}
