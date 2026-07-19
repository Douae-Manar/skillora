<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\CareerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// الصفحة الرئيسية كتحول مباشرة لصفحة اللوجين
Route::get('/', fn() => redirect()->route('login'));

// روابط الدخول والتسجيل
Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// مجموعة الروابط المحمية (خاص يكون User مسجل)
Route::group(['middleware' => function ($request, $next) {
    if (!Session::has('user_id')) {
        return redirect()->route('login')->with('error', 'Please log in first!');
    }
    return $next($request);
}], function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/job-search', [JobController::class, 'index']);
    Route::get('/skills-catalog', [SkillController::class, 'index']);
    Route::get('/rankings', [RankingController::class, 'index']);
    Route::get('/career-advisor', [CareerController::class, 'index']);

    Route::post('/logout', function () {
        Session::flush();
        return redirect()->route('login');
    })->name('logout');
});

// أي رابط غير معرف كيرجع للوجين
Route::fallback(fn() => redirect()->route('login'));