<?php
namespace App\Services\Mobile;

use App\Interfaces\ProviderServiceInterfaces;
use GuzzleHttp\Client;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
const AUTH_KEYS_URL_APPLE = 'https://appleid.apple.com/auth/keys';

class AppleService implements ProviderServiceInterfaces
{
    public function __construct() {
        $this->client = new Client();
    }

    public function getUserProfile($token) {
        $authKeys = $this->client
                        ->get(AUTH_KEYS_URL_APPLE)
                        ->getBody()
                        ->getContents();
        $publicKeys = json_decode($authKeys, true);
        $algo = array_map(function($key) {
            return $key['alg'];
        }, $publicKeys['keys']);

        $payload = (array)JWT::decode($token, JWK::parseKeySet($publicKeys), array_unique($algo));
        $payload['id'] = $payload['sub'];
        return $payload;
    }
}