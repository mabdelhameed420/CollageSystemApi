<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\FCMService;
use App\Traits\GeneralTraits;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use GeneralTraits;

    public function __construct()
    {
        $this->middleware('checkToken:api-admins', ['except' => ['login']]);
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
        return $this->returnData('admin_update', $admin, "admin updated successfully");
    }
    public function delete(Request $request)
    {
        $admin = Admin::find($request->input('id'));
        $admin->delete();

        return $this->returnSuccessMessage("Admin deleted successfully");
    }
    public function show(Request $request)
    {
        $admin = Admin::find($request->id);

        return $this->returnSuccessMessage("Admin found successfully");
    }
    public function index()
    {
        $admins = Admin::all();
        return $this->returnData('admins', $admins, "Admin found successfully");
    }

    public function login(Request $request)
    {
        $credentials = request()->only('national_id', 'password');
        $token =Auth::guard('api-admins')->attempt($credentials);
        if (!$token) {
            return $this->returnError("401","Unauthorized");
        } else {
            $admin = Admin::where('national_id', $request->national_id)->first();
            $admin->admin_token = $token;
            return $this->returnData('admin_login', $admin, "Admin login successfully");
        }
    }

    public function logout()
    {
        auth()->logout();
        return $this->returnSuccessMessage('Successfully logged out');
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
