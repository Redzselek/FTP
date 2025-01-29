<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container position-absolute top-50 start-50 translate-middle">
        <h1 class="text-center">Bejelentkezés</h1>
    
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    
    
        <div>
            <form action="<?php echo e(route('vizsga.bejelentkezes')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cím</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength=255>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Jelszó</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password">
                        <button class="btn btn-outline-secondary" type="button" id="password-visibility-toggle">
                            <i class="bi bi-eye-slash" id="password-visibility-icon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Belépés</button>
            </form>
            
            <a href="<?php echo e(route('vizsga.regisztracio')); ?>">Regisztráció</a>
        </div>
    </div>
</body>

</html>



<script>
    const passwordInput = document.getElementById('password');
    const passwordVisibilityToggle = document.getElementById('password-visibility-toggle');
    const passwordVisibilityIcon = document.getElementById('password-visibility-icon');

    passwordVisibilityToggle.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordVisibilityIcon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passwordInput.type = 'password';
            passwordVisibilityIcon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
</script>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/login.blade.php ENDPATH**/ ?>