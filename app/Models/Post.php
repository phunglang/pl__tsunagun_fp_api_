<?php

namespace App\Models;

use App\Traits\Filters;
use App\Traits\FiltersTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Filters, FiltersTraits;

    protected $connection = 'mongodb';
    protected $collection = 'posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', //search like
        'content', //search like
        'status',
        'images',
        'user_id',
        'is_deleted'
    ];

    // protected $appends = [
    //     'report_count'
    // ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'status' => 'integer'
    ];

    public function scopeFilter($query, $dataSearch) {
        return $this->apply($query, $dataSearch);
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, 'post_id', '_id');
    }

    public function getPostReports()
    {
        return $this->hasMany(Report::class, 'post_id', '_id');
    }

    public function getReportCountAttribute()
    {
        $id = $this->_id;
        return Report::where(function ($q) use ($id) {
            $q->where('post_id', $id);
        })->count();
    }

    public function isLikeBy(User $user)
    {
        return $user->likes->where('post_id', $this->id)->first();
    }

    public function scopeSearch($query, $dataSearch) {
        return $this->applySearch($query, $dataSearch);
    }

    public function getIsLikeAttribute()
    {
        return !empty($this->isLikeBy(Auth::user()));
    }

    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }
}
