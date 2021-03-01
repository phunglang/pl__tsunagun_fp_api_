<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class SkillRepository.
 */
class SkillRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\Skill::class;
    }
    public function update($status,$id)
    {
        $this->model->whereIn("_id",$id)->update(["status" => $status]);

    }
    public function deleteSkills($id)
    {
        $this->model->whereIn("_id",$id)->update(['is_deleted'=>true]);
    }

    public function listSkills($query) {
        return $this->model->where('is_deleted',false)
            ->latest()
            ->paginate(intval($query['size']));
    }

}
