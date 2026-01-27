<?php

namespace App\Policies;

use App\Models\StorageLocation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorageLocationPolicy
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

    public function view(User $user, StorageLocation $storageLocation): bool
    {
        return $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, StorageLocation $storageLocation): bool
    {
        return false;
    }

    public function delete(User $user, StorageLocation $storageLocation): bool
    {
        return false;
    }

    public function restore(User $user, StorageLocation $storageLocation): bool
    {
        return false;
    }

    public function forceDelete(User $user, StorageLocation $storageLocation): bool
    {
        return false;
    }
}
