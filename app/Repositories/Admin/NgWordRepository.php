<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class NgWordRepository.
 */
class NgWordRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\NGword::class;
    }
    public function deleteByName($name)
    {
        return $this->model->where('name',$name)->delete();
    }
}
