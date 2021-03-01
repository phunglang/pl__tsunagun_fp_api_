<?php
namespace App\Repositories;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
/**
 * Class UserRepository.
 */
class ReportRepository extends BaseRepository
{
   /**
     * @return string
     *  Return the model
     */
    public function model()
    {  
        return  Report::class;
    }

    // public function __construct() {
    // }

    public function saveReport($dataRequest){
        $idOwn =   Auth::user()->_id;
        $this->model->insert([
            'own_id' => $idOwn,
            'user_id' => $dataRequest['idUserReported'],
            'reason' => $dataRequest['reason'],
        ]);
    }

}
