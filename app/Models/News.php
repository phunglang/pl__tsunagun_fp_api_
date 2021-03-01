<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'news';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'publish_date',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
