<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Zona;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZonaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Zona $zona): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Zona $zona): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Zona $zona): bool
    {
        return $user->is_admin && $zona->operator()->count() === 0;
    }
}
