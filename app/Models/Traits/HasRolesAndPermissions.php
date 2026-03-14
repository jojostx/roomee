<?php

namespace App\Models\Traits;

use App\Enums\UserRole;
use Filament\Panel;

trait HasRolesAndPermissions
{
    public function canAccessPanel(Panel $panel): bool
    {
        $role = $this->role;

        if ($role instanceof UserRole) {
            return in_array($role, [UserRole::ADMIN, UserRole::STAFF], true);
        }

        return in_array($role, [UserRole::ADMIN->value, UserRole::STAFF->value], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isAdminOrStaff(): bool
    {
        $role = $this->role;

        if ($role instanceof UserRole) {
            return in_array($role, [UserRole::ADMIN, UserRole::STAFF], true);
        }

        return in_array((string) $role, [UserRole::ADMIN->value, UserRole::STAFF->value], true);
    }

    public function isRegularUser(): bool
    {
        $role = $this->role;

        if ($role instanceof UserRole) {
            return $role === UserRole::USER;
        }

        return (string) $role === UserRole::USER->value;
    }

    public function canManageListings(): bool
    {
        return $this->isRegularUser()
            && $this->hasVerifiedEmail()
            && (bool) $this->profile_updated
            && $this->isVerified()
            && !$this->isSuspended();
    }
}
