<?php

namespace App\Policies;

use App\Models\CaseFile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CaseFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseFile  $caseFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CaseFile $caseFile)
    {
        return $user->id === $caseFile->created_by_user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array(Role::IS_LAWYER, $user->roles->pluck('id')->toArray());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseFile  $caseFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CaseFile $caseFile)
    {
        return $user->id === $caseFile->created_by_user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseFile  $caseFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CaseFile $caseFile)
    {
        return $user->id === $caseFile->created_by_user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseFile  $caseFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CaseFile $caseFile)
    {
        return $user->id === $caseFile->created_by_user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseFile  $caseFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CaseFile $caseFile)
    {
        return $user->id === $caseFile->created_by_user_id;
    }
}
