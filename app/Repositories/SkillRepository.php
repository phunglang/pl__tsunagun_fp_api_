<?php

namespace App\Repositories;

use App\Interfaces\SkillRepositoryInterface;
use App\Models\Skill;

/**
 * Class SkillRepository.
 */
class SkillRepository implements SkillRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Skill $model)
    {
        $this->model = $model;
    }

    public function list() {
        return $this->model
                        ->select('name', 'status')
                        ->where([
                            'is_deleted'=> false,
                            'status' => 1
                        ])
                        ->get();
    }
}
