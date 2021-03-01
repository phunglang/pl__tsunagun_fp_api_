<?php

use App\Http\Controllers\Admin\NgWordController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SkillController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\HomeController as HomeCtr;
use App\Http\Controllers\NewsController as NewsCtr;
use App\Http\Controllers\ProvinceController as ProvinceCtr;
use App\Http\Controllers\LoginController as LoginCtr;
use App\Http\Controllers\RegisterController as RegisterCtr;
use App\Http\Controllers\SkillController as SkillCtr;
use App\Http\Controllers\PostController as PostCtr;
use App\Http\Controllers\JobController as JobCtr;
use App\Http\Controllers\LikeController as LikeCtr;
use App\Http\Controllers\UserController as UserCtr;
use App\Http\Controllers\ChatController as ChatCtr;
use App\Http\Controllers\ForgetPasswordController as ForgetPasswordCtr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//login
Route::post('login', [LoginCtr::class, 'login']);
Route::post('login/{provider}', [LoginCtr::class, 'resolveLoginByProvider']);

//register
Route::post('register/send-opt-to-email', [RegisterCtr::class, 'sendOptCodeToEmail']);
Route::post('register/verify-email', [RegisterCtr::class, 'authenticationEmail']);
Route::post('register', [RegisterCtr::class, 'register']);
Route::post('register/{id}/verify-identity', [RegisterCtr::class, 'verifyIdentity']);
Route::post('register/{id}/apply-certificates', [RegisterCtr::class, 'applyCertificates']);
Route::get('skills', [SkillCtr::class, 'index']);
Route::get('provincials', [ProvinceCtr::class, 'index']);

//forget-password
Route::post('forget-password/send-opt-to-email', [ForgetPasswordCtr::class, 'sendOptCodeToEmail']);
Route::post('forget-password/verify-email', [ForgetPasswordCtr::class, 'authenticationEmail']);
Route::post('forget-password/{email}/reset-password', [ForgetPasswordCtr::class, 'resetPassword']);

Route::get('user_management/download',[UserController::class, 'download'] );
Route::middleware('auth:api')->group(function () {
    //profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserCtr::class, 'infoProfile']);
        Route::post('update-profile', [UserCtr::class, 'updateProfile']);
        Route::post('change-email', [UserCtr::class, 'changeEmail']);
        Route::post('change-provider', [UserCtr::class, 'changeProvider']);
        Route::post('change-avatar', [UserCtr::class, 'changeAvatar']);
        Route::post('add-to-certificates', [UserCtr::class, 'applyCertificates']);
        Route::post('setting-notify/{type}', [UserCtr::class, 'setupNotify']);
        Route::post('change-password', [UserCtr::class, 'changePassword']);
        Route::delete('delete-account', [UserCtr::class, 'deleteAccount']);
        Route::get('blocks/list', [UserCtr::class, 'listBlock']);
        Route::post('blocks/update', [UserCtr::class, 'updateBlock']);
        Route::get('reports/list', [UserCtr::class, 'listReport']);
        Route::post('reports/update', [UserCtr::class, 'updateReport']);
    });

    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatCtr::class, 'index']);
        Route::get('/conversation-detail', [ChatCtr::class, 'conversationDetail']);
        Route::get('/count-mess', [ChatCtr::class, 'countMess']);
        Route::post('/send-report', [ChatCtr::class, 'sendReport']);
        Route::post('/send-block', [ChatCtr::class, 'sendBlock']);
        Route::post('/send-hidden', [ChatCtr::class, 'sendHidden']);
        Route::get('/get-list-user-hidden', [ChatCtr::class, 'getListUserHidden']);
        Route::post('/send-mess', [ChatCtr::class, 'sendMess']);
    });

    //members
    Route::get('members', [UserCtr::class, 'listMember']);

    Route::get('users/{id}', [UserCtr::class, 'detailUser']);
    Route::get('users/{id}/posts', [PostCtr::class, 'getPosts']);
    Route::get('my-jobs', [JobCtr::class, 'getJobs']);

    //posts
    Route::get('posts', [PostCtr::class, 'index']);
    Route::post('posts', [PostCtr::class, 'store']);
    Route::delete('posts/{id}', [PostCtr::class, 'destroy']);

    //news
    Route::get('news', [NewsCtr::class, 'index']);

    //jobs
    Route::get('jobs', [JobCtr::class, 'index']);
    Route::post('jobs', [JobCtr::class, 'store']);
    Route::get('jobs/{id}', [JobCtr::class, 'show']);
    Route::post('jobs/{id}', [JobCtr::class, 'update']);
    Route::delete('jobs/{id}', [JobCtr::class, 'destroy']);

    //home
    Route::prefix('home')->group(function () {
        Route::get('/users', [HomeCtr::class, 'getUsers']);
        Route::get('/posts', [HomeCtr::class, 'getPosts']);
        Route::get('/jobs', [HomeCtr::class, 'getJobs']);
        Route::get('/my-jobs', [HomeCtr::class, 'getMyJobs']);
    });

    //likes
    Route::get('{id}/likes', [LikeCtr::class, 'getLikes']);
    Route::get('{id}/likes/total_likes,is_like', [LikeCtr::class, 'totalLikes']);
    Route::post('like', [LikeCtr::class, 'like']);

    //logout
    Route::post('/logout', [UserCtr::class, 'logout']);
});

Route::post('admin/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::prefix('admin')->group(function () {
            // Route::get('/listUser',[UserController::class, 'index'] )->name('listUser');
            Route::post('user_management',[UserController::class, 'index'] );
            Route::get('user_management/{id}',[UserController::class, 'show'] );
            Route::put('user_management/{id}',[UserController::class, 'update'] );
            Route::delete('delete-user/{id}',[UserController::class, 'delete'] );


            //Route for category api
            Route::post('/listCategory',[SkillController::class, 'index'])->name('listCategory');
            Route::get('/category/detail/{id}',[SkillController::class, 'detail']);
            Route::post('/category/new',[SkillController::class, 'create'])->name('category.new');
            Route::get('/listUser',[UserController::class, 'index'] )->name('listUser');

            Route::get('/listCategory',[SkillController::class, 'index'])->name('listSkill');
            Route::get('/category/detail',[SkillController::class, 'detail']);


            Route::post('/category/setStatus',[SkillController::class, 'setStatus'])->name('category.setStt');
            Route::post('/category/update',[SkillController::class, 'update'])->name('category.update');
            Route::post('/category/delete',[SkillController::class, 'delete'])->name('category.delete');

            //Route for admin-post api
            Route::post('/listAdminPost',[PostController::class, 'listAdminPost'])->name('listAdminPost');
            Route::get('/admin-post/detail/{id}',[PostController::class, 'detail'])->name('admin-post.detail');
            Route::post('/admin-post/new',[PostController::class, 'create'])->name('admin-post.new');
            Route::post('/admin-post/update',[PostController::class, 'update'])->name('admin-post.update');
            Route::post('/admin-post/delete',[PostController::class, 'deleteNew'])->name('admin-post.delete');

            //Route for user-post api
            Route::post('/listUserPost',[PostController::class, 'listUserPost'])->name('listUserPost');
            Route::get('/user-post/detail/{id}',[PostController::class, 'detailUserPost'])->name('user-post.detail');
            Route::post('/user-post/update',[PostController::class, 'updateUserPost'])->name('user-post.update');
            Route::post('/user-post/delete',[PostController::class, 'delete'])->name('user-post.delete');

            //Route for ng-word api
            Route::post('/listNgWord',[NgWordController::class, 'listNgWord'])->name('listNgWord');
            Route::post('/ng-word/update',[NgWordController::class, 'update'])->name('ng-word.update');


            //Route for validation api
            Route::post('/validate-ids',[ValidationController::class, 'index'] )->name('validate_ids');
            Route::post('/validate/detail',[ValidationController::class, 'detail'] )->name('validate.datail');
            Route::put('/validate/detail',[ValidationController::class, 'update'] )->name('validate.update');

            //Route for jon admin api
            Route::post('job',[JobController::class, 'index'] );
            Route::get('job/{id}',[JobController::class, 'show'] );
            Route::put('job/{id}',[JobController::class, 'update'] );
            Route::delete('job/{id}',[JobController::class, 'delete'] );

            //Route for account api
            Route::get('account',[UserController::class, 'account'] );
            Route::put('account',[UserController::class, 'update_account'] );
        });
    });
});
