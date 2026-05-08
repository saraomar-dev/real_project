@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px;">
    {{-- Page Header --}}
    <div class="page-heading mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold"><i class="bi bi-shield-check text-primary"></i> Land Use Compliance Audit</h3>
            <p class="text-muted">Inspect rented plots, document findings, and report pests.</p>
        </div>

        {{-- تظهر للأدمن فقط استخراج التقارير --}}
        @if(auth()->user()->role === 'admin')
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-file-earmark-pdf"></i> Generate Reports
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
<li><a class="dropdown-item" href="{{ route('admin.inspections.report') }}"><i class="bi bi-file-pdf text-danger"></i> Export Compliance PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print Audit Trail</a></li>
                </ul>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <section class="section">
        {{-- الجدول الأول: الأراضي الجاهزة للمعاينة --}}
        <div class="card shadow-sm border-0 rounded-4 mb-5">
            <div class="card-header bg-primary text-white fw-bold py-3">
                Rented Plots Ready for Inspection
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Plot #</th>
                                <th>Tenant</th>
                                <th>Last Inspection</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentedPlots as $plot)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $plot->plot_number }}</td>
                                <td>{{ $plot->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $plot->inspections->last() ? $plot->inspections->last()->created_at->format('M d, Y') : 'Never Inspected' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- التعديل هنا: لو واردن يظهر الزرار، لو أدمن يظهر نص فقط --}}
                                    @if(auth()->user()->role === 'warden')
                                        <a href="{{ route('warden.inspections.create', $plot->id) }}" class="btn btn-sm btn-warning px-4 rounded-pill shadow-sm fw-bold">
                                            <i class="bi bi-pencil-square"></i> Perform Inspection
                                        </a>
                                    @elseif(auth()->user()->role === 'admin')
                                        <span class="badge bg-light text-dark border">Monitoring Mode</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No rented plots available for audit.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- الجدول الثاني: سجل التقارير --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-dark text-white fw-bold py-3">
                Recent Inspection Reports (Audit Trail)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Plot</th>
                                <th>Status</th>
                                <th>Pests?</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allInspections as $insp)
                            <tr>
                                <td class="ps-4 small">{{ $insp->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold">#{{ $insp->plot->plot_number }}</td>
                                <td>
                                    @if($insp->status == 'good' || $insp->status == 'compliant')
                                        <span class="badge bg-success">Compliant</span>
                                    @else
                                        <span class="badge bg-danger">Violation</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $insp->has_pests 
                                        ? '<i class="bi bi-bug-fill text-danger"></i> Yes' 
                                        : '<i class="bi bi-check text-success"></i> No' !!}
                                </td>
                                <td class="small text-muted">{{ \Illuminate\Support\Str::limit($insp->notes, 40) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection