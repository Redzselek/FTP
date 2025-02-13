<?php $__env->startSection('title', 'Bejelentkezés'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Bejelentkezés</div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('vizsga.bejelentkezes')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email cím</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Jelszó</label>
                            <div class="input-group password-input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <a href="<?php echo e(route('vizsga.password.request')); ?>">Elfelejtett jelszó?</a>
                        </div>
                        <button type="submit" class="btn btn-primary">Bejelentkezés</button>
                    </form>
                    <div class="mt-3">
                        <p>Még nincs fiókod? <a href="<?php echo e(route('vizsga.regisztracio')); ?>">Regisztrálj most!</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/vizsga/password-toggle.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializePasswordToggles();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/login.blade.php ENDPATH**/ ?>