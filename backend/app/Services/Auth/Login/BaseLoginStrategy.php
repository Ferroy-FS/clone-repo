<?php
namespace App\Services\Auth\Login;
use App\Models\User;
use App\Models\TrainerApplication;
use App\Models\TrainerDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
abstract class BaseLoginStrategy {
    protected function validateUser(array $credentials, ?string $requiredRole = null): User {
        $email = strtolower(trim((string)($credentials['email'] ?? ''))); $password = (string)($credentials['password'] ?? '');
        $user = User::query()->with('role')->where('email',$email)->first();
        if(!$user || !Hash::check($password,$user->password_hash)) throw ValidationException::withMessages(['email'=>['Invalid email or password.']]);
        if(!$user->is_active) throw ValidationException::withMessages(['email'=>['This account is not active. Please verify OTP first.']]);
        if($requiredRole && $user->roleName() !== $requiredRole) throw ValidationException::withMessages(['email'=>["This login is only for {$requiredRole} users."]]);
        return $user;
    }
    protected function userPayload(User $user): array {
        $application = TrainerApplication::query()->where('user_id',$user->id)->latest('id')->first();
        $canAccessTrainerWorkspace = $application?->status === 'approved' || TrainerDetail::query()->where('user_id',$user->id)->exists();
        return [
            'id'=>$user->id,
            'email'=>$user->email,
            'full_name'=>$user->full_name,
            'phone'=>$user->phone,
            'role'=>$user->roleName(),
            'is_active'=>$user->is_active,
            'email_verified_at'=>optional($user->email_verified_at)->toISOString(),
            'trainer_status'=>$application?->status ?? 'not_submitted',
            'can_access_trainer_workspace'=>$canAccessTrainerWorkspace,
            'trainer_application_id'=>$application?->id,
        ];
    }
}
