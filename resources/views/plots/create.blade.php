@extends('layouts.app')
<style>
    .page-heading {
        margin-top: 80px; 
    }
</style>


@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h3 class="mb-0 fw-bold text-primary">Add New Plot</h3>
        </div>
        <div class="card-body">
            <!-- لاحظي إضافة enctype لرفع الملفات -->
            <form action="{{ route('plots.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Plot Number</label>
                        <input type="text" name="plot_number" class="form-control" placeholder="e.g. A-101" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Area (sqm)</label>
                        <input type="number" step="0.01" name="area_sqm" class="form-control" placeholder="e.g. 50.00" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Soil Quality</label>
                        <select name="soil_quality" class="form-select">
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                            <option value="poor">Poor</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Sunlight Exposure (%)</label>
                        <input type="number" name="sunlight_exposure" min="0" max="100" class="form-control" placeholder="0-100" required>
                    </div>

                    <!-- حقل المكان الجديد -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Location Tag</label>
                        <input type="text" name="location_tag" class="form-control" placeholder="e.g. East Sector / Garden A">
                    </div>

                    <!-- حقل رفع الصورة الجديد -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Plot Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Accepted formats: jpg, png, jpeg (Max 2MB)</small>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('plots.index') }}" class="btn btn-light border">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Save Plot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection