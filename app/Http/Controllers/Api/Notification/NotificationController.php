<?php

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class NotificationController extends Controller
{

    public function sendNotification(Request $request)
    {
        $fcm_token = $request->fcm_token;
        $title = $request->title;
        $message = $request->body;
        $type = $request->type;
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
            'authorization: key=' . 'AAAAjfF8Wec:APA91bEWxNWtrsJ99bucIsqsA_QCpga1OFNOBoOMRwiFZpkGE1F0oLO84hZNEYxWj3KuMcjlaO6_icPysdIeIBFjpAkxNns70u8focMYTzcrnNxfPqaNdd2i3rZRJOr_eMY5hOGE_K0T',
            'content-type: application/json'
        ];

        $postdata = '{
            "to" : "' . $fcm_token . '",
                "notification" : {
                    "title":"' . $title . '",
                    "body" : "' . $message . '"
                },
            "data" : {
                "id" : "' . 1 . '",
                "title":"' . $title . '",
                "description" : "' . $message . '",
                "body" : "' . $message . '",
                "is_read": 0
                }
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);

        return response()->json(
            [
                'success' => true,
                'message' => 'Notification send successfully.',
                'data' => json_decode($result),
                'type' => $type
            ]
        );
    }
    public function sendNotificationForAllUsersByFCMToken(Request $request)
    {
        $title = $request->input('title');
        $body = $request->input('body');

        $tokens = Student::whereNotNull('fcm_token')->pluck('fcm_token')->all();


        $data = [
            'title' => $title,
            'body' => $body,
        ];

        $payload = [
            'registration_ids' => $tokens,
            'notificastion' => $data,
            'data' => $data,
        ];

        $headers = [
            'authorization: key=' . 'AAAAjfF8Wec:APA91bEWxNWtrsJ99bucIsqsA_QCpga1OFNOBoOMRwiFZpkGE1F0oLO84hZNEYxWj3KuMcjlaO6_icPysdIeIBFjpAkxNns70u8focMYTzcrnNxfPqaNdd2i3rZRJOr_eMY5hOGE_K0T',
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        curl_close($ch);
        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully',
            'result' => json_decode($result),
        ]);
    }
}
