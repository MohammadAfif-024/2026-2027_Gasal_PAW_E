<?php
    $matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
    $praktikum = ["JARKOM", "PAW"];

    for ($i = 0; $i < 8; $i++) {
        if ($matkul[$i] == $praktikum[0] || $matkul[$i] == $praktikum[1]) {
            echo "Saya sedang mengambil matkul " . $matkul[$i] . " termasuk praktikumnya<br>";
        } 
        elseif ($i == 0 || $i == 1 || $i == 2 || $i == 3  ) {
            echo "Saya sudah mengambil matkul " . $matkul[$i] . " Semester lalu<br>";
        } 
        else {
            echo "Saya belum mengambil matkul " . $matkul[$i] . " <br>";
        }
    }
?>
