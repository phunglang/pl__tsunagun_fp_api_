<?php
namespace App\Services\Admin;

use App;
use App\Repositories\Admin\NewsRepository;
use Config;
use Request;
use App\Post;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\PostRepository;
use App\Repositories\Admin\UserRepository;

class PostService
{
    /**
     * Property
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    protected $postRepository;
protected $userRepository;
protected $newRepository;
    /**
     * Init.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */

    public function __construct(PostRepository $postRepository,UserRepository $userRepository,NewsRepository $newRepository) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->newRepository = $newRepository;
    }

    /**
     * List User.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    public function listAdPosts ($dataRequest){
        return  $this->newRepository->listAdminPost($dataRequest);
    }

    public function listUserPosts ($dataRequest){
        
        $user_id = $this->userRepository->getUser();
        $arr=[];

        foreach ($user_id as $val ){
            array_push($arr, $val['_id']);
        }
        
        $collection = $this->postRepository->listPosts($dataRequest,$arr);
        
        if(isset($dataRequest['filters']['orderName']) && $dataRequest['filters']['orderName'] == 'report_count') {
            if($dataRequest['filters']['orderType'] == 'asc')
                $collection =  $collection->sortBy('report_count');   
            else  
                $collection = $collection->sortByDesc('report_count'); 
        }
        
        return $collection->paginate($collection->count(), intval($dataRequest['size']), $dataRequest['page']);
    }

    public function getWithReport ($id) {
       $report= $this->postRepository->detailPost($id);
       return $report;
    }

    public function detailNew($id) {
       return $this->newRepository->getById($id);
    }
    public function updateNewById($_id,$data) {
            return $this->newRepository->updateById($_id,$data);
    }

    public function createNews($data)
    {
        return $this->newRepository->create($data);
    }
}
