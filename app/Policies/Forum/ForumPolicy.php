<?php

namespace App\Policies\Forum;

use App\Models\User;

class ForumPolicy
{
    public function createCategories(User $user): bool
    {
        return $user->user_type === 'Admin';
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
        return $user->user_type === 'Admin';
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
