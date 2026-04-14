<?php

namespace App\Policies;

use App\Models\Maintenance;
use App\Models\User;

class MaintenancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PIMPINAN, User::ROLE_STAFF], true);
    }

    public function view(User $user, Maintenance $maintenance): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function update(User $user, Maintenance $maintenance): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $this->create($user);
    }
}
