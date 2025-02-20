<?php

namespace App\Policies\Traits;

use App\Models\User;

trait ChecksUserRole
{
    protected function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    protected function isOperator(User $user): bool
    {
        return $user->role === 'operator';
    }

    protected function hasRole(User $user, string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($user->role, $roles);
    }

    protected function canManageZone(User $user, int $zoneId): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->zones->contains($zoneId);
    }
}
