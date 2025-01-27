<!doctype html>
<html lang="hu" data-bs-theme="dark">
<head>
    <title>Mikulás csomag</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?php echo e(asset('css/mikulas/mikulas.css')); ?>">
</head>

<body>
    <div class="container">
        <form action="<?php echo e(route('mikulasLead')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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
                    <?php $__currentLoopData = $edessegek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edesseg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($edesseg->nev); ?></td>
                            <td><?php echo e($edesseg->ar); ?></td>
                            
                            <td class="text-center">
                                <label class="container2">
                                <input type="checkbox" name="edessegId[]" value="<?php echo e($edesseg->id); ?>">
                                <svg viewBox="0 0 64 64" height="2em" width="2em"><path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path></svg>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <input id="osszesen" name="ar">Összesen: 0ft</input>
        </div>
        <div>
            
                <div class="form">
                    <input class="input" name="Gyerek" placeholder="Név" required="" type="text">
                    <span class="input-border"></span>
                </div>
                <button type="submit" class="btn btn-success">Küldés</button>
            
        </div>
    </form> 
    </div>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>
<script>
    const sumPrice = document.getElementById('osszesen');
    const prices = document.querySelectorAll('tbody tr td:nth-child(2)');
    const checkboxes = document.querySelectorAll('tbody tr td:nth-child(3) input');
    let sum = 0;
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('click', () => {
            sum = 0;
            prices.forEach((price, index) => {
                if (checkboxes[index].checked) {
                    sum += Number(price.textContent);
                }
            });
            sumPrice.textContent = 'Összesen: ' + sum + ' Ft';
            sumPrice.value = sum;
        });
    });
</script>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/mikulas/mikulas.blade.php ENDPATH**/ ?>