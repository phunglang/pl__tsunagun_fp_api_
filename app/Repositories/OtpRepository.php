<?php

namespace App\Repositories;

use App\Models\Otp;
use App\Interfaces\OtpRepositoryInterface;

/**
 * Class OtpRepository.
 */
class OtpRepository implements OtpRepositoryInterface
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
    public function __construct(Otp $model) {
        $this->model = $model;
    }

    public function checkUserComfirmOTP($query) {
        return $this->model->where($query)->first();
    }
}
