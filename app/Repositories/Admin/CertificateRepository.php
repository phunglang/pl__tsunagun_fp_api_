<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class SkillRepository.
 */
class CertificateRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\Certificate::class;
    }
    public function update($id, $data)
    {
        $this->model->where("_id",$id)->update($data);
    }
}
