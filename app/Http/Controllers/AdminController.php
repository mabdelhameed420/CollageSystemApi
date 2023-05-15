<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\FCMService;

class AdminController extends Controller
{

    public function store(Request $request)
    {
        $adminExists = Admin::where('national_id', $request->input('national_id'))->first();
        if ($adminExists) {
            return response()->json([
                'message' => 'Admin already exists!',
                'data' => $adminExists,
                'statue' => 201
            ], 400);
        } else {
            $validatedData = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'national_id' => 'required|unique:admins,national_id',
                'email' => 'required|email|unique:admins,email',
                'fcm_token' => 'string|nullable',
                'phone_no' => 'required',
                'password' => 'required',



            ]);

            $admin = Admin::create($validatedData);
            $admin->password = bcrypt($admin->password);
            $admin->save();
            return response()->json([
                'message' => 'Admin created successfully!',
                'data' => $admin,
                'statue' => 201
            ], 201);
        }
    }


    public function update(Request $request, Admin $admin)
    {
        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'national_id' => 'required|unique:admins,national_id,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone_no' => 'required',
            'password' => 'required'

        ]);

        $admin->update($validatedData);
        return response()->json([
            'message' => 'Admin updated successfully!',
            'data' => $admin,
            'statue' => 201
        ], 200);
    }
    public function delete(Request $request)
    {
        $admin = Admin::find($request->input('id'));
        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully',
            'data' => $admin,
            'statue' => 201
        ], 201);
    }
    public function show(Request $request)
    {
        $admin = Admin::find($request->input('id'));

        return response()->json([
            'message' => 'Admin found successfully',
            'data' => $admin,
            'statue' => 201
        ], 201);
    }
    public function index()
    {
        $admins = Admin::all();
        return response()->json([
            'message' => 'Admins found successfully',
            'data' => $admins,
            'statue' => 201
        ], 201);
    }

    public function login($national_id, $password)
    {
        $adminExists = Admin::where('national_id', $national_id)->first();
        if ($adminExists) {
            if (password_verify($password, $adminExists->password)) {
                return response()->json([
                    'message' => 'Admin logged in successfully!',
                    'data' => $adminExists,
                    'statue' => 201
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Wrong password!',
                    'data' => null,
                    'statue' => 400
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Admin does not exist!',
                'data' => null,
                'statue' => 400
            ], 400);
        }
    }
    public function sendNotificationrToAdmins($id)
    {
        // get a user to get the fcm_token that already sent.               from mobile apps
        $user = Admin::findOrFail($id);
        FCMService::send(
            $user->fcm_token,
            [
                'title' => 'your title',
                'body' => 'your body',
            ]
        );
    }
}
