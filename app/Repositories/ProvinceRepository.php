<?php

namespace App\Repositories;

use App\Interfaces\ProvinceRepositoryInterface;
use App\Models\Province;

/**
 * Class ProvinceRepository.
 */
class ProvinceRepository implements ProvinceRepositoryInterface
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
    public function __construct(Province $model)
    {
        $this->model = $model;
    }

    public function getProvince() {
        return $this->model
                        ->select('name')
                        ->get();
    }
}
