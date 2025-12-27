<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public $blocklist;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // Admins can view all users in Filament, regular users can browse
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        // Admins can view any user profile
        if ($user->isAdmin()) {
            return true;
        }

        // Regular users can only view profiles of same gender and school
        return ($user->gender === $model->gender) && ($user->school_id === $model->school_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Only admins can create users in Filament
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        // Admins can update any user
        if ($user->isAdmin()) {
            return true;
        }

        // Regular users can only update themselves
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        // Only admins can delete users
        // Prevent admins from deleting themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        // Only admins can restore deleted users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        // Only admins can force delete users
        // Prevent admins from force deleting themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can replicate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function replicate(User $user, User $model)
    {
        // Only admins can replicate users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can interact with the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function interactWith(User $user, User $model)
    {
        return !$user->isBlockedBy($model);
    }

    /**
     * Determine whether the user can block the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function block(User $user, User $model)
    {
        return !$user->hasBlocked($model) && $user->isNot($model);
    }
    
    /**
     * Determine whether the user can unblock the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function unblock(User $user, User $model)
    {
        return $user->hasBlocked($model) && $user->isNot($model);
    }
}