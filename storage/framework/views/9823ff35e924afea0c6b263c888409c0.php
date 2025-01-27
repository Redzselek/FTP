<!doctype html>
<html lang="hu" data-bs-theme="dark">
    <head>
        <title>Webshop feltöltés</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    </head>

    <body>
        <header>
            <nav class="nav justify-content-center">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link fs-3" href="<?php echo e(route('webshop')); ?>">Webshop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fs-3" href="<?php echo e(route('webshopfeltoltes')); ?>">Termék feltöltés</a>
                    </li>
                </ul>
            </nav>
        </header>
        <main>
            <div class="container">
                <div class="row justify-content-center align-items-center g-2">
                    <div class="col-md-8">
                        <form action="<?php echo e(route('feltoltes')); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label fs-2">Termék címe</label>
                                <input type="text" name="nev" class="form-control" id="nev" required maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-2">Termék ára</label>
                                <input type="text" name="ar" class="form-control" id="ar" required maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-2">Termék akció ára</label>
                                <input type="text" name="akcios_ar" class="form-control" id="akcios_ar" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-2">Termék leírás</label>
                                <input type="text" name="leiras" class="form-control" id="leiras" required maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fs-2">Kép feltöltés</label>
                                <input type="file" name="file" class="form-control fs-5" id="file" required accept="image/jpeg, image/png">
                            </div>
                            <button type="submit" value="feltoltes" class="btn btn-primary fs-2">Feltöltés</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
                
            </div>
        </main>
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous" ></script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/webshop/webshopfeltolt.blade.php ENDPATH**/ ?>