<?php

namespace App\Models;

use App\Traits\Filters;
use App\Traits\FiltersTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, FiltersTraits, Filters;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'line_id',
        'facebook_id',
        'apple_id',
        'username', //search like
        'password',
        'role', //0:user, 1:admin
        'status', //0:無効, 1:有効(is member), 2:退会
        'department', //0:保険会社専属, 1:保険代理店, 2:その他
        'connect_areas',
        'genre', //search like
        'experience', //search like
        'birthday',
        'comment', //search like
        'websites',
        'image',
        'ID_validate',
        'is_deleted',
        'note',
        'index'
    ];
    // protected $withCount = ['getLikes'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
        'department' => 'integer',
        'index' => 'integer',
        'is_deleted' => 'boolean'
    ];

    protected $dates = [
        'birthday'
    ];

    protected $appends = [
        'last_messages',
        'count_mess_not_read'
    ];

    public function getLastMessagesAttribute()
    {
        $id = $this->_id;
        return Message::where(function ($q) use ($id) {
            $q->where('own_id', Auth::id());
            $q->where('client_id', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('own_id', $id);
            $q->where('client_id', Auth::id());
        })->orderBy('created_at', 'desc')->first();
    }

    public function getCountMessNotReadAttribute()
    {
        $id = $this->_id;
        return Message::where(function ($q) use ($id) {
            $q->where('own_id', Auth::id());
            $q->where('client_id', $id);
            $q->where('status', 0);
        })->orWhere(function ($q) use ($id) {
            $q->where('own_id', $id);
            $q->where('client_id', Auth::id());
            $q->where('status', 0);
        })->count();
    }

    public function scopeSearch($query, $dataSearch)
    {
        return $this->applySearch($query, $dataSearch);
    }

    public function scopeFilter($query, $dataSearch)
    {
        return $this->apply($query, $dataSearch);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getCertificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', '_id');
    }

    public function getIdImages()
    {
        return $this->hasMany(File::class, 'user_id', '_id');
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, 'user_id', '_id');
    }

    public function getJobs()
    {
        return $this->hasMany(Job::class, 'user_id', '_id');
    }

    public function getUserReports()
    {
        return $this->hasMany(Report::class, 'user_id', '_id');
    }

    public function getOwnReports()
    {
        return $this->hasMany(Report::class, 'own_id', '_id');
    }

    public function getOwnMess()
    {
        return $this->hasMany(Message::class, 'own_id', '_id');
    }

    public function getClientMess()
    {
        return $this->hasMany(Message::class, 'client_id', '_id');
    }

    public function getLikes()
    {
        return $this->hasMany(Like::class, 'user_id', '_id');
    }

    public function getFile()
    {
        return $this->belongsTo(File::class, 'user_id', '_id');
    }

    public function settingNotification()
    {
        return $this->hasMany(SettingNotification::class, 'user_id', '_id');
    }

    public function getAgeAttribute()
    {
        return $this->birthday->age ?? null;
    }

    public function getArea()
    {
        return $this->hasOne(Province::class, 'area', 'users_connect_area');
    }

    public function getConnectAreas()
    {
        return $this->belongsToMany(Province::class, null, 'users_connect_area', 'connect_areas');
    }

    public function otp()
    {
        return $this->hasOne(Otp::class, 'email', 'email');
    }

    public function getUserChat()
    {
        return $this->belongsToMany(User::class, null, 'chat_user_ids', 'chat_user_ids');
    }

    public function getMess()
    {
        return $this->hasMany(User::class, null, 'chat_user_ids', 'chat_user_ids');
    }

    public function isLikeBy(User $user)
    {
        return $user->likes->where('user_id', $this->id)->first();
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'own_id', '_id');
    }

    public function getIsLikeAttribute()
    {
        return !empty($this->isLikeBy(Auth::user()));
    }

    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }
}
