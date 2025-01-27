<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('css/menhely.css')); ?>">
    <title>Egyedi menhely</title>
</head>
<body>
    <div class="header">
        <img src="<?php echo e(asset('img/logo.png')); ?>" alt="">
        <h2>Egyedi Állatmenhely</h2>
        <div class="menu">
            <a href="/menhely/fooldal">Főoldal</a>
            <a href="/menhely/nyilvantartas">Nyilvántartás</a>
            <a href="" id="scrollTrigger">Kapcsolat</a>
        </div>
    </div>
    <div class="main">
        <div class="welcome">
            <h1>Üdvözlünk az Egyedi Állatmenhely oldalán!</h1>
            <p>"Amilyen tökéletes, a maga nemében felülmúlhatatlan minden igazán nagy művészeti alkotás, olyan tökéletes a maga nemében minden élőlény a földön." -Venetianer Pál</p>
        </div>
    </div>
    <div class="animals">

        <?php $__currentLoopData = $allatok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $animal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="nameage">
                    <h3>Név: <?php echo e($animal->nev); ?></h3>
                    <p>Kor: <?php echo e($animal->eletkor); ?></p>
                </div>
                <div class="species"><p>Fajta: <?php echo e($animal->fajta); ?></p></div>
                <div class="about">
                    <p>Szín: <?php echo e($animal->szin); ?></p>
                    <p>Neme: 
                        <?php if($animal->neme == 1): ?>
                            Hím
                        <?php elseif($animal->neme == 0): ?>
                            Nőstény
                        <?php endif; ?>
                    </p>
                    <p>Bekerülés ideje: <?php echo e($animal->bekerules_ideje); ?></p>
                    <p>Chipszám: <?php echo e($animal->chipszam); ?></p>
                    <p>Faj: 
                        <?php if($animal->faj == 'Kutya'): ?>
                            Kutya 🐕
                        <?php elseif($animal->faj == 'Macska'): ?>
                            Macska 🐈
                        <?php endif; ?>
                    </p>
                    <p>Veszettség elleni oltás:
                        <?php if($animal->veszettseg_oltas == 1): ?>
                            Van
                        <?php elseif($animal->veszettseg_oltas == 0): ?>
                            Nincs
                        <?php endif; ?>
                    </p>
                    <p>Parvo elleni oltás: 
                        <?php if($animal->parvo_oltas == 1): ?>
                            Van
                        <?php elseif($animal->parvo_oltas == 0): ?>
                            Nincs
                        <?php endif; ?>

                    </p>
                    <p>Kombinált oltás: 
                        <?php if($animal->kombinalt_oltas == 1): ?>
                            Van
                        <?php elseif($animal->kombinalt_oltas == 0): ?>
                            Nincs
                        <?php endif; ?>
                    </p>
                </div>
                <div class="editDelete">
                    <button onclick="torles(<?php echo e($animal->id); ?>)">🚮</button>
                    
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        

    </div>
    <div class="footer">
        <div>
            <h1>Egyedi Állatmenhely</h1>
            <h3>Elérhetőségek:</h3>
            <p>12345-678</p>
            <p>egyedi@allatmenhely.hu</p>
            <p>www.egyediallatmenhely.hu</p>
            <p>4269 Zedfalva, Ajax utca 32/1</p>
        </div>
        <form action="" method="get">
            <label for="name">Név:</label><br>
            <input type="text" name="name" id="name"><br>
            <label for="email">E-mail:</label><br>
            <input type="email" name="email" id="email"><br>
            <label for="message">Üzenet:</label><br>
            <textarea name="message" id="message" cols="43" rows="10"></textarea><br>
            <input type="submit" value="Küldés">
        </form>
    </div>
</body>
</html>

<script>
    function HTTPRequest(url, callback) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
    if (callback != null) {
        callback(this.responseText);
    }
    }
    }
    xhttp.open('GET', url, true);
    xhttp.send();
    }
    document.getElementById('scrollTrigger').addEventListener('click', function(event) {
        window.scrollBy(0, document.body.scrollHeight);
        event.preventDefault();
    });

    function torles(id) {
    HTTPRequest("/menhely/torles/" + id, () => {
        location.reload();
    });
    }

    function szerkesztes(id) {
    HTTPRequest("/menhely/szerkesztes/" + id, () => {
        location.reload();
    });
}
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/menhely.blade.php ENDPATH**/ ?>