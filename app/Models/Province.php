<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Province extends Model
{
    protected $collection = 'provincials';

    protected $fillable = [
        'name',
        'city_id',
        'users_connect_area',
        'jobs_connect_area'
    ];


     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'users_connect_area',
        'jobs_connect_area',
    ];

    protected $with = ['getCitys'];

    public function getCitys()
    {
        return $this->belongsTo(City::class, 'city_id', '_id');
    }

    public function getUsers()
    {
        return $this->belongsToMany(User::class, null, 'connect_areas', 'users_connect_area');
    }

    public function getJob()
    {
        return $this->belongsToMany(Job::class, null, 'province_id','job_id');
    }
}
