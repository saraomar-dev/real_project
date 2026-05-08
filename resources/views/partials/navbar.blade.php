@if(auth()->check())
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #e3f2fd;">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/">🌿 GardenProject</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Garden Plots --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('plots.index') }}">
                        Garden Plots
                    </a>
                </li>

                {{-- Users --}}
                <li class="nav-item">
                    <a class="nav-link" href="/users">Users</a>
                </li>

                {{-- Profile --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.show', auth()->id()) }}">
                        Profile
                    </a>
                </li>

                {{-- Seeds --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seeds.index') }}">
                        Seeds
                    </a>
                </li>

                {{-- Invoices --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('invoices.index') }}">
                        Invoices
                    </a>
                </li>

                {{-- Soil --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('soil.index') }}">
                        Soil
                    </a>
                </li>

                {{-- Pests --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pest.index') }}">
                        Pests
                    </a>
                </li>

                {{-- Invitations --}}
                @if(auth()->user()->role === 'user')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sharing.index') }}">
                            Invitations
                        </a>
                    </li>
                @endif

                {{-- Inspections --}}
                @if(auth()->user()->role === 'warden')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('warden.inspections.index') }}">
                            Inspections
                        </a>
                    </li>
                @endif

                {{-- Admin --}}
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.show') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.requests') }}">
                            Requests
                        </a>
                    </li>
                @endif

                {{-- Logout --}}
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-link btn btn-link">Logout</button>
                    </form>
                </li>

            </ul>

            {{-- Right side --}}
            <ul class="navbar-nav ms-auto align-items-center">

            {{-- Notifications --}}
<li class="nav-item dropdown me-3">
    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

        <i class="bi bi-bell-fill fs-5 text-warning"></i>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif

    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0">

        @forelse(auth()->user()->unreadNotifications as $notification)
            <li>
                <a class="dropdown-item small" href="{{ route('notifications.read', $notification->id) }}">
                    {{ $notification->data['title'] }}
                </a>
            </li>
        @empty
            <li class="p-2 text-center small text-muted">
                No notifications
            </li>
        @endforelse

    </ul>
</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.show', auth()->id()) }}">
                        {{ auth()->user()->name }}
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

@endif