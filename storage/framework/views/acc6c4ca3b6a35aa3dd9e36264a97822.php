
<select id="tablaMeret" onchange="tablaMeret(this.value)">
    <option value="">Válassz méretet</option>
    <option value="2">2x2</option>
    <option value="3">3x3</option>
    <option value="4">4x4</option>
    <option value="5">5x5</option>
    <option value="6">6x6</option>
    <option value="7">7x7</option>
    <option value="8">8x8</option>
</select>

<table>
    <?php for($i = 0; $i < $meret; $i++): ?>
        <tr>
            <?php for($j = 0; $j < $meret ; $j++): ?>
                <td><input <?php if(isset($tabla[$j][$i]) && $tabla[$j][$i] == 1): ?> checked <?php endif; ?> onclick="Mentes('<?php echo e($i); ?>','<?php echo e($j); ?>')" id="<?php echo e($i); ?>_<?php echo e($j); ?>" style="height: 50px; width: 50px; margin: 15px" type="checkbox"></td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>



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

    function Mentes(oszlop, sor) {
        for (let i = 0; i < <?php echo e($meret); ?>; i++) {
            for (let j = 0; j < <?php echo e($meret); ?>; j++) {
                if (!(document.getElementById(i + "_" + j).checked)) {
                    console.log("üres lett");
                    HTTPRequest("/mentes/" + i + "/" + j+"/0");
                }
                else{
                    if (i == oszlop && j == sor) {
                        console.log(i + "_" + j);
                        HTTPRequest("/mentes/" + i + "/" + j+"/1");
                    }
                }
            }
        }
    }

    function tablaMeret(meret) {
        meret = document.getElementById("tablaMeret").value;
        HTTPRequest("/meret/"+meret,(responseText) => {
            location.reload();
        });
        console.log(meret);
    }
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/egyedirobi.moriczcloud.hu/resources/views/tabla.blade.php ENDPATH**/ ?>