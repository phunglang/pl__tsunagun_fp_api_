<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\UserService;
use App\Services\Admin\CertificateService;

class ValidationController extends Controller
{

    protected $userService;
    protected $certificateService;

    public function __construct(UserService $userService, CertificateService $certificateService)
    {
        $this->userService = $userService;
        $this->certificateService = $certificateService;
    }

     /**
     * Handle the incoming request.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $dataRequest = [
            'filters' => $request->only('userName', 'orderName', 'orderType'),
            'size' => $request->size
        ];
        $users = $this->userService->listUser($dataRequest);
        return response()->json($users, 200);
    }
    
      /**
     * Handle the incoming request.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {   
        $ids = $request->users;
        $users = $this->userService->listUserByIds($ids);
        return response()->json($users, 200);
    }
    
     /**
     * Handle the incoming request.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $id = $request->_id;
        $status = $request->status;
        $type = $request->type;
        
        $result = false;
        if($type == 0)
            $result = $this->userService->update($id, ['ID_validate' => $status]);
        else 
            $result = $this->certificateService->update($id, ['status' => $status]);
        return response()->json($result, 200);
    }
}


