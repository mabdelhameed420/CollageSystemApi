<?php

namespace App\Http\Controllers;

use App\Events\QuizAdded;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Student;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'question_text' => 'required|string|max:255',
            'answer_a' => 'required|string|max:255',
            'answer_b' => 'required|string|max:255',
            'answer_c' => 'required|string|max:255',
            'answer_d' => 'required|string|max:255',
            'correct_answer' => 'required|integer|max:11',
            'quiz_id' => 'required|exists:quizzes,id',


        ]);
        $question = Question::create($validatedData);
        $question->save();
        return response()->json([
            'message' => 'success created',
            'data' => $question
        ], 201);
    }

    public function update(Request $request, Question $question)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:255',
            'answer_a' => 'required|string|max:255',
            'answer_b' => 'required|string|max:255',
            'answer_c' => 'required|string|max:255',
            'answer_d' => 'required|string|max:255',
            'correct_answer' => 'required|integer|max:11',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        $question->update($validatedData);
        return response()->json([
            'message' => 'success updated',
            'data' => $question
        ], 200);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'message' => 'success deleted',
            'data' => $question
        ], 201);
    }
    public function show(Question $question)
    {
        return response()->json([
            'message' => 'success retrieved',
            'data' => $question
        ], 200);
    }
    public function index()
    {
        $questions = Question::all();
        return response()->json([
            'message' => 'success retrieved',
            'data' => $questions
        ], 200);
    }
    public function getQuestionsByQuizId($quiz_id)
    {
        $questions = Question::where('quiz_id', $quiz_id)->get();
        return response()->json([
            'message' => 'success retrieved',
            'data' => $questions
        ], 200);
    }
    // Assuming you have a "Question" model representing the "questions" table
    // and a "Quiz" model representing the "quizzes" table

    public function getQuestionsByQuizIdAndLecturerId($quizId, $lecturerId)
    {
        // Retrieve questions by quiz_id and lecturer_id
        $questions = Question::whereHas('quiz', function ($query) use ($quizId, $lecturerId) {
            $query->where('id', $quizId)
                ->where('lecturer_id', $lecturerId);
        })
            ->get();
        $quiz = Quiz::where('id', $quizId)->first();
        if (!$quiz) {
            return [];
        }
        $limit_time = $quiz->limit_time;

        // Return questions
        return response(
            [
                'message' => 'success retrieved',
                'data' => $questions,
                'quiz_time' => $limit_time
            ],
            200
        );
    }
    public function addQuiz($quiz_id){
        $quiz = Quiz::find($quiz_id);
        $course = Course::find($quiz->course_id);
        $classroom = Classroom::find($quiz->classroom_id);
        $students = Student::where('department_id', $course->department_id)->get();
        $tokens=[];
        foreach ($students as $student) {
            if ($student->fcm_token != null) {
                array_push($tokens, $student->fcm_token);
            }
        }
        $lecturer =Lecturer::find($quiz->lecturer_id);
        
        if (!$quiz) {
            return response()->json([
                'message' => 'Quiz not found'
            ], 404);
        }
        $data = [
            'title' => $quiz->title,
            'body' => 'تم اضافة اختبار جديد لمادة ' . $course->name .  ' من قبل ' . $lecturer->firstname . ' ' . $lecturer->lastname . ' للفصل الدراسي '
            . $classroom->name . '  الرجاء الضغط علي رساله لدخول الاختبارات ' .' مده الاختبار هي ' . $quiz->limit_time . ' دقيقه'  .' '. 'من الان',
            'sound' => 'default',
            'color' => '#203E78',
        ];
        $customData=[
            'notification_type'=>'quizAdded',
            'quiz_id'=>$quiz->id,
            'quiz_time'=>$quiz->limit_time,
            'quiz_title'=>$quiz->title,
            'course_name'=>$course->name,
            'classroom_name'=>$classroom->name,
            'lecturer_name'=>$lecturer->firstname . ' ' . $lecturer->lastname,
        ];
        $payload = [
            'data' => [
                'notification_type'=>'quizAdded',
                'quiz_id'=>$quiz->id,

            ],
            'registration_ids' => $tokens,
            'notification' => $data,
            'priority' => 'high',
            'messge_type' => 'quizAdded'
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
        $questions = Question::where('quiz_id', $quiz->id)->get();
        event(new QuizAdded($quiz, $questions,$lecturer,$classroom));
        return response()->json([
            'message' => 'push notification sent successfully for students',
            'data' => $questions,
            'result' => json_decode($result,true)
        ], 201);
    }
}
