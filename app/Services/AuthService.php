<?php

namespace App\Services;

use App;
use Config;
use Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    public function register(array $inputs = [])
    {
        try {
            $user = User::create([
                'name' => $inputs['name'],
                'email' => $inputs['email'],
                'password' => bcrypt($inputs['password']),
            ]);
        } catch (Exception $e) {
            report($e);
            $user = null;
        }

        return $user;
    }

    public function login(array $inputs = [])
    {
        $passportConfig = Config::get('services.passport');
        $passportConfig['username'] = $inputs['email'];
        $passportConfig['password'] = $inputs['password'];
        $credentials = [
            'email' => $inputs['email'],
            'password' => $inputs['password'],
        ];

        // dd($inputs);
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('Access Token')->accessToken;
            return $token;
        }

        return [];
     
    }
}