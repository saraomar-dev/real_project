@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="bi bi-shield-check"></i> Plot Inspection: #{{ $plot->plot_number }}
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('inspections.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plot_id" value="{{ $plot->id }}">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Compliance Status</label>
                            <select name="status" class="form-select rounded-3" required>
                                <option value="good">Compliant (ملتزم)</option>
                                <option value="warning">Warning (تنبيه)</option>
                                <option value="violation">Violation (مخالف)</option>
                            </select>
                        </div>

                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="has_pests" id="pestSwitch">
                                <label class="form-check-label fw-bold" for="pestSwitch">
                                    <i class="bi bi-bug text-danger"></i> Pests Detected?
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Warden Notes</label>
                            <textarea name="notes" class="form-control rounded-3" rows="4" placeholder="Enter findings here..." required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold rounded-pill shadow-sm">
                                SAVE INSPECTION
                            </button>
                            <a href="{{ route('warden.inspections.index') }}" class="btn btn-link text-muted text-decoration-none">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection