<?php

namespace App\Policies;

use App\Models\ContactPerson;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator';
    }

    public function view(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'operator';
    }

    public function update(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    public function delete(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    public function restore(User $user, ContactPerson $contactPerson): bool
    {
        return false;
    }

    public function forceDelete(User $user, ContactPerson $contactPerson): bool
    {
        return false;
    }
}
