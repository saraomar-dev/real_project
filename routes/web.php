<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeedController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SoilRecordController;
use App\Http\Controllers\PestReportController;
use App\Http\Controllers\ComplianceAuditController;
use App\Http\Controllers\PlotShareController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ToolReservationController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TradeRequestController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\VolunteerHourController;
use App\Notifications\PestReportedNotification;
use App\Notifications\ComplaintNotification;


// ================= HOME =================
Route::get('/', function () {
    return view('welcome');
});


// ================= USERS =================
Route::resource('users', UserController::class)->only(['create', 'store']);

Route::middleware('auth')->resource('users', UserController::class)
    ->only(['index', 'show', 'edit', 'update', 'destroy']);

Route::get('/login', [UserController::class, 'show_login'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');


// ================= DASHBOARD =================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('isAdmin')
    ->name('dashboard.show');


// ================= AUDIT LOGS =================
Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->middleware('auth')
    ->name('audit.logs');


// ================= SEEDS =================
Route::resource('seeds', SeedController::class)->middleware('auth');


// ================= PLOTS =================
Route::middleware('auth')->group(function () {

    Route::get('/plots', [PlotController::class, 'index'])->name('plots.index');

    Route::get('/plots/create', [PlotController::class, 'create'])
        ->middleware('isAdmin')->name('plots.create');

    Route::post('/plots', [PlotController::class, 'store'])
        ->middleware('isAdmin')->name('plots.store');

    Route::get('/plots/{plot}', [PlotController::class, 'show'])->name('plots.show');

    Route::post('/plots/{plot}/rent', [PlotController::class, 'rent'])->name('plots.rent');

    Route::post('/plots/{plot}/approve', [PlotController::class, 'approveLease'])->name('plots.approve');

    Route::get('/plots/{plot}/edit', [PlotController::class, 'edit'])
        ->middleware('isAdmin')->name('plots.edit');

    Route::put('/plots/{plot}', [PlotController::class, 'update'])
        ->middleware('isAdmin')->name('plots.update');

    Route::delete('/plots/{plot}', [PlotController::class, 'destroy'])
        ->middleware('isAdmin')->name('plots.destroy');
});


// ================= ADMIN REQUESTS =================
Route::get('/admin/requests', [PlotController::class, 'pendingRequests'])
    ->middleware('isAdmin')
    ->name('admin.requests');


// ================= PLANTING =================
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/plots/{plot}/plant', [PlotController::class, 'plantPage'])
        ->name('plots.plant.page');

    Route::post('/plots/{plot}/plant', [PlotController::class, 'plant'])
        ->name('plots.plant');
});


// ================= INVOICES =================
Route::middleware('auth')->group(function () {

    Route::get('/my-invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF'])->name('invoices.download');

    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
});


// ================= LEASES =================
Route::middleware('auth')->group(function () {

    Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');

    Route::post('/leases/{id}/renew', [LeaseController::class, 'renew'])
        ->middleware('role:admin,warden')->name('leases.renew');

    Route::post('/leases/{id}/terminate', [LeaseController::class, 'terminate'])
        ->middleware('role:admin,warden')->name('leases.terminate');
});


// ================= SOIL =================
Route::middleware('auth')->group(function () {

    Route::get('/soil-health', [SoilRecordController::class, 'index'])->name('soil.index');

    Route::post('/soil-health/store', [SoilRecordController::class, 'store'])->name('soil.store');

    Route::get('/soil/{id}/edit', [SoilRecordController::class, 'edit'])->name('soil.edit');

    Route::put('/soil/{id}', [SoilRecordController::class, 'update'])->name('soil.update');
});


// ================= PEST REPORTS =================
Route::middleware('auth')->group(function () {

    Route::get('/pest-reports', [PestReportController::class, 'index'])->name('pest.index');

    Route::post('/pest-reports/store', [PestReportController::class, 'store'])->name('pest.store');

    Route::post('/pest-reports/{id}/resolve', [PestReportController::class, 'resolve'])->name('pest.resolve');
});


