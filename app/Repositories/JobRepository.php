<?php

namespace App\Repositories;

use App\Interfaces\JobRepositoryInterface;
use App\Models\Job;

/**
 * Class JobRepository.
 */
class JobRepository implements JobRepositoryInterface
{
    protected $model;
    /**
     * @return string
     *  Return the model
     */
    public function __construct(Job $model) {
        $this->model = $model;
    }

    public function list($dataRequest, $id = null) {
        //show: title, content, created_at, recruiting, connect_areas, connect_skills
        //search: connect_skills, connect_areas, recruiting, keyword, sort: created_at
        //status, is_deleted
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'content',
                            'status',
                            'recruiting_end',
                            'connect_areas',
                            'connect_skills',
                            'user_id',
                            'created_at'
                        )
                        ->where([
                            'is_deleted'=> false,
                            'status' => 1
                        ])
                        ->when(isset($id), function ($query) use ($id) {
                            $query->where('user_id', $id);
                        })
                        ->when(isset($dataRequest['filters']), function ($query) use ($dataRequest) {
                            $query->filter($dataRequest['filters']);
                        })
                        ->with([
                            'getUser:avatar,username',
                            'getConnectAreas',
                            'getConnectSkills'
                        ])
                        ->paginate(intval($dataRequest['size']));

    }

    public function detail($id) {
        //show: title, created_at, :get_skills,:khu vuc, :user (username, avatar, like, status), content, recruiting_end, :job related (2)
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'content',
                            'status',
                            'recruiting_end',
                            'connect_areas',
                            'connect_skills',
                            'user_id',
                            'created_at'
                        )
                        ->with([
                            'getUser:avatar,username,status',
                            'getConnectAreas',
                            'getConnectSkills'
                        ])
                        ->find($id);
    }

    public function getRelated($userId, $id)
    {
        return $this->model
                        ->where('user_id', $userId)
                        ->where('_id', '<>', $id)
                        ->where([
                            'is_deleted'=> false,
                            'status' => 1
                        ])
                        ->orderBy('id','DESC')
                        ->limit(2)
                        ->get();
    }

    public function find($id)
    {
        return $this->model
                        ->find($id);
    }

    public function getJobs($id = null) {
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'content'
                        )
                        ->where([
                            'is_deleted'=> false,
                            'status' => 1
                        ])
                        ->when(isset($id), function ($query) use ($id) {
                            $query->where('user_id', $id);
                        })
                        ->latest()
                        ->limit(intval(request()->limit))
                        ->get();
    }
}
