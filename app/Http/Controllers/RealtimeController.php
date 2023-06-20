<?php

namespace App\Http\Controllers;

use App\Events\LiveAdded;
use App\Models\Classroom;
use App\Models\Lecturer;
use App\Models\Realtimes;
use App\Models\Student;
use Illuminate\Routing\Route;

class RealtimeController extends Controller
{
    public function stratLive($id)
    {
        $classroom = Classroom::find($id);
        $lecturer = Lecturer::find($classroom->lecturer_id);
        $students= Student::where('department_id',$lecturer->department_id)->get();
        event(new LiveAdded($students, $lecturer, $classroom));
        $data = [
            'title' => "تم بدا محاضرة مباشر من الدكتور " . $lecturer->firstname . ' ' . $lecturer->lastname,
            'body' => "يمكنك الان الدخول للمحاضرة من خلال الدخول الي الفصل الدراسي",
        ];
        $tokens = [];
        foreach ($students as $student) {
            if ($student->fcm_token != null) {
                array_push($tokens, $student->fcm_token);
            }
        }
        // $tokens = ['fcm_token'];
        $payload = [
            'registration_ids' => $tokens,
            'notification' => $data,
            'data' => [
                'volume' => '3.21.15',
                'contents' => 'http://www.news-magazine.com/world-week/21659772',
            ],
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
        curl_close($ch);
        $result = curl_exec($ch);
        foreach($students as $student){
            $realtime =Realtimes::where('student_id',$student->id)->first();
            if($realtime == null){
                $realtime = new Realtimes();
                $realtime->student_id = $student->id;
                $realtime->lecturer_id = $lecturer->id;
                $realtime->is_online = false;
                $realtime->is_quiz_started = false;
                $realtime->is_quiz_finished = false;
                $realtime->is_live = true;
                $realtime->save();
            }else{
                $realtime->update(['is_live' => true]);
                $realtime->save();
            }

        }

        return response()->json([
            'message' => 'live started successfully',
            'data' => $classroom,
            'statue' => 200,
            'noti' => json_decode($result),

        ]);
    }
    public function updateStatus($student_id,$is_online)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_online = $is_online;
        $realtime->update(['is_online' => $is_online]);
        $realtime->save();
        return response()->json([
            'message' => 'student status updated successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function finishLive($student_id,$is_live)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_live = $is_live;
        //update is_live to false in database
        $realtime->update(['is_live' => $is_live]);
        $realtime->save();
        return response()->json([
            'message' => 'live finished successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function startQuiz($student_id,$is_quiz_started)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_quiz_started = $is_quiz_started;
        $realtime->update(['is_quiz_started' => $is_quiz_started]);
        $realtime->save();
        return response()->json([
            'message' => 'quiz started successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }


}
