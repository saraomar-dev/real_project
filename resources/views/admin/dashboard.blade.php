<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<hr>

<div class="container mt-4">

    <h3 class="mb-3 text-center">Reports Overview</h3>

    <div class="row">

        <!-- 🔴 Damage Card -->
        <div class="col-md-6 mb-3">
            <div class="card shadow bg-danger text-white">
                <div class="card-body text-center">
                    <h5>Damage Reports</h5>
                    <h2>{{ $damagesCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- 🟡 Incident Card -->
        <div class="col-md-6 mb-3">
            <div class="card shadow bg-warning text-dark">
                <div class="card-body text-center">
                    <h5>Incident Reports</h5>
                    <h2>{{ $incidentsCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

    </div>

</div>
<hr>

<h4>Damage Reports</h4>

@foreach($damages ?? [] as $d)

    <div class="card mb-2 p-2">

        <p>{{ $d->description }}</p>

        @if($d->image)
            <img src="{{ asset('storage/' . $d->image) }}" width="100">
        @endif

        
        @if(!$d->fine)
            <a href="/damage/{{ $d->id }}/fine" class="btn btn-warning btn-sm">
                Add Fine
            </a>
        @else
            <span class="text-success">Fine Added</span>
        @endif

    </div>

@endforeach