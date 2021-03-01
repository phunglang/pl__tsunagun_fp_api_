<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait Filters
{
    public function applySearch(Builder $builder, $dataFilter){
        $options = $this->optionsSearch();
        foreach ($options[$this->collection] as $item) {
            switch($item){
                case 'username':
                    $builder->when(isset($dataFilter['nameSearch']), function ($query) use ($dataFilter) {
                        $query->where('username', $dataFilter['nameSearch']);
                    });
                    break;
                case 'connect_area':
                    $builder->when(isset($dataFilter['areaSearch']), function ($query) use ($dataFilter) {
                        // $query->whereHas('getConnectAreas', function ($q) use ($dataFilter) {
                        //     $q->whereIn('_id', $dataFilter['areaSearch']);
                        // });
                        //$query->whereIn('area', $dataFilter['areaSearch']);
                        $query->where(function ($query) use ($dataFilter) {
                            $query->where('_id', "No");
                            foreach($dataFilter['areaSearch'] as $provincial) {
                                $query->orWhere('connect_areas', $provincial, true);
                            }
                        });
                    });   
                    break;
                case 'department':
                        $builder->when(isset($dataFilter['departmentSelected']), function ($query) use ($dataFilter) {
                            $query->where('department', $dataFilter['departmentSelected']);
                        });
                        break;
                case 'skill':
                    if($this->collection == 'jobs') {
                        $builder->when(isset($dataFilter['skillListID']), function ($query) use ($dataFilter) {
                            $query->where(function ($query) use ($dataFilter) {
                                foreach($dataFilter['skillListID'] as $skill) {
                                    $query->where('connect_skills', $skill, true);
                                }
                            });
                        });
                    }
                    else {
                        $builder->when(isset($dataFilter['skillListID']), function ($query) use ($dataFilter) {
                            $query->whereHas('getCertificates', function ($q) use ($dataFilter) {
                                $q->whereIn('skill_id', $dataFilter['skillListID']);
                            });
                        });
                    }
                        break;
                case 'skillStatusSelected':
                            $builder->when(isset($dataFilter['skillStatusSelected']), function ($query) use ($dataFilter) {
                                $query->whereHas('getCertificates', function ($q) use ($dataFilter) {
                                    $q->whereIn('status', array_map('intval',$dataFilter['skillStatusSelected']));
                                });
                            });
                        break;
                case 'statusSelected':
                    if($this->collection == 'jobs') {
                        $builder->when(isset($dataFilter['statusSelected']), function ($query) use ($dataFilter) {
                            $query->where(function ($query) use ($dataFilter) {
                                $query->whereHas('getUser', function ($q) use ($dataFilter) {
                                    $q->where('status', intval($dataFilter['statusSelected']));
                                });
                             });
                        });
                    }
                    else {
                            $builder->when(isset($dataFilter['statusSelected']), function ($query) use ($dataFilter) {
                                $query->whereIn('status',array_map('intvalval',$dataFilter['statusSelected']));
                            });
                    }
                    
                    break;
                case 'reportSelected':
                    if($this->collection == 'jobs') {
                        $builder->when(isset($dataFilter['reportSelected']), function ($query) use ($dataFilter) {
                            if((int)$dataFilter['reportSelected'] == 1) {
                                    $query->whereHas('getUserReports');
                            }else if ((int)$dataFilter['reportSelected'] == 0){
                                $query->whereDoesntHave('getUserReports');
                            }
                        });   
                    }
                    else {
                        $builder->when(isset($dataFilter['reportSelected']), function ($query) use ($dataFilter) {
                            if((int)$dataFilter['reportSelected'] == 1) {
                                    $query->whereHas('getUserReports');
                            }else if ((int)$dataFilter['reportSelected'] == 0){
                                $query->whereDoesntHave('getUserReports');
                            }
                        });
                    }
                    break;
                case 'report_status_selected':
                    $builder->when(isset($dataFilter['report_status_selected']), function ($query) use ($dataFilter) {
                        if((int)$dataFilter['report_status_selected'] == 1) {
                            $query->whereHas('getPostReports');
                        }else if ((int)$dataFilter['report_status_selected'] == 0){
                            $query->whereDoesntHave('getPostReports');
                        }
                    });
                    break;
                case 'user_status_selected':
                    $builder->when(isset($dataFilter['user_status_selected']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {

                            $query->whereHas('getUser', function ($q) use ($dataFilter) {
                                $q->whereIn('status', array_map('intval', $dataFilter['user_status_selected']));
                            });
                         });
                    });
                    break;
                case 'text_search':
                    if($this->collection == 'jobs') {
                        $builder->when(isset($dataFilter['text_search']), function ($query) use ($dataFilter) {
                            $query->where(function ($query) use ($dataFilter) {
                                $query->where('content', 'like', '%' . $dataFilter['text_search'] . '%')
                                ->orWhere('title', 'like', '%' . $dataFilter['text_search'] . '%');
                            });
                        });
                    }
                    else {
                        $builder->when(isset($dataFilter['text_search']), function ($query) use ($dataFilter) {
                            $query->where(function ($query) use ($dataFilter) {
                                $query->whereHas('getUser',function ($q) use ($dataFilter) {
                                    $q->where('username', $dataFilter['text_search']);
                                })->orWhere('title', $dataFilter['text_search']);

                            });
                        });   
                    }
                    break;
                case 'date_start':
                    $builder->when(isset($dataFilter['date_start']) , function ($query) use ($dataFilter) {
                        $date_start =\Carbon\Carbon::parse($dataFilter['date_start']);
                        if(isset($dataFilter['date_end'])) {
                            $date_end =\Carbon\Carbon::parse($dataFilter['date_end']);
                            $query->whereBetween('created_at',[$date_start,$date_end]);
                        } else
                            $query->where('created_at','>=',$date_start);
                    });
                    break;
                case 'date_end':
                    $builder->when(isset($dataFilter['date_end']), function ($query) use ($dataFilter) {
                        $date_end =\Carbon\Carbon::parse($dataFilter['date_end']);
                        if(isset($dataFilter['date_start'])) {
                            $date_start=\Carbon\Carbon::parse($dataFilter['date_start']);
                            $query->whereBetween('created_at',[$date_start,$date_end]);
                        } else
                            $query->where('created_at','<=',$date_end);
                    });
                    break;
                case 'recruiting_start':
                    $builder->when(isset($dataFilter['recruiting_start']) , function ($query) use ($dataFilter) {
                        $recruiting_start = \Carbon\Carbon::parse($dataFilter['recruiting_start']);
                        $query->where('recruiting_end', '>', $recruiting_start);
                    });
                    break;
                case 'recruiting_end':
                    $builder->when(isset($dataFilter['recruiting_end']) , function ($query) use ($dataFilter) {
                        $recruiting_end = \Carbon\Carbon::parse($dataFilter['recruiting_end']);
                        if(isset($dataFilter['recruiting_start'])) {
                            $recruiting_end->modify('+1 day');
                            $recruiting_start = \Carbon\Carbon::parse($dataFilter['recruiting_start']);
                            $recruiting_start->modify('+1 day');
                            $query->whereBetween('recruiting_end',[$recruiting_start, $recruiting_end]);
                        }
                        else {
                            $query->where('recruiting_end', '>=', $recruiting_end);
                        }
                    });
                    break;
                case 'user_status_selected':
                    $builder->when(isset($dataFilter['user_status_selected']), function ($query) use ($dataFilter) {
                        $query->where(function ($query) use ($dataFilter) {

                            $query->whereHas('getUser', function ($q) use ($dataFilter) {
                                $q->whereIn('status', array_map('intval', $dataFilter['user_status_selected']));
                            });
                            });
                    });
                    break;
                default:
                    break;
            }
        }
    }

    public function optionsSearch(){
        return [
            'users'          => ['username', 'connect_area','department','skill','skillStatusSelected','statusSelected','reportSelected'],
            'posts'          => ['report_status_selected','user_status_selected','text_search','date_start','date_end'],
            'jobs'          => [
                                'connect_area',
                                'skill',
                                'statusSelected',
                                'reportSelected', 
                                'date_start',
                                'date_end', 
                                'recruiting_start', 
                                'recruiting_end',
                                'text_search'
                            ],
        ];
    }
}
