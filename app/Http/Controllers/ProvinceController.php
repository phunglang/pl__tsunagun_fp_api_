<?php

namespace App\Http\Controllers;

use App\Interfaces\ProvinceRepositoryInterface;

class ProvinceController extends Controller
{
    protected $provinceRepository;

    public function __construct(ProvinceRepositoryInterface $provinceRepository) {
        $this->provinceRepository = $provinceRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()  {
        $provincials = $this->provinceRepository->getProvince();
        return response()->json([
            'data' => $provincials
        ], 200);
    }
}
