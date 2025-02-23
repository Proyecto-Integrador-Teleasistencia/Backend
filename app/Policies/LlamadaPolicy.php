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
    public function update(User $user, Llamada $llamada): bool
    {
        return $user->role === 'admin' || $user->role === 'operator' && $llamada->paciente->zona_id === $user->zona_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Llamada $llamada): bool
    {
        return $user->role === 'admin' || $llamada->operador_id === $user->id || $this->canManageZone($user, $llamada->paciente->zona_id);
    }
}
