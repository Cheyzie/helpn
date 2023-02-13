<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user):Response|bool
    {
        return !$user->hasRole('banned');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Bill  $bill
     * @return Response|bool
     */
    public function view(User $user, Bill $bill): Response|bool
    {
        return !$user->hasRole('banned');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->role->name !== 'banned';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Bill  $bill
     * @return Response|bool
     */
    public function update(User $user, Bill $bill): Response|bool
    {
        return $user->id === $bill->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Bill  $bill
     * @return Response|bool
     */
    public function delete(User $user, Bill $bill): Response|bool
    {
        return $user->hasRole('admin') || $user->id === $bill->user_id;
    }
}
