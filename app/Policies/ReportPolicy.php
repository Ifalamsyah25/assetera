<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function view(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PIMPINAN], true);
    }

    public function export(User $user): bool
    {
        return $this->view($user);
    }
}
