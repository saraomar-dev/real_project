@extends('layouts.app')

@section('content')
<style>
    .page-heading { margin-top: 80px; }
    .badge-pests {
        background-color: #ffe5e5;
        color: #d9534f;
        border: 1px solid #d9534f;
    }
    .status-badge { font-size: 0.8rem; padding: 5px 12px; border-radius: 20px; }
</style>

<div class="container page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Plot Details: #{{ $plot->plot_number }}</h3>
                <p class="text-subtitle text-muted">Technical specifications and monitoring status.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('plots.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    @php
        $userId = auth()->id();
        $userRole = auth()->user()->role;
        $isOwner = $plot->user_id == $userId;
        $isWarden = $userRole == 'warden';
        $isAdmin = $userRole == 'admin';
        
        $isPartner = \App\Models\PlotShare::where('plot_id', $plot->id)
                        ->where('shared_with', $userId)
                        ->where('status', 'accepted')
                        ->exists();
    @endphp

    <section class="section">
        <div class="row">
            {{-- العمود الأول: الصورة وسجل التفتيش --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        @if($plot->image)
                            <img src="{{ asset($plot->image) }}" class="img-fluid rounded-4 shadow-sm mb-4" style="width: 100%; max-height: 400px; object-fit: cover;">
                        @else
                            <div class="alert alert-secondary text-center py-5 rounded-4 mb-4">
                                <i class="bi bi-image-fill fs-1 d-block mb-2"></i> No plot image uploaded.
                            </div>
                        @endif

                        <h4 class="card-title">Technical Overview</h4>
                        <p class="text-muted">
                            Sector: <strong>{{ $plot->location_tag ?? 'Main Garden' }}</strong>. 
                            Status: <span class="badge {{ $plot->status == 'available' ? 'bg-success' : 'bg-warning text-dark' }}">{{ strtoupper($plot->status) }}</span>
                        </p>

                        {{-- سجل التفتيش (Audit Logs) --}}
                        @if($isAdmin || $isWarden || $isOwner || $isPartner)
                            <div class="mt-5 pt-4 border-top">
                                <h5 class="text-success mb-3"><i class="bi bi-shield-shaded"></i> Compliance Audit Logs</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th><th>Status</th><th>Pests</th><th>Observer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($plot->inspections as $inspection)
                                                <tr>
                                                    <td>{{ $inspection->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge {{ $inspection->status == 'compliant' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ strtoupper($inspection->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{!! $inspection->has_pests ? '<span class="badge badge-pests">YES</span>' : 'No' !!}</td>
                                                    <td class="small text-muted">{{ $inspection->warden->name ?? 'Staff' }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center py-3 text-muted">No inspection records found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- العمود الثاني: المواصفات والأزرار --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0">Plot Specifications</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                Area <span><strong>{{ $plot->area_sqm }} sqm</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                Soil Type <span class="badge bg-light-info text-dark">{{ strtoupper($plot->soil_quality) }}</span>
                            </li>
                        </ul>

                        {{-- منطقة الأزرار الديناميكية --}}
                        <div class="d-grid gap-2">

                            {{-- 1. لليوزر الجديد: زرار التأجير --}}
                            @if($plot->status === 'available' && $userRole === 'user' && !$isOwner)
                                <form action="{{ route('plots.rent', $plot->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                        <i class="bi bi-key-fill"></i> RENT THIS PLOT
                                    </button>
                                </form>
                            @endif

                            {{-- 2. للمالك أو الشريك --}}
                            {{-- 2. للمالك أو الشريك --}}
@if($isOwner || $isPartner)
    @php
        // بنجيب أخر عقد لليوزر ده على الأرض دي
        $currentLease = \App\Models\Lease::where('plot_id', $plot->id)
                        ->where('user_id', $isOwner ? $userId : $plot->user_id)
                        ->latest()
                        ->first();
    @endphp

    {{-- التعديل هنا: بنخلي الشرط يقبل rented أو active --}}
    @if($currentLease && in_array(strtolower($currentLease->status), ['active', 'rented']))
        @if(!$plot->seed_id)
            <a href="{{ route('plots.plant.page', $plot->id) }}" class="btn btn-success py-2 fw-bold mb-2 w-100">
                <i class="bi bi-plus-circle"></i> START PLANTING
            </a>
        @else
            <div class="alert alert-success py-2 text-center small border-0 shadow-sm mb-2">
                <i class="bi bi-check-circle"></i> Growing: <strong>{{ $plot->seed->name }}</strong>
            </div>
        @endif

        {{-- زرار الشكاوى --}}
        <a href="{{ route('complaints.create', ['plot_id' => $plot->id]) }}" class="btn btn-danger py-2 fw-bold w-100 mb-2">
            <i class="bi bi-exclamation-triangle"></i> REPORT AN ISSUE
        </a>

        @if($isOwner)
            <a href="{{ route('sharing.index') }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-people"></i> Manage Sharing
            </a>
        @endif
    @else
        {{-- دي الرسالة اللي كانت بتطلع لك --}}
        <div class="alert alert-warning text-center small border-0 shadow-sm">
            <i class="bi bi-clock-history"></i> Lease Status: {{ $currentLease->status ?? 'Not Found' }}
            <br>Wait for Admin to activate your lease.
        </div>
    @endif
@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection