<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class LoginController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $result = $this->authService->login($request->only(['email', 'password']));
        if (empty($result)) {
            return response()->json([
                'data' => [
                    'message' => 'Login failed!',
                ],
            ]);
        }
        return response()->json([
                'access_token' => $result,
                'status' => 200,
                'message' => 'Login successfully!',
        ]);
    }
}
