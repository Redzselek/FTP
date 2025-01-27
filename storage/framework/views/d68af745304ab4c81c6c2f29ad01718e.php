<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galéria</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            background-color: #2e2e2e;
            color: white;
        }
        a {
            color: #00c3ff;
        }
    </style>
</head>
<body>
    <h1>Galéria</h1>
    <p><a href="https://egyedirobi.moriczcloud.hu/fajlfeltoltes">Új feltöltés</a></p>
    <?php $__currentLoopData = $kepek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:inline-block !important">
            <a href="uploads/<?php echo e($kep->kep_url); ?>" target="_blank">
                <img src="uploads/<?php echo e($kep->kep_url); ?>" style="height:150px" alt="<?php echo e($kep->kep_leiras); ?>">
            </a>
                <h3><?php echo e($kep->kep_cim); ?></h3>
                <h5><?php echo e($kep->kep_leiras); ?></h5>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/galeria/galeria.blade.php ENDPATH**/ ?>