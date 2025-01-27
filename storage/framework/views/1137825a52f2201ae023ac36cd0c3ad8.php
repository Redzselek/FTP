<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if(isset($allat)): ?>
        <title><?php echo e($allat); ?> és <?php echo e($szamitas); ?></title>        
    <?php endif; ?>
    <?php if(isset($szin)): ?>
        <title><?php echo e($szin); ?></title>        
    <?php endif; ?>

</head>
<body>
    <?php if(isset($allat)): ?>
    <h1>Állat: <?php echo e($allat); ?></h1>
    <h1>Számítás: <?php echo e($szamitas); ?></h1>
    <?php endif; ?>
    <?php if(isset($szin)): ?>
        <h1>A megadott szín <?php echo e($szin); ?></h1>
    <?php endif; ?>

    <?php if(isset($kontroller)): ?>
        <?php if($tipus == "vezetekes"): ?>
            <img src="https://s13emagst.akamaized.net/products/40736/40735883/images/res_d6d0e620f9770f30e2bb11809f335576.jpg" alt="">
        <?php endif; ?>
    <?php endif; ?>

    <?php if(isset($tipus == "vezeteknelkul")): ?>
        <?php if($tipus == "vezeteknelkul"): ?>
            <img src="https://images.euronics.hu/product_images/800x600/resize/2_pxwufau2.png?v=2" alt="">
        <?php endif; ?>
    <?php endif; ?>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/xboxteszt.blade.php ENDPATH**/ ?>