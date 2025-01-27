@extends('vizsgaremek.layout.layout')

@section('content')
    <div class="container position-absolute top-50 start-50 translate-middle">
        <h1 class="text-center">Elfelejtett jelszó</h1>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email cím</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    <div class="form-text">Add meg az email címed, és küldünk egy új jelszót.</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Új jelszó kérése
                </button>
            </form>
            <a href="{{ route('login') }}" class="mt-3 d-inline-block">Vissza a bejelentkezéshez</a>
        </div>
    </div>
@endsection