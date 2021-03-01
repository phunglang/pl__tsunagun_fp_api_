<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'path',
        'type', #0:image,1:video,2:audio,3:document,4:other
        'thumbnail',
        'duration',
        'size',
        'user_id',
        'post_id',
        'messages_id',
        'certificate_id'
    ];

    public function getCertificates()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id', '_id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function getMess()
    {
        return $this->belongsTo(Message::class, 'messages_id', '_id');
    }
}
