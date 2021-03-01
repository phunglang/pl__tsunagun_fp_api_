<?php

namespace App\Notifications;

use App\Helpers\SettingHelper;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class PushLikeNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($like, $type)
    {
        $this->like = $like;
        $this->type = $type;
        $this->status = SettingHelper::getSetting('like');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function handle()
    {
        if (!$this->status)
            return;

        $parameters = [
            'include_external_user_ids' => [
                $this->like->{'get'.ucwords($this->type)}->user_id
            ],
            'data' => [
                '_id' => $this->like->id,
                'type' => 'LIKE',
                'like_id' => $this->like->{$this->type.'_id'},
                'like_type' => $this->type,
                'sender' => Auth::user()->id,
            ],
            'headings' => [
                'en' => '新着メッセージがあります'
            ],
            'contents' => [
                'en' => Auth::user()->username . 'さんが' . $this->like->{'get'.ucwords($this->type)}->title . 'にいいねしました'
            ]
        ];
        OneSignal::sendNotificationCustom($parameters);
    }
}