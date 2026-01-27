<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManufacturerPolicy
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

    public function view(User $user, Manufacturer $manufacturer): bool
    {
        return $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }

    public function delete(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }

    public function restore(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }

    public function forceDelete(User $user, Manufacturer $manufacturer): bool
    {
        return false;
    }
}
