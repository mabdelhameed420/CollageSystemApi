<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    public static function send($token, $notification)
    {
        Http::acceptJson()->withToken(config('fcm.token'))->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'to' => $token,
                'notification' => $notification,
            ]
        );
    }
    public static function sendNotification($fcmTokens, $title, $body, $data)
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
            'data' => $data,
        ];
        foreach ($fcmTokens as $fcm_token) {
            self::send($fcm_token, $notification);
        }
    }
}
