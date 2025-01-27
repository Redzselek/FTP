<!doctype html>
<html lang="hu" data-bs-theme="dark">
    <head>
        <title>Webshop</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body>
                <header>
            <nav class="nav justify-content-center">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active fs-3" href="<?php echo e(route('webshop')); ?>">Webshop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-3" href="<?php echo e(route('webshopfeltoltes')); ?>">Termék feltöltés</a>
                    </li>
                </ul>
        </header>   
        <main>
            <div class="container">
                <div class="row">
                    <?php $__currentLoopData = $termekek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-3">
                        <div class="card">
                            <div style="height: 200px; width: 200px; background: url('/uploads/webshop/<?php echo e($termek->kep_url); ?>'); background-size: cover; background-position: center center; margin-left: auto; margin-right: auto">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($termek->nev); ?></h5>
                                <p class="card-text"><?php echo e($termek->leiras); ?></p>
                                <p class="card-text"><?php echo e($termek->ar); ?></p>
                                <p class="card-text"><?php echo e($termek->akcios_ar); ?></p>
                                <a href="/webshop/reszletek/<?php echo e($termek->id); ?>" class="btn btn-primary">Részletek</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </main>

    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/webshop/webshop.blade.php ENDPATH**/ ?>