@extends('vizsga.layouts.app')

@section('title', 'Regisztráció')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Regisztráció</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('vizsga.regisztralas') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Név</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email cím</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Jelszó</label>
                            <div class="input-group password-input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div class="form-text">A jelszónak legalább 8 karakter hosszúnak kell lennie.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
                            <div class="input-group password-input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Regisztráció</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <p class="mb-0">Már van fiókod? <a href="{{ route('vizsga.bejelentkez') }}" class="text-decoration-none">Jelentkezz be!</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/vizsga/password-toggle.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializePasswordToggles();
    });
</script>
@endsection
@endsection
