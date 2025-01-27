<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Etelform</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <form action="/kajapost" method="post">
        <?php echo csrf_field(); ?>
        <p>Ketegória:</p>
        <select name="kategoria" id="kategoriaSelect" onchange="Valtozik()">
            <?php $__currentLoopData = $kategoriak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($k); ?>"><?php echo e($k); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value="uj">Új kategória</option>
            
        </select>

        <div id="ujkategoriabox" style="display: none">
            <p>Új kategória</p>
            <input type="text" name="ujkategoria">
        </div>

        <p>Kaja</p><input type="text" name="nev" id="kaja">
        <p>Ár</p><input type="number" name="ar" id="ar">
        <input type="submit" value="Küldés">  
    </form>
</body>
</html>

<script>
    function Valtozik(){
        document.getElementById("ujkategoriabox").style.display = document.getElementById("kategoriaSelect").value=="uj"?"block":"none";
        
    }
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/etelform.blade.php ENDPATH**/ ?>