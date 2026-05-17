<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // GET ALL USERS
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 'active',
            'data' => $users
        ]);
    }

    // GET ALL MEMBERS 
    public function members()
    {
        $members = User::where('role_id', 3)->get();

        return response()->json([
            'status' => 'active',
            'data' => $members
        ]);
    }
    // GET ALL TRAINERS 
    public function trainers()
    {
        $trainers = User::where('role_id', 2)->get();

        return response()->json([
            'status' => 'active',
            'data' => $trainers
        ]);
    }
    // GET SINGLE USER
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'data' => $user
        ]);
    }

    // CREATE USER
    
    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'message' => 'You have successfully created a new user',
            'data' => $user
        ]);
    }
    // UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated',
            'data' => $user
        ]);
    }

    // DELETE USER
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}