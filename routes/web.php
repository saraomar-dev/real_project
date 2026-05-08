<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, UserController, SeedController, AuditLogController,
    WaitlistController, LeaseController, InspectionController, PlotController,
    InvoiceController, SoilRecordController, PestReportController,
    ComplianceAuditController, PlotShareController, ComplaintController,
    ToolReservationController, ToolController, MarketplaceController,
    TradeRequestController, RatingController, IncidentReportController,
    DamageReportController, TaskController, ShiftController, VolunteerHourController
};


Route::get('/', function () {
    return view('welcome');
});

// ================= AUTH & USERS =================
Route::resource('users', UserController::class)->only(['create', 'store']);
Route::resource('users', UserController::class)->middleware('auth')->only(['index', 'show', 'edit', 'update', 'destroy']);

Route::get('/login', [UserController::class, 'show_login'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');

// ================= DASHBOARD & AUDIT =================
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.show');
Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('auth')->name('audit.logs');

// ================= PLOTS (Rawan & Core) =================
Route::middleware('auth')->group(function () {
    Route::get('/plots', [PlotController::class, 'index'])->name('plots.index');
    Route::get('/plots/create', [PlotController::class, 'create'])->middleware('isAdmin')->name('plots.create');
    Route::post('/plots', [PlotController::class, 'store'])->middleware('isAdmin')->name('plots.store');
    Route::get('/plots/{plot}', [PlotController::class, 'show'])->name('plots.show');
    Route::post('/plots/{plot}/rent', [PlotController::class, 'rent'])->name('plots.rent');
    Route::post('/plots/{plot}/approve', [PlotController::class, 'approveLease'])->name('plots.approve');
    Route::post('/plots/{plot}/reject', [PlotController::class, 'rejectLease'])->name('plots.reject');

    Route::middleware('isAdmin')->group(function () {
        Route::get('/plots/{plot}/edit', [PlotController::class, 'edit'])->name('plots.edit');
        Route::put('/plots/{plot}', [PlotController::class, 'update'])->name('plots.update');
        Route::delete('/plots/{plot}', [PlotController::class, 'destroy'])->name('plots.destroy');
    });
});

// ================= SOIL & PESTS (Menna) =================
Route::middleware('auth')->group(function () {
    Route::get('/soil-health', [SoilRecordController::class, 'index'])->name('soil.index');
    Route::post('/soil-health/store', [SoilRecordController::class, 'store'])->name('soil.store');
    Route::get('/soil/{id}/edit', [SoilRecordController::class, 'edit'])->name('soil.edit');
    Route::put('/soil/{id}', [SoilRecordController::class, 'update'])->name('soil.update');

    Route::get('/pest-reports', [PestReportController::class, 'index'])->name('pest.index');
    Route::post('/pest-reports/store', [PestReportController::class, 'store'])->name('pest.store');
    Route::post('/pest-reports/{id}/resolve', [PestReportController::class, 'resolve'])->name('pest.resolve');
});

// ================= INVOICES & LEASES =================
Route::middleware('auth')->group(function () {
    Route::get('/my-invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{id}/download', [InvoiceController::class, 'downloadPDF'])->name('invoices.download');
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');

    Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');
    Route::middleware('role:admin,warden')->group(function () {
        Route::post('/leases/{id}/renew', [LeaseController::class, 'renew'])->name('leases.renew');
        Route::post('/leases/{id}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');
    });
});

// ================= TOOLS & RESERVATIONS =================
Route::middleware('auth')->group(function () {
    Route::resource('tools', ToolController::class);
    Route::get('/reservations', [ToolReservationController::class, 'index']);
    Route::get('/reservations/create', [ToolReservationController::class, 'create']);
    Route::post('/reservations', [ToolReservationController::class, 'store']);
    Route::post('/tools/{id}/checkout', [ToolController::class, 'checkout']);
    Route::post('/tools/{id}/return', [ToolController::class, 'returnTool']);
});

// ================= MARKETPLACE & TRADE =================
Route::middleware('auth')->group(function () {
    Route::get('/marketplace', [MarketplaceController::class, 'index']);
    Route::post('/marketplace', [MarketplaceController::class, 'store']);
    Route::post('/trade/{id}/request', [TradeRequestController::class, 'store']);
    Route::post('/trade/{id}/accept', [TradeRequestController::class, 'accept']);
    Route::post('/trade/{id}/reject', [TradeRequestController::class, 'reject']);
    Route::get('/trade-requests', [TradeRequestController::class, 'indexRequests']);
    Route::post('/rate', [RatingController::class, 'storeRating']);
});

// ================= INSPECTIONS (Warden) =================
Route::middleware(['auth', 'role:warden'])->group(function () {
    Route::get('/warden/inspections', [InspectionController::class, 'index'])->name('warden.inspections.index');
    Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create'])->name('warden.inspections.create');
    Route::post('/warden/inspections', [InspectionController::class, 'store'])->name('inspections.store');
});

// ================= SHARING & COMPLAINTS =================
Route::middleware('auth')->group(function () {
    Route::get('/sharing', [PlotShareController::class, 'index'])->name('sharing.index');
    Route::post('/sharing/invite', [PlotShareController::class, 'store'])->name('sharing.invite');
    Route::post('/sharing/{id}/accept', [PlotShareController::class, 'accept'])->name('sharing.accept');
    Route::post('/sharing/{id}/reject', [PlotShareController::class, 'reject'])->name('sharing.reject');
    
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints/store', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/complaints/{id}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
});

// ================= TASKS, SHIFTS & VOLUNTEERS =================
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{id}/done', [TaskController::class, 'markAsDone']);

    Route::get('/shifts', [ShiftController::class, 'index']);
    Route::post('/shifts', [ShiftController::class, 'store'])->middleware('isAdmin');
    Route::get('/shifts/{id}/join', [ShiftController::class, 'join']);

    Route::get('/volunteer-hours', [VolunteerHourController::class, 'create']);
    Route::post('/volunteer-hours', [VolunteerHourController::class, 'store']);
});

// ================= NOTIFICATIONS =================
Route::get('/notifications/{id}/read', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect($notification->data['url'] ?? route('dashboard.show'));
})->name('notifications.read');

// ================= SEEDS & ADMIN =================
Route::resource('seeds', SeedController::class)->middleware('auth');
Route::get('/admin/requests', [PlotController::class, 'pendingRequests'])->middleware('isAdmin')->name('admin.requests');


// ابحثي عن الجزء ده في web.php وتأكدي إنه كدة
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/waitlist', [WaitlistController::class, 'index'])->name('admin.waitlist');
    Route::post('/admin/waitlist/assign/{id}', [WaitlistController::class, 'assignPlot'])->name('admin.waitlist.assign');
});
// ================= SHARING =================
Route::middleware(['auth'])->group(function () {
    Route::get('/sharing', [PlotShareController::class, 'index'])->name('sharing.index');
    
    // غيرنا الاسم هنا من sharing.invite ليكون sharing.store عشان يطابق الـ View
    Route::post('/sharing/invite', [PlotShareController::class, 'store'])->name('sharing.store');

    Route::post('/sharing/{id}/accept', [PlotShareController::class, 'accept'])->name('sharing.accept');
    Route::post('/sharing/{id}/reject', [PlotShareController::class, 'reject'])->name('sharing.reject');
});

// 1. العرض متاح للأدمن والواردن (عشان الرقابة)
Route::get('/warden/inspections', [InspectionController::class, 'index'])
    ->middleware(['auth', 'role:admin,warden'])


    ->name('warden.inspections.index');

// 2. الإضافة (الفورم والحفظ) متاحة للواردن فقط
Route::middleware(['auth', 'role:warden'])->group(function () {
    Route::get('/warden/inspections/create/{plot}', [InspectionController::class, 'create'])->name('warden.inspections.create');
    Route::post('/warden/inspections', [InspectionController::class, 'store'])->name('inspections.store');
});

// ادمجي الرولز هنا عشان الصفحة تفتح للاتنين
Route::get('/warden/inspections', [InspectionController::class, 'index'])
    ->middleware(['auth', 'role:admin,warden']) // ضيفي admin هنا
    ->name('warden.inspections.index');

    Route::get('/admin/inspections/report', [InspectionController::class, 'generateReport'])->name('admin.inspections.report');

    // رووت الانضمام للويتلست
Route::post('/waitlist/join', [WaitlistController::class, 'store'])
    ->name('waitlist.store')
    ->middleware('auth');

// رووت الخروج من الويتلست (عشان ميطلعش إيرور في سطر 37)
Route::delete('/waitlist/{id}', [WaitlistController::class, 'destroy'])
    ->name('waitlist.destroy')
    ->middleware('auth');

    // الطريق لصفحة اختيار البذور والزراعة
Route::get('/plots/{plot}/plant', [App\Http\Controllers\PlotController::class, 'plantPage'])
    ->name('plots.plant.page');

    // الرووت ده هو اللي بيستقبل البيانات من الفورم ويخزنها
Route::post('/plots/{plot}/plant', [App\Http\Controllers\PlotController::class, 'plant'])
    ->name('plots.plant');

    Route::post('/plots/{plot}/harvest', [App\Http\Controllers\PlotController::class, 'harvest'])
    ->name('plots.harvest');