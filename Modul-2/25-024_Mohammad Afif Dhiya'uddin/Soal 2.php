<?php
    $matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];

    foreach ($matkul as $mata_kuliah) {
        switch ($mata_kuliah) {
            case "PTI":
                echo "Saya suka $mata_kuliah <br>";
                break;
            case "ALPRO":
                echo "Saya suka $mata_kuliah <br>";
                break;
            case "DPW":
                echo "Saya suka $mata_kuliah <br>";
                break;
            case "STRUKDAT":
                echo "Saya suka $mata_kuliah <br>";
                break;
            case "JARKOM":
                echo "Saya suka $mata_kuliah <br>";
                break;
            case "PAW":
                echo "Saya suka $mata_kuliah <br>";
                break;
            default:
                echo "Saya tidak mengambil matkul $mata_kuliah <br>";
                break;
        }
    }
?>
