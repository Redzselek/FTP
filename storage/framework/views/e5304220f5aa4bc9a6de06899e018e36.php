<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($title); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if(request()->has('dark')): ?>
        <link rel="stylesheet" href="/css/style-dark.css">
    <?php endif; ?>
</head>
<body>
    <div id="blog-nav">
        <p><a href='/blog/<?php echo e($faj); ?>'>Világos </a><p>| <a href='/blog/<?php echo e($faj); ?>/dark'>Sötét</a></p></p>
        <p><a href='/blog/kutya'>Kutya </a><p>| <a href='/blog/macska'>Macskja</a></p></p>
    </div>
    <h1 id="blog-title"><?php echo e($title); ?></h1>
    <h2 id="blog-author"><?php echo e($author); ?></h2><h2 id="blog-date"><?php echo e($date); ?></h2>
    <p id="blog-content"><?php echo e($content); ?></p>
    <img src="<?php echo e($kep); ?>" id="blog-kep">

</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/blog.blade.php ENDPATH**/ ?>