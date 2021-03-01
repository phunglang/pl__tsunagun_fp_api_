<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'own_id',
        'client_id',
        'status',
        'content'
    ];

    public function getOwn()
    {
        return $this->belongsTo(User::class, 'own_id', '_id');
    }

    public function getClient()
    {
        return $this->belongsTo(User::class, 'client_id', '_id');
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, 'messages_id', '_id');
    }

}
