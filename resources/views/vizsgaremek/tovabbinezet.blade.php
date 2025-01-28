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
                                        ertekeles: ertek
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
                            <button type="submit" class="btn btn-primary">Hozzászólás küldése</button>
                        </form>
                    @else
                        <p>A hozzászóláshoz be kell jelentkezni.</p>
                    @endauth

                    <div class="mt-4">
                        @foreach($hozzaszolasok as $comment)
                            <div class="card mb-3" id="comment-{{ $comment->id }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                            <p class="comment-text mb-1">{{ $comment->hozzaszolas }}</p>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->format('Y.m.d H:i') }}</small>
                                        </div>
                                        @auth
                                            @if(Auth::id() === $comment->user_id)
                                                <div class="dropdown">
                                                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                                                Szerkesztés
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                                                Törlés
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
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
    const commentCard = document.querySelector(`#comment-${commentId}`);
    const commentText = commentCard.querySelector('.comment-text').textContent;
    
    const textarea = document.createElement('textarea');
    textarea.className = 'form-control mb-2';
    textarea.value = commentText;
    
    const saveButton = document.createElement('button');
    saveButton.className = 'btn btn-primary btn-sm me-2';
    saveButton.textContent = 'Mentés';
    
    const cancelButton = document.createElement('button');
    cancelButton.className = 'btn btn-secondary btn-sm';
    cancelButton.textContent = 'Mégse';
    
    const buttonContainer = document.createElement('div');
    buttonContainer.appendChild(saveButton);
    buttonContainer.appendChild(cancelButton);
    
    const originalContent = commentCard.querySelector('.card-body').innerHTML;
    commentCard.querySelector('.card-body').innerHTML = '';
    commentCard.querySelector('.card-body').appendChild(textarea);
    commentCard.querySelector('.card-body').appendChild(buttonContainer);
    
    saveButton.onclick = function() {
        fetch(`/vizsgaremek/hozzaszolas/${commentId}/szerkesztes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                hozzaszolas: textarea.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment szerkesztése során');
        });
    };
    
    cancelButton.onclick = function() {
        commentCard.querySelector('.card-body').innerHTML = originalContent;
    };
}

function deleteComment(commentId) {
    if(confirm('Biztosan törölni szeretné ezt a hozzászólást?')) {
        fetch(`/vizsgaremek/hozzaszolas/${commentId}/torles`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.message) {
                document.querySelector(`#comment-${commentId}`).remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment törlése során');
        });
    }
}
</script>
@endsection