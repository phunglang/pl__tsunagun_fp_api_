<?php

namespace App\Services\Admin;

use App;
use App\Models\Province;
use Config;
use Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\JobRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class JobService
 * By: Huynh Le Anh Tai
 * @package App\Services
 */
class JobService
{
 /**
     * Property
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    protected $jobRepository;

    /**
     * Init.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */

    public function __construct(JobRepository $jobRepository) {
        $this->jobRepository = $jobRepository;
    } 

     /**
     * List Job.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    public function listJob($dataRequest) {
        if(!empty($dataRequest['filters']['areaSearch'])){
            $provinderId = Province::where('name', 'like', '%' . $dataRequest['filters']['areaSearch'] . '%')->pluck('_id')->toArray();
            $dataRequest['filters']['areaSearch'] = $provinderId;
        }
        
        $collection = $this->jobRepository->listJob($dataRequest);
        if(isset($dataRequest['filters']['orderName']) && in_array($dataRequest['filters']['orderName'], ['report_count', 'owner_name']) ) {
            if($dataRequest['filters']['orderType'] == 'asc')
                $collection =  $collection->sortBy( $dataRequest['filters']['orderName'] );   
            else
                $collection = $collection->sortByDesc( $dataRequest['filters']['orderName'] ); 
        }  
        
        return $collection->paginate($collection->count(), intval($dataRequest['size']), $dataRequest['page']);
    }
    
    public function update($id, $status) {
        return $this->jobRepository->update($id, ['status' => $status]);
    }
    
    
     /**
     * Delete Job.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    public function delete($delete_id) {
        $jobs = $this->jobRepository->deleteJob($delete_id);
        return $jobs;
    }
    
    /**
     * get Job By Id.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    public function getJobById($id) {
        $jobs = $this->jobRepository->getJobById($id);
        return $jobs;
    }
}
