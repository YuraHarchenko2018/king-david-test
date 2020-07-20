<?php

namespace App\Policies;

use App\Models\Tasks;
use App\Models\Users;
use Illuminate\Auth\Access\HandlesAuthorization;

class TasksPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Users  $user
     * @return mixed
     */
    public function viewAny(Users $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Users  $user
     * @param  \App\Tasks  $tasks
     * @return mixed
     */
    public function view(Users $user, Tasks $tasks)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Users  $user
     * @return mixed
     */
    public function create(Users $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Users  $user
     * @param  \App\Tasks  $tasks
     * @return mixed
     */
    public function update(Users $user, Tasks $tasks)
    {
        // \Illuminate\Support\Facades\Log::debug(json_encode([$user, $tasks]));
        return true; // $user->id == $tasks->boards->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Users  $user
     * @param  \App\Tasks  $tasks
     * @return mixed
     */
    public function delete(Users $user, Tasks $tasks)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Users  $user
     * @param  \App\Tasks  $tasks
     * @return mixed
     */
    public function restore(Users $user, Tasks $tasks)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Users  $user
     * @param  \App\Tasks  $tasks
     * @return mixed
     */
    public function forceDelete(Users $user, Tasks $tasks)
    {
        //
    }
}
