<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted;
use Illuminate\Http\Request;

use App\Models\Comment;
use App\Models\Student;
use App\Models\StudentAffair;
use App\Models\Lecturer;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = Comment::create([
            'comment_text' => $request->comment_text,
            'timestamp' => $request->timestamp,
            'student_id' => $request->student_id,
            'student_affairs_id' => $request->student_affairs_id,
            'lecturer_id' => $request->lecturer_id,
            'post_id' => $request->post_id
        ]);
        event(new CommentPosted($comment));
        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => $comment
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        $comment->update([
            'comment_text' => $request->comment_text,
            'timestamp' => $request->timestamp,
            'student_id' => $request->student_id,
            'student_affairs_id' => $request->student_affairs_id,
            'lecturer_id' => $request->lecturer_id,
            'post_id' => $request->post_id

        ]);
        return response()->json([
            'message' => 'Comment updated successfully.',
            'data' => $comment
        ], 200);
    }
    public function destroy($id)
    {
        Comment::destroy($id);
        return response()->json([
            'message' => 'Comment deleted successfully.'
        ], 200);
    }
    public function getCommentsByPostId($id)
    {
        $comments = Comment::where('post_id', $id)->get();
        foreach ($comments as $comment) {
            if (!is_null($comment->student_id)) {
                $student = Student::where('id', $comment->student_id)->first();
                $comment->person_name = $student->firstname . ' ' . $student->lastname;
            } else if (!is_null($comment->lecturer_id)) {
                $lecturer = Lecturer::where('id', $comment->lecturer_id)->first();
                $comment->person_name = $lecturer->firstname . ' ' . $lecturer->lastname;
            } else {
                $studentAffairs = StudentAffair::where('id', $comment->student_affairs_id)->first();
                $comment->person_name = $studentAffairs->firstname . ' ' . $studentAffairs->lastname;
            }
        }
        return response()->json([
            'message' => 'Comments retrieved successfully.',
            'data' => $comments
        ], 200);
    }
}
