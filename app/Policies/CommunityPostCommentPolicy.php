<?php

namespace App\Policies;

use App\Models\CommunityPostComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommunityPostCommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CommunityPostComment $communityPostComment): bool
    {
        return $communityPostComment->author_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CommunityPostComment $communityPostComment): bool
    {
        return $communityPostComment->author_id === $user->id;
    }
}
