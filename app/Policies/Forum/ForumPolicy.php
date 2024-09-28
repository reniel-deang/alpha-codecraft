<?php

namespace App\Policies\Forum;

use App\Models\User;

class ForumPolicy
{
    public function createCategories(User $user): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return true;
    }

    public function moveCategories(User $user): bool
    {
        return $user->user_type === 'Admin';
    }

    public function editCategories(User $user): bool
    {
        return $user->user_type === 'Admin';
    }

    public function deleteCategories(User $user): bool
    {
        if(in_array($user->user_type, ['Admin', 'Teacher'])) {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function markThreadsAsRead(User $user): bool
    {
        return true;
    }

    public function viewTrashedThreads(User $user): bool
    {
        return $user->user_type === 'Admin';
    }

    public function viewTrashedPosts(User $user): bool
    {
        return $user->user_type === 'Admin';
    }
}
