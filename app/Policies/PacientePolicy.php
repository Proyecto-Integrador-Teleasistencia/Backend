<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;
use App\Policies\Traits\ChecksUserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class PacientePolicy
{
    use HandlesAuthorization, ChecksUserRole;

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator';
    }

    public function update(User $user, Paciente $patient): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zona_id === $patient->zona_id;
        }

        return false;
    }

    public function delete(User $user, Paciente $patient): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'operator') {
            return $user->zona_id === $patient->zona_id;
        }

        return false;
    }
}
