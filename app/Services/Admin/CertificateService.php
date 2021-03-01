<?php
namespace App\Services\Admin;

use App;
use Config;
use Request;
use App\Certificate;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Admin\CertificateRepository;

class CertificateService
{
    /**
     * Property
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */
    protected $cetificateRepository;

    /**
     * Init.
     * By : Huynh Le Anh Tai
     * @param  \Illuminate\Http\Request  $dataRequest
     * @return \Illuminate\Http\Response
     */

    public function __construct(CertificateRepository $cetificateRepository) {
        $this->cetificateRepository = $cetificateRepository;
    }

    
    public function update($id, $data) {
        $this->cetificateRepository->update($id, $data);
    }
}
