@extends('vizsgaremek.layout.layout')
@section('content')

    <div class="text-center">
        <h1 class="display-4"> Szia! </h1>
        <p class="lead">Ez egy mozi oldal, ahol a felhasználók töltik fel a filmeket és sorozatokat, ahol tudnak értékelni és kommentelni. Te is csatlakozz hozzánk!</p>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4">Legjobban értékelt műsorok</h2>
        <div class="row">
            @foreach($topMusorok as $musor)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        @if($musor->image_url)
                        <div style="height: 350px; width: 350px; background: url('/uploads/vizsgaremek/{{$musor->image_url}}'); background-size: contain; background-repeat: no-repeat; background-position: center center; margin-left: auto; margin-right: auto">
                        </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $musor->title }}</h5>
                            <p class="card-text">
                                <strong>Értékelés:</strong> {{ number_format($musor->user_rating, 1) }} / 5
                            </p>
                            <a href="{{ route('musor.megtekint', $musor->id) }}" class="btn btn-primary">Részletek</a>
                        </div>
                    </div>
                </div>
                @if($loop->iteration >= 6)
                    @break
                @endif
            @endforeach
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4">Legújabb hozzászólások</h2>
        <div class="row">
            @foreach($latestComments as $comment)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $comment->show->title }}</h5>
                            <p class="card-text">{{ $comment->comment }}</p>
                            <div class="text-muted">
                                <small>Írta: {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('musor.megtekint', $comment->show_id) }}" class="btn btn-sm btn-primary mt-2">Műsor megtekintése</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection