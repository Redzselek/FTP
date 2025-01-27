<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <?php if(isset($musor)): ?>
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
                        <?php if(auth()->guard()->check()): ?>
                        <div class="d-flex align-items-center mb-3">
                            <span class="me-2">Értékelés:</span>
                            <?php
                                $user_rating = $musor->user_rating ?? 0;
                            ?>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi bi-star<?php echo e($user_rating >= $i ? '-fill' : ''); ?> text-warning cursor-pointer mx-1" 
                                   style="cursor: pointer;"
                                   onclick="ertekeles(<?php echo e($musor->id); ?>, <?php echo e($i); ?>)"></i>
                            <?php endfor; ?>
                            <?php if($musor->ertekeles): ?>
                                <span class="ms-2">(Átlag: <?php echo e(number_format($musor->ertekeles, 1)); ?>)</span>
                            <?php endif; ?>
                        </div>
                        <script>
                            function ertekeles(id, ertek) {
                                fetch("/teliprojekt/musorok/ertekeles/" + id, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        musor_id: id,
                                        ertekeles: ertek,
                                        edit: <?php echo e($musor->user_rating ? 1 : 0); ?>

                                    })
                                })
                                .then(response => {
                                    if (response.ok) {
                                        window.location.reload();
                                    } else {
                                        throw new Error('Network response was not ok');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Hiba történt az értékelés során');
                                });
                            }
                        </script>
                        <?php endif; ?>
                        <p class="card-text">
                            <small class="text-muted">
                                Feltöltve: <?php echo e(\Carbon\Carbon::parse($musor->created_at)->format('Y.m.d H:i')); ?>

                            </small>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="card-body">
                        <h5 class="card-title">Nincs megjeleníthető műsor</h5>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mt-3">
                <a href="<?php echo e(url('/teliprojekt/musorok')); ?>" class="btn btn-secondary">Vissza a listához</a>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Hozzászólások</h5>
                </div>
                <div class="card-body">
                    <?php if(auth()->guard()->check()): ?>
                        <form action="<?php echo e(route('hozzaszolas.store', $musor->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group mb-3">
                                <textarea class="form-control" name="hozzaszolas" rows="3" placeholder="Írja meg hozzászólását..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Küldés</button>
                        </form>
                    <?php else: ?>
                        <p class="text-center">A hozzászóláshoz kérjük, jelentkezzen be!</p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <?php $__currentLoopData = $hozzaszolasok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hozzaszolas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text"><?php echo e($hozzaszolas->hozzaszolas); ?></p>
                                    <p class="card-text">
                                        <small class="text-muted d-flex">
                                            <?php echo e($hozzaszolas->user->name); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($hozzaszolas->created_at)->format('Y.m.d H:i')); ?>

                                            <?php if(Auth::id() === $hozzaszolas->user_id): ?>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary" onclick="editComment(<?php echo e($hozzaszolas->id); ?>)">Szerkesztés</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteComment(<?php echo e($hozzaszolas->id); ?>)">Törlés</button>
                                            </div>
                                            <?php endif; ?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editComment(commentId) {
    const newContent = prompt('Módosítsa a kommentet:');
    if (newContent) {
        fetch(`/teliprojekt/musorok/hozzaszolas/szerkesztes/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                hozzaszolas: newContent  // Changed from 'tartalom'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment szerkesztése közben.');
        });
    }
}

function deleteComment(commentId) {
    if (confirm('Biztosan törölni szeretné ezt a kommentet?')) {
        fetch(`/teliprojekt/musorok/hozzaszolas/torles/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hiba történt a komment törlése közben.');
        });
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('teliprojekt.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/teliprojekt/tovabbinezet.blade.php ENDPATH**/ ?>