@extends('vizsgaremek.layout.layout')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: center;">
        <form action="{{ route('feltoltes') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate style="width: 50%">
            @csrf
            <h1>Tölts fel műsort</h1>
            <div class="mb-3">
                <label for="cim" class="form-label">Cím</label>
                <input type="text" class="form-control" id="cim" name="cim" maxlength=50 required>
                <div class="invalid-feedback">
                    Kérlek add meg a címét!
                </div>
            </div>
            <div class="mb-3">
                <label for="leiras" class="form-label">Leírása</label>
                <input type="text" class="form-control" id="leiras" name="leiras" maxlength=500>
            </div>
            <div class="mb-3">
                <label for="kategoria" class="form-label">Kategória</label>
                <select class="form-select" id="kategoria" name="kategoria" required>
                    <option value="">Válassz...</option>
                    <option value="film">Film</option>
                    <option value="sorozat">Sorozat</option>
                </select>
                <div class="invalid-feedback">
                    Kérlek válassz egy kategóriát!
                </div>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Add meg a műsor borítóképét</label>
                <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
                <div class="invalid-feedback">
                    Kérlek add meg a borítóképét!
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Feltöltés</button>
        </form>
    </div>
</div>
    {{-- Az action részben annak a route-nak a name tulajdonsága van, amit meg akarunk hívni, akinek az adatot küldjük.

    A form alatt/fölött legyen egy ilyen rész, ez fogadja a visszatérő adatot (siker/hiba): --}}
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Feltöltés eredménye</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                    <div style="color: green">
                        {{ session('success') }}
                    </div>
                    @elseif (session('error'))
                    <div style="color: red">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                </div>
            </div>
        </div>
    </div>

@endsection