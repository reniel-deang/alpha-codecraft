<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassroomPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        $enrollment = $user->enrollments()->where('classroom_id', $classroom->id)->first();

        return $enrollment ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Classroom $classroom): bool
    {
        return $classroom->teacher_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Classroom $classroom): bool
    {
        return $classroom->teacher_id === $user->id;
    }
}
