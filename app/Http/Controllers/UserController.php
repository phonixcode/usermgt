<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\PasswordRegex;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(UserResource::collection($users), 200);
    }

    public function store(Request $request)
    {
        $validator = $this->validateUserCreate($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'User Created Successfully'], 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['data' => $user], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = $this->validateUserUpdate($request, $user);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json(['message' => 'User Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Check if the authenticated user has the 'admin' role
        if (Auth::user()->roles !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ensure that the authenticated user cannot delete themselves
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot delete yourself'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User Deleted Successfully'], 204);
    }

    protected function validateUserCreate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required','string','min:8', new PasswordRegex],
        ]);
    }

    protected function validateUserUpdate(Request $request, User $user)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['required','string','min:8', new PasswordRegex],
        ]);
    }
}
