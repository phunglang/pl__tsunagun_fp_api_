<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    protected $model;
    /**
     * @return string
     *  Return the model
     */
    public function __construct(Post $model) {
        $this->model = $model;
    }

    public function list($dataRequest, $id = null) {
        //show: user(avatar, username), created_at, content, files()
        //search: keyword
        //where: status, is_deleted
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'status',
                            'content',
                            'created_at',
                            'user_id'
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
                        ->with(['getUser:avatar,username', 'getFiles'])
                        ->latest()
                        ->paginate(intval($dataRequest['size']))
                        ->map(function ($user) {
                            return $user->append('is_like', 'total_likes');
                        });
    }

    public function find($id) {
        return $this->model
                        ->find($id);
    }

    public function getPosts()
    {
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'content',
                            'user_id'
                        )
                        ->where([
                            'is_deleted'=> false,
                            'status' => 1
                        ])
                        ->with(['getUser:avatar,username'])
                        ->latest()
                        ->limit(intval(request()->limit))
                        ->get();
    }
}
