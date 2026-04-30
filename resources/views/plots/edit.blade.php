@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h3 class="mb-0 fw-bold text-primary">Edit Plot: {{ $plot->plot_number }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('plots.update', $plot->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Plot Number</label>
                        <input type="text" name="plot_number" value="{{ $plot->plot_number }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Area (sqm)</label>
                        <input type="number" step="0.01" name="area_sqm" value="{{ $plot->area_sqm }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Soil Quality</label>
                        <select name="soil_quality" class="form-select">
                            <option value="excellent" {{ $plot->soil_quality == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ $plot->soil_quality == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ $plot->soil_quality == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ $plot->soil_quality == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sunlight Exposure (%)</label>
                        <input type="number" name="sunlight_exposure" value="{{ $plot->sunlight_exposure }}" min="0" max="100" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="available" {{ $plot->status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="rented" {{ $plot->status == 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="maintenance" {{ $plot->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('plots.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection