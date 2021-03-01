<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NgWordRequest;
use App\Repositories\Admin\NgWordRepository;
use Illuminate\Http\Request;

class NgWordController extends Controller
{
    private $_repository;
    public function __construct(NgWordRepository $ngWordRepository)
    {
        $this->_repository = $ngWordRepository;
    }

    public function listNgWord()
    {
        $result=$this->_repository->get();
        return response()->json($result,200);
    }

    public function update(Request $request)
    {
        $create= $request->input('tagAdd');
        $delete = $request->input('tagRemove');
        if(count($create) == 0 && count($delete) ==0)
            return response()->json(['message'=>'NGワードは必須です。','errors'=>'NGワードは必須です。'],422);

        foreach($create as $item) {
            $da=['name'=>$item];
            $this->_repository->create($da);

        }
        foreach($delete as $item) {
            $this->_repository->deleteByName($item);
        }
        return response()->json(['message'=>'登録しました。'],200);

    }


}
