<?php

namespace App\Http\Controllers;

use App\Interfaces\JobRepositoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $userRepository;
    protected $postRepository;
    protected $jobRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository,
        JobRepositoryInterface  $jobRepository
    ) {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->jobRepository  = $jobRepository;
    }

    public function getUsers() {
        $users = $this->userRepository->getUsers();
        return response()->json([
            'data' =>  $users
        ], 200);
    }

    public function getPosts() {
        $posts = $this->postRepository->getPosts();
        return response()->json([
            'data' =>  $posts
        ], 200);
    }

    public function getJobs() {
        $jobs = $this->jobRepository->getJobs();
        return response()->json([
            'data' =>  $jobs
        ], 200);
    }

    public function getMyJobs() {
        $jobs = $this->jobRepository->getJobs(Auth::user()->id);
        return response()->json([
            'data' =>  $jobs
        ], 200);
    }
}
