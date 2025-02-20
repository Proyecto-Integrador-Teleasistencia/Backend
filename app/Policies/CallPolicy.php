<?php

namespace App\Policies;

use App\Models\Call;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Los administradores y operadores pueden ver llamadas
        return in_array($user->role, ['admin', 'operator']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Call $call): bool
    {
        // Los administradores pueden ver cualquier llamada
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden ver llamadas de sus zonas asignadas
        if ($user->role === 'operator') {
            return $user->zones->contains($call->patient->zone_id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo operadores y administradores pueden crear llamadas
        return in_array($user->role, ['admin', 'operator']);
    }

    /**
     * Determine whether the user can make outgoing calls.
     */
    public function makeOutgoingCall(User $user, Call $call): bool
    {
        // Los administradores pueden hacer llamadas salientes a cualquier zona
        if ($user->role === 'admin') {
            return true;
        }

        // Los operadores solo pueden hacer llamadas salientes a pacientes de sus zonas
        if ($user->role === 'operator' && $call->type === 'outgoing') {
            return $user->zones->contains($call->patient->zone_id);
        }

        return false;
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
