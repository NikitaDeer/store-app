<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
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

    public function view(User $user, Country $country): bool
    {
        return $user->isEmployee();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Country $country): bool
    {
        return false;
    }

    public function delete(User $user, Country $country): bool
    {
        return false;
    }

    public function restore(User $user, Country $country): bool
    {
        return false;
    }

    public function forceDelete(User $user, Country $country): bool
    {
        return false;
    }
}
