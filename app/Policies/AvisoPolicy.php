<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AvisoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator';
    }

    public function view(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($alert->patient->zone_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'operator';
    }

    public function update(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($alert->patient->zone_id);
        }

        return false;
    }

    public function delete(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($alert->patient->zone_id);
        }

        return false;
    }

    public function restore(User $user, Alert $alert): bool
    {
        return false;
    }

    public function forceDelete(User $user, Alert $alert): bool
    {
        return false;
    }
}
