<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #e3f2fd;">

    <div class="container-fluid">

        <a class="navbar-brand" href="#">project</a>

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

                {{-- USERS --}}
                <li class="nav-item">
                    <a class="nav-link active" href="/users">
                        Users
                    </a>
                </li>

                {{-- PROFILE --}}
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('users.show', auth()->user()->id) }}">
                        Profile
                    </a>
                </li>

                {{-- ADMIN ONLY --}}
                @if(auth()->user()->role === 'admin')

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('dashboard.show') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('audit.logs') }}">
                            Audit Logs
                        </a>
                    </li>

                @endif


                {{-- SEEDS --}}
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('seeds.index') }}">
                        Seed Bank
                    </a>
                </li>


                {{-- TOOLS --}}
                <li class="nav-item">
                    <a class="nav-link" href="/tools">
                        Tools
                    </a>
                </li>


                {{-- TASKS --}}
                <li class="nav-item">
                    <a class="nav-link" href="/tasks">
                        Tasks
                    </a>
                </li>


                {{-- SHIFTS --}}
                <li class="nav-item">
                    <a class="nav-link" href="/shifts">
                        Shifts
                    </a>
                </li>


                {{-- RESERVATIONS --}}
                <li class="nav-item">
                    <a class="nav-link" href="/reservations">
                        Reservations
                    </a>
                </li>


                {{-- MARKETPLACE --}}
                <li class="nav-item">
                    <a class="nav-link" href="/marketplace">
                        Marketplace
                    </a>
                </li>


                {{-- LOGOUT --}}
                <li class="nav-item">

                    <form action="{{ route('logout') }}"
                          method="POST"
                          style="display:inline;">

                        @csrf

                        <button type="submit"
                                class="nav-link"
                                style="background:none;border:none;">

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>