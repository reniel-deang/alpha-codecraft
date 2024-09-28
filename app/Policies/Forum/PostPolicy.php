<?php

namespace App\Policies\Forum;

use App\Models\User;
use TeamTeaTime\Forum\Models\Post;

class PostPolicy
{
    public function edit($user, Post $post): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return $user->getKey() === $post->author_id;
    }

    public function delete($user, Post $post): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return $user->getKey() === $post->author_id;
    }

    public function restore($user, Post $post): bool
    {
        if($user->user_type === 'Teacher' && !$user->teacherDetail->is_verified) {
            return false;
        }
        return $user->getKey() === $post->author_id;
    }
}
