<?php

namespace App\Policies;

use App\Models\InventoryMovement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryMovementPolicy
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

    public function view(User $user, InventoryMovement $inventoryMovement): bool
    {
        return $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return $user->isEmployee();
    }

    public function update(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }

    public function delete(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }

    public function restore(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }

    public function forceDelete(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }
}
