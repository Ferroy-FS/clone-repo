<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use App\Models\Authentication;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OtpController extends Controller
{
    // Generate random 6 digit OTP
    private function generateOtp(): string {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Send OTP for login
    public function sendLoginOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find auth record
        $auth = Authentication::where('email', $request->email)->first();
        if (!$auth) {
            return response()->json(['status' => false, 'message' => 'Email tidak ditemukan'], 404);
        }

        // Check password
        if (!Hash::check($request->password, $auth->password_hash)) {
            return response()->json(['status' => false, 'message' => 'Password salah'], 401);
        }

        // Check user is active
        $user = User::find($auth->user_id);
        if (!$user || !$user->is_active) {
            return response()->json(['status' => false, 'message' => 'Akun tidak aktif'], 403);
        }

        // Invalidate old OTPs
        Otp::where('email', $request->email)
           ->where('type', 'login')
           ->where('is_used', false)
           ->update(['is_used' => true]);

        // Create new OTP
        $code = $this->generateOtp();
        Otp::create([
            'email'      => $request->email,
            'code'       => $code,
            'type'       => 'login',
            'is_used'    => false,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send email
        Mail::to($request->email)->send(new OtpMail($code, 'login', $user->full_name));

        return response()->json([
            'status'  => true,
            'message' => 'Kode OTP telah dikirim ke email Anda',
            'email'   => $request->email
        ]);
    }

    // Verify OTP for login
    public function verifyLoginOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6'
        ]);

        $otp = Otp::where('email', $request->email)
                  ->where('code', $request->code)
                  ->where('type', 'login')
                  ->where('is_used', false)
                  ->where('expires_at', '>', Carbon::now())
                  ->first();

        if (!$otp) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ], 401);
        }

        // Mark OTP as used
        $otp->update(['is_used' => true]);

        // Get user and generate token
        $auth = Authentication::where('email', $request->email)->first();
        $user = User::find($auth->user_id);
        $user->last_login = now();
        $user->save();

        $token = $user->createToken('gym-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil!',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // Send OTP for forgot password
    public function sendForgotOtp(Request $request) {
        $request->validate(['email' => 'required|email']);

        $auth = Authentication::where('email', $request->email)->first();
        if (!$auth) {
            return response()->json(['status' => false, 'message' => 'Email tidak ditemukan'], 404);
        }

        $user = User::find($auth->user_id);

        // Invalidate old OTPs
        Otp::where('email', $request->email)
           ->where('type', 'forgot')
           ->where('is_used', false)
           ->update(['is_used' => true]);

        // Create new OTP
        $code = $this->generateOtp();
        Otp::create([
            'email'      => $request->email,
            'code'       => $code,
            'type'       => 'forgot',
            'is_used'    => false,
            'expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send email
        Mail::to($request->email)->send(new OtpMail($code, 'forgot', $user->full_name));

        return response()->json([
            'status'  => true,
            'message' => 'Kode OTP reset password telah dikirim',
            'email'   => $request->email
        ]);
    }

    // Verify OTP for forgot password
    public function verifyForgotOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6'
        ]);

        $otp = Otp::where('email', $request->email)
                  ->where('code', $request->code)
                  ->where('type', 'forgot')
                  ->where('is_used', false)
                  ->where('expires_at', '>', Carbon::now())
                  ->first();

        if (!$otp) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ], 401);
        }

        // Mark as used
        $otp->update(['is_used' => true]);

        return response()->json([
            'status'  => true,
            'message' => 'OTP verified! Silakan buat password baru.',
            'email'   => $request->email
        ]);
    }

    // Reset password
    public function resetPassword(Request $request) {
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        $auth = Authentication::where('email', $request->email)->first();
        if (!$auth) {
            return response()->json(['status' => false, 'message' => 'Email tidak ditemukan'], 404);
        }

        $auth->update([
            'password_hash'       => Hash::make($request->password),
            'password_updated_at' => now()
        ]);

        $user = User::find($auth->user_id);

        return response()->json([
            'status'    => true,
            'message'   => 'Password berhasil diubah!',
            'user_name' => $user->full_name,
            'role'      => $user->role_id == 1 ? 'Admin' : ($user->role_id == 2 ? 'Trainer' : 'Member')
        ]);
    }
}
