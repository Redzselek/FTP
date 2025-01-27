<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    width: 90%;
    max-width: 800px;
}

h2 {
    margin-bottom: 20px;
    color: #ff9800;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    padding: 12px;
    border: 1px solid #333;
    text-align: center;
}

th {
    background-color: #2e2e2e;
    color: #ff9800;
}

td {
    background-color: #2e2e2e;
}

button.delete-btn {
    background-color: #ff4d4d;
    box-shadow: 3px 3px 10px #ff4d4d00;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.25s ease-in-out;
    font-size: 24px;
}

button.delete-btn:hover {
    background-color: #e60000;
    box-shadow: 1px 1px 3px #a70000;
    transition: 0.25s ease-in-out;
}

/* Reszponzív beállítások */
@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 15px;
    }

    table {
        font-size: 14px;
    }

    th, td {
        padding: 8px;
    }

    button.delete-btn {
        font-size: 18px;
        padding: 6px 10px;
    }
}

@media (max-width: 480px) {
    table, th, td {
        display: block;
        width: 100%;
    }

th, td {
    text-align: left;
    padding: 10px;
    border: none;
    border-bottom: 1px solid #333;
}

th {
    background-color: transparent;
    color: #ff9800;
}

tr {
    margin-bottom: 15px;
    display: block;
}

button.delete-btn {
    width: 100%;
    font-size: 16px;
    padding: 10px;
}
}


.ujbtn {
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
    
.ujbtn:hover {
        background-color: #e68900;;
        box-shadow: #a14100 3px 3px 10px;
        transition: 0.25s ease-in-out;
    }
</style>
</head>

<body>
    <div class="container">
        <h2>Laptop Lista</h2>
        <table>
            <thead>
                <tr>
                    <th>Gyártó</th>
                    <th>Típus</th>
                    <th>RAM</th>
                    <th>SSD</th>
                    <th>Monitor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="data-table-body">
                <?php $__currentLoopData = $szamitogep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->gyarto); ?></td>
                    <td><?php echo e($item->tipus); ?></td>
                    <td><?php echo e($item->ram); ?>GB</td>
                    <td><?php echo e($item->ssd); ?>GB</td>
                    <td><?php echo e($item->monitor == 1 ? ' ✔' : ' ✖'); ?></td>
                    <td><button class="delete-btn" onclick="torles(<?php echo e($item->id); ?>)"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <button class="ujbtn" onclick="location.href='/szamitogep/feltoltes'">Új CSV</button>
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

    function torles(id) {
    let ok = confirm("Biztosan törli a laptopot?");
    if (ok) {
        HTTPRequest("/szamitogep/torles/" + id, () => {
            location.reload();
        });
    }
    
    }
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/szamitogepLista.blade.php ENDPATH**/ ?>