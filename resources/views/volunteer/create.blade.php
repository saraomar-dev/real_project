@if(auth()->user()->role=='admin')
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Hours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card p-4 shadow">

        <h3 class="text-center mb-4">📊 Add Volunteer Hours</h3>

        <form method="POST" action="/volunteer-hours">
            @csrf

            <!-- Shift -->
            <div class="mb-3">
                <label class="form-label">Shift</label>
                <select name="shift_id" class="form-select">
                    @foreach(\App\Models\Shift::all() as $shift)
                        <option value="{{ $shift->id }}">
                            {{ $shift->date }} - {{ $shift->time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Hours -->
            <div class="mb-3">
                <label class="form-label">Hours</label>
                <input type="number" name="hours" class="form-control" placeholder="Enter hours">
            </div>

            <button class="btn btn-primary w-100">
                💾 Save Hours
            </button>

        </form>

    </div>

</div>
<h1>{{auth()->user()->role}}</h1>
</body>
</html>

@endif