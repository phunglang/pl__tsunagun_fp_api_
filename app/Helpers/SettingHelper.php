<?php

namespace App\Helpers;

use App\Models\SettingNotification;
use App\Constants\SettingNotifyConstant;
use Illuminate\Support\Facades\Auth;

class SettingHelper
{
    public static function getSetting($type)
    {
        return SettingNotification::where([
                                        'type' => SettingNotifyConstant::getType($type),
                                        'user_id' => Auth::user()->id
                                    ])
                                    ->first()
                                    ->is_push_notification ?? true;
    }
}