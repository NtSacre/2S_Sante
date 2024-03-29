<?php

namespace App\Policies;

use App\Models\Temoignage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TemoignagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Temoignage $temoignage): bool
    {
        return $user->id == $temoignage->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_blocked === 0 && $user->id !== null;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Temoignage $temoignage): bool
    {
        return $user->id == $temoignage->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Temoignage $temoignage): bool
    {
        return $user->id == $temoignage->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Temoignage $temoignage): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Temoignage $temoignage): bool
    // {
    //     //
    // }
}
