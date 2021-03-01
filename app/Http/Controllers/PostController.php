<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Helpers\FileManage;
use App\Http\Requests\PostRequest;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository) {
        $this->postRepository = $postRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $dataRequest = [
            'filters' => $request->only('keyword'),
            'size' => $request->size
        ];
        $posts = $this->postRepository->list($dataRequest);
        return response()->json($posts, 200);
    }

    public function getPosts(Request $request, $id) {
        $dataRequest = [
            'size' => $request->size
        ];
        $posts = $this->postRepository->list($dataRequest, $id);
        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $dataRequest = $request->only('title', 'content', 'files');
        $post = new Post();
        $post->title = $dataRequest['title'];
        $post->content = $dataRequest['content'];
        $post->status = 1;
        $post->is_deleted = false;
        $post->user_id = Auth::user()->id;
        $post->save();

        if(isset($dataRequest['files'])) {
            foreach ($dataRequest['files'] as $item) {
                $imageFileName = time().'.'.$item->getClientOriginalExtension();
                $file = new FileManage($imageFileName, $item, 'App\Models\File', 'public', 's3', 'uploads/tsunagun_fp');
                $file->uploadFileToS3([
                    'type' => 'image',
                    'post_id' => $post->id
                ]);
            };
        }
        return response()->json([
            'message' => 'Success'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->postRepository->find($id);
        $post->update([
            'is_deleted' => true
        ]);
    }
}
