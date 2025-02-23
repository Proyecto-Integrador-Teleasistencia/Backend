<?php

namespace App\Policies;

use App\Models\Llamada;
use App\Models\User;
use App\Models\Paciente;
use App\Policies\Traits\ChecksUserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class LlamadaPolicy
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
     * Determine whether the user can make outgoing calls.
     */
    public function makeOutgoingCall(User $user, Paciente|Llamada $target): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isOperator($user)) {
            $zoneId = $target instanceof Paciente ? $target->zona_id : $target->paciente->zona_id;
            
            // Si es una llamada, verificar que sea saliente
            if ($target instanceof Llamada && $target->tipo_llamada !== 'saliente') {
                return false;
            }
            
            return $user->zones->contains($zoneId);
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Llamada $call): bool
    {
        return $user->role === 'admin' || $user->role === 'operator' && $call->paciente->zona_id === $user->zona_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Llamada $call): bool
    {
        // Los administradores pueden eliminar cualquier llamada
        if ($this->isAdmin($user)) {
            return true;
        }

        // Los operadores solo pueden eliminar llamadas que ellos hayan creado
        if ($this->isOperator($user)) {
            return $call->operator_id === $user->id && $this->canManageZone($user, $call->patient->zone_id);
        }

        return false;
    }
}
