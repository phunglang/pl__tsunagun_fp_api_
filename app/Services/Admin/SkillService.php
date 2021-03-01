<?php
namespace App\Services\Admin;

use App;
use Config;
use Request;
use App\Skill;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\SkillRepository;

class SkillService
{
    /**
     * Property
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    protected $skillRepository;

    /**
     * Init.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */

    public function __construct(SkillRepository $skillRepository) {
        $this->skillRepository = $skillRepository;
    }

    /**
     * List User.
     * By : Vu Trong Luat
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */





}
