<?php
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeedController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ToolReservationController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\DamageReportController;
//use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ShiftController;
use App\Models\shift;
use App\Http\Controllers\VolunteerHourController;
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


//Route::get('/dashboard',[DashboardController::class, 'index'] )->middleware('isAdmin')->name('dashboard.show');
Route::get('/dashboard',[DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.show');
Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->middleware('auth')
    ->name('audit.logs');
    Route::resource('seeds', SeedController::class)->middleware('auth');


//plot page by--rawan--
Route::resource('plots', PlotController::class)->middleware('auth');

Route::get('/tools',[ToolController::class,'index']);
Route::get('/tools/create',[ToolController::class,'create']);
Route::post('/tools',[ToolController::class,'store']);
Route::delete('/tools/{id}',[ToolController::class,'destroy']);
Route::get('/tools/{id}/edit',[ToolController::class,'edit']);
Route::put('/tools/{id}',[ToolController::class,'update']);
Route::get('/reservations/create',[ToolReservationController::class,'create']);
Route::post('/reservations',[ToolReservationController::class,'store']);
Route::get('/reservations',[ToolReservationController::class,'index']);
Route::post('/tools/{id}/checkout', [ToolController::class, 'checkout']);
Route::post('/tools/{id}/return', [ToolController::class, 'returnTool']);
//Route::post('/tools/{id}/checkout',[ToolController::class,'checkout'])->middleware('auth');
//Route::post('/tools/{id}/return',[ToolController::class,'returnTool'])->middleware('auth');
Route::get('/marketplace', [MarketplaceController::class, 'index']);
Route::post('/marketplace', [MarketplaceController::class, 'store']);
Route::post('/trade/{id}/request', [TradeRequestController::class, 'store']) ->middleware('auth');
Route::post('/trade/{id}/accept', [TradeRequestController::class, 'accept']);
Route::post('/trade/{id}/reject', [TradeRequestController::class, 'reject']);
Route::get('/trade-requests', [TradeRequestController::class, 'indexRequests']);
Route::post('/rate', [RatingController::class, 'storeRating']);

Route::post('/incident', [IncidentReportController::class, 'store']);
Route::middleware('auth')->group(function () {

    Route::post('/damage', [DamageReportController::class, 'store']);
    Route::get('/damage', [DamageReportController::class, 'index']);
    Route::get('/damage/{id}/fine', [DamageReportController::class, 'addFine']);

});
//Route::get('/dashboard', [DashboardController::class, 'index']);
//Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard']);
Route::get('/damage/{id}/fine', [DamageReportController::class, 'addFine']);
//Route::get('/tasks', [TaskController::class, 'index']);
//Route::post('/tasks', [TaskController::class, 'store']);
//Route::get('/tasks/{id}/done', function($id){
    //$task = \App\Models\Task::findOrFail($id);
   // $task->status = 'done';
   // $task->save();

   // return back();
//});
//use App\Http\Controllers\TaskController;

Route::middleware('auth')->group(function () {

    // عرض tasks (كل الناس تشوف)
    Route::get('/tasks', [TaskController::class, 'index']);

    // إضافة task (أدمن فقط)
    Route::post('/tasks', [TaskController::class, 'store']);

    // mark as done
    Route::get('/tasks/{id}/done', function ($id) {

        $task = \App\Models\Task::findOrFail($id);
        $task->status = 'done';
        $task->save();

        return back();

    });

});
/*Route::get('/shifts', [ShiftController::class, 'index']);
Route::post('/shifts', [ShiftController::class, 'store']);
Route::get('/shifts/{id}/join', function($id){

    $shift = Shift::findOrFail($id);

    if(!$shift->users->contains(auth()->id())) {

        $shift->users()->attach(auth()->id());

    }

    return back();

});*/
Route::middleware('auth')->group(function () {

    Route::get('/shifts', [ShiftController::class, 'index']);

    Route::post('/shifts', function (\Illuminate\Http\Request $request) {

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        return app(\App\Http\Controllers\ShiftController::class)->store($request);
    });

    Route::get('/shifts/{id}/join', function($id){

        $shift = \App\Models\Shift::findOrFail($id);

        if(!$shift->users->contains(auth()->id())) {
            $shift->users()->attach(auth()->id());
        }

        return back();

    });

});
/*Route::middleware('auth')->group(function () {

    Route::get('/shifts', [ShiftController::class, 'index']);

    Route::post('/shifts', function (\Illuminate\Http\Request $request) {

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        return app(\App\Http\Controllers\ShiftController::class)->store($request);
    });


    Route::get('/shifts/{id}/join', function($id){

        $shift = \App\Models\Shift::findOrFail($id);

        if(!$shift->users->contains(auth()->id())) {
            $shift->users()->attach(auth()->id());
        }

        return back();

    });

})*/
//Route::post('/volunteer-hours', [VolunteerHourController::class, 'store']);
//Route::get('/volunteer-hours', [VolunteerHourController::class, 'create']);
/*Route::middleware('auth')->group(function () {

    Route::post('/volunteer-hours', [VolunteerHourController::class, 'store']);

    Route::post('/volunteer-hours', [VolunteerHourController::class, 'create']);

});*/
Route::middleware('auth')->group(function () {

    // صفحة إضافة ساعات (لو هتستخدميها)

    Route::get('/volunteer-hours', [VolunteerHourController::class, 'create']);

    // حفظ الساعات

    Route::post('/volunteer-hours', [VolunteerHourController::class, 'store']);

});