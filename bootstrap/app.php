<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'     => \App\Http\Middleware\IsAdmin::class,
            'isAdmin'   => \App\Http\Middleware\IsAdmin::class,
            'checkRole' => \App\Http\Middleware\RoleManager::class,
            'role'      => \App\Http\Middleware\RoleManager::class, // ضيفي السطر ده هنا
        ]);
    })
        
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
// حماية صفحات الإدارة (للأدمن فقط)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/reports', [DashboardController::class, 'index']);
});

// حماية صفحات المشاركة (لليوزر العادي والمالك فقط)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/sharing', [PlotShareController::class, 'index'])->name('sharing.index');
    Route::post('/sharing/invite', [PlotShareController::class, 'store'])->name('sharing.invite');
});
  


    