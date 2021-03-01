<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Interfaces\LikeRepositoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\JobRepositoryInterface;
use App\Notifications\PushLikeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected $likeRepository;
    protected $userRepository;
    protected $postRepository;
    protected $jobRepository;

    public function __construct(
        LikeRepositoryInterface $likeRepository,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        JobRepositoryInterface  $jobRepository
    ) {
        $this->likeRepository = $likeRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->jobRepository  = $jobRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function like(Request $request)
    {
        $dataRequest = $request->only('type', '_id');

        $collection = $this->{$dataRequest['type'].'Repository'}->find($dataRequest['_id']);
        ($collection->isLikeBy(Auth::user())
            ? $collection->isLikeBy(Auth::user())->delete()
            : $this->store($dataRequest)
        );
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function store($dataRequest) {
        $like = Like::create([
            'own_id' => Auth::user()->id,
            $dataRequest['type'].'_id' => $dataRequest['_id']
        ]);
        dispatch(new PushLikeNotification($like , $dataRequest['type']));
    }


    public function getLikes(Request $request, $id)
    {
        $query = [
            $request->type. '_id' => $id
        ];
        $users = $this->userRepository->getLikes($query);
        return response()->json($users, 200);
    }

    public function totalLikes(Request $request, $id)
    {
        $query = [
            $request->type. '_id' => $id
        ];
        $total = $this->likeRepository->totalLikes($query);
        $isLike = $this->likeRepository->isLikedBy(Auth::user(), $query) == 1;
        return response()->json([
            'data' => [
                'total_likes' => $total,
                'is_like' => $isLike
            ]
        ], 200);
    }
}
