@extends('vizsgaremek.layout.layout')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: center;">
        <form action="{{ route('feltoltes') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate style="width: 50%">
            @csrf
            <h1>Tölts fel műsort</h1>
            <div class="mb-3">
                <label for="title" class="form-label">Cím</label>
                <input type="text" class="form-control" id="title" name="title" maxlength="200" required>
                <div class="invalid-feedback">
                    Kérlek add meg a címét!
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Leírása</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategória</label>
                <input type="text" class="form-control" id="category" name="category" maxlength="20" required>
                <div class="invalid-feedback">
                    Kérlek add meg a kategóriát!
                </div>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Típus</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Válassz...</option>
                    <option value="film">Film</option>
                    <option value="sorozat">Sorozat</option>
                </select>
                <div class="invalid-feedback">
                    Kérlek válassz egy típust!
                </div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Add meg a műsor borítóképét</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="invalid-feedback">
                    Kérlek add meg a borítóképét!
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Feltöltés</button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feltöltés eredménye</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezár</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    });
</script>
@endif

@if(session('error'))
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hiba</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('error') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezár</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('errorModal'));
        modal.show();
    });
</script>
@endif
@endsection