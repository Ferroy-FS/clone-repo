<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerDetail;

class TrainerController extends Controller
{
    // ================= LIST ALL TRAINERS =================
    public function listTrainers()
    {
        $trainers = TrainerDetail::with('user')->get();

        return response()->json([
            'status' => true,
            'message' => 'All trainers retrieved successfully',
            'data' => $trainers
        ]);
    }

    // ================= GET ONE TRAINER =================
    public function trainerDetail($id)
    {
        $trainer = TrainerDetail::with('user')->where('user_id', $id)->first();

        if (!$trainer) {
            return response()->json([
                'status' => false,
                'message' => 'Trainer not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Trainer detail retrieved successfully',
            'data' => $trainer
        ]);
    }

    // ================= CREATE TRAINER PROFILE =================
    public function createTrainer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|unique:trainer_details,user_id',
            'specialization' => 'required|string',
            'biography' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'hourly_rate' => 'nullable|numeric'
        ]);

        $trainer = TrainerDetail::create([
            'user_id' => $request->user_id,
            'specialization' => $request->specialization,
            'biography' => $request->biography,
            'experience_years' => $request->experience_years,
            'hourly_rate' => $request->hourly_rate
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trainer profile created successfully',
            'data' => $trainer
        ], 201);
    }

    // ================= DELETE TRAINER PROFILE =================
    public function destroy($user_id)
    {
        $trainer = TrainerDetail::where('user_id', $user_id)->first();

        if (!$trainer) {
            return response()->json([
                'status' => false,
                'message' => 'Trainer not found'
            ], 404);
        }

        $trainer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Trainer deleted successfully'
        ]);
    }
}