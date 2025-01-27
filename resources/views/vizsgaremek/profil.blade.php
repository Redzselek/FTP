@extends('vizsgaremek.layout.layout')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Profil adatok</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Név</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="name-input" value="{{ $user['name'] }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="name-btn">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email cím</label>
                        <p class="form-control">{{ $user['email'] }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regisztráció dátuma</label>
                        <p class="form-control">{{ $user['date'] }}</p>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('valtoztatas') }}" class="btn btn-primary">
                            Jelszó módosítása
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="toggleMusorok()">
                    <h3 class="mb-0">Feltöltött műsoraim</h3>
                    <i class="bi bi-chevron-down" id="toggleIcon"></i>
                </div>
                <div class="card-body" id="musorokContainer" style="display: none;">
                    @if(count($musorok) > 0)
                        <form id="deleteForm">
                            @csrf
                            <div class="list-group">
                                @foreach($musorok as $musor)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" name="musor_ids[]" value="{{ $musor->id }}" class="form-check-input me-3 musor-checkbox">
                                            <div>
                                                <h5 class="mb-1">{{ $musor->cim }}</h5>
                                                <small>Feltöltve: {{ date('Y.m.d', strtotime($musor->created_at)) }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-primary me-2" onclick="editMusor({{ $musor->id }}, '{{ $musor->cim }}', '{{ $musor->leiras }}', '{{ $musor->kategoria }}', '{{ $musor->kep_url }}')">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="deleteButtonContainer" class="mt-3" style="display: none;">
                                <button type="button" class="btn btn-danger" onclick="deleteSelected()">
                                    <i class="bi bi-trash-fill me-2"></i>Kijelölt műsorok törlése
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-center mb-0">Még nem töltöttél fel műsort.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Műsor szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_cim" class="form-label">Cím</label>
                        <input type="text" class="form-control" id="edit_cim" name="cim" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="edit_leiras" class="form-label">Leírás</label>
                        <textarea class="form-control" id="edit_leiras" name="leiras"required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kategoria" class="form-label">Kategória</label>
                        <select class="form-select" id="edit_kategoria" name="kategoria" required>
                            <option value="film">Film</option>
                            <option value="sorozat">Sorozat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_file" class="form-label">Kép</label>
                        <input type="file" class="form-control" id="edit_file" name="file" accept="image/jpeg,image/png,image/webp">
                        <div class="mt-2">
                            <img id="current_image" src="" alt="Jelenlegi kép" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts') {{-- Ez bele push-olja a @stack(scripts)-be hogy működjön a js --}}
<script>
    const nameInput = document.getElementById('name-input');
    const nameBtn = document.getElementById('name-btn');
    const nameError = document.getElementById('name-error');
    let isEditing = false;
    let originalName = nameInput.value;
    let nameTooltip = null;

    // Név input változás figyelése
    nameInput.addEventListener('input', function() {
        if (isEditing) {
            const hasChanged = nameInput.value.trim() !== originalName;
            nameBtn.classList.toggle('btn-outline-secondary', !hasChanged);
            nameBtn.classList.toggle('btn-success', hasChanged);
            nameBtn.innerHTML = hasChanged ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-pencil-fill"></i>';
            clearErrors();
        }
    });

    // Input focus elvesztése
    nameInput.addEventListener('blur', function() {
        if (!isEditing) {
            clearErrors();
        }
    });

    // Gomb click esemény
    nameBtn.addEventListener('click', async function() {
        if (!isEditing) {
            // Szerkesztés mód bekapcsolása
            isEditing = true;
            nameInput.readOnly = false;
            nameInput.focus();
            return;
        }

        // Ha szerkesztés módban vagyunk és a név változott
        const newName = nameInput.value.trim();
        if (newName === originalName) {
            exitEditMode();
            return;
        }

        try {
            const response = await fetch('{{ route('nev.valtoztatas') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: newName })
            });

            const data = await response.json();

            if (data.success) {
                originalName = newName;
                clearErrors();
                // Frissítjük a layout-ban lévő nevet is
                const profileBtn = document.querySelector('[data-bs-title]');
                if (profileBtn) {
                    profileBtn.setAttribute('data-bs-title', newName);
                }
                exitEditMode();
            } else {
                showError(data.error);
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Hiba történt a név mentése közben');
        }
    });

    // Enter gomb kezelése
    nameInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && isEditing) {
            nameBtn.click();
        }
    });

    // Escape gomb kezelése
    nameInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isEditing) {
            nameInput.value = originalName;
            exitEditMode();
            clearErrors();
        }
    });

    // Hiba megjelenítése
    function showError(message) {
        nameInput.classList.add('is-invalid');
        nameError.textContent = message;
        
        // Tooltip létrehozása vagy frissítése
        if (nameTooltip) {
            nameTooltip.dispose();
        }
        nameTooltip = new bootstrap.Tooltip(nameInput, {
            title: message,
            placement: 'top',
            trigger: 'manual'
        });
        nameTooltip.show();
    }

    // Hibaüzenetek törlése
    function clearErrors() {
        nameInput.classList.remove('is-invalid');
        nameError.textContent = '';
        if (nameTooltip) {
            nameTooltip.hide();
            nameTooltip.dispose();
            nameTooltip = null;
        }
    }

    // Szerkesztés mód kikapcsolása
    function exitEditMode() {
        isEditing = false;
        nameInput.readOnly = true;
        nameBtn.classList.remove('btn-success');
        nameBtn.classList.add('btn-outline-secondary');
        nameBtn.innerHTML = '<i class="bi bi-pencil-fill"></i>';
    }

    // Add this new JavaScript code
    function toggleMusorok() {
        const container = document.getElementById('musorokContainer');
        const icon = document.getElementById('toggleIcon');
        if (container.style.display === 'none') {
            container.style.display = 'block';
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        } else {
            container.style.display = 'none';
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        }
    }

    // Show/hide delete button based on checkbox selection
    document.querySelectorAll('.musor-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const deleteButton = document.getElementById('deleteButtonContainer');
            const anyChecked = document.querySelector('.musor-checkbox:checked');
            deleteButton.style.display = anyChecked ? 'block' : 'none';
        });
    });

    // Handle deletion of selected programs
    function deleteSelected() {
        if (!confirm('Biztosan törölni szeretnéd a kijelölt műsorokat?')) {
            return;
        }

        const form = document.getElementById('deleteForm');
        const formData = new FormData(form);
        const selectedIds = Array.from(formData.getAll('musor_ids[]'));

        fetch('{{ route("musor.torles") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                musor_ids: selectedIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a törlés során.');
        });
    }

    function editMusor(id, title, description, category, imageUrl) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_cim').value = title;
        document.getElementById('edit_leiras').value = description;
        document.getElementById('edit_kategoria').value = category;
        document.getElementById('current_image').src = '/uploads/vizsgaremek/' + imageUrl;
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    // Handle form submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        fetch('{{ route("musor.szerkesztes") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hiba történt a mentés során.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a mentés során.');
        });
    });
</script>
@endpush
@endsection