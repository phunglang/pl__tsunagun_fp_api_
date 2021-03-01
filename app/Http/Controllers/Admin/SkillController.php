<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Skill;
use App\Repositories\Admin\SkillRepository;
use App\Services\Admin\SkillService;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    private $skillService;
    private $skillRepository;
    private $skill;
    public function __construct(SkillService $skillService,Skill $skill,SkillRepository $skillRepository)
    {
        $this->skillService = $skillService;
        $this->skillRepository = $skillRepository;
        $this->skill=$skill;
    }

    public function index(Request $request)
    {
        $dataRequest = ['filters' => [],
            'size' => $request->size];
       $skills= $this->skillRepository->listSkills($dataRequest);
       return response()->json($skills,200);
     }
     public function detail($_id)
     {
         $skill= $this->skillRepository->getById($_id);
         return response()->json($skill,200);

     }

     public function create(CategoryRequest $request)
     {
         $data = ['name'=>$request->name,
             'status' => $request->status,
             'is_deleted' => false];

         $this->skillRepository->create($data);
         return response()->json(['message'=>'created successfully'],200);

     }
    public function setStatus(Request $request)
    {

        $status= $request->input('status');
        $_idarr= $request->_id;
         $this->skillRepository->update($status,$_idarr);
         $this->skill->refresh();
         $skills=$this->skillRepository->get();
//                return response()->json($request, 200);

        return response()->json($skills, 200);
    }

    public function update(CategoryRequest $request)
    {
        $_id= $request->input('_id');
        $data=$request->except('token');
        $this->skillRepository->updateById($_id,$data);
          return response()->json(['message' => '※保存しました。'],200);

    }
    public function delete(Request $request)
    {
        $_idarr= $request->_id;
         $this->skillRepository->deleteSkills($_idarr);
        $this->skill->refresh();
        $skills=$this->skillRepository->get();

        return response()->json($skills, 200);

    }


}
