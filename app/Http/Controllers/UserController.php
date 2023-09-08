<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
        return UserResource::collection($users);
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
            "photo" => "nullable",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "phone_number" => "required|min:9|max:15",
            "address" => "required|min:10",
            "gender" => "required|in:male,female",
            "dob" => "required",
            "role" => "required|in:admin,staff"
        ]);

        $user = User::create([
            "name" => $request->name,
            "photo" => $request->photo,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "gender" => $request->gender,
            "dob" => $request->dob,
            "role" => 'staff',
        ]);

        return new UserResource($user);
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
            abort(404, 'user not found');
        }

        return new UserResource($user);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "nullable|min:3",
            "photo" => "nullable",
            "email" => "required|email|unique:users,email,$id",
            "phone_number" => "required|min:9|max:15",
            "address" => "required|min:10",
            "gender" => "required|in:male,female",
            "dob" => "required",
            // "role" => "required|in:admin,staff"
        ]);

        $user = User::find($id);
        if (is_null($user)) {
            abort(404, 'user not found');
        }

        $user->update([
            "name" => $request->name,
            "photo" => $request->photo,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "gender" => $request->gender,
            "dob" => $request->dob,
            "role" => 'staff'
        ]);
        return new UserResource($user);
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
            abort(404, 'user not found');
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
    public function ban(string $id)
    {
        Gate::authorize('isAdmin');

        $user = User::find($id);
        if (is_null($user)) {
            abort(404, 'user not found');
        }

        if ($user->role != 'ban') {

            // log out all sessions
            foreach ($user->tokens as $token) {
                $token->delete();
            }

            $user->update([
                'role' => 'ban'
            ]);

            return response()->json([
                'message' => 'User has been banned successfully'
            ]);

        } else {
            return response()->json([
                'message' => 'User has been banned already'
            ]);
        }
        //
    }

    public function current() {
        return Auth::user();
    }
}
