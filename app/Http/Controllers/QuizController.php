<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();

        return response()->json($quizzes);
    }

    public function store(Request $request)
    {
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'classroom_id' => $request->classroom_id,
            'limit_time' => $request->limit_time,
            'lecturer_id' => $request->lecturer_id,
            'number_questions' => $request->number_questions,
            'course_id' => $request->course_id
        ]);
        return response()->json([
            'message' => 'Quiz created successfully.',
            'data' => $quiz
        ], 201);
    }

    public function show(Quiz $quiz)
    {
        return response()->json(
            [
                'message' => 'Quiz found successfully.',
                'data' => $quiz
            ],
            200
        );
    }

    public function update(Request $request, Quiz $quiz)
    {
        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'classroom_id' => $request->classroom_id,
            'limit_time' => $request->limit_time,
            'lecturer_id' => $request->lecturer_id,
            'number_questions' => $request->number_questions
        ]);

        return response()->json([
            'message' => 'Quiz updated successfully.',
            'data' => $quiz
        ], 200);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return response()->json([
            'message' => 'Quiz deleted successfully.',
            'data' => $quiz
        ], 200);
    }
}
