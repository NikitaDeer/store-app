<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isEmployee();
    }

    public function view(User $user, Stock $stock): bool
    {
        return $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Stock $stock): bool
    {
        return false;
    }

    public function delete(User $user, Stock $stock): bool
    {
        return false;
    }

    public function restore(User $user, Stock $stock): bool
    {
        return false;
    }

    public function forceDelete(User $user, Stock $stock): bool
    {
        return false;
    }
}
