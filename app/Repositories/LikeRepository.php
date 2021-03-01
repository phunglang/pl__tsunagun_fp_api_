<?php

namespace App\Repositories;

use App\Models\Like;
use App\Interfaces\LikeRepositoryInterface;
use App\Models\User;

/**
 * Class UserRepository.
 */
class LikeRepository implements LikeRepositoryInterface
{
    /**
     * @var Model
    */
    protected $model;
    /**
     *
     * @param Model $model
    */
    public function __construct(Like $model) {
        $this->model = $model;
    }

    public function totalLikes($query) {
        return $this->model
                        ->where($query)
                        ->count();
    }

    public function isLikedBy(User $user, $query)
    {
        return $this->model
                        ->where('own_id', $user->id)
                        ->where($query)
                        ->count();
    }
}
