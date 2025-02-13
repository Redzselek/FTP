<?php $__env->startSection('title', 'Elfelejtett jelszó'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Elfelejtett jelszó</div>
                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('vizsga.password.email')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email cím</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Jelszó visszaállítása</button>
                    </form>
                    <div class="mt-3">
                        <p>Emlékszel a jelszavadra? <a href="<?php echo e(route('vizsga.bejelentkez')); ?>">Jelentkezz be!</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/forgot-password.blade.php ENDPATH**/ ?>