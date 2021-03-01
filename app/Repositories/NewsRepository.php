<?php

namespace App\Repositories;

use App\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use Carbon\Carbon;

/**
 * Class NewsRepository.
 */
class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var Model
    */
    protected $model;
    /**
     *
     * @param Model $model
    */
    public function __construct(News $model) {
        $this->model = $model;
    }

    public function list($dataRequest)
    {
        return $this->model
                        ->select(
                            '_id',
                            'title',
                            'content',
                            'publish_date',
                            'created_at',
                        )
                        ->where('is_deleted', false)
                        ->where('publish_date', '<=', Carbon::now())
                        ->orderBy('publish_date', 'desc')
                        ->paginate(intval($dataRequest['size']));
    }
}
