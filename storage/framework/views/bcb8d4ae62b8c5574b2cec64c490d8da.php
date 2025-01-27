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
        <p>Kaja</p><input type="text" name="nev" id="kaja">
        <p>Ár</p><input type="number" name="ar" id="ar">
        <input type="submit" value="Küldés">  
    </form>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/etelek.blade.php ENDPATH**/ ?>