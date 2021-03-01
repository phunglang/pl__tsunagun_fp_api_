<?php

namespace App\Http\Controllers;

use App\Interfaces\SkillRepositoryInterface;

class SkillController extends Controller
{
    protected $skillRepository;

    public function __construct(SkillRepositoryInterface $skillRepository) {
        $this->skillRepository = $skillRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()  {
        $kills = $this->skillRepository->list();
        return response()->json([
            'data' => $kills
        ], 200);
    }
}
