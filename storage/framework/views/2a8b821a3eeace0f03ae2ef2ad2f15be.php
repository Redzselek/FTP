<!doctype html>
<html lang="hu" data-bs-theme="dark">

<head>
    <title>Lakás</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
    
    .border{
        border: 2px solid #037e0d93 !important;
        box-shadow: #037e0d93 0px 0px 0px 1px inset;
        margin-bottom: 10px;
    }

    .border.lefoglalva {
        border: 2px solid #ff2020 !important;
        box-shadow: #f7ff8993 0px 20px 20px 4px inset;
        margin-bottom: 10px;
    }

    </style>
</head>

<body>
    <main>
        <div class="container text-white">
            <div class="row">
                <div class="col-8">
                    <div class="row p-3 justify-content-start text-center fs-4">
                        <div class="col-3">
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[18]['emelet'] == 5 && $lakasok[18]['szoba'] == 1 && $lakasok[18]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="51"> 5-1</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[14]['emelet'] == 4 && $lakasok[14]['szoba'] == 1 && $lakasok[14]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="41"> 4-1</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[10]['emelet'] == 3 && $lakasok[10]['szoba'] == 1 && $lakasok[10]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="31"> 3-1</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[6]['emelet'] == 2 && $lakasok[6]['szoba'] == 1 && $lakasok[6]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="21"> 2-1</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[2]['emelet'] == 1 && $lakasok[2]['szoba'] == 1 && $lakasok[2]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="11"> 1-1</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[0]['emelet'] == 0 && $lakasok[0]['szoba'] == 1 && $lakasok[0]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="01">Fsz - 1</div>    
                        </div>                        
                        <div class="col-3">
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[16]['emelet'] == 4 && $lakasok[16]['szoba'] == 3 && $lakasok[16]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" style="" id="43"> </div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[15]['emelet'] == 4 && $lakasok[15]['szoba'] == 2 && $lakasok[15]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="42"> 4-2</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[11]['emelet'] == 3 && $lakasok[11]['szoba'] == 2 && $lakasok[11]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="32"> 3-2</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[7]['emelet'] == 2 && $lakasok[7]['szoba'] == 2 && $lakasok[7]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="22"> 2-2</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[3]['emelet'] == 1 && $lakasok[3]['szoba'] == 2 && $lakasok[3]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="12"> 1-2</div>
                            <div class="p-3 border bg-dark nemFoglalhato">🚪</div>
                        </div>
                        
                        <div class="col-3">
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[16]['emelet'] == 4 && $lakasok[16]['szoba'] == 3 && $lakasok[16]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="43"> </div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[16]['emelet'] == 4 && $lakasok[16]['szoba'] == 3 && $lakasok[16]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="43"> 4-3</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[12]['emelet'] == 3 && $lakasok[12]['szoba'] == 3 && $lakasok[12]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="33"> 3-3</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[8]['emelet'] == 2 && $lakasok[8]['szoba'] == 3 && $lakasok[8]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="23"> 2-3</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[4]['emelet'] == 1 && $lakasok[4]['szoba'] == 3 && $lakasok[4]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="13"> 1-3</div>
                            <div class="p-3 border bg-dark nemFoglalhato">🚪</div>
                        </div>
                        
                        <div class="col-3">
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[19]['emelet'] == 5 && $lakasok[19]['szoba'] == 4 && $lakasok[19]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="54"> 5-4</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[17]['emelet'] == 4 && $lakasok[17]['szoba'] == 4 && $lakasok[17]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="44"> 4-4</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[13]['emelet'] == 3 && $lakasok[13]['szoba'] == 4 && $lakasok[13]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="34"> 3-4</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[9]['emelet'] == 2 && $lakasok[9]['szoba'] == 4 && $lakasok[9]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="24"> 2-4</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[5]['emelet'] == 1 && $lakasok[5]['szoba'] == 4 && $lakasok[5]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="14"> 1-4</div>
                            <div class="p-3 border bg-dark <?php echo e(($lakasok[1]['emelet'] == 0 && $lakasok[1]['szoba'] == 4 && $lakasok[1]['foglalt'] == 1) ? 'lefoglalva' : ''); ?>" id="04">Fsz - 4</div>
                        </div>  
                    </div>
                </div>
                <div class="col-4">
                    <form method="post" action="<?php echo e(route('foglal')); ?>">
                        <?php echo csrf_field(); ?>
                    <label for="emelet" class="form-label text-white">Emelet</label>
                    <select class="form-select form-select-lg bg-dark text-white" name="emelet" id="emelet" onchange="updateSzobaOptions()">
                        <option value="" selected>Válassz emeletet</option>
                        <option value="0">Földszint</option>
                        <option value="1">1. emelet</option>
                        <option value="2">2. emelet</option>
                        <option value="3">3. emelet</option>
                        <option value="4">4. emelet</option>
                        <option value="5">5. emelet</option>
                    </select>
                    <label for="szoba" class="form-label text-white">Szoba</label>
                    <select class="form-select form-select-lg bg-dark text-white" name="szoba" id="szoba">
                        <option value="" selected>Válassz szobát</option>
                    </select>
                    
                        
                        <div class="row p-5">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Foglalás</button>
                            </div>
                        </div>
                    </form>
                    <form action="<?php echo e(route('torol')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <label for="emelet" class="form-label text-white">Emelet törlés</label>
                        <select class="form-select form-select-lg bg-dark text-white" name="emelet2" id="emelet2" onchange="updateSzobaOptions2()">
                            <option value="" selected>Válassz törlésre emeletet</option>
                            <option value="0">Földszint</option>
                            <option value="1">1. emelet</option>
                            <option value="2">2. emelet</option>
                            <option value="3">3. emelet</option>
                            <option value="4">4. emelet</option>
                            <option value="5">5. emelet</option>
                        </select>
                        <label for="szoba" class="form-label text-white">Törölni való szoba</label>
                        <select class="form-select form-select-lg bg-dark text-white" name="szoba2" id="szoba2">
                            <option value="" selected>Válassz törölésre szobát</option>
                        </select>
                            <div class="row p-5">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Törlés</button>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </main>
</body>

</html>
<script>
    function updateSzobaOptions() {
        const emelet = document.getElementById('emelet').value;
        const szobaSelect = document.getElementById('szoba');
        szobaSelect.innerHTML = '<option value="" selected>Válassz törölésre szobát</option>';
        
        let szobak;
        if (emelet === '0' || emelet === '5') {
            szobak = ['1', '4'];
        } else {
            szobak = ['1.', '2', '3', '4'];
        }
        
        szobak.forEach(szoba => {
            const option = document.createElement('option');
            option.value = szoba;
            option.textContent = szoba;
            szobaSelect.appendChild(option);
        });
    }
    function updateSzobaOptions2() {
        const emelet = document.getElementById('emelet2').value;
        const szobaSelect = document.getElementById('szoba2');
        szobaSelect.innerHTML = '<option value="" selected>Válassz szobát</option>';
        
        let szobak;
        if (emelet === '0' || emelet === '5') {
            szobak = ['1', '4'];
        } else {
            szobak = ['1', '2', '3', '4'];
        }
        
        szobak.forEach(szoba => {
            const option = document.createElement('option');
            option.value = szoba;
            option.textContent = szoba;
            szobaSelect.appendChild(option);
        });
    }
    
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/lakas/lakas.blade.php ENDPATH**/ ?>