

<?php $__env->startSection('title', 'Tartalom feltöltése'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center mb-0">Új tartalom feltöltése</h2>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('vizsga.uploadManager')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="title" class="form-label">Cím</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Leírás</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategória</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Válassz kategóriát...</option>
                                    <option value="Sci-Fi">Sci-Fi</option>
                                    <option value="Krimi">Krimi</option>
                                    <option value="Komedia">Komédia</option>
                                    <option value="Anime">Anime</option>
                                    <option value="Horror">Horror</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Típus</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Válassz típust...</option>
                                    <option value="film">Film</option>
                                    <option value="sorozat">Sorozat</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="file" class="form-label">Borítókép</label>
                            <input type="file" class="form-control" id="file" name="file" accept="image/jpeg,image/png,image/webp" required>
                            <div class="form-text">Támogatott formátumok: JPG, JPEG, PNG, WEBP (max. 15MB)</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Feltöltés</button>
                            <a href="<?php echo e(route('vizsga.dashboard')); ?>" class="btn btn-secondary">Vissza a főoldalra</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/upload.blade.php ENDPATH**/ ?>