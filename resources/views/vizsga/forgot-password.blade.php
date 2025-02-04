@extends('vizsga.layouts.app')

@section('title', 'Elfelejtett jelszó')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Elfelejtett jelszó</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('vizsga.password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email cím</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Jelszó visszaállítása</button>
                    </form>
                    <div class="mt-3">
                        <p>Emlékszel a jelszavadra? <a href="{{ route('vizsga.bejelentkez') }}">Jelentkezz be!</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
