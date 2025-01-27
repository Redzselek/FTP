<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fájl feltöltés a galériába</title>
    <link rel="stylesheet" href="https://unpkg.com/simpledotcss/simple.min.css">
</head>
<body>
    <h1>Fájl feltöltés</h1>
    <p><a href="<?php echo e(route('galeria')); ?>">Galéria</a></p>
    <form action="<?php echo e(route('feltoltes')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <label>Kép címe</label>
        <input name="cim" required type="text" maxlength="50">
        <br>
        <label>Kép leírás</label>
        <input name="leiras" required type="text" maxlength="50">
        <br>
        <input name="file" required type="file" accept="image/jpeg, image/png">
        <br>
        <button type="submit" value="feltoltes">Feltöltés</button>
    </form>

    <?php if(session('success')): ?>
        <div style="color: green"><?php echo e(session('success')); ?></div>
    <?php elseif(session('error')): ?>
        <div style="color: red" ><?php echo e(session('error')); ?></div>
    <?php endif; ?>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/galeria/fajlfeltoltes.blade.php ENDPATH**/ ?>