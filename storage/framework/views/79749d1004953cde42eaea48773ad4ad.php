<?php $__env->startSection('title', 'Sorozatok'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1 class="mb-4">Sorozatok</h1>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php $__currentLoopData = $shows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $show): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col">
            <div class="card h-100">
                <img src="<?php echo e(asset('uploads/vizsga/' . $show->image_url)); ?>" class="card-img-top" alt="<?php echo e($show->title); ?>"
                    style="height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($show->title); ?></h5>
                    <p class="card-text"><?php echo e($show->description); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge text-bg-primary"><?php echo e($show->category); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('vizsga.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/vizsga/series.blade.php ENDPATH**/ ?>