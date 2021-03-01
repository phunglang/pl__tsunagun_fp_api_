<?php

namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model;

class SettingNotification extends Model
{
    protected $collection = 'setting_notifications';
    protected $fillable = [
        'is_push_notification',
        'is_send_email',
        'type', //0:message 1:like 2:orther
        'user_id'
    ];

    protected $casts = [
        'is_send_email' => 'boolean',
        'is_push_notification' => 'boolean',
        'type' => 'integer'
    ];

    public function getUser()
    {
        return $this->hasOne(User::class, 'user_id', '_id');
    }
}
