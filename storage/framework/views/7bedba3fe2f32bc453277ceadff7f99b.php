<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-4">Feltöltő Műsorok</h2>
    
    <div class="row">
        <?php $__currentLoopData = $musorok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $musor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100" onclick="window.location.href='musorok/tovabbinezet/<?php echo e($musor->id); ?>'">
                <?php if($musor->kep_url): ?>
                    <div style="height: 350px; width: 350px; background: url('/uploads/teliprojekt/<?php echo e($musor->kep_url); ?>'); background-size: contain; background-repeat: no-repeat; background-position: center center; margin-left: auto; margin-right: auto">
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($musor->cim); ?></h5>
                    <p class="card-text">
                        <small class="text-muted">Feltöltő: <?php echo e($musor->feltolto_neve); ?></small>
                    </p>
                    <p class="card-text"><?php echo e($musor->leiras); ?></p>
                    <p class="card-text">
                        <span class="badge bg-primary"><?php echo e($musor->kategoria); ?></span>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            Feltöltve: <?php echo e(\Carbon\Carbon::parse($musor->created_at)->format('Y.m.d H:i')); ?>

                        </small>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if(count($musorok) == 0): ?>
    <div class="alert alert-info" role="alert">
        Még nincsenek feltöltött műsorok.
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('teliprojekt.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/teliprojekt/musorok.blade.php ENDPATH**/ ?>