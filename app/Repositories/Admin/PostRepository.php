<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Helper\FileManage;
//use Your Model

/**
 * Class PostRepository.
 */
class PostRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\Post::class;
    }
    public function listPosts($queryRequest,$id) {
        $query = $this->model
            ->with([
                'getUser'=>function($query) use ($queryRequest) {
                    $query->select('*')->orderBy('created_at','desc');
                },
                'getPostReports'=> function ($query) use ($queryRequest) {
                    $query->select('_id','post_id','created_at')->orderBy('created_at','desc');
                }
            ])
            ->whereIn('user_id',$id)
            ->where('is_deleted',false);
        if(isset($queryRequest['filters']['user_id']))
            $query->where('user_id', $queryRequest['filters']['user_id']);
            
        if(!empty($queryRequest['filters']['orderName'])){
            $query->orderBy($queryRequest['filters']['orderName'], $queryRequest['filters']['orderType']);
        }
        
        else {
            $query->latest();
        }
        
        return $query->search($queryRequest['filters'])->get()
            ->map(function ($user) {
                return $user->append('report_count');
            });
    }
    public function detailPost($id) {
        return $this->model
            ->with(['getPostReports'=> function ($query) {
                $query->select('_id','post_id','reason','created_at')->orderBy('created_at','desc')->first();
            },
                'getUser'=>function($query) {
                $query->select('_id','username');
                },
                'getFiles' =>function($query) {
                $query->select('type','post_id','path');
                }])

            ->where('_id',$id)->first();
    }
}
