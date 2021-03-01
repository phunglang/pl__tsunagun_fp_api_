<?php
namespace App\Services\Mobile;

use App\Interfaces\ProviderServiceInterfaces;
use GuzzleHttp\Client;
const AUTH_KEYS_URL_LINE = 'https://api.line.me/v2/profile';

class LineService implements ProviderServiceInterfaces
{
    public function __construct() {
        $this->client = new Client();
    }

    public function getUserProfile($token) {
        $headers = [
            'Authorization' => 'Bearer '.$token,
            'Accept'        => 'application/json',
        ];
        $response = $this->client->request('GET', AUTH_KEYS_URL_LINE, [
            'headers' => $headers
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}