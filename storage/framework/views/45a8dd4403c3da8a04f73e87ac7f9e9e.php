<?php $__env->startSection('title', 'Profilom'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h2>Profilom</h2>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h4>Profil adatok</h4>
                    <p><strong>Név:</strong> <?php echo e($user->name); ?></p>
                    <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                </div>

                <div class="mb-4">
                    <h4>Jelszó módosítása</h4>
                    <form action="<?php echo e(route('vizsga.password')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Jelenlegi jelszó</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Új jelszó</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Új jelszó megerősítése</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Jelszó módosítása</button>
                    </form>
                </div>

                <div class="mt-5">
                    <h4 class="text-danger">Fiók törlése</h4>
                    <p>A fiók törlése végleges művelet. Minden adatod elvész.</p>
                    <form action="<?php echo e(route('vizsga.profile.delete')); ?>" method="POST" onsubmit="return confirm('Biztosan törölni szeretnéd a fiókodat? Ez a művelet nem visszavonható!');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger">Fiók törlése</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/profile.blade.php ENDPATH**/ ?>