<?php
namespace App\Services\Mobile;

use App\Interfaces\ProviderServiceInterfaces;
use GuzzleHttp\Client;
const AUTH_KEYS_URL_GOOGLE = 'https://www.googleapis.com/oauth2/v3/tokeninfo';

class GoogleService implements ProviderServiceInterfaces
{
    public function __construct() {
        $this->client = new Client();
    }

    public function getUserProfile($token) {
        $checkToken = $this->client->get(AUTH_KEYS_URL_GOOGLE.'?id_token='.$token);
        $data = json_decode($checkToken->getBody()->getContents(), true);
        return $data;
    }
}