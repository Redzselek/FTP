<head>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #121212;
        color: #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    
    .container {
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        text-align: center;
        width: 550px;
    }
    
    h2 {
        margin-bottom: 20px;
        color: #ff9800;
    }
    
    textarea {
        width: 100%;
        height: 250px;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #333;
        background-color: #2e2e2e;
        color: #e0e0e0;
        resize: none;
    }
    
    button {
        background-color: #ff9800;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        box-shadow: #a14100 0px 0px;
        transition: 0.5s ease-in-out;
    }
    
    button:hover {
        background-color: #e68900;;
        box-shadow: #a14100 3px 3px 10px;
        transition: 0.25s ease-in-out;
    }
    @media (max-width: 768px) {
        .container {
            width: 95%;
        }

        textarea {
            height: 200px;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
        }
    }
    </style>
</head>

<div class="container">
    <?php echo csrf_field(); ?>
    <h2>CSV Feltöltés</h2>
    <textarea rows="10" id="adatok">gyarto;tipus;ram;ssd;monitor
Dell;Aptiva;16;512;1
Dell;Notino;32;512;0
Dell;Notino;32;256;1
HP;Pavilion;64;1024;1
HP;Optima;64;1024;0</textarea>
    <button onclick="feltoltes()" id="feltoltes">Feltöltés</button>
</div>

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

    function feltoltes() {
        let textArea = document.getElementById('adatok').value;
        let lines = textArea.split('\n').slice(1);
        let data = lines.map(line => line.split(';'));
        let hibak = [];
        data.forEach((sor, index) => {
            if (sor.length !== 5) {
                hibak.push(index+1);
            }
            else {
                if (isNaN(sor[2]) || isNaN(sor[3])) {
                    hibak.push(index+1);
                }
                else if (sor[4] !== "0" && sor[4] !== "1") {
                    hibak.push(index+1);
                }
            }
        });
        if (hibak.length > 0) {
            let hibaStr = hibak.join(', ');
            alert(`Sikertelen feltöltés! A következ sorokban hiba van: ${hibaStr}`);
        }
        else {
            data.forEach(line => {
                HTTPRequest('/szamitogep/beiras/'+line[0]+"/"+line[1]+"/"+line[2]+"/"+line[3]+"/"+line[4], null);
                setTimeout(() => {
                    window.location.href = "/szamitogep/listaz";                
                }, 500);
            
            });
        }
    }
    
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/szamitogepFeltoltes.blade.php ENDPATH**/ ?>