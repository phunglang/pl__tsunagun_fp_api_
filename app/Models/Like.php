<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'likes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'own_id',
        'user_id',
        'post_id',
        'job_id',
    ];

    public function getOwn()
    {
        return $this->belongsTo(User::class, 'own_id', '_id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function getPost()
    {
        return $this->belongsTo(Post::class, 'post_id', '_id');
    }

    public function getJob()
    {
        return $this->belongsTo(Job::class, 'job_id', '_id');
    }
}
