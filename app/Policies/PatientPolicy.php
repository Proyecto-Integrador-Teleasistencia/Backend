<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Los administradores pueden ver todos los pacientes
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden ver la lista de pacientes
        return $user->role === 'operator';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Los administradores pueden ver cualquier paciente
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden ver pacientes de sus zonas asignadas
        if ($user->role === 'operator') {
            return $user->zones->contains($patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo los administradores pueden crear pacientes
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Los administradores pueden actualizar cualquier paciente
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden actualizar pacientes de sus zonas asignadas
        if ($user->role === 'operator') {
            return $user->zones->contains($patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // Solo los administradores pueden eliminar pacientes
        return $user->role === 'admin';
    }
}
