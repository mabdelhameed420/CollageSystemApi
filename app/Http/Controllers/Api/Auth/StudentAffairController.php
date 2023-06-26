<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentAffair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentAffairController extends Controller
{

    public function index()
    {
        $studentAffairs = StudentAffair::all();

        return response()->json([
            'message' => 'Student Affairs found successfully.',
            'data' => $studentAffairs,
            'statue' => 200
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'national_id' => 'required|string|unique:student_affairs,national_id',
            'email' => 'required|email|unique:student_affairs,email',
            'phone_no' => 'required|string',
            'image' => 'nullable|string',
            'admin_id' => 'required|integer|exists:admins,id',
            'password' => 'required|string',
            'responsible_level' => 'required|string',
            'date_added' => 'required|string',


        ]);
        if (!$validator->passes()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
                'statue' => 400
            ], 400);
        }

        $studentAffair = StudentAffair::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'image' => $request->image,
            'admin_id' => $request->admin_id,
            'password' => bcrypt($request->password),
            'responsible_level' => $request->responsible_level,
            'date_added' => $request->date_added

        ]);

        return response()->json([
            'message' => 'Student Affairs created successfully.',
            'data' => $studentAffair,
            'statue' => 201
        ], 201);
    }

    public function update(Request $request, StudentAffair $studentAffair)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'national_id' => 'required|string|unique:student_affairs,national_id,' . $studentAffair->id,
            'email' => 'required|email|unique:student_affairs,email,' . $studentAffair->id,
            'phone_no' => 'required|string',
            'image' => 'nullable|string',
            'admin_id' => 'required|integer|exists:admins,id',
            'password' => 'nullable|string',
            'responsible_level' => 'required|string',
            'date_added' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors(),
                    'data' => null,
                    'statue' => 400

                ],
                400
            );
        }

        $studentAffair->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'image' => $request->image,
            'admin_id' => $request->admin_id,
            'password' => $request->password ? bcrypt($request->password) : $studentAffair->password,
            'responsible_level' => $request->responsible_level,
            'date_added' => $request->date_added


        ]);
        $studentAffair->save();
        return response()->json([
            'message' => 'Student Affairs updated successfully.',
            'data' => $studentAffair,
            'statue' => 200
        ], 200);
    }
    
    public function destroy(StudentAffair $studentAffair)
    {
        $studentAffair->delete();
        return response()->json([
            'message' => 'Student Affairs deleted successfully.',
            'data' => $studentAffair,
            'statue' => 200
        ], 200);
    }

    public function show(StudentAffair $studentAffair)
    {
        return response()->json([
            'message' => 'Student Affairs found successfully.',
            'data' => $studentAffair,
            'statue' => 200
        ], 200);
    }
    public function login(Request $request)
    {
        $credentials = request()->only('national_id', 'password');
        $token = Auth::guard('api-affairs')->attempt($credentials);
        if (!$token) {
            return $this->returnError("401", "Unauthorized");
        } else {
            $studentAffair = StudentAffair::where('national_id', $request->national_id)->first();
            $studentAffair->api_token = $token;
            return $this->returnData('Affairs_login', $studentAffair, "Student affair login successfully");
        }
    }
    public function getAllStudentAffairs()
    {
        $studentAffairs = StudentAffair::all();
        return response()->json([
            'message' => 'Student Affairs found successfully.',
            'data' => $studentAffairs,
            'statue' => 200
        ], 200);
    }
    public function delete(Request $request)
    {
        $studentAffair = StudentAffair::find($request->id);
        if ($studentAffair) {
            $studentAffair->delete();
            return response()->json([
                'message' => 'Student Affairs deleted successfully.',
                'data' => $studentAffair,
                'statue' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Student Affairs not found.',
                'data' => null,
                'statue' => 400
            ], 400);
        }
    }
    public function addStudentAffair(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'national_id' => 'required|string|unique:student_affairs,national_id',
                'email' => 'required|email|unique:student_affairs,email',
                'phone_no' => 'required|string',
                'image' => 'nullable|string',
                'admin_id' => 'required|integer|exists:admins,id',
                'password' => 'required|string',
                'responsible_level' => 'required|string',
                'date_added' => 'required|string',
            ]
        );
        if (!$validator->passes()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
                'statue' => 400
            ], 400);
        }
    }

    public function logout()
    {
        auth()->logout();
        return $this->returnSuccessMessage('Successfully logged out');
    }
}
