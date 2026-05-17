<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Authentication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ================= REGISTER =================
    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'full_name' => 'required',
                'birth_date' => 'required|date',
                'phone' => 'required',
                'password' => 'required|min:4',
                'role_id' => 'required|integer'
            ]);

            DB::beginTransaction();

            // Create user
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'is_active' => true, // ✅ FIXED
                'created_at' => now()
            ]);

            // Create authentication
            Authentication::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'provider' => 'local',
                'is_active' => true,
                'password_updated_at' => now()
            ]);

            // Generate token
            $token = $user->createToken('gym-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Register failed',
                'error' => $e->getMessage() // 🔥 helps debugging
            ], 500);
        }
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Find authentication record
            $auth = Authentication::where('email', $request->email)->first();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found'
                ], 404);
            }

            // Check password
            if (!Hash::check($request->password, $auth->password_hash)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Wrong password'
                ], 401);
            }

            // Get user
            $user = User::find($auth->user_id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // 🚨 CHECK ACTIVE USER
            if (!$user->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'User is inactive'
                ], 403);
            }

            // Update last login
            $user->last_login = now();
            $user->save();

            // Generate token
            $token = $user->createToken('gym-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login success',
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error',
                'error' => $e->getMessage() // 🔥 THIS is why you saw "Server error"
            ], 500);
        }
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'Logout success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}