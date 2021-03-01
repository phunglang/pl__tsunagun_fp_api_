<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class NewsRepository.
 */
class NewsRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\News::class;
    }

    public function listAdminPost($dataRequest)
    {
        $query = $this->model
            ->select(
                '_id',
                'title',
                'content',
                'publish_date'
            )
            ->where('is_deleted', false);
        if(!empty($dataRequest['filters']['orderName'])){
            $query->orderBy($dataRequest['filters']['orderName'], $dataRequest['filters']['orderType']);
        }
        else {
            $query->latest();
        }
        return $query->paginate(intval($dataRequest['size']));
    }
}
