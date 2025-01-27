<?php $__env->startSection('content'); ?>

    <div class="text-center">
        <h1 class="display-4"> Szia! </h1>
        <p class="lead">Ez egy mozi oldal, ahol a felhasználók töltik fel a filmeket és sorozatokat, ahol tudnak értékelni és kommentelni. Te is csatlakozz hozzánk!</p>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4">Legjobban értékelt műsorok</h2>
        <div class="row">
            <?php $__currentLoopData = $topMusorok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $musor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <?php if($musor->kep_url): ?>
                        <div style="height: 350px; width: 350px; background: url('/uploads/teliprojekt/<?php echo e($musor->kep_url); ?>'); background-size: contain; background-repeat: no-repeat; background-position: center center; margin-left: auto; margin-right: auto">
                        </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($musor->cim); ?></h5>
                            <p class="card-text">
                                <strong>Értékelés:</strong> <?php echo e(number_format($musor->user_rating, 1)); ?> / 5
                            </p>
                            <a href="<?php echo e(route('musor.megtekint', $musor->id)); ?>" class="btn btn-primary">Részletek</a>
                        </div>
                    </div>
                </div>
                <?php if($loop->iteration >= 6): ?>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="mb-4">Legújabb hozzászólások</h2>
        <div class="row">
            <?php $__currentLoopData = $latestComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($comment->musor->cim); ?></h5>
                            <p class="card-text"><?php echo e($comment->hozzaszolas); ?></p>
                            <div class="text-muted">
                                <small>Írta: <?php echo e($comment->user->name); ?> - <?php echo e($comment->created_at->diffForHumans()); ?></small>
                            </div>
                            <a href="<?php echo e(route('musor.megtekint', $comment->musor_id)); ?>" class="btn btn-sm btn-primary mt-2">Műsor megtekintése</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('teliprojekt.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/teliprojekt/fooldal.blade.php ENDPATH**/ ?>