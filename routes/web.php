<?php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class)
    ->only(['create', 'store']);
Route::resource('users', UserController::class)->middleware('auth')
    ->only(['index', 'show', 'edit', 'update','destroy']);;

Route::get('/login', [UserController::class,'show_login'])->name('login');
Route::post('/login', [UserController::class,'login'])->name('login.post');
Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth');


Route::get('/dashboard',[DashboardController::class, 'index'] )->middleware('isAdmin')->name('dashboard.show');
   
Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->middleware('auth')
    ->name('audit.logs');
