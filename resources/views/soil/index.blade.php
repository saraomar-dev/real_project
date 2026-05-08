@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Soil Health Management</h2>
            

   
            {{-- 1. فورم الإضافة: تظهر فقط للـ Warden أو الـ Admin --}}
            @if(auth()->user()->role === 'warden')
            <div class="card mb-4 shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-plus-circle"></i> Add New Soil Record (Staff Only)
                </div>
                <div class="card-body">
                    <form action="{{ route('soil.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="fw-bold">Select Plot</label>
                                <select name="plot_id" class="form-control" required>
                                    @foreach($plots as $plot)
                                        {{-- عرض رقم الأرض واسم صاحبها عشان الواردن ميتلخبطش --}}
                                        <option value="{{ $plot->id }}">
                                            Plot #{{ $plot->id }} (Owner: {{ $plot->user->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="fw-bold">pH Level</label>
                                <input type="number" step="0.1" name="ph_level" class="form-control" placeholder="6.5" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="fw-bold">Fertilizer Type</label>
                                <input type="text" name="fertilizer_type" class="form-control" placeholder="Organic" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="fw-bold">Current Crop</label>
                                <input type="text" name="crop_type" class="form-control" placeholder="Tomatoes" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Warden Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Describe soil health status..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success shadow-sm">Save Official Status</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- 2. جدول عرض السجلات: يظهر للكل بس البيانات متفلترة من الكنترولر --}}
            <div class="card shadow-sm border-dark">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history"></i> Soil History Log</span>
                    @if(auth()->user()->role === 'user')
                        <small class="badge bg-primary">Viewing your plot records</small>
                    @endif
                </div>
                <div class="card-body">
                    @if($records->isEmpty())
                        <div class="alert alert-info text-center">No soil records found yet.</div>
                    @else
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Plot</th>
                                    <th>pH Level</th>
                                    <th>Fertilizer</th>
                                    <th>Crop</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($record->record_date)->format('d M, Y') }}</td>
                                    <td><span class="badge bg-secondary">#{{ $record->plot_id }}</span></td>
                                    <td>
                                        {{-- تلوين الـ pH حسب الحموضة (حركة احترافية ليكي) --}}
                                        <span class="badge {{ $record->ph_level > 7 ? 'bg-warning text-dark' : 'bg-info text-dark' }}">
                                            {{ $record->ph_level }}
                                        </span>
                                    </td>
                                    <td>{{ $record->fertilizer_type }}</td>
                                    <td>{{ $record->crop_type }}</td>
                                    <td class="text-start"><small>{{ $record->notes }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection