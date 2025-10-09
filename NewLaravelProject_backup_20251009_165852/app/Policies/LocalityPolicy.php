<?php

namespace App\Policies;

use App\Models\Locality;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocalityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view localities
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Locality $locality): bool
    {
        return true; // Anyone can view a locality
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only admins can create localities
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Locality $locality): bool
    {
        return $user->isAdmin() || 
               ($user->isTownHall() && $user->locality_id === $locality->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Locality $locality): bool
    {
        return $user->isAdmin(); // Only admins can delete localities
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Locality $locality): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Locality $locality): bool
    {
        return false;
    }
}
