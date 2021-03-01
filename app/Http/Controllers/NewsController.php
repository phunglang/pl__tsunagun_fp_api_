<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Interfaces\NewsRepositoryInterface;

class NewsController extends Controller
{
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository) {
        $this->newsRepository = $newsRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataRequest = [
            'size' => $request->size
        ];
        $news = $this->newsRepository->list($dataRequest);
        return response()->json($news, 200);
    }
}
