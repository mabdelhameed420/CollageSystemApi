<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudentAffair;
use Illuminate\Http\Request;
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
            'fcm_token' => 'nullable|string',
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
    public function login($national_id, $password)
    {
        $studentAffair = StudentAffair::where('national_id', $national_id)->first();
        if ($studentAffair) {
            if (password_verify($password, $studentAffair->password)) {
                return response()->json([
                    'message' => 'Student Affairs found successfully.',
                    'data' => $studentAffair,
                    'statue' => 200
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Password is incorrect.',
                    'data' => null,
                    'statue' => 400
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'National ID is incorrect.',
                'data' => null,
                'statue' => 400
            ], 400);
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
    public function delete($id)
    {
        $studentAffair = StudentAffair::find($id);
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
                'fcm_token' => 'required|string',
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
}
