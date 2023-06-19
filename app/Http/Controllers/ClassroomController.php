<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Events\LiveAdded;

class ClassroomController extends Controller
{
    public function store(Request $request)
    {
        // Retrieve the input values
        $courseId = $request->input('course_id');
        $lecturerId = $request->input('lecturer_id');
        // Check if a classroom already exists for the course ID
        $existingClassroom = Classroom::where('course_id', $courseId)->first();
        if ($existingClassroom) {
            return response()->json([
                'message' => 'الفصل الدراسي موجود بالفعل لدي دكتور',
                'status' => 400
            ]);
        }

        // Create a new classroom
        $classroom = new Classroom;
        $classroom->course_id = $courseId;
        $classroom->lecturer_id = $lecturerId;
        $classroom->save();

        return response()->json([
            'message' => 'Classroom created successfully',
            'data' => $classroom,
            'status' => 201
        ]);
    }


    public function index()
    {
        $classrooms = Classroom::all();
        return response()->json([
            'message' => 'Classrooms retrieved successfully',
            'data' => $classrooms,
            'statue' => 200

        ]);
    }




    public function show(Classroom $classroom)
    {
        return response()->json([
            'message' => 'Classroom found successfully',
            'data' => $classroom,
            'statue' => 200
        ]);
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validatedData = $request->validate([
            // 'student_id' => 'exists:students,id',
            'course_id' => 'exists:courses,id',
            'lecturer_id' => 'exists:lecturers,id',
        ]);

        $classroom->update($validatedData);

        return response()->json([
            'message' => 'Classroom updated successfully',
            'data' => $classroom,
            'statue' => 200
        ]);
    }
    public function stratLive($id)
    {
        $classroom = Classroom::find($id);
        $classroom->is_live = true;
        $lecturer = Lecturer::find($classroom->lecturer_id);
        $students= Student::where('department_id',$classroom->department_id)->get();
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
        $result = curl_exec($ch);
        curl_close($ch);
        return response()->json([
            'message' => 'live started successfully',
            'data' => $classroom,
            'statue' => 200
        ]);
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return response()->json([
            'message' => 'Classroom deleted successfully',
            'data' => $classroom,
            'statue' => 200


        ]);
    }
    public function getStudentClassrooms(Request $request)
    {
        $classrooms = Classroom::where('student_id', $request->input('student_id'))->get();
        return response()->json([
            'message' => 'Classrooms retrieved successfully',
            'data' => $classrooms,
            'statue' => 200
        ]);
    }
    public function getLecturerClassrooms(Request $request)
    {
        $classrooms = Classroom::where('lecturer_id', $request->input('lecturer_id'))->get();
        return response()->json([
            'message' => 'Classrooms retrieved successfully',
            'data' => $classrooms,
            'statue' => 200

        ]);
    }
    public function getClassroomsByDepartmentId($departmentId)
    {
        // Retrieve all classrooms by department ID
        $classrooms = Classroom::whereHas('course', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->get();

        // Retrieve all course names
        $courseNames = Course::pluck('name', 'id')->toArray();

        // Map course names to classrooms
        $classrooms = $classrooms->map(function ($classroom) use ($courseNames) {
            $classroom->course_name = isset($courseNames[$classroom->course_id]) ? $courseNames[$classroom->course_id] : null;
            return $classroom;
        });
        foreach ($classrooms as $classroom) {
            $lecturer = Lecturer::where('id', $classroom->lecturer_id)->first();
            $classroom->lecturer_name = $lecturer->firstname . ' ' . $lecturer->lastname;
        }

        return response([
            'message' => 'classrooms department',
            'data' => $classrooms,
            'status' => 200,
        ]);
    }
    public function getClassroomByLecturerId($lecturerId)
    {
        // Retrieve all classrooms by lecturer ID
        $classrooms = Classroom::whereHas('course', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->get();
        $lecturer = Lecturer::where('id', $lecturerId)->first();
        // Retrieve all course names
        $courseNames = Course::pluck('name', 'id')->toArray();

        // Map course names to classrooms
        $classrooms = $classrooms->map(function ($classroom) use ($courseNames) {
            $classroom->course_name = isset($courseNames[$classroom->course_id]) ? $courseNames[$classroom->course_id] : null;
            return $classroom;
        });
        foreach ($classrooms as $classroom) {
            $classroom->lecturer_name = $lecturer->firstname . ' ' . $lecturer->lastname;
        }

        return response([
            'message' => 'Classrooms by lecturer ID',
            'data' => $classrooms,
            'status' => 200,
        ]);
    }
}
