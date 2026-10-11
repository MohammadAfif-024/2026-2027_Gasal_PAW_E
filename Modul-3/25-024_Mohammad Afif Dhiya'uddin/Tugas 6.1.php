<?php
    // 1. array_push()
    $array1 = array("A");

    echo "Array awal: (\"A\")<br>";
    array_push($array1, "B");

    echo "Hasil array_push: ";
    foreach ($array1 as $data) {
        echo $data . " ";
    }

    echo "<br><br>";

    // 2. array_merge()
    $array2 = array("A", "B");
    $array3 = array("C");

    echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
    $hasilMerge = array_merge($array2, $array3);

    echo "Hasil array_merge: ";
    foreach ($hasilMerge as $data) {
        echo $data . " ";
    }

    echo "<br><br>";

    // 3. array_values()
    $array4 = array("x" => 1, "y" => 2);

    echo "Array awal: (\"x\" => 1, \"y\" => 2)<br>";
    $hasilValues = array_values($array4);

    echo "Hasil array_values: ";
    foreach ($hasilValues as $data) {
        echo $data . " ";
    }

    echo "<br><br>";

    // 4. array_search()
    $array5 = array("A", "B", "C");

    echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
    $hasilSearch = array_search("B", $array5);

    echo "Hasil array_search: " . $hasilSearch;

    echo "<br><br>";

    // 5. array_filter()
    $array6 = array(0, 1, false, 2, "", 3, "array");

    echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
    $hasilFilter = array_filter($array6);

    echo "Hasil array_filter: ";
    foreach ($hasilFilter as $data) {
        echo $data . " ";
    }

    echo "<br><br>";

    // 6. Sorting array numerik
    $angka = array(3, 1, 2);

    echo "Array awal: (3, 1, 2)<br>";

    $hasilSort = $angka;
    sort($hasilSort);

    echo "Hasil sort: ";
    foreach ($hasilSort as $data) {
        echo $data . " ";
    }

    echo "<br>";

    $hasilRsort = $angka;
    rsort($hasilRsort);

    echo "Hasil rsort: ";
    foreach ($hasilRsort as $data) {
        echo $data . " ";
    }

    echo "<br><br>";

    // 7. Sorting array asosiatif
    $mahasiswa = array(
        "Peter" => 35,
        "Ben" => 37,
        "Joe" => 43
    );

    echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";

    // asort: berdasarkan nilai dari kecil ke besar
    $hasilAsort = $mahasiswa;
    asort($hasilAsort);

    echo "Hasil asort: ";
    foreach ($hasilAsort as $nama => $nilai) {
        echo $nama . "=>" . $nilai . ", ";
    }

    echo "<br>";

    // ksort: berdasarkan kunci dari A ke Z
    $hasilKsort = $mahasiswa;
    ksort($hasilKsort);

    echo "Hasil ksort: ";
    foreach ($hasilKsort as $nama => $nilai) {
        echo $nama . "=>" . $nilai . ", ";
    }

    echo "<br>";

    // arsort: nilai dari besar ke kecil
    $hasilArsort = $mahasiswa;
    arsort($hasilArsort);

    echo "Hasil arsort: ";
    foreach ($hasilArsort as $nama => $nilai) {
        echo $nama . "=>" . $nilai . ", ";
    }

    echo "<br>";

    // krsort: berdasarkan kunci dari Z ke A
    $hasilKrsort = $mahasiswa;
    krsort($hasilKrsort);

    echo "Hasil krsort: ";
    foreach ($hasilKrsort as $nama => $nilai) {
        echo $nama . "=>" . $nilai . ", ";
    }
?>
