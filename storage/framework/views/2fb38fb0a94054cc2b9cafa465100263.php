<!DOCTYPE html>
<html lang="hu" data-bs-theme="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Szavazás</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body {
                user-select: none;
            }
            .diagram-td {
                max-width: 300px;
            }
            .diagram{
                width: calc(1% * var(--val));
                height: 15px;
                background: linear-gradient(90deg, rgba(74,98,138,1) 0%, rgba(122,178,211,1) 26%, rgba(185,229,232,1) 100%);
                /* animation: diagram-width 1.5s cubic-bezier(.26,0,.81,.31); */
            }
            .diagram.legtobb {
                background: linear-gradient(90deg, rgba(0,85,20,1) 0%, rgba(29,143,55,1) 26%, rgba(137,255,164,1) 100%);;
            }
            .diagram.legkevesebb {
                background: linear-gradient(90deg, rgba(69,0,7,1) 0%, rgba(145,7,20,1) 26%, rgba(221,49,65,1) 100%);
            }
            /* @keyframes diagram-width { from { width: 0 } } */
        </style>
    </head>
<body class="bg-dark">
    <div id="tablazat">
        
    </div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function HTTPRequest(url, callback)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (callback != null) callback(this.responseText)
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function Frissit()
    {
        HTTPRequest("/szavazat-tablazat", function (response) {
            document.getElementById("tablazat").innerHTML = response;
        });
    }
    Frissit();
    setInterval(Frissit, 1000);
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/szavazas.blade.php ENDPATH**/ ?>