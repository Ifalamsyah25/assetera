<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_PIMPINAN, User::ROLE_STAFF], true);
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $this->create($user);
    }
}
