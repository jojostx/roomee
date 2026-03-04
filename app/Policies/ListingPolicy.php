<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdminOrStaff() || $user->canManageListings();
    }

    public function view(User $user, Listing $listing): bool
    {
        return $user->isAdminOrStaff()
            || ($listing->user_id === $user->id && $user->canManageListings());
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrStaff() || $user->canManageListings();
    }

    public function update(User $user, Listing $listing): bool
    {
        return $user->isAdminOrStaff()
            || ($listing->user_id === $user->id && $user->canManageListings());
    }

    public function delete(User $user, Listing $listing): bool
    {
        return $user->isAdminOrStaff()
            || ($listing->user_id === $user->id && $user->canManageListings());
    }
}
