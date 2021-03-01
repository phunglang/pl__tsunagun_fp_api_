<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class JobRepository.
 */
class JobRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\Job::class;
    }
    public function listJob($dataRequest) {

        $query = $this->model->select('title', 'content', 'status', 'recruiting_start', 'recruiting_end', 'connect_areas', 'connect_skills', 'user_id', 'created_at')
                            ->with([
                                'getUser' => function ($query) {
                                    $query->select('username', 'status')->with('getUserReports');;
                                },
                                'getConnectAreas' => function ($query) {
                                    $query->select('*');
                                },
                                'getSkills' => function ($query) {
                                    $query->select('*');
                                },
                                'getReports' => function ($query) {
                                    $query->select('*');
                                },
                            ])
                            ->where('is_deleted', false);
        if(isset($dataRequest['filters']['user_id']))
            $query->where("user_id", $dataRequest['filters']['user_id']);
        $query->search($dataRequest['filters']);
        
        if(!empty($dataRequest['filters']['orderName'])){
            $query->orderBy($dataRequest['filters']['orderName'], $dataRequest['filters']['orderType']);
        }
        else {
            $query->latest();
        }
        
        return $query->get()->map(function ($user) {
            return $user->append('report_count', 'owner_name');
        });
    }
    
    public function deleteJob($delete_id) {
        $this->model->where("_id", $delete_id)->delete();
    }
    
    public function update($id, $data)
    {
        $this->model->where("_id",$id)->update($data);
    }
    
    public function getJobById($id) {
        return $query = $this->model->select('title', 'content', 'status', 'recruiting_start', 'recruiting_end', 'connect_areas', 'connect_skills', 'user_id', 'created_at')
                            ->with([
                                'getUser' => function ($query) {
                                    $query->select('username', 'status')->with('getUserReports');;
                                },
                                'getConnectAreas' => function ($query) {
                                    $query->select('*');
                                },
                                'getSkills' => function ($query) {
                                    $query->select('*');
                                },
                                'getReports' => function ($query) {
                                    $query->select('*');
                                },
                            ])
                            ->where('is_deleted', false)
                            ->where("_id", $id)
                            ->first();
    }
}

