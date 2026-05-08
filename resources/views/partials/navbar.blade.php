@if(auth()->check())
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">🌿 GardenProject</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                {{-- 1. اللينك الأساسي للأراضي (متاح للكل) --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('plots') ? 'active' : '' }}" href="{{ route('plots.index') }}">
                        <i class="bi bi-grid-3x3-gap"></i> Garden Plots
                    </a>
                </li>

                {{-- 2. Seeds & Invoices (متاح للكل) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seeds.index') }}"><i class="bi bi-flower1"></i> Seeds</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="{{ route('invoices.index') }}"><i class="bi bi-receipt"></i> Invoices</a>
                </li>

                {{-- 3. التربة والآفات (Soil & Pests) --}}
                <li class="nav-item">
                    <a class="nav-link text-info fw-bold" href="{{ route('soil.index') }}">
                        <i class="bi bi-moisture"></i> Soil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="{{ route('pest.index') }}">
                        <i class="bi bi-bug"></i> Pests
                    </a>
                </li>

                {{-- 4. للمزارع فقط: الدعوات --}}
                @if(auth()->user()->role === 'user')
                <li class="nav-item">
                    <a class="nav-link text-primary fw-bold" href="{{ route('sharing.index') }}">
                        <i class="bi bi-people"></i> Invitations
                        @php
                            $invitesCount = \App\Models\PlotShare::where('shared_with', auth()->id())->where('status', 'pending')->count();
                        @endphp
                        @if($invitesCount > 0)
                            <span class="badge bg-danger ms-1">{{ $invitesCount }}</span>
                        @endif
                    </a>
                </li>
                @endif

                {{-- 5. للموظفين (Warden/Admin) --}}
                @if (auth()->user()->role === 'warden')
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="{{ route('warden.inspections.index') }}">
                            <i class="bi bi-clipboard-check"></i> Inspections
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="{{ route('dashboard.show', auth()->id()) }}">📊 Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="{{ route('admin.requests') }}">
                            Requests <span class="badge bg-danger">{{ \App\Models\Plot::where('status', 'pending')->count() }}</span>
                        </a>
                    </li>
                @endif
            </ul>

            {{-- اليمين: التنبيهات والبروفايل --}}
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown me-3">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell-fill fs-5 text-warning"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <li><a class="dropdown-item small" href="{{ route('notifications.read', $notification->id) }}">{{ $notification->data['title'] }}</a></li>
                        @empty
                            <li class="p-2 text-center small text-muted">No alerts</li>
                        @endforelse
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="{{ route('users.show', auth()->id()) }}">
                        {{ auth()->user()->name }}
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endif