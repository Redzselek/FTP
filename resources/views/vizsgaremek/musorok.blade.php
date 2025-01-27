@extends('vizsgaremek.layout.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Feltöltő Műsorok</h2>
    
    <div class="row">
        @foreach($musorok as $musor)
        <div class="col-md-4 mb-4">
            <div class="card h-100" onclick="window.location.href='musorok/tovabbinezet/{{ $musor->id }}'">
                @if($musor->kep_url)
                    <div style="height: 350px; width: 350px; background: url('/uploads/vizsgaremek/{{$musor->kep_url}}'); background-size: contain; background-repeat: no-repeat; background-position: center center; margin-left: auto; margin-right: auto">
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $musor->cim }}</h5>
                    <p class="card-text">
                        <small class="text-muted">Feltöltő: {{ $musor->feltolto_neve }}</small>
                    </p>
                    <p class="card-text">{{ $musor->leiras }}</p>
                    <p class="card-text">
                        <span class="badge bg-primary">{{ $musor->category }}</span>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            Feltöltve: {{ \Carbon\Carbon::parse($musor->created_at)->format('Y.m.d H:i') }}
                        </small>
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(count($musorok) == 0)
    <div class="alert alert-info" role="alert">
        Még nincsenek feltöltött műsorok.
    </div>
    @endif
</div>
@endsection