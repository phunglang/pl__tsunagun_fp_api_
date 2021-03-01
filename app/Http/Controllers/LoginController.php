<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginProviderRequest;
use App\Http\Requests\LoginRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Services\ProviderFactory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    protected $userRepository;
    protected $providerFactory;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ProviderFactory $providerFactory
    ) {
        $this->userRepository = $userRepository;
        $this->providerFactory = $providerFactory;
    }

    public function login(LoginRequest $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 0
        ];

        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorised'], 401);

        $tokenResult = Auth::user()->createToken('Access Token');
        $token = $tokenResult->token;
        ($request->remember_me &&
            $token->expires_at = Carbon::now()->addWeeks(1));
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'is_validated' => Auth::user()->ID_validate,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ], 200);
    }

    public function resolveLoginByProvider(LoginProviderRequest $request, $provider) {
        $dataRequest = $request->only('access_token');

        $providerService = $this->providerFactory->createProvider($provider);
        $data = $providerService->getUserProfile($dataRequest['access_token']);

        $credentials = [
            $provider.'_id' => $data['id'],
            'role' => 0
        ];

        $user = $this->userRepository->checkUserByCredentials($credentials);
        if (empty($user->id)) {
            return response()->json([
                'status' => false,
                'message' => 'Ban chua dang ky tai khoan'
            ], 200);
        }
        Auth::login($user);

        $tokenResult = $user->createToken('Personal Access Client');
        $token = $tokenResult->token;
        ($request->remember_me &&
            $token->expires_at = Carbon::now()->addMonth(1));
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'is_validated' => Auth::user()->ID_validate,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ], 200);
    }
}
