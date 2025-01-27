<!doctype html>
<html lang="hu" data-bs-theme="dark">

<head>
    <title>Téli-Projekt</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        #edit_leiras {
            height: 150px;
        }
    </style>

</head>
<body>

    {{-- Hasznos kommentek lejjebb --}}

    <header>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ route('fooldal') }}">Téli-Projekt</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('fooldal') ? 'active' : '' }}" href="{{ route('fooldal') }}">Főoldal</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('feltoltesoldal') ? 'active' : '' }}" href="{{ route('feltoltesoldal') }}">Feltöltés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('musorok') ? 'active' : '' }}" href="{{ route('musorok') }}">Műsorok</a>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Bejelentkezés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('regisztracio') ? 'active' : '' }}" href="{{ route('regisztracio') }}">Regisztráció</a>
                            </li>
                        @endguest
                    </ul>
                    @auth
                        <div class="d-flex align-items-center">
                            <a href="{{ route('profil') }}" class="btn btn-outline-light me-2" data-bs-title="Profil" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <form action="{{ route('kijelentkezes') }}" method="GET" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" title="Kijelentkezés" data-bs-title="Kijelentkezés" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    {{-- Az oldal tartalma itt fog megjelenni mindig a @yield miatt --}}
    {{-- Az ebben a fileban történő változások minden oldalon láthatóak lesznek --}}
    {{-- Tutorial video (a @yield, @extends és @section/@endsection használatára):--}}
    {{-- https://www.youtube.com/watch?v=3UhgEsLxmG8&ab_channel=YeloCode --}}

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    @stack('scripts')
</body>
</html>