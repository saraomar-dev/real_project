<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garden Project</title>

    {{-- 1. Bootstrap 5 CSS (الأساس) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- 2. Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- 3. Mazar CSS (لو محتاجة ستايل القالب) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    
    @yield('styles')
</head>

<body>
    <div id="app">
        @include('partials.navbar')

        <div id="main" class="pt-5 mt-4"> {{-- ضفت مسافة عشان الـ navbar الـ fixed --}}
            @yield('content')
        </div>
    </div>

    {{-- 4. Bootstrap JS (ملف واحد فقط في الآخر) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>