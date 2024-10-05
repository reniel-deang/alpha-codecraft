<?php

namespace App\Policies;

use App\Models\CommunityPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommunityPostPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CommunityPost $post): bool
    {
        return $post->author_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CommunityPost $post): bool
    {
        return $post->author_id === $user->id;
    }
}
