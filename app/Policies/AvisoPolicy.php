<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AvisoPolicy
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
    public function view(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede ver alertas de pacientes en sus zonas
            return $user->zones->contains($alert->patient->zone_id);
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
    public function update(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede actualizar alertas de pacientes en sus zonas
            return $user->zones->contains($alert->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alert $alert): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            // El operador solo puede eliminar alertas de pacientes en sus zonas
            return $user->zones->contains($alert->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alert $alert): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alert $alert): bool
    {
        return false;
    }
}
