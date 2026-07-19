<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\CareerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// 1. استعملنا Controller مباشر بلا fn() باش ما يبقاش مشكل الـ Cache
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 2. هنا استعملنا 'middleware' العادي ديال لارافيل باش Railway يرتاح فالبناء
Route::middleware(['web'])->group(function () {
    
    // هاد الـ Group خاصو يخدم غير يلا كان الـ user_id موجود فالسيسيون
    // أحسن طريقة هي تخلي الـ Middleware هو اللي كيتكلف بـ Authentication
    Route::group(['middleware' => 'check.login'], function () {
        
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
});

Route::fallback(function () {
    return redirect()->route('login');
});