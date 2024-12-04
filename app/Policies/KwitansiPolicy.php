<?php

namespace App\Policies;

use App\Models\Kwitansi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KwitansiPolicy
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
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Superintendent') || Auth::user()->hasRole('Supervisor') || Auth::user()->hasRole('Staff');
    }

    public function view(User $user, Kwitansi $kwitansi)
    {
        return true;
    }

    public function create(User $user)
    {
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Staff');
    }

    public function update(User $user, Kwitansi $kwitansi)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Supervisor')) {
            return true;
        }
        if (Auth::user()->hasRole('Staff')) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Kwitansi $kwitansi)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Staff')) {
            return true;
        }
        return false;
    }
}
