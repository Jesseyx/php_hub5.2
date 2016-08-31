<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $currentUser, User $user)
    {
        return $currentUser->may('manage_users') || $currentUser->id == $user->id;
    }

    public function delete(User $currentUser, User $user)
    {
        return $currentUser->may('manage_users') || $currentUser->id == $user->id;
    }

    public function blocking(User $currentUser, User $user)
    {
        return $currentUser->may('manage_users');
    }
}
