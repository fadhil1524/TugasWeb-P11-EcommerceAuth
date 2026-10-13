<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * Aturan:
 *  - admin  : boleh edit & hapus semua post
 *  - editor : boleh edit semua post, hanya boleh hapus post miliknya
 *  - user   : hanya boleh edit & hapus post miliknya
 */
class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin', 'editor');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin');
    }
}
