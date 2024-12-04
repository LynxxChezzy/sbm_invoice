<?php

namespace App\Policies;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PerusahaanPolicy
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

    public function view(User $user, Perusahaan $perusahaan)
    {
        return true;
    }

    public function create(User $user)
    {
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Supertendent');
    }

    public function update(User $user, Perusahaan $perusahaan)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Supertendent')) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Perusahaan $perusahaan)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Supertendent')) {
            return true;
        }
        return false;
    }
}
