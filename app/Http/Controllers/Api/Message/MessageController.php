<?php

namespace App\Http\Controllers\Api\Message;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Classroom;
use App\Models\StudentAffair;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $message = Message::create($request->all());
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/messages/', $filename);
            $message->image = $filename;
        }
        $username = '';//
        if ($request->chat_id!=null) {
            $chat = Chat::find($request->input('chat_id'));
        if ($chat->student_sender_id != null) {
            $student = Student::find($chat->student_sender_id);
            $username = $student->firstname . ' ' . $student->lastname;
        } else if ($chat->lecturer_sender_id != null) {
            $lecturer = Lecturer::find($chat->lecturer_sender_id);
            $username = $lecturer->firstname . ' ' . $lecturer->lastname;
        } else {
            $student_affair = StudentAffair::find($chat->student_affair_sender_id);
            $username = $student_affair->firstname . ' ' . $student_affair->lastname;
            
            
        }
        }


        $message->sentAt = date('H:i:s A');
        event(new MessageSent(
            $username,
            $message
        ));


        $message->save();
        return response()->json([
            'message' => 'Message created successfully',
            'data' => $message
        ], 201);
    }

    public function delete(Request $request)
    {
        $message = Message::find($request->input('id'));
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully',
            'data' => $message
        ], 201);
    }
    public function update(Request $request)
    {
        $message = Message::find($request->input('id'));
        $message->content = $request->input('content');
        $message->sentAt = now();
        $message->image = $request->file('image') ? $request->file('image')->store('images', 'public') : null;
        $message->voice_file = $request->file('voice_file') ? $request->file('voice_file')->store('voice_files', 'public') : null;
        $message->classroom_id = $request->input('classroom_id');
        $message->chat_id = $request->input('chat_id');
        $message->sender = $request->input('sender');
        $message->receiver = $request->input('receiver');
        $message->save();


        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message
        ], 201);
    }
    public function show(Request $request)
    {
        $message = Message::find($request->input('id'));

        return response()->json([
            'message' => 'Message found successfully',
            'data' => $message
        ], 201);
    }
    public function index()
    {
        $messages = Message::all();

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages
        ], 201);
    }
    public function getMessagesByChatId($id)
    {
        $messages = Message::where('chat_id', $id)->get();

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages
        ], 201);
    }
    public function getMessagesByClassroomId($classroomId)
    {
        $messages = Message::where('classroom_id', $classroomId)->get();
        $classroom = Classroom::find($classroomId);
        $lecturer_id = $classroom->lecturer_id;
        $lecturer = Lecturer::find($lecturer_id);
        foreach ($messages as $message) {
            if ($message->sender == $lecturer->id) {
                $lecturer = Lecturer::find($message->sender);
                $message->sender_name = $lecturer->firstname . ' ' . $lecturer->lastname;
                $message->sender_image = $lecturer->image;
            } else {
                $student = Student::find($message->sender);
                $message->sender_name = $student->firstname . ' ' . $student->lastname;
                $message->sender_image = $student->image;
            }
        }

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages,

        ], 201);
    }
    public function deleteMessageById($id)
    {
        $message = Message::find($id);
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully',
            'data' => $message,
            'updated_at' => now()
        ], 201);
    }
    public function sentMessage(Request $request)
    {
        event(new MessageSent(
            $request->sender,
            $request->content
        ));
    }
}
