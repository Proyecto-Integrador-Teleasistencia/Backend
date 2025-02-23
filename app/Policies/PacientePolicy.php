<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;
use App\Policies\Traits\ChecksUserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class PacientePolicy
{
    use HandlesAuthorization, ChecksUserRole;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paciente $patient): bool
    {
        // Los administradores pueden actualizar cualquier paciente
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden actualizar pacientes de sus zonas asignadas
        if ($user->role === 'operator') {
            return $user->zona_id === $patient->zona_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paciente $patient): bool
    {
        // Solo los administradores pueden eliminar pacientes
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden eliminar pacientes de sus zonas asignadas
        if ($user->role === 'operator') {
            return $user->zona_id === $patient->zona_id;
        }

        return false;
    }
}
