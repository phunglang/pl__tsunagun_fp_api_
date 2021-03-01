<?php

namespace App\Interfaces;

use App\Models\User;

interface LikeRepositoryInterface
{
    public function totalLikes(array $query);
    public function isLikedBy(User $user, $query);
}