<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'certificates';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', //skill_validate 1:認証済,　0:認証待ち, -1:未認証
        'user_id',
        'skill_id'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'status' => 'integer'
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function getSkill()
    {
        return $this->belongsTo(Skill::class, 'skill_id', '_id');
    }

    public function getFile()
    {
        return $this->belongsTo(File::class, 'certificate_id', '_id');
    }

    public function getImages()
    {
        return $this->hasMany(File::class, 'certificate_id', '_id');
    }

}
