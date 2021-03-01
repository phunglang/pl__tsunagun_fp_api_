<?php

namespace App\Services\Admin;

use App;
use App\Models\Province;
use Config;
use Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class UserService
 * By: Vu Trong Luat
 * @package App\Services
 */
class UserService
{
 /**
     * Property
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    protected $userRepository;

    /**
     * Init.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

     /**
     * List User.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    public function listUser($dataRequest) {
        if(!empty($dataRequest['filters']['areaSearch'])){
            $listArea = explode(',', $dataRequest['filters']['areaSearch']);
            // $provinderId = Province::where('name', 'like', '%' . $dataRequest['filters']['areaSearch'] . '%')->pluck('_id')->toArray();
            $provinderId = Province::whereIn('name', $listArea)->pluck('_id')->toArray();
            $dataRequest['filters']['areaSearch'] = $provinderId;
        }
        // DB::enableQueryLog();
        $users = $this->userRepository->listUser($dataRequest);
        // dd(DB::getQueryLog());
        return $users;
    }

    public function listUserByIds($ids) {
        return $this->userRepository->listUserByIds($ids);
    }

    public function update($id, $data) {
        return $this->userRepository->update($id, $data);
    }

    public function getById($id) {
        return $this->userRepository->getUserById($id);
    }
}
