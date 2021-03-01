<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Requests\JobRequest;
use App\Interfaces\JobRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JobController extends Controller
{
    protected $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository) {
        $this->jobRepository = $jobRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataRequest = [
            'filters' => $request->only('skill_ids', 'provincial_ids', 'recruiting_end', 'keyword'),
            'size' => $request->size,
        ];
        $jobs = $this->jobRepository->list($dataRequest);
        return response()->json($jobs, 200);
    }

    public function getJobs(Request $request)
    {
        $dataRequest = [
            'size' => $request->size,
        ];
        $jobs = $this->jobRepository->list($dataRequest, Auth::user()->id);
        return response()->json($jobs, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)
    {
        $dataRequest = $request->only('title','content','recruiting_end','connect_areas','connect_skills');

        $job = new Job();
        $job->title = $dataRequest['title'];
        $job->content = $dataRequest['content'];
        $job->recruiting_start = Carbon::now();
        $job->recruiting_end = $dataRequest['recruiting_end'];
        $job->status = 1;
        $job->user_id = Auth::user()->id;
        $job->is_deleted = false;
        $job->save();
        $job->getConnectAreas()->attach($dataRequest['connect_areas']);
        $job->getConnectSkills()->attach($dataRequest['connect_skills']);
        return response()->json([
            'message' => 'Success'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = $this->jobRepository->detail($id);
        if($job->user_id != Auth::user()->id) {
            $related = $this->jobRepository->getRelated($job->user_id, $id);
            $job['get_related'] = $related;
        }
        return response()->json([
            'data' =>  $job
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(JobRequest $request, $id)
    {
        $dataRequest = $request->only('title','content','recruiting_end','connect_areas','connect_skills');

        $job = $this->jobRepository->find($id);
        $job->title = $dataRequest['title'];
        $job->content = $dataRequest['content'];
        $job->getConnectAreas()->attach($dataRequest['connect_areas']);
        $job->getConnectSkills()->attach($dataRequest['connect_skills']);
        $job->recruiting_end = $dataRequest['recruiting_end'];
        $job->save();
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = $this->jobRepository->find($id);
        $job->update([
            'is_deleted' => true
        ]);
    }
}
