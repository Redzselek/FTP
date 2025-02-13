<?php $__env->startSection('title', 'Feltöltéseim'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Feltöltéseim</h2>
            <button class="btn btn-danger" id="deleteSelected" style="display: none;">
                Kijelöltek törlése
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Borítókép</th>
                            <th>Cím</th>
                            <th>Kategória</th>
                            <th>Típus</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $shows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $show): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-id="<?php echo e($show->id); ?>">
                            <td>
                                <input type="checkbox" class="form-check-input show-select" value="<?php echo e($show->id); ?>">
                            </td>
                            <td>
                                <img src="<?php echo e(asset('uploads/vizsga/' . $show->image_url)); ?>" alt="<?php echo e($show->title); ?>" 
                                     style="height: 50px; width: 50px; object-fit: cover;" class="rounded">
                            </td>
                            <td><?php echo e($show->title); ?></td>
                            <td><?php echo e($show->category); ?></td>
                            <td><?php echo e(ucfirst($show->type)); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-show" data-show="<?php echo e(json_encode($show)); ?>">
                                    Szerkesztés
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Szerkesztő Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tartalom szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Cím</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Leírás</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Kategória</label>
                        <select class="form-select" id="edit_category" name="category" required>
                            <option value="Sci-Fi">Sci-Fi</option>
                            <option value="Krimi">Krimi</option>
                            <option value="Komedia">Komédia</option>
                            <option value="Anime">Anime</option>
                            <option value="Horror">Horror</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Típus</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="film">Film</option>
                            <option value="sorozat">Sorozat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_file" class="form-label">Új borítókép (opcionális)</label>
                        <input type="file" class="form-control" id="edit_file" name="file" accept="image/jpeg,image/png,image/webp">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Mentés</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    
    // Szerkesztés gomb eseménykezelő
    document.querySelectorAll('.edit-show').forEach(button => {
        button.addEventListener('click', function() {
            const show = JSON.parse(this.dataset.show);
            document.getElementById('edit_id').value = show.id;
            document.getElementById('edit_title').value = show.title;
            document.getElementById('edit_description').value = show.description;
            document.getElementById('edit_category').value = show.category;
            document.getElementById('edit_type').value = show.type;
            editModal.show();
        });
    });

    // Mentés gomb eseménykezelő
    document.getElementById('saveChanges').addEventListener('click', function() {
        const form = document.getElementById('editForm');
        const formData = new FormData(form);

        fetch('/vizsga/show/edit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hiba történt a mentés során!');
            }
        });
    });

    // Checkbox kezelés
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.show-select');
    const deleteSelected = document.getElementById('deleteSelected');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButton();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const selectedCount = document.querySelectorAll('.show-select:checked').length;
        deleteSelected.style.display = selectedCount > 0 ? 'block' : 'none';
    }

    // Törlés gomb eseménykezelő
    deleteSelected.addEventListener('click', function() {
        if (!confirm('Biztosan törölni szeretnéd a kijelölt elemeket?')) {
            return;
        }

        const selectedIds = Array.from(document.querySelectorAll('.show-select:checked'))
                                .map(checkbox => checkbox.value);

        fetch('/vizsga/show/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Hiba történt a törlés során!');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/feltoltesek.blade.php ENDPATH**/ ?>