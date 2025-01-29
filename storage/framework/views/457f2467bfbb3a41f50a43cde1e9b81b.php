<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <title>Dashboard</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><?php echo e($user['name']); ?></h2>
                <h3><?php echo e($user['email']); ?></h3>
            </div>
            <div>
                <a href="<?php echo e(route('vizsga.upload.form')); ?>" class="btn btn-primary me-2">Új film feltöltése</a>
                <a href="<?php echo e(route('vizsga.kijelentkezes')); ?>" class="btn btn-danger">Kijelentkezés</a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php $__currentLoopData = $shows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $show): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <div class="card h-100">
                    <img src="<?php echo e(asset('uploads/vizsga/' . $show->image_url)); ?>" class="card-img-top" alt="<?php echo e($show->title); ?>"
                        style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($show->title); ?></h5>
                        <p class="card-text"><?php echo e($show->description); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge text-bg-primary"><?php echo e($show->category); ?></span>
                            <span class="badge text-bg-secondary"><?php echo e(ucfirst($show->type)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/dashboard.blade.php ENDPATH**/ ?>