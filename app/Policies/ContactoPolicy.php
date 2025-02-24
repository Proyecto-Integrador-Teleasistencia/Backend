<?php

namespace App\Policies;

use App\Models\ContactPerson;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede ver contactos de pacientes en sus zonas
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'operator';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede actualizar contactos de pacientes en sus zonas
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContactPerson $contactPerson): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede eliminar contactos de pacientes en sus zonas
            return $user->zones->contains($contactPerson->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContactPerson $contactPerson): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContactPerson $contactPerson): bool
    {
        return false;
    }
}
