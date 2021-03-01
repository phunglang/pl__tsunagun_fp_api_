<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'otps';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'otp_code'
    ];
}
