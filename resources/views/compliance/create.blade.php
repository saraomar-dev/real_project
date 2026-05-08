@extends('layouts.app')
<style>
    .page-heading {
        margin-top: 80px;
    }
</style>

@section('content')
<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4 text-primary">New Compliance Inspection</h4>
        <form action="{{ route('compliance.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Select Plot</label>
                    <select name="plot_id" class="form-select">
                        @foreach($plots as $plot)
                            <option value="{{ $plot->id }}">Plot #{{ $plot->plot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="compliant" class="text-success">Compliant (ملتزم)</option>
                        <option value="violation" class="text-danger">Violation (مخالف)</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Inspection Image</label>
                <input type="file" name="inspection_image" class="form-control">
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary px-4 shadow-0">Submit Report</button>
        </form>
    </div>
</div>
@endsection