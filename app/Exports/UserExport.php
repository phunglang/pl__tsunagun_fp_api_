<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Services\Admin\UserService;
use DateTime;

class UserExport implements FromCollection,WithHeadings,WithMapping
{
    private $userService;
    public function __construct($filters, UserService $userService) 
    {
        $this->filters = $filters;
        $this->userService = $userService;
    }
    
    public function headings(): array
    {
        return [
            '登録年月日',
            'ユーザーネーム',
            'エリア',
            '年齢',
            '所属',
            'スキル/資格',
            'スキル認証',
            '身分証認証',
            'ステータス',
            '通報履歴'
        ];
    }
    
    public function map($user): array {
        return [
            (new DateTime($user->created_at))->format('Y-m-d'),
            $user->username,
            $this->getArea($user),
            $user->age,
            $this->getDeparment($user),
            $this->getSkill($user),
            $this->getSkillValidate($user),
            $this->getIdValidate($user),
            $this->getStatus($user),
            $this->getReport($user)
        ];
    }

    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dataRequest = [
            'filters' => $this->filters
        ];
        $users = $this->userService->listUser($dataRequest);
        //dd($users);
        return $users;
    }
    
    public function getSkill($user)
    {
        $skill = [];
        foreach($user->getCertificates as $certificate) {
            $skill[] = $certificate->getSkill->name;
        }
        return implode(", ", $skill);
    }
    
    public function getSkillValidate($user)
    {
        $skill = [];
        foreach($user->getCertificates as $certificate) {
            if($certificate->status == 1)
                $skill[] = $certificate->getSkill->name;
        }
        return implode(", ", $skill);
    }
    
    public function getIdValidate($user)
    {
        switch($user->ID_validate) {
            case 0:
                return '認証待ち';
            case 1:
                return '認証済';
            case 2:
                return '未認証';
        }
    }
    
    public function getDeparment($user) 
    {
        switch($user->department) {
            case 0:
                return '保険会社専属';
            case 1:
                return '保険代理店';
            default:
                return 'その他';
        }   
    }
    
    public function getStatus($user)
    {
        return $user->status == 1 ? '有効' : '無効';
    }
    
    public function getReport($user)
    {
        $count = count($user->getUserReports->toArray());
        if($count == 0)
            return "";
        return $count. ' 時間';
    }
    
    public function getArea($user)
    {
        $count = count($user->getConnectAreas->toArray());
        if($count == 0)
            return "";
        $area_arr = [];
        foreach($user->getConnectAreas as $area) {
            $area_arr[] = $area->name;
        }
        return implode(", ", $area_arr);
    }
}
