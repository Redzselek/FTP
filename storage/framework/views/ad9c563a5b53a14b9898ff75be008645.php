<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('vizsga.dashboard')); ?>">FilmPlatform</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('vizsga.dashboard')); ?>">Kezdőlap</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('vizsga.movies')); ?>">Filmek</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('vizsga.series')); ?>">Sorozatok</a>
                </li>
            </ul>
            
            <?php if(auth()->guard()->check()): ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo e(route('vizsga.profile')); ?>">Profilom</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('vizsga.feltoltesek')); ?>">Feltöltéseim</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('vizsga.watchlist')); ?>">Könyvjelzők</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo e(route('vizsga.kijelentkezes')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">Kijelentkezés</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('vizsga.bejelentkez')); ?>">Bejelentkezés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('vizsga.regisztracio')); ?>">Regisztráció</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/layouts/menu.blade.php ENDPATH**/ ?>