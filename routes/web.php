<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


// ================= USERS =================

Route::resource('users', UserController::class)
    ->only(['create', 'store']);

Route::resource('users', UserController::class)
    ->middleware('auth')
    ->only(['index', 'show', 'edit', 'update', 'destroy']);

Route::get('/login', [UserController::class, 'show_login'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ================= DASHBOARD =================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('isAdmin')
    ->name('dashboard.show');


// ================= AUDIT LOGS =================

Route::get('/audit-logs', [AuditLogController::class, 'index'])
    ->middleware('auth')
    ->name('audit.logs');


// ================= SEEDS =================

Route::resource('seeds', SeedController::class)
    ->middleware('auth');


// ================= PLOTS =================

Route::middleware('auth')->group(function () {

    Route::get('/plots', [PlotController::class, 'index'])->name('plots.index');

    Route::get('/plots/create', [PlotController::class, 'create'])
        ->middleware('isAdmin')
        ->name('plots.create');

    Route::post('/plots', [PlotController::class, 'store'])
        ->middleware('isAdmin')
        ->name('plots.store');

    Route::get('/plots/{plot}', [PlotController::class, 'show'])
        ->name('plots.show');

    Route::post('/plots/{plot}/rent', [PlotController::class, 'rent'])
        ->name('plots.rent');

    Route::post('/plots/{plot}/approve', [PlotController::class, 'approveLease'])
        ->name('plots.approve');

    Route::middleware('isAdmin')->group(function () {

        Route::get('/plots/{plot}/edit', [PlotController::class, 'edit'])
            ->name('plots.edit');

        Route::put('/plots/{plot}', [PlotController::class, 'update'])
            ->name('plots.update');

        Route::delete('/plots/{plot}', [PlotController::class, 'destroy'])
            ->name('plots.destroy');
    });
});


// ================= ADMIN REQUESTS =================

Route::get('/admin/requests', [PlotController::class, 'pendingRequests'])
    ->middleware('isAdmin')
    ->name('admin.requests');


// ================= PLANTING =================

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/plots/{plot}/plant', [PlotController::class, 'showPlantForm'])
        ->name('plots.plant.page');

    Route::post('/plots/{plot}/plant', [PlotController::class, 'plant'])
        ->name('plots.plant');
});


// ================= INVOICES =================

Route::middleware('auth')->group(function () {

    Route::get('/my-invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');

    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show');

    Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF'])
        ->name('invoices.download');

    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])
        ->name('invoices.pay');
});


// ================= LEASES =================

Route::middleware(['auth'])->group(function () {

    Route::get('/leases', [LeaseController::class, 'index'])
        ->name('leases.index');

    Route::middleware(['role:admin,warden'])->group(function () {

        Route::post('/leases/{id}/renew', [LeaseController::class, 'renew'])
            ->name('leases.renew');

        Route::post('/leases/{id}/terminate', [LeaseController::class, 'terminate'])
            ->name('leases.terminate');
    });
});


// ================= SOIL =================

Route::get('/soil-health', [SoilRecordController::class, 'index'])
    ->name('soil.index');

Route::post('/soil-health/store', [SoilRecordController::class, 'store'])
    ->name('soil.store');


// ================= PEST REPORTS =================

Route::middleware('auth')->group(function () {

    Route::get('/pest-reports', [PestReportController::class, 'index'])
        ->name('pest.index');

    Route::post('/pest-reports/store', [PestReportController::class, 'store'])
        ->name('pest.store');

    Route::post('/pest-reports/{id}/resolve', [PestReportController::class, 'resolve'])
        ->name('pest.resolve');
});


// ================= WAITLIST =================

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::post('/waitlist/join', [WaitlistController::class, 'store'])
        ->name('waitlist.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/waitlist', [WaitlistController::class, 'index'])
        ->name('admin.waitlist');

    Route::post('/admin/waitlist/assign/{id}', [WaitlistController::class, 'assignPlot'])
        ->name('admin.waitlist.assign');
});

Route::delete('/waitlist/{id}', [WaitlistController::class, 'destroy'])
    ->name('waitlist.destroy');

Route::get('/waitlist', [WaitlistController::class, 'index'])
    ->middleware('auth');


// ================= COMPLIANCE =================

Route::get('/compliance/create', [ComplianceAuditController::class, 'create'])
    ->name('compliance.create');

Route::post('/compliance/store', [ComplianceAuditController::class, 'store'])
    ->name('compliance.store');


// ================= INSPECTIONS =================

Route::middleware(['auth', 'role:warden'])->group(function () {

    Route::get('/warden/inspections', [InspectionController::class, 'index'])
        ->name('warden.inspections.index');

    Route::post('/warden/inspections/store', [InspectionController::class, 'store'])
        ->name('inspections.store');

    Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create'])
        ->name('warden.inspections.create');
});


// ================= SHARING =================

Route::middleware(['auth'])->group(function () {

    Route::get('/sharing', [PlotShareController::class, 'index'])
        ->name('sharing.index');

    Route::post('/sharing/invite', [PlotShareController::class, 'store'])
        ->name('sharing.invite');

    Route::post('/sharing/{id}/accept', [PlotShareController::class, 'accept'])
        ->name('sharing.accept');

    Route::post('/sharing/{id}/reject', [PlotShareController::class, 'reject'])
        ->name('sharing.reject');
});

Route::resource('sharing', PlotShareController::class);


// ================= COMPLAINTS =================

Route::post('/complaints', [ComplaintController::class, 'store'])
    ->name('complaints.store');

    Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
    // روت حل الشكوى للأدمن
Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])
    ->name('complaints.resolve');
    Route::post('/leases/{id}/reject', [PlotController::class, 'rejectLease'])->name('plots.reject');

    Route::get('/warden/inspections', [InspectionController::class, 'index']);

Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create']);

// روت عرض صفحة الشكوى (GET)
Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');

// روت حفظ الشكوى (POST) - تأكدي إن العنوان هو /complaints/store
Route::post('/complaints/store', [ComplaintController::class, 'store'])->name('complaints.store');
// ================= NOTIFICATIONS =================

Route::get('/notifications/{id}/read', function ($id) {

    $notification = auth()->user()->notifications()->findOrFail($id);

    $notification->markAsRead();

    return redirect($notification->data['url']);

})->name('notifications.read');



Route::get('/warden/inspections', [InspectionController::class, 'index'])
    ->name('warden.inspections.index');

Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create'])
    ->name('warden.inspections.create');

    Route::post('/warden/inspections', [InspectionController::class, 'store'])
    ->name('inspections.store');
    Route::get('/soil-health', [SoilRecordController::class, 'index'])
    ->middleware('auth');
    Route::get('/soil-health', [SoilRecordController::class, 'index'])
    ->name('soil.index')
    ->middleware('auth');
    Route::get('/soil/{id}/edit', [SoilController::class, 'edit'])->name('soil.edit');
Route::put('/soil/{id}', [SoilController::class, 'update'])->name('soil.update');


Route::get('/soil/{id}/edit', [SoilRecordController::class, 'edit'])->name('soil.edit');
Route::put('/soil/{id}', [SoilRecordController::class, 'update'])->name('soil.update');
