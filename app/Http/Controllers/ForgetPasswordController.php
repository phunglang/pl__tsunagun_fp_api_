<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\EmailRequest;
use App\Interfaces\OtpRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgetPasswordController extends Controller
{
    protected $otpRepository;
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository, OtpRepositoryInterface $otpRepository) {
        $this->userRepository = $userRepository;
        $this->otpRepository = $otpRepository;
    }

    public function sendOptCodeToEmail(EmailRequest $request) {
        $dataRequest = $request->only('email');

        $credentials = [
            'email' => $dataRequest['email'],
            'role' => 0
        ];
        $user = $this->userRepository->checkUserByCredentials($credentials);
        if (empty($user))
            return response()->json([
                'message' => 'User dont exist'
            ], 400);

        $user->otp->update([
            'otp_code' => str_pad(rand(0, 999999), 6, 0, STR_PAD_LEFT)
        ]);

        $dataSendMail = [
            'otp_code' => $user->otp->otp_code,
            'email' => $user->email
        ];
        dispatch(new SendMail('mails.mail-forget-password', $dataSendMail, $dataRequest['email'], 'Forget password'));
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function authenticationEmail(OtpRequest $request) {
        $dataRequest = $request->only('email', 'otp');
        $otp = null;
        foreach ($dataRequest['otp'] as $value) {
            $otp .= $value;
        }

        $options = [
            'otp_code' => $otp,
            'email' => $dataRequest['email']
        ];
        $otp = $this->otpRepository->checkUserComfirmOTP($options);
        if (empty($otp))
            return response()->json([
                'message' => '※不正また無効OTPコード。'
            ], 400);

        $otp->require_reset_password_at = Carbon::now();
        $otp->otp_code = null;
        $otp->save();
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function resetPassword(ForgetPasswordRequest $request, $email) {
        $dataRequest = $request->only('new_password');

        $credentials = [
            'email' => $email,
            'role' => 0
        ];
        $user = $this->userRepository->checkUserByCredentials($credentials);
        $user->update([
            'password' => Hash::make($dataRequest['new_password'])
        ]);
        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
