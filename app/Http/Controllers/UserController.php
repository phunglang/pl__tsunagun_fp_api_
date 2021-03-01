<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Certificate;
use App\Models\SettingNotification;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Requests\ApplyCertificatesRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterEmailRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\VerifyIdentityRequest;
use App\Http\Requests\ProviderRequest;
use App\Services\ProviderFactory;
use App\Constants\SettingNotifyConstant;
use App\Helpers\FileManage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function infoProfile(User $user) {
        $user = $this->userRepository->infoProfile();
        return response()->json([
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UpdateProfileRequest $request) {
        $dataRequest = $request->only('username', 'comment', 'department', 'genre', 'experience', 'birthday', 'connect_areas', 'websites');

        $user = Auth::user();
        $user->username = $dataRequest['username'];
        $user->comment = $dataRequest['comment'];
        $user->department = $dataRequest['department'];
        $user->genre = $dataRequest['genre'];
        $user->experience = $dataRequest['experience'];
        $user->birthday = $dataRequest['birthday'];
        $user->getConnectAreas()->attach($dataRequest['connect_areas']);
        $user->websites = $dataRequest['websites'];
        $user->save();
        return response()->json([
            'message' => 'Update Success',
            'data' => $user
        ], 200);
    }

    public function changeEmail(RegisterEmailRequest $request) {
        $dataRequest = $request->only('email');

        $user = Auth::user();
        $user->update([
            'email' => $dataRequest['email']
        ]);
        return response()->json([
            'message' => 'Success',
            'data' => [
                'email' => $user->email,
                'updated_at' => $user->updated_at
            ]
        ], 200);
    }

    public function changeAvatar(VerifyIdentityRequest $request) {
        $dataRequest = $request->only('image');

        $user = Auth::user();
        (isset($user->avatar)
            && Storage::disk('public')->delete($user->avatar));
		$user->avatar = Storage::disk('public')->putFile('uploads', $dataRequest['image']);
        $user->save();

        return response()->json([
            'message' => 'Success',
            'data' => [
                'avatar' => $user->avatar,
                'updated_at' => $user->updated_at
            ]
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $dataRequest = $request->only('new_password');

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($dataRequest['new_password'])
        ]);
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function changeProvider(ProviderRequest $request) {
        $dataRequest = $request->only('access_token', 'provider');

        $providerService = $this->providerFactory->createProvider($dataRequest['provider']);
        $data = $providerService->getUserProfile($dataRequest['access_token']);

        $credentials = [
            $dataRequest['provider'].'_id' => $data['id'],
            'role' => 0
        ];

        $user = $this->userRepository->checkUserByCredentials($credentials);
        if (isset($user)) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan nay da dc su dung!'
            ], 400);
        }

        $user = Auth::user();
        $user->update([
            $dataRequest['provider'].'_id' => $data['id']
        ]);
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function setupNotify(Request $request, $type) {
        $dataRequest = $request->only('is_push_notification', 'is_send_email');

        SettingNotification::updateOrCreate([
                'type' => SettingNotifyConstant::getType($type),
                'user_id' => Auth::user()->id
            ],
            $dataRequest
        );
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function applyCertificates(ApplyCertificatesRequest $request) {
        $dataRequest = [
            'certificates' => $request->certificates
        ];

        foreach ($dataRequest['certificates'] as $item) {
            $certificate = new Certificate();
            $certificate->skill_id = $item['skill_id'];
            $certificate->user_id = Auth::user()->id;
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

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
                'message' => 'Success'
            ], 200);
    }

    public function deleteAccount() {
        $user = Auth::user();
        $user->getJobs()->update(['is_deleted' => true]);
        $user->getPosts()->update(['is_deleted' => true]);
        $user->getCertificates()->delete();
        $user->getFile()->delete();
        $user->getLikes()->delete();
        $user->getOwnReports()->delete();
        $user->getUserReports()->delete();
        $user->settingNotification()->delete();
        $user->getConnectAreas()->detach($user->_id);
        $user->otp()->delete();
        $user->update([
            'is_deleted' => true
        ]);
    }

    //members
    public function listMember(Request $request) {
        $dataRequest = [
            'filters' => $request->only('skill_ids', 'department', 'provincial_ids', 'ages', 'keyword'),
            'size' => $request->size,
        ];

        $members = $this->userRepository->listMember($dataRequest);
        return response()->json($members, 200);
    }

    public function detailUser($id) {
        $user = $this->userRepository->detailUser($id);
        return response()->json([
            'data' => $user
        ], 200);
    }

    public function listBlock() {
        $users = $this->userRepository->listBlock();
        return response()->json($users, 200);
    }

    public function updateBlock(Request $request) {
        $dataRequest = $request->only('ids');

        $user = Auth::user();
        $user->block_user_ids = array_values(array_diff($user->block_user_ids, $dataRequest['ids']));
        $user->save();

        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function removeBlock(Request $request) {
        $dataRequest = $request->only('ids');

        $user = Auth::user();
        $user->pull('block_user_ids', $dataRequest['ids']);

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
