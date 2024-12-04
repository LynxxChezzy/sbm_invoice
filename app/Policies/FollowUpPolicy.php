<?php

namespace App\Policies;

use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowUpPolicy
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

    public function view(User $user, FollowUp $followUp)
    {
        return true;
    }

    public function create(User $user)
    {
        return Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Staff');
    }

    public function update(User $user, FollowUp $followUp)
    {
        if (Auth::user()->hasRole('Administrator')) {
            return true;
        }
        if (Auth::user()->hasRole('Staff')) {
            return true;
        }
        return false;
    }

    public function delete(User $user, FollowUp $followUp)
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