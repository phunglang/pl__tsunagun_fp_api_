<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use App\Exports\UserExport;
use App\Http\Requests\AccountRequest;
use Illuminate\Support\Facades\Hash;
use Excel;
class UserController extends Controller
{

     /**
     * Handle the incoming request.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private $userService;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }


     /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the incoming request.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
            $dataRequest = [
                'filters' => $request->only('nameSearch','areaSearch','departmentSelected','skillListID','skillStatusSelected','statusSelected','reportSelected', 'orderName', 'orderType'),
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
    public function show($id)
    {   
        $users = $this->userService->getById($id);
        return response()->json($users, 200);
    }
    
        /**
     * Handle the incoming request.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {   
        $dataRequest = $request->only("comment", "status", "note");
        $users = $this->userService->update($id, $dataRequest);
        return response()->json($users, 200);
    }
    
    public function download(Request $request)
    {
        $filters = $request->only('nameSearch','areaSearch','departmentSelected','skillListID','skillStatusSelected','statusSelected','reportSelected', 'orderName', 'orderType');
        if(isset($filters['skillListID']))
            $filters['skillListID'] = explode(",", $filters['skillListID']);
        if(isset($filters['skillStatusSelected']))
            $filters['skillStatusSelected'] = explode(",", $filters['skillStatusSelected']);
        if(isset($filters['statusSelected']))
            $filters['statusSelected'] = explode(",", $filters['statusSelected']);
        $dataRequest = $request->only("comment", "status", "note");
        
        return Excel::download(new UserExport($filters, $this->userService), 'user.xlsx');
    }
    
    public function account(Request $request)
    {
        return response()->json($request->user(), 200);
    }
    
    public function update_account(AccountRequest $request)
    {
        $data = ['email' => $request->email];
        if($request->password != '')
            $data['password'] =  Hash::make($request->password);
        $this->userService->update($request->user()->_id, $data);
        return response()->json(["logout" => true], 200);
    }
}
