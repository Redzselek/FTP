@extends('vizsgaremek.layout.layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @isset($musor)
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
                            <span class="badge bg-primary">{{ $musor->kategoria }}</span>
                        </p>
                        @auth
                        <div class="d-flex align-items-center mb-3">
                            <span class="me-2">Értékelés:</span>
                            @php
                                $user_rating = $musor->user_rating ?? 0;
                            @endphp
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star{{ $user_rating >= $i ? '-fill' : '' }} text-warning cursor-pointer mx-1" style="cursor: pointer;" onclick="ertekeles({{ $musor->id }}, {{ $i }})"></i>
                            @endfor
                            @if($musor->ertekeles)
                                <span class="ms-2">(Átlag: {{ number_format($musor->ertekeles, 1) }})</span>
                            @endif
                        </div>
                        <script>
                            function ertekeles(id, ertek) {
                                fetch("/vizsgaremek/musorok/ertekeles/" + id, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        musor_id: id,
                                        ertekeles: ertek,
                                        edit: {{ $musor->user_rating ? 1 : 0 }}
                                    })
                                })
                                .then(response => {
                                    if (response.ok) {
                                        window.location.reload();
                                    } else {
                                        throw new Error('Network response was not ok');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Hiba történt az értékelés során');
                                });
                            }
                        </script>
                        @endauth
                        <p class="card-text">
                            <small class="text-muted">
                                Feltöltve: {{ \Carbon\Carbon::parse($musor->created_at)->format('Y.m.d H:i') }}
                            </small>
                        </p>
                    </div>
                @else
                    <div class="card-body">
                        <h5 class="card-title">Nincs megjeleníthető műsor</h5>
                    </div>
                @endisset
            </div>
            <div class="mt-3">
                <a href="{{ url('/vizsgaremek/musorok') }}" class="btn btn-secondary">Vissza a listához</a>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Hozzászólások</h5>
                </div>
                <div class="card-body">
                    @auth
                        <form action="{{ route('hozzaszolas.store', $musor->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <textarea class="form-control" name="hozzaszolas" rows="3" placeholder="Írja meg hozzászólását..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Küldés</button>
                        </form>
                    @else
                        <p class="text-center">A hozzászóláshoz kérjük, jelentkezzen be!</p>
                    @endauth

                    <div class="mt-4">
                        @foreach($hozzaszolasok as $hozzaszolas)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text">{{ $hozzaszolas->hozzaszolas }}</p>
                                    <p class="card-text">
                                        <small class="text-muted d-flex">
                                            {{ $hozzaszolas->user->name }} - 
                                            {{ \Carbon\Carbon::parse($hozzaszolas->created_at)->format('Y.m.d H:i') }}
                                            @if(Auth::id() === $hozzaszolas->user_id)
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary" onclick="editComment({{ $hozzaszolas->id }})">Szerkesztés</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteComment({{ $hozzaszolas->id }})">Törlés</button>
                                            </div>
                                            @endif
                                        </small>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editComment(commentId) {
    const newContent = prompt('Módosítsa a kommentet:');
    if (newContent) {
        fetch(`/vizsgaremek/musorok/hozzaszolas/szerkesztes/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                hozzaszolas: newContent  // Changed from 'tartalom'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment szerkesztése közben.');
        });
    }
}

function deleteComment(commentId) {
    if (confirm('Biztosan törölni szeretné ezt a kommentet?')) {
        fetch(`/vizsgaremek/musorok/hozzaszolas/torles/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment törlése közben.');
        });
    }
}
</script>
@endsection