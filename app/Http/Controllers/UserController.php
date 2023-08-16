<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $users;
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('register');

        Gate::authorize('isAdmin');
        
        $request->validate([
            "name" => "nullable|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "phone_number" => "required|min:9|max:15",
            "address" => "required|min:10",
            "gender" => "required|in:male,female",
            "dob" => "required",
            // "role" => "required|in:admin,staff"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "gender" => $request->gender,
            "dob" => $request->dob,
            "role" => 'staff'
        ]);

        return $user;
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('isAdmin');
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        return $user;
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "nullable|min:3",
            "email" => "required|email|unique:users,email,$id",
            "phone_number" => "required|min:9|max:15",
            "address" => "required|min:10",
            "gender" => "required|in:male,female",
            "dob" => "required",
            // "role" => "required|in:admin,staff"
        ]);

        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }

        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "gender" => $request->gender,
            "dob" => $request->dob,
            "role" => 'staff'
        ]);
        return $user;
        //
    }

    public function changePassword(Request $request)
    {
        // change its own password
        $request->validate([
            "current_password" => "required|current_password",
            "new_password" => "required|min:8|max:15"
        ]);
        Auth::user()->password = Hash::make($request->new_password);
        Auth::user()->save();
        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function modifyPassword(Request $request)
    {
        // change password by admin user
        // @fix, add option to log out all sessions
        Gate::authorize('isAdmin');

        $request->validate([
            "new_password" => "required|min:8|max:15",
            "user_id" => "required|exists:users,id"
        ]);
        $user = User::find($request->user_id);
        if (is_null($user)) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'message' => 'Staff password changed successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
