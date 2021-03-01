<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPostRequest;
use App\Models\Post;
use App\Repositories\Admin\PostRepository;
use App\Services\Admin\PostService;
use App\Services\Admin\SkillService;
use Illuminate\Http\Request;
use MongoDate;

class PostController extends Controller
{
    private $postService;
    private $postRepository;
    private $post;
    public function __construct(Post $post,PostRepository $postRepository,PostService $postService)
    {
        $this->postRepository = $postRepository;
        $this->post=$post;
        $this->postService=$postService;
    }

    public function listAdminPost(Request $request)
    {
        $dataRequest = [
            'filters' => $request->only('orderName', 'orderType'),
            'size' => $request->size
        ];
        $posts= $this->postService->listAdPosts($dataRequest);
        return response()->json($posts,200);
    }

    public function listUserPost(Request $request)
    {

        $dataRequest = [
            'filters' => $request->only('report_status_selected','user_status_selected','date_start','date_end','text_search', 'user_id', 'orderName', 'orderType'),
            'size' => $request->size,
            'page' => $request->page
        ];
        $posts= $this->postService->listUserPosts($dataRequest);
        return response()->json($posts,200);
    }

    public function detail($id)
    {
        $post= $this->postService->detailNew($id);
        return response()->json($post,200);

    }
    public function detailUserPost($id)
    {
        $post= $this->postService->getWithReport($id);
        return response()->json($post,200);

    }

    public function create(AdminPostRequest $request)
    {
        $data = [
            'publish_date'=>new \MongoDB\BSON\UTCDateTime(strtotime($request->publish_date)*1000) ,
            'title'=>$request->title,
            'content'=>$request->input('content'),
            'is_deleted' => false];

        $this->postService->createNews($data);

        return response()->json(['message'=>'created successfully'],200);

    }
    public function update(AdminPostRequest $request)
    {
        $_id = $request->_id;
        $data=$request->except('_id');
         $data['publish_date']=new \MongoDB\BSON\UTCDateTime(strtotime($data['publish_date'])*1000);

        $result=  $this->postService->updateNewById($_id,$data);
        return response()->json(['message'=>'created successfully','data'=> $result],200);

    }
    public function updateUserPost(Request $request)
    {
        $_id = $request->_id;
        $data=$request->only('status');
       $result= $this->postRepository->updateById($_id,$data);
        return response()->json(['message'=>'updated successfully','data'=>$result],200);

    }
public function deleteNew(Request $request)
    {
        $_id= $request->_id;
        $posts= $this->postService->updateNewById($_id,['is_deleted'=>true]);
        return response()->json(['message'=>'deleted successfully','data'=>$posts], 200);

    }
    public function delete(Request $request)
    {
        $_id= $request->_id;
       $posts= $this->postRepository->updateById($_id,['is_deleted'=>true]);
        return response()->json(['message'=>'deleted successfully','data'=>$posts], 200);

    }


}
