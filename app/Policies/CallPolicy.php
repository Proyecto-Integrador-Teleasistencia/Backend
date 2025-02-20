<?php

namespace App\Policies;

use App\Models\Call;
use App\Models\User;
use App\Models\Patient;
use App\Policies\Traits\ChecksUserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallPolicy
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
    public function view(User $user, Call $call): bool
    {
        return $this->canManageZone($user, $call->patient->zone_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasRole($user, ['admin', 'operator']);
    }

    /**
     * Determine whether the user can make outgoing calls.
     */
    public function makeOutgoingCall(User $user, Patient|Call $target): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if ($this->isOperator($user)) {
            $zoneId = $target instanceof Patient ? $target->zone_id : $target->patient->zone_id;
            
            // Si es una llamada, verificar que sea saliente
            if ($target instanceof Call && $target->type !== 'outgoing') {
                return false;
            }
            
            return $user->zones->contains($zoneId);
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Call $call): bool
    {
        return $this->canManageZone($user, $call->patient->zone_id);
    }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Call $call): bool
    {
        // Los administradores pueden actualizar cualquier llamada
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden actualizar llamadas que ellos hayan creado
        if ($user->role === 'operator') {
            return $call->operator_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Call $call): bool
    {
        // Solo los administradores pueden eliminar llamadas
        return $user->role === 'admin';
    }
}
