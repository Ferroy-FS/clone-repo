<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexTableRequest;
use App\Http\Requests\Admin\StoreTrainerRequest;
use App\Http\Requests\Admin\UpdateTrainerRequest;
use App\Models\Role;
use App\Models\TrainerDetail;
use App\Models\User;
use App\Support\ApiResponse;
use App\Support\SearchTerm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrainerManagementController extends Controller
{
    public function index(IndexTableRequest $request)
    {
        $data = $request->validated();
        $search = SearchTerm::contains($data['search'] ?? null);

        $trainers = TrainerDetail::query()
            ->with('user.role')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('specialization', 'ilike', $search)
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('full_name', 'ilike', $search)
                                ->orWhere('email', 'ilike', $search);
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($request->perPage());

        return ApiResponse::success('Trainers loaded.', $trainers);
    }

    public function store(StoreTrainerRequest $request)
    {
        $data = $request->validated();

        $trainer = DB::transaction(function () use ($data) {
            $role = Role::query()->firstOrCreate(
                ['name' => 'member'],
                ['description' => 'Fitnez member/user']
            );

            $user = User::query()->create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'role_id' => $role->id,
                'password_hash' => Hash::make($data['password']),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return TrainerDetail::query()->create([
                'user_id' => $user->id,
                'specialization' => $data['specialization'] ?? null,
                'biography' => $data['biography'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'hourly_rate' => $data['hourly_rate'] ?? 0,
                'avg_rating' => 0,
            ]);
        });

        return ApiResponse::success('Trainer created.', $trainer->load('user.role'), 201);
    }

    public function update(UpdateTrainerRequest $request, TrainerDetail $trainer)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $trainer) {
            $userPayload = [
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'is_active' => $data['is_active'],
            ];

            if (! empty($data['password'])) {
                $userPayload['password_hash'] = Hash::make($data['password']);
            }

            $trainer->user->update($userPayload);

            $trainer->update([
                'specialization' => $data['specialization'] ?? null,
                'biography' => $data['biography'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'hourly_rate' => $data['hourly_rate'] ?? 0,
            ]);
        });

        return ApiResponse::success('Trainer updated.', $trainer->fresh('user.role'));
    }

    public function destroy(TrainerDetail $trainer)
    {
        DB::transaction(function () use ($trainer) {
            $user = $trainer->user;
            $trainer->delete();
            $user?->delete();
        });

        return ApiResponse::success('Trainer deleted.');
    }
}
