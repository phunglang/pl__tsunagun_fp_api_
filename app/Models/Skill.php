<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'skills';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'is_deleted',
        'jobs_connect_skill'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'status' => 'integer'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'jobs_connect_skill',
    ];

    public function getCertificates()
    {
        return $this->hasMany(Certificate::class, 'skill_id', '_id')->where('is_deleted', false);
    }

    public function getJobs()
    {
        return $this->belongsToMany(Job::class, null, 'connect_skills', 'jobs_connect_skill');
    }
}
