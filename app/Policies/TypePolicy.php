<?php

namespace App\Policies;

use App\Models\Type;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class TypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  ?User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  ?User $user
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, Type $type)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Type $type)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Type $type)
    {
        return $user->hasRole('admin');
    }

}
