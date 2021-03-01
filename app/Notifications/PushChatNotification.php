<?php

namespace App\Notifications;

use App\Helpers\SettingHelper;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class PushChatNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->status = SettingHelper::getSetting('message');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function handle()
    {
        if (!$this->status) return;

        $parameters = [
            'include_external_user_ids' => [
                $this->message->client_id
            ],
            'data' => [
                '_id' => $this->message->id,
                'type' => 'CHAT',
                'sender' => Auth::user()->id,
            ],
            'headings' => [
                'en' => '新着メッセージがあります'
            ],
            'contents' => [
                'en' => $this->message->content ?? '画像が送信されました'
            ]
        ];
        OneSignal::sendNotificationCustom($parameters);
    }
}