<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Policies\Traits\ChecksUserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization, ChecksUserRole;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasRole($user, ['admin', 'operator']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
    {
        return $this->canManageZone($user, $patient->zone_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return $this->isAdmin($user);
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
