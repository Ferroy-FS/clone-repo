<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberDetail;
use App\Models\TrainerBooking;

class MemberController extends Controller
{
    private function formatMember($member)
    {
        return [
            'id'              => $member->id,
            'user_id'         => $member->user_id,
            'name'            => $member->user->full_name ?? 'Unknown',
            'full_name'       => $member->user->full_name ?? 'Unknown',
            'email'           => $member->user->email ?? '-',
            'phone'           => $member->user->phone ?? '-',
            'heightCm'        => $member->height_cm,
            'weightKg'        => $member->weight_kg,
            'fitnessGoals'    => $member->fitness_goals,
            'experienceLevel' => $member->experience_level
        ];
    }

    public function allMembers()
    {
        $members = MemberDetail::with('user')->get()
            ->map(fn($m) => $this->formatMember($m));
        return response()->json(['status' => true, 'members' => $members]);
    }

    public function profile($user_id)
    {
        $member = MemberDetail::with('user')->where('user_id', $user_id)->first();
        if (!$member) {
            return response()->json(['status' => false, 'message' => 'Member not found'], 404);
        }
        return response()->json(['status' => true, 'member' => $this->formatMember($member)]);
    }

    public function membersByTrainer($trainer_id)
    {
        $memberIds = TrainerBooking::where('trainer_id', $trainer_id)
            ->pluck('member_id')->unique();
        $members = MemberDetail::with('user')
            ->whereIn('user_id', $memberIds)->get()
            ->map(fn($m) => $this->formatMember($m));
        return response()->json(['status' => true, 'members' => $members]);
    }

    public function createProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|unique:member_details,user_id',
        ]);
        $member = MemberDetail::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Member profile created successfully',
            'data' => $member
        ], 201);
    }

    public function updateProfile(Request $request, $user_id)
    {
        $member = MemberDetail::where('user_id', $user_id)->first();
        if (!$member) {
            return response()->json(['status' => false, 'message' => 'Member not found'], 404);
        }
        $member->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Member profile updated successfully',
            'data' => $member
        ]);
    }

    public function destroy($user_id)
    {
        $member = MemberDetail::where('user_id', $user_id)->first();
        if (!$member) {
            return response()->json(['status' => false, 'message' => 'Member not found'], 404);
        }
        $member->delete();
        return response()->json(['status' => true, 'message' => 'Member deleted successfully']);
    }
}
