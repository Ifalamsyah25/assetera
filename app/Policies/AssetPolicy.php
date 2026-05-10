<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PIMPINAN, User::ROLE_STAFF], true);
    }

    public function view(User $user, Asset $asset): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function update(User $user, Asset $asset): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $this->create($user);
    }

    public function export(User $user): bool
    {
        return $this->viewAny($user);
    }
}
