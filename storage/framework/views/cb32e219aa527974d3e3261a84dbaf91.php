<!doctype html>
<html lang="hu" data-bs-theme="dark">

<head>
    <title>Foglalas</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <div class="col-md-8" style="margin-top: 5%">
            <table class="table table-striped">
                <tr>
                    <th>Hétfő 
                        <?php if($foglalasok[0]['nap'] == 'Hétfő'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Kedd
                        <?php if($foglalasok[0]['nap'] == 'Kedd'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Szerda
                        <?php if($foglalasok[0]['nap'] == 'Szerda'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Csütörtök
                        <?php if($foglalasok[0]['nap'] == 'Csütörtök'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Péntek
                        <?php if($foglalasok[0]['nap'] == 'Péntek'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Szombat
                        <?php if($foglalasok[0]['nap'] == 'Szombat'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                    <th>Vasárnap
                        <?php if($foglalasok[0]['nap'] == 'Vasárnap'): ?>
                            <br>
                            <?php echo e($foglalasok[0]['nev']); ?>

                        <?php endif; ?>
                    </th>
                </tr>
                <tr>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                    <td><button class="btn btn-primary">Foglal</button></td>
                </tr>
            </table>
            <input type="text" class="form-control" placeholder="Név"/>
        </div>
    </div>
    
    
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/foglalas.blade.php ENDPATH**/ ?>