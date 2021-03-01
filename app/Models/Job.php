<?php

namespace App\Models;

use App\Traits\Filters;
use App\Traits\FiltersTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Job extends Model
{
    use HasFactory, FiltersTraits, Filters;

    protected $connection = 'mongodb';
    protected $collection = 'jobs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'status',
        'recruiting_start',
        'recruiting_end',
        'connect_areas',
        'connect_skills',
        'user_id',
        'is_deleted'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'connect_areas',
        'connect_skills',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'status' => 'integer'
    ];

    protected $dates = [
        'recruiting_start',
        'recruiting_end'
    ];

    // protected $appends = [
    //     'report_count',
    //     'owner_name'
    // ];
    
    public function scopeSearch($query, $dataSearch) {
        return $this->applySearch($query, $dataSearch);
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    // public function getProvince()
    // {
    //     return $this->belongsToMany(Province::class, null, '_id','jobs_connect_area');
    // }

    public function getConnectAreas()
    {
        return $this->belongsToMany(Province::class, null, 'jobs_connect_area', 'connect_areas');
    }

    public function getConnectSkills()
    {
        return $this->belongsToMany(Skill::class, null, 'jobs_connect_skill', 'connect_skills');
    }

    public function scopeFilter($query, $dataSearch) {
        return $this->apply($query, $dataSearch);
    }

    public function getSkills()
    {
        return $this->belongsToMany(Skill::class, null, 'jobs_connect_skill', 'connect_skills');
    }

    public function getReports()
    {
        return $this->hasMany(Report::class, 'job_id', '_id');
    }
    
    public function getReportCountAttribute()
    {
        $id = $this->_id;
        return Report::where(function ($q) use ($id) {
            $q->where('job_id', $id);
        })->count();
    }
    
    public function getOwnerNameAttribute()
    {
        $id = $this->user_id;
        $user = User::where(function ($q) use ($id) {
            $q->where('_id', $id);
        })->first();
        if($user)
            return $user->username;
        return $user;
    }

    public function getUserReports()
    {
        return $this->hasMany(Report::class, 'user_id', 'user_id');
    }

    public function isLikeBy(User $user)
    {
        return $user->likes->where('job_id', $this->id)->first();
    }
}