// ================= WAITLIST =================
Route::middleware('auth')->group(function () {

    Route::post('/waitlist/join', [WaitlistController::class, 'store'])->name('waitlist.store');

    Route::get('/waitlist', [WaitlistController::class, 'index']);

    Route::delete('/waitlist/{id}', [WaitlistController::class, 'destroy'])->name('waitlist.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/waitlist', [WaitlistController::class, 'index'])->name('admin.waitlist');

    Route::post('/admin/waitlist/assign/{id}', [WaitlistController::class, 'assignPlot'])->name('admin.waitlist.assign');
});


// ================= COMPLIANCE =================
Route::get('/compliance/create', [ComplianceAuditController::class, 'create'])->name('compliance.create');

Route::post('/compliance/store', [ComplianceAuditController::class, 'store'])->name('compliance.store');


// ================= INSPECTIONS =================
Route::middleware(['auth', 'role:warden'])->group(function () {

    Route::get('/warden/inspections', [InspectionController::class, 'index'])->name('warden.inspections.index');

    Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create'])->name('warden.inspections.create');

    Route::post('/warden/inspections', [InspectionController::class, 'store'])->name('inspections.store');
});


// ================= SHARING =================
Route::middleware('auth')->group(function () {

    Route::get('/sharing', [PlotShareController::class, 'index'])->name('sharing.index');

    Route::post('/sharing/invite', [PlotShareController::class, 'store'])->name('sharing.invite');

    Route::post('/sharing/{id}/accept', [PlotShareController::class, 'accept'])->name('sharing.accept');

    Route::post('/sharing/{id}/reject', [PlotShareController::class, 'reject'])->name('sharing.reject');
});


// ================= COMPLAINTS =================
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');

Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');


// ================= NOTIFICATIONS =================
Route::get('/notifications/{id}/read', function ($id) {

    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    return redirect($notification->data['url']);
})->name('notifications.read');


// ================= TASKS =================
Route::middleware('auth')->group(function () {

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);

    Route::get('/tasks/{id}/done', function ($id) {

        $task = \App\Models\Task::findOrFail($id);
        $task->status = 'done';
        $task->save();

        return back();
    });
});


// ================= SHIFTS =================
Route::middleware('auth')->group(function () {

    Route::get('/shifts', [ShiftController::class, 'index']);

    Route::post('/shifts', function (\Illuminate\Http\Request $request) {

        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        return app(ShiftController::class)->store($request);
    });

    Route::get('/shifts/{id}/join', function ($id) {

        $shift = \App\Models\Shift::findOrFail($id);

        if(!$shift->users->contains(auth()->id())) {
            $shift->users()->attach(auth()->id());
        }

        return back();
    });
});


// ================= VOLUNTEER =================
Route::middleware('auth')->group(function () {

    Route::get('/volunteer-hours', [VolunteerHourController::class, 'create']);
    Route::post('/volunteer-hours', [VolunteerHourController::class, 'store']);
});

Route::get('/notifications/{id}/read', function($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect($notification->data['url'] ?? '/');
})->name('notifications.read');

// لازم الاسم يكون soil.index
Route::get('/soil-health', [SoilRecordController::class, 'index'])->name('soil.index');

// ولازم الاسم يكون pest.index
Route::get('/pest-control', [PestController::class, 'index'])->name('pest.index');


Route::get('/notifications/{id}/read', function($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    
    // تعليم النوتفيكيشن كأنها اتفرت
    $notification->markAsRead();
    
    // بنجيب الـ url من الداتا اللي إنتي مخزناها في النوتفيكيشن فوق
    $url = $notification->data['url'] ?? url('/');
    
    return redirect($url);
})->name('notifications.read');


Route::get('/notifications/{id}/read', function($id) {
    // 1. نجيب الإشعار
    $notification = auth()->user()->notifications()->findOrFail($id);
    
    // 2. نعلمه كـ "مقروء" عشان يختفي من العداد
    $notification->markAsRead();
    
    // 3. نجيب الرابط اللي متخزن جوه الإشعار ونروح له
    // لو مفيش رابط، يوديه لصفحة الـ Dashboard كأمان
    $url = $notification->data['url'] ?? route('dashboard.show');
    
    return redirect($url);
})->name('notifications.read');

Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])
    ->name('notifications.read');

    Route::get('/notifications/{id}/read', function ($id) {

    $notification = auth()->user()->notifications()->findOrFail($id);

    $notification->markAsRead();

    return back();

})->name('notifications.read');