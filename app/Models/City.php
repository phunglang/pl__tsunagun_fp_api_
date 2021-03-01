<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class City extends Model
{
    protected $collection = 'citys';

    protected $fillable = [
        'name'
    ];

    public function getProvince()
    {
        return $this->hasMany(Province::class, 'id_city', '_id')->orderBy('_id');
    }
}
