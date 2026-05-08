@extends('layouts.app')

<style>
    .page-heading { margin-top: 80px; }
    .badge-pest { font-size: 0.85rem; padding: 0.5em 0.8em; }
</style>

@section('content')
<div class="container page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bug text-danger"></i> Pest Monitoring & Community Safety</h2>
        <span class="badge bg-info text-dark">Early Warning System Active</span>
    </div>

    {{-- 1. قسم البلاغ الجديد --}}
    @if($plots->count() > 0)
    <div class="card mb-4 shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-megaphone"></i> Submit New Pest Report
        </div>
        <div class="card-body">
            <form action="{{ route('pest.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Select Your Plot</label>
                        <select name="plot_id" class="form-select" required>
                            <option value="">-- Choose Plot --</option>
                            @foreach($plots as $plot)
                                <option value="{{ $plot->id }}">Plot #{{ $plot->plot_number ?? $plot->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="fw-bold">Pest Type (e.g. Aphids, Locusts)</label>
                        <input type="text" name="pest_type" class="form-control" placeholder="What did you find?" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Describe the issue</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tell us more about the infection area..." required></textarea>
                </div>
                <button type="submit" class="btn btn-danger shadow-sm">Submit Report</button>
            </form>
        </div>
    </div>
    @endif

    {{-- 2. جدول التقارير --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-journal-text"></i> Community-wide Pest Logs (Early Warning Feed)</span>
            <span class="badge bg-light text-dark">{{ $reports->count() }} Records Found</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Plot</th>
                            <th>Reported By</th>
                            <th>Pest Type</th>
                            <th>Status</th>
                            {{-- عمود الأكشن يظهر فقط للواردن والأدمن في الرأس --}}
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'warden')
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                            <td><span class="badge bg-info text-white">#{{ $report->plot->plot_number ?? $report->plot_id }}</span></td>
                            <td>{{ $report->user->name }}</td>
                            <td>{{ $report->pest_type }}</td>
                            <td>
                                <span class="badge badge-pest {{ $report->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            {{-- هنا مكان الزرار الصح داخل الـ Body --}}
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'warden')
                            <td>
                                @if($report->status === 'pending')
                                    <form action="{{ route('pest.resolve', $report->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success shadow-sm">
                                            <i class="bi bi-check-circle"></i> Mark Resolved
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success small fw-bold"><i class="bi bi-patch-check"></i> Resolved</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">The garden is currently safe! No pest reports found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection