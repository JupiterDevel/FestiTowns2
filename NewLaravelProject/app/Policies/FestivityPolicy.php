<?php

namespace App\Policies;

use App\Models\Festivity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FestivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can view festivities
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Festivity $festivity): bool
    {
        return true; // Anyone can view a festivity
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow all authenticated users to create (suggest) festivities
        // Approval will be handled based on user role in the controller
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Festivity $festivity): bool
    {
        return $user->isAdmin() || 
               ($user->isTownHall() && $user->locality_id === $festivity->locality_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Festivity $festivity): bool
    {
        return $user->isAdmin() || 
               ($user->isTownHall() && $user->locality_id === $festivity->locality_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Festivity $festivity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Festivity $festivity): bool
    {
        return false;
    }
}
