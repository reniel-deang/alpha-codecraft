<?php

namespace App\Policies\Forum;

use App\Models\User;
use TeamTeaTime\Forum\Models\Category;

class CategoryPolicy
{
    public function view(User $user, Category $category): bool
    {
        return true;
    }

    public function edit(User $user, Category $category): bool
    {
        return $user->user_type === 'Admin';
    }

    public function delete(User $user, Category $category): bool
    {
        if(in_array($user->user_type, ['Admin', 'Teacher'])) {
            if($user->user_type === 'Teacher' &&
                $user->teacherDetail->is_verfied) {
                    return false;
            }

            return true;
        }
    }

    public function createThreads(User $user, Category $category): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return true;
    }

    public function manageThreads(User $user, Category $category): bool
    {
        return $this->deleteThreads($user, $category)
            || $this->restoreThreads($user, $category)
            || $this->moveThreadsFrom($user, $category)
            || $this->lockThreads($user, $category)
            || $this->pinThreads($user, $category);
    }

    public function deleteThreads(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function restoreThreads(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function moveThreadsFrom(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function moveThreadsTo(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function lockThreads(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function pinThreads(User $user, Category $category): bool
    {
        if($user->user_type !== 'Admin') {
            if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
                return false;
            }
            return $user->getKey() === $category->author_id;
        } else {
            return true;
        }
    }

    public function markThreadsAsRead(User $user, Category $category): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return true;
    }
}
