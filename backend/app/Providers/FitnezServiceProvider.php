<?php
namespace App\Providers;
use App\Models\OtpCode;
use App\Models\User;
use App\Observers\OtpCodeObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
class FitnezServiceProvider extends ServiceProvider { public function register(): void {} public function boot(): void { User::observe(UserObserver::class); OtpCode::observe(OtpCodeObserver::class); } }
