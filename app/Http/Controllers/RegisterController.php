<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Models\Certificate;
use App\Jobs\SendMail;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\RegisterEmailRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyIdentityRequest;
use App\Http\Requests\ApplyCertificatesRequest;
use App\Interfaces\OtpRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Helpers\FileManage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegisterController extends Controller
{
    protected $otpRepository;
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository, OtpRepositoryInterface $otpRepository) {
        $this->userRepository = $userRepository;
        $this->otpRepository = $otpRepository;
    }

    public function sendOptCodeToEmail(RegisterEmailRequest $request) {
        $dataRequest = $request->only('email');

        $options = [
            'email' => $dataRequest['email']
        ];
        $otp = $this->otpRepository->checkUserComfirmOTP($options);
        if (empty($otp)) {
            $otp = new Otp();
            $otp->email = $dataRequest['email'];
        }
        $otp->otp_code = str_pad(rand(0, 999999), 6, 0, STR_PAD_LEFT);
        $otp->save();

        $dataSendMail = [
            'otp_code' => $otp->otp_code,
            'email' => $otp->email
        ];
        dispatch(new SendMail('mails.mail-authentication-email', $dataSendMail, $dataRequest['email'], 'Xac nhan email'));
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

        $otp->otp_code = null;
        $otp->verify_email_at = Carbon::now();
        $otp->save();
        return response()->json([
            'message' => 'Success',
            'data' => [
                'email' => $otp->email,
                'verify_email_at' => $otp->verify_email_at
            ]
        ], 200);
    }

    public function register(RegisterRequest $request) {
        $dataRequest = $request->only('email', 'password', 'username', 'birthday', 'connect_areas', 'department', 'provider');

        $user = new User();
        $user->username = $dataRequest['username'];
        $user->email = $dataRequest['email'];
        if(isset($dataRequest['provider'])) {
            $client_id = $dataRequest['provider'].'_id';
            $user->$client_id = $dataRequest['client_id'];
        }
        if(isset($dataRequest['password'])) {
            $user->password = Hash::make($dataRequest['password']);
        }
        $user->birthday = $dataRequest['birthday'];
        $user->getConnectAreas()->attach($dataRequest['connect_areas']);
        $user->department = $dataRequest['department'];
        $user->status = 0;
        $user->role = 0;
        $user->save();
        return response()->json([
            'message' => 'Register success',
            'data' => [
                'email' => $user->email,
                'id' => $user->id
            ]
        ], 201);
    }

    public function verifyIdentity(VerifyIdentityRequest $request, $id) {
        $dataRequest = [
            'image' => $request->file('image')
        ];

        $credentials = [
            '_id' => $id,
            'role' => 0
        ];
        $user = $this->userRepository->checkUserByCredentials($credentials);
        if(empty($user))
            return response()->json([
                'message' => 'User dont exist'
            ], 400);

        $user->ID_validate = 0;
        $user->save();

        $imageFileName = time().'.'.$dataRequest['image']->getClientOriginalExtension();
        $file = new FileManage($imageFileName, $dataRequest['image'], 'App\Models\File', 'public', 's3', 'uploads/tsunagun_fp');
        $file->uploadFileToS3([
            'type' => 'image',
            'user_id' => $user->id
        ]);
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function applyCertificates(ApplyCertificatesRequest $request, $id) {
        $dataRequest = [
            'certificates' => $request->certificates
        ];

        foreach ($dataRequest['certificates'] as $item) {
            $certificate = new Certificate();
            $certificate->skill_id = $item['skill_id'];
            $certificate->user_id = $id;
            $certificate->status = 1;
            $certificate->save();

            $imageFileName = time().'.'.$item['image']->getClientOriginalExtension();
            $file = new FileManage($imageFileName, $item['image'], 'App\Models\File', 'public', 's3', 'uploads/tsunagun_fp');
            $file->uploadFileToS3([
                'type' => 'image',
                'certificate_id' => $certificate->id
            ]);
        };
        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
