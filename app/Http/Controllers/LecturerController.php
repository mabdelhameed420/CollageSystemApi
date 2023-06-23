<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lecturer;
use App\Models\Department;
use App\Traits\GeneralTraits;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{    use GeneralTraits;

    public function __construct()
    {
        $this->middleware('checkToken:api-lecturers', ['except' => ['login']]);
    }

    public function index()
    {
        $lecturers = Lecturer::with('department')->get();
        return $this-> returnData('lecturers',$lecturers,);;
    }

    public function show(Request $request)
    {
        $lecturer = Lecturer::with('department')->find($request->id);

        if (!$lecturer) {
            return $this->returnError('201',"lecturer not found");;
        }
        return $this->returnData('lecturer',$lecturer);;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'national_id' => 'required|string|unique:lecturers,national_id',
            'email' => 'required|email|unique:lecturers,email',
            'image' => 'nullable|image',
            'course_id' => 'required|integer|exists:courses,id',
            'phone_no' => 'required|string|unique:lecturers,phone_no',
            'password' => 'required|string|min:6',
            'department_id' => 'required|integer|exists:departments,id'

        ]);

        if ($validator->fails()) {
            return $this->returnValidationError('E001',$validator);
        }

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/images');
        }

        $lecturer = Lecturer::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'image' => $image,
            'course_id' => $request->course_id,
            'phone_no' => $request->phone_no,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id
        ]);
        $lecturer->save();

        return $this->returnData('lecturer',$lecturer);
    }

    public function update(Request $request)
    {
        $lecturer = Lecturer::findOrFail($request->lecturerid);

        $lecturer->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'image' => $request->image,
            'course_id' => $request->course_id,
            'phone_no' => $request->phone_no,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
        ]);


        return $this->returnData('lecturer',$lecturer);
    }

    public function destroy($request)
    {
        $lecturer = Lecturer::findOrFail($request->lecturer_id);

        $lecturer->delete();

        return $this->returnSuccessMessage("deleted succcessfully");
    }
    public function login(Request $request)
    {
        $credentials = request()->only('national_id', 'password');
        $token =Auth::guard('api-lecturers')->attempt($credentials);
        if (!$token) {
            return $this->returnError("401","Unauthorized");
        } else {
            $lecturer = Lecturer::where('national_id', $$request->national_id)->first();
            $lecturer->api_token = $token;
            return $this->returnData('lecturer_login', $lecturer, "lecturer login successfully");
        }
    }
    public function getLecturerById(Request $request)
    {
        $lecturer = Lecturer::with('department')->find($request->id);

        if (!$lecturer) {
            return $this->returnError('',"lecturer don't exists");
        }
        return $this->returnData('lecturer',$lecturer);
    }
    public function getLecturerByCourseId($course_id)
    {
        $lecturer = Lecturer::where('course_id', $course_id)->first();
        if (!$lecturer) {
            return response()->json([
                'message' => 'Lecturer not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data' => $lecturer
        ], 200);
    }
    public function getClassroomByLecturerId($lecturer_id)
    {
        $classroom = Classroom::where('lecturer_id', $lecturer_id)->first();
        return response()->json([
            'message' => 'Classroom retrieved successfully',
            'data' => $classroom,
            'statue' => 200

        ]);
    }

    public function logout()
    {
        auth()->logout();
        return $this->returnSuccessMessage('Successfully logged out');
    }

    public function getAllLecturers()
    {
        $lecturers = Lecturer::all();
        foreach ($lecturers as $lecturer) {
            $department = Department::find($lecturer->department_id);
            $lecturer->department_name = $department->name;
        }
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data' => $lecturers
        ], 200);
    }

    public function sendNotificationrToLecturer(Request $request)
    {
        $lecturer = Lecturer::find($request->student_id);
        $fcm_token = $lecturer->fcm_token;
        $title = $request->title;
        $body = $request->body;
        $data = [
            'title' => $title,
            'body' => $body
        ];
    }
}
