<!doctype html>
<html lang="hu" data-bs-theme="dark">
<head>
    <title>Mikulás csomag</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="table-responsive">
            <table class="table w-auto table-xxl table-dark table-striped">
                <thead>
                    <tr>
                        <th scope="col">Név</th>
                        <th scope="col">Ár</th>
                        <th scope="col">Belekerül</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $mikulasok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mikulas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($mikulas->nev); ?></td>
                            <td><?php echo e($mikulas->ar); ?></td>
                            <td><?php echo e($mikulas->tartalom); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
    </div>


</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>

<?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/mikulas/mikulasLista.blade.php ENDPATH**/ ?>