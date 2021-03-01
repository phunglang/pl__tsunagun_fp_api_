<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
/**
 * Class UserRepository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Model
    */
    protected $model;

    /**
     *
     * @param Model $model
    */
    public function __construct(User $model) {
        $this->model = $model;
    }

    public function getListContact() {
        $listIdUserChat =  Auth::user()->chat_user_ids;
        if (!empty($listIdUserChat)) {
            return $this->model->whereIn('_id', $listIdUserChat)->paginate(3);
        }
        return [];
    }

    public function blockUser($dataRequest) {
        $own = Auth::user();
        $own->push('block_user_ids', $dataRequest['idUserReported'], true);
    }

    public function checkUserByCredentials($query) {
        return $this->model
                        ->where($query)
                        ->first();
    }

    public function infoProfile() {
        return Auth::user()
                        ->load('getFile','settingNotification')
                        ->load(['getCertificates' => function ($query) {
                            $query->select(
                                    'skill_id',
                                    'user_id',
                                    'status'
                                )->with('getSkill:name');
                        }]);
    }

    public function detailUser($id) {
        //show: username, avatar, tuoi, get_likes, department, khu vuc
        //profile: comment, get_certificates, khu vuc, genre, experience, websites, get_jobs(2)
        //post: username, avatar, created_at, title, content
        return $this->model
                        ->select(
                            '_id',
                            'avatar',
                            'username',
                            'comment',
                            'department',
                            'genre',
                            'experience',
                            'birthday',
                            'websites',
                            'last_login_at',
                            'created_at'
                        )
                        ->with([
                            'getJobs'=> function ($query) {
                                $query
                                    ->where([
                                        'is_deleted'=> false,
                                        'status' => 1
                                    ])
                                    ->latest()
                                    ->limit(2);
                            },
                            'getCertificates' => function ($query) {
                                $query->select(
                                        'skill_id',
                                        'user_id',
                                        'status'
                                    )->with('getSkill:name');
                            },
                            'getConnectAreas'
                        ])
                        ->find($id)
                        ->append('age', 'is_like', 'total_likes');
    }

    public function listMember($dataRequest) {
        //list show: username, avatar, tuoi, like_count, department, khu vuc(area), comment
        //search: skill_id of chung chi(n), department(1), khu vuc(n), tuoi(n), keyword
        //sort: like_count, created_at
        //$projections = ['username', 'department', 'area', 'experience', 'genre', 'websites', 'sex', 'billing_status'];
        //with[], filters[],where[]: status->1
        return $this->model
                        ->select(
                            '_id',
                            'avatar',
                            'username',
                            'comment',
                            'department',
                            'connect_areas',
                            'birthday',
                            'created_at'
                        )
                        ->where([
                            'role' => 0,
                            'status' => 1,
                            'is_deleted' => false
                        ])
                        ->filter($dataRequest['filters'])
                        ->with('getConnectAreas')
                        ->paginate(intval($dataRequest['size']))
                        ->map(function ($user) {
                            return $user->append('age', 'is_like', 'total_likes');
                        });
    }

    public function getUsers() {
        return $this->model
                        ->select(
                            '_id',
                            'avatar',
                            'username',
                            'created_at'
                        )
                        ->where([
                            'role' => 0,
                            'status' => 1,
                            'is_deleted' => false
                        ])
                        ->with('getConnectAreas')
                        ->latest()
                        ->limit(intval(request()->limit))
                        ->get();
    }

    public function find($id)
    {
        return $this->model
                        ->find($id);
    }

    public function getLikes($query) {
        $ownIds = Like::where($query)
                        ->latest()
                        ->pluck('own_id')
                        ->toArray();

        return $this->model
                        ->select(
                            '_id',
                            'avatar',
                            'username',
                            'created_at'
                        )
                        ->whereIn('_id', $ownIds)
                        ->paginate(intval(request()->limit));
    }

    public function listBlock() {
        return $this->model
                        ->select(
                            '_id',
                            'avatar',
                            'username',
                            'created_at'
                        )
                        ->whereIn('_id', Auth::user()->block_user_ids ?? [])
                        ->paginate(intval(request()->size));
    }
}
