<?php
namespace App\Services\Mobile;

use App\Interfaces\ProviderServiceInterfaces;
use GuzzleHttp\Client;
const AUTH_KEYS_URL_FACEBOOK = 'https://graph.facebook.com/me';

class FacebookService implements ProviderServiceInterfaces
{
    public function __construct() {
        $this->client = new Client();
    }

    public function getUserProfile($token) {
        $checkToken = $this->client->get(AUTH_KEYS_URL_FACEBOOK.'?fields=id,name,email&access_token='.$token);
        $data = json_decode($checkToken->getBody()->getContents(), true);
        return $data;
    }
}