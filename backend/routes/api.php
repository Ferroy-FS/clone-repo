<?php
use App\Http\Controllers\Admin\LandingVisitReportController;
use App\Http\Controllers\Admin\ProspectiveMemberReviewController;
use App\Http\Controllers\Admin\ScheduleManagementController;
use App\Http\Controllers\Admin\TrainerManagementController;
use App\Http\Controllers\Admin\TrainerApplicationReviewController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Analytics\LandingVisitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrowserTrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ManualPaymentMethodController;
use App\Http\Controllers\ManualProspectiveRegistrationController;
use App\Http\Controllers\MembershipPackageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TrainerApplicationController;
use App\Http\Controllers\OtpController;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\JwtAuthenticate;
use Illuminate\Support\Facades\Route;
Route::get('/landing',[LandingController::class,'index']);
Route::get('/membership-packages',[MembershipPackageController::class,'index']);
Route::get('/manual-payment-methods',[ManualPaymentMethodController::class,'index']);
Route::prefix('analytics')->group(function(){ Route::post('/landing-visit',[LandingVisitController::class,'store']); });
Route::prefix('auth')->group(function(){
    Route::post('/prospective-registration/start',[ManualProspectiveRegistrationController::class,'start']);
    Route::post('/prospective-registration/upload-proof',[ManualProspectiveRegistrationController::class,'uploadProof']);
    Route::get('/prospective-registration/status',[ManualProspectiveRegistrationController::class,'status']);
    Route::post('/register-prospective-member',[AuthController::class,'registerProspectiveMember']);
    Route::post('/request-login-otp',[AuthController::class,'requestLoginOtp']); Route::post('/verify-login-otp',[AuthController::class,'verifyLoginOtp']); Route::post('/password/forgot',[AuthController::class,'forgotPassword']); Route::post('/password/reset',[AuthController::class,'resetPassword']); Route::post('/login',[AuthController::class,'login']); Route::post('/member-login',[AuthController::class,'memberLogin']);
    Route::post('/otp/send',[OtpController::class,'send']); Route::post('/otp/verify',[OtpController::class,'verify']);
    Route::middleware(JwtAuthenticate::class)->group(function(){ Route::get('/me',[AuthController::class,'me']); Route::post('/logout',[AuthController::class,'logout']); });
});
Route::middleware(JwtAuthenticate::class)->group(function(){
    Route::prefix('browser')->group(function(){ Route::post('/heartbeat',[BrowserTrackingController::class,'heartbeat']); Route::post('/elect-leader',[BrowserTrackingController::class,'electLeader']); Route::post('/release-leader',[BrowserTrackingController::class,'releaseLeader']); });
    Route::get('/dashboard/summary',[DashboardController::class,'summary']); Route::get('/dashboard/stream',[DashboardController::class,'stream']);
    Route::get('/notifications',[NotificationController::class,'index']); Route::get('/notifications/unread-count',[NotificationController::class,'unreadCount']); Route::patch('/notifications/{notification}/read',[NotificationController::class,'markAsRead']);
    Route::get('/trainer/application',[TrainerApplicationController::class,'status']); Route::post('/trainer/application',[TrainerApplicationController::class,'store']); Route::post('/trainer/workspace/enter',[TrainerApplicationController::class,'enterWorkspace']); Route::post('/trainer/workspace/leave',[TrainerApplicationController::class,'leaveWorkspace']);
    Route::prefix('admin')->middleware(EnsureRole::class.':admin')->group(function(){
        Route::get('/roles',[UserManagementController::class,'roles']);
        Route::get('/landing-visits',[LandingVisitReportController::class,'index']); Route::get('/landing-visits/summary',[LandingVisitReportController::class,'summary']);
        Route::get('/prospective-members',[ProspectiveMemberReviewController::class,'index']); Route::post('/prospective-members/{registration}/approve',[ProspectiveMemberReviewController::class,'approve']); Route::post('/prospective-members/{registration}/reject',[ProspectiveMemberReviewController::class,'reject']);
        Route::get('/trainer-applications',[TrainerApplicationReviewController::class,'index']); Route::post('/trainer-applications/{application}/approve',[TrainerApplicationReviewController::class,'approve']); Route::post('/trainer-applications/{application}/reject',[TrainerApplicationReviewController::class,'reject']); Route::get('/trainer-applications/{application}/documents/{type}',[TrainerApplicationReviewController::class,'download'])->whereIn('type',['cv','certificate']);
        Route::apiResource('users',UserManagementController::class)->only(['index','store','update','destroy']); Route::apiResource('trainers',TrainerManagementController::class)->only(['index','store','update','destroy']); Route::apiResource('schedules',ScheduleManagementController::class)->parameters(['schedules'=>'schedule'])->only(['index','store','update','destroy']);
    });
});
