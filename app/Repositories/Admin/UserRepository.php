<?php

namespace App\Repositories\Admin;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

//use Your Model

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\User::class;
    }
    public function listUser($dataRequest) {

        $query = $this->model->select('created_at','username', 'index', 'birthday','connect_areas','department','ID_validate','status', 'area')
                            ->with([
                                'getCertificates' => function ($query) {
                                        $query->select('user_id','skill_id','status')->with('getSkill:name');
                                },
                                'getUserReports' => function ($query) use ($dataRequest) {
                                    $query->select('user_id','created_at')->orderBy('created_at','desc');
                                },
                                'getConnectAreas' => function ($query) {
                                    $query->select('*');
                                },
                            ])
                            ->whereNotNull('ID_validate')
                            ->where('role', 0)
                            ->search($dataRequest['filters']);
                            
        if(!empty($dataRequest['filters']['orderName'])){
            $query->orderBy($dataRequest['filters']['orderName'], $dataRequest['filters']['orderType']);
        }
        else {
            $query->latest();
        }
        
        if(isset($dataRequest['size']))
            return $query->paginate(intval($dataRequest['size']));          
        else
            return $query->get();
    }

    public function getAdmin() {
        return $this->model
            ->select('_id')
            ->where('role',1)->get();
    }

    public function getUser() {
        return $this->model
            ->select('_id')
            ->where('role',0)->get();
    }

    public function updateCertificates($attributes, $id) {
        $result= $this->model->with('getCertificates')->where("_id",$id)->first();
        if($result)
        {
            $resCert = $result->getCertificates;
            foreach ($resCert as $val ){
                $val->images = $attributes['images'];
                $val->save();
            }
            return $result;
        }
        return false;

    }
    public function listUserByIds($ids) {
        return $this->model->select('username', 'birthday','department','ID_validate','status', 'image')
                            ->with([
                                'getCertificates' => function ($query) {
                                        $query->select('user_id','skill_id','status','images')->with('getSkill:name')->with('getImages');
                                },
                                'getIdImages'=> function ($query) {
                                    $query->select('title','path','user_id');
                                },
                                'getConnectAreas' => function ($query) {
                                    $query->select('*');
                                },
                            ])
                            ->where('role', 0)
                            ->whereIn('_id', $ids)
                            ->get();
    }
    
    public function getUserById($id) {
        return $this->model->select('*')
        ->with([
            'getCertificates' => function ($query) {
                    $query->select('user_id','skill_id','status','images')->with('getSkill:name')->with('getImages');
            },
            'getIdImages'=> function ($query) {
                $query->select('title','path','user_id');
            },
            'getUserReports' => function ($query) {
                $query->select('user_id','created_at', 'reason')->orderBy('created_at','desc')->get();
            },
            'getConnectAreas' => function ($query) {
                $query->select('*');
            },
        ])
        ->where('_id', $id)
        ->first();
    }
    
    public function update($id, $data)
    {
        $this->model->where("_id",$id)->update($data);
    }
}
