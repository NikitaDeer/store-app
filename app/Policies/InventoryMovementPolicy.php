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
        if ($user->isAdmin() && in_array($ability, [
            'update',
            'delete',
            'restore',
            'forceDelete',
            'deleteAny',
            'restoreAny',
            'forceDeleteAny',
        ], true)) {
            return false;
        }

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

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, InventoryMovement $inventoryMovement): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
