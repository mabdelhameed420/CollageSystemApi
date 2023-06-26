<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommentReply;

class CommentReplyController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'comment_text' => 'required|string',
            'student_id' => 'nullable|exists:students,id',
            'student_affairs_id' => 'nullable|exists:student_affairs,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
            'timestamp' => 'required|date_format:Y-m-d H:i:s',
            'comment_id' => 'required|exists:comments,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $commentReply = CommentReply::create($validatedData);

        return response()->json($commentReply, 201);
    }

    public function update(Request $request, CommentReply $commentReply)
    {
        $validatedData = $request->validate([
            'comment_text' => 'required|string',
            'student_id' => 'nullable|exists:students,id',
            'student_affairs_id' => 'nullable|exists:student_affairs,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
            'timestamp' => 'required|date_format:Y-m-d H:i:s',
            'comment_id' => 'required|exists:comments,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $commentReply->update($validatedData);
        return response()->json([
            'message' => 'Comment reply updated successfully.',
            'data' => $commentReply
        ], 200);
    }

    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'post_id' => 'required|exists:posts,id',
        ]);


        CommentReply::where('comment_id', $validatedData['comment_id'])
            ->where('post_id', $validatedData['post_id'])
            ->delete();

        return response()->json([
            'message' => 'Comment reply deleted successfully.'
        ], 200);
    }
}
