<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPostRequest;
use App\Services\Admin\JobService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private $jobService;
    public function __construct(JobService $jobService)
    {
        $this->jobService=$jobService;
    }

    public function index(Request $request)
    {
        if(isset($request->delete_id)) {
            $this->jobService->delete($request->delete_id);
        }
        $dataRequest = [
            'filters' => $request->only('areaSearch', 'date_start', 'date_end', 'recruiting_start', 'recruiting_end', 'skillListID', 'text_search', 'recruiting_end', 'statusSelected','reportSelected', 'user_id', 'orderName', 'orderType'),
            'size' => $request->size,
            'page' => $request->page
        ];
        $jobs = $this->jobService->listJob($dataRequest);
        return response()->json($jobs, 200);
    }    
    
    public function show($id)
    {
        $jobs = $this->jobService->getJobById($id);
        return response()->json($jobs, 200);
    }    
    
    public function update($id, Request $request)
    {
        $jobs = $this->jobService->update($id, $request->status);
        return response()->json($jobs, 200);
    }
    
    public function delete($id)
    {
        $jobs = $this->jobService->delete($id);
        return response()->json("ok", 200);
    }
}
