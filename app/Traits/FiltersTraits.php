<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait FiltersTraits
{
    public function apply(Builder $builder, $dataFilter)
    {
        $options = $this->options();
        foreach ($options[$this->collection] as $item) {
            switch ($item) {
                case 'skill_ids':
                    $builder->when(isset($dataFilter['skill_ids']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {
                            switch ($this->collection) {
                                case 'users':
                                    $query->whereHas('getCertificates', function ($q) use ($dataFilter) {
                                        $q->whereIn('skill_id', $dataFilter['skill_ids']);
                                    });
                                    break;
                                case 'jobs':
                                    foreach($dataFilter['skill_ids'] as $skill_id) {
                                        $query->orWhereIn('connect_skills', [$skill_id]);
                                    }
                                    break;
                                default:
                                    break;
                            }
                        });
                    });
                    break;
                case 'department':
                    $builder->when(isset($dataFilter['department']), function ($query) use ($dataFilter) {
                        $query->where('department', (int)$dataFilter['department']);
                    });
                    break;
                case 'ages':
                    $builder->when(isset($dataFilter['ages']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {
                            foreach($dataFilter['ages'] as $age) {
                                $query->orWhereBetween('birthday', [now()->subYears((int)$age + 10), now()->subYears((int)$age)]);
                            }
                        });
                    });
                    break;
                case 'provincial_ids':
                    $builder->when(isset($dataFilter['provincial_ids']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {
                            foreach($dataFilter['provincial_ids'] as $provincial) {
                                $query->orWhereIn('connect_areas', [$provincial]);
                            }
                        });
                    });
                    break;
                case 'recruiting_end':
                    $builder->when(isset($dataFilter['recruiting_end']), function ($query) use ($dataFilter) {
                        $query
                            ->where('recruiting_start', '<=', Carbon::now())
                            ->where('recruiting_end', '>=', Carbon::parse($dataFilter['recruiting_end'] . " 23:59:59"));
                    });
                    break;
                case 'keyword':
                    $builder->when(isset($dataFilter['keyword']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {
                            switch ($this->collection) {
                                case 'users':
                                    $query
                                        ->where('username', 'like', '%'.$dataFilter['keyword'].'%')
                                        ->orWhere('genre', 'like', '%'.$dataFilter['keyword'].'%')
                                        ->orWhere('experience', 'like', '%'.$dataFilter['keyword'].'%')
                                        ->orWhere('comment', 'like', '%'.$dataFilter['keyword'].'%');
                                    break;
                                case 'posts':
                                    $query
                                        ->where('title', 'like', '%'.$dataFilter['keyword'].'%')
                                        ->orWhere('content', 'like', '%'.$dataFilter['keyword'].'%');
                                    break;
                                case 'jobs':
                                    $query
                                        ->where('title', 'like', '%'.$dataFilter['keyword'].'%')
                                        ->orWhere('content', 'like', '%'.$dataFilter['keyword'].'%');
                                    break;
                                default:
                                    // $query->whereRaw(['$text' => ['$search' => $dataFilter['keyword']]]);
                                    break;
                            }
                        });
                    });
                    break;
                default:
                    $builder->orderBy(request()->sort ?? 'created_at', 'desc');
                    break;
            }
        }

        return $builder;
    }

    public function options()
    {
        return [
            'users'  => ['skill_ids', 'department', 'provincial_ids', 'ages', 'keyword'],
            'jobs'   => ['skill_ids', 'provincial_ids', 'recruiting_end', 'keyword'],
            'posts'  => ['keyword']
        ];
    }
}

