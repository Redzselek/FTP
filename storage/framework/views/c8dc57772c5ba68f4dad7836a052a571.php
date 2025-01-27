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
    <div class="container">
        <div class="nyilvantartas">
            <h1>Töltse fel kisállata adatait!</h1>
        </div>
        <div class="form">
                    <div>
                        <p>Faj: <input type="text" required name="faj" id="faj"></p>
                    </div>
                    <div>
                        <p>Név: <input type="text" required name="nev" id="nev"></p>
                    </div>
                    <div>
                        <p>Életkor: <input  required type="number" name="eletkor" id="eletkor"></p>
                    </div>
                    <div>
                        <p>Fajta: <input type="text" required name="fajta" id="fajta"></p>
                    </div>
                    <div>
                        <select id='nem'>
                            <option value="semmi" disabled selected>Nem:</option>
                            <option value='1'>Hím</option>
                            <option value='0'>Nőstény</option>
                        </select>
                    </div>
                    <div>
                        <p>Szín: <input type="text" required name="szin" id="szin"></p>
                    </div>
                    <h3>Oltás: </h3>
                    <div class="oltas">
                        
                        <input type="checkbox" id="veszettseg" value="veszettseg">
                        <label for="veszettseg">Veszettség</label>
                        <input type="checkbox" id="palvo" value="palvo">
                        <label for="palvo">Palvo</label>
                        <input type="checkbox" id="kombinalt" value="kombinalt">
                        <label for="kombinalt">Kombinált</label>
                    </div>
                    <div>
                        <p>Chipszám: <input required type="text" required name="" id="chipszam"></p>
                    </div>
                    <div>
                        <p>Bekerülés ideje: <input type="date" required name="" id="bekerul"></p></p>
                    </div> 
                    <div>
                        <button onclick="feltoltes()">Feltöltés</button>
                    </div>
            </div>
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

    function feltoltes()
    {
        var faj = document.getElementById('faj').value
        var nev = document.getElementById('nev').value
        var eletkor = document.getElementById('eletkor').value
        var fajta = document.getElementById('fajta').value
        var nemselect = document.getElementById('nem')
        var nem = nemselect.options[nemselect.selectedIndex].value;
        var szin = document.getElementById('szin').value
        var veszettseg = document.getElementById("veszettseg")
        var palvo = document.getElementById("palvo")
        var kombinalt = document.getElementById("kombinalt")
        var chipszam = document.getElementById('chipszam').value
        var bekerul = document.getElementById('bekerul').value
        var oltas_veszett = 0
        var oltas_palvo = 0
        var oltas_kombinalt = 0

        if(veszettseg.checked == true)
        {
            oltas_veszett = 1
        }   
        if(palvo.checked == true)
        {
            oltas_palvo = 1
        }   
        if(kombinalt.checked == true)
        {
            oltas_kombinalt = 1
        }   


        var mehet = true
        let oltasok = [veszettseg,palvo,kombinalt]
        let lista = [faj,nev,eletkor,fajta,nem,szin,chipszam,bekerul]
        for(var i = 0; i < lista.length; i++)
        {
            if(lista[i] == "")
            {
                mehet = false
            }
        }
        if(veszettseg.checked == false && palvo.checked == false && kombinalt.checked == false)
        {
            console.log("asd")
            mehet = false
        }
        if(nem == "semmi"){
            console.log('semmi')
        }

        if(mehet){ 
            HTTPRequest("/menhely/beiras/"+nev+"/"+eletkor+"/"+fajta+"/"+szin+"/"+nem+"/"+bekerul+"/"+chipszam+ "/" +faj + "/"+oltas_veszett+"/"+oltas_palvo+"/"+oltas_kombinalt,null);
            console.log("asdasd");
            
        }
    }
    document.getElementById('scrollTrigger').addEventListener('click', function(event) {
        window.scrollBy(0, document.body.scrollHeight);
        event.preventDefault(); 
    });
</script> <?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/menhely_nyilvantartas.blade.php ENDPATH**/ ?>