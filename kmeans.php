<?php

//////////////////////////////////////////////////////////////////////////////////////
//                                                                                  //
//                  Metode K means (pengelompokan status gizi)                      //
//                                                                                  //
//                  8 April 2021                                                    //
//                                                                                  //
//                  XAMPP Version : 5.6.40   ||    PHP Version : 5                  //
//                                                                                  //
//////////////////////////////////////////////////////////////////////////////////////

// Dataset 50
$dataset = [[0.352380952, 0.247311828], // Dataset berisi Tinggi Badan dan Berat Badan yg telah di normalisasi.
            [0.352380952, 0.397849462],
            [0.257142857, 0.161290323],
            [0.257142857, 0.483870968],
            [0.104761905, 0.247311828],
            [0.085714286, 0.161290323],
            [0.142857143, 0],
            [0.114285714, 0.462365591],
            [0.447619048, 0.075268817],
            [0.466666667, 0.290322581],
            [0.495238095, 0.376344086],
            [0.476190476, 0.537634409],
            [0.161904762, 0.215053763],
            [0.2, 0.139784946],
            [0.104761905, 0.322580645],
            [0, 0.23655914],
            [0.923809524, 0.913978495],
            [0.676190476, 0.666666667],
            [0.542857143, 0.483870968],
            [1, 0.806451613],
            [1, 0.462365591],
            [0.971428571, 0.698924731],
            [0.79047619, 0.634408602],
            [0.542857143, 0.709677419],
            [0.923809524, 1],
            [0.485714286, 0.720430108],
            [0.066666667, 0.268817204],
            [0.39047619, 0.161290323],
            [0.40952381, 0.505376344],
            [0.352380952, 0.634408602],
            [0.276190476, 0.387096774],
            [0.295238095, 0.247311828],
            [0.123809524, 0],
            [0.161904762, 0.247311828],
            [0.142857143, 0],
            [0.114285714, 0.35483871],
            [0.580952381, 0.129032258],
            [0.504761905, 0.247311828],
            [0.495238095, 0.365591398],
            [0.476190476, 0.494623656],
            [0.161904762, 0.344086022],
            [0.238095238, 0.215053763],
            [0.142857143, 0.150537634],
            [0, 0.075268817],
            [0.923809524, 0.419354839],
            [0.771428571, 0.602150538],
            [0.542857143, 0.322580645],
            [0.876190476, 0.634408602],
            [0.885714286, 0.52688172],
            [0.971428571, 0.47311828]];
// print_r($dataset);

// Centroid  5
$centroid = [[0.92, 1],     // Gizi Buruk(C0)
             [1, 0.46],     // Gizi kurang(C1)
             [0.35, 0.63],  // Gizi Baik(C2)
             [0.58, 0.13],  // Gizi Lebih(C3)
             [0, 0.08]];    // Obesitas(C4)
// print_r($centroid);


// Banyak Dataset
$banyak_dataset = sizeof($dataset); // 50
echo "<br/>";
echo("Banyak Dataset : ");
echo($banyak_dataset);

// Banyak Centroid
$banyak_centroid = sizeof($centroid); // 5
echo "<br/>";
echo("Banyak Centroid : ");
echo($banyak_centroid);

// Banyak Kolom
$banyak_kolom = sizeof($dataset[0]); // 2
echo "<br/>";
echo("Banyak Kolom : ");
echo($banyak_kolom);
echo "<br/>";echo "<br/>";


// Fungsi euclidean distance
function hitung_euclidean_distance($dataset, $centroid, $banyak_kolom) {

    $siat = 0;
    for ($i=0; $i < $banyak_kolom; $i++) { // 2 X
        $siat = $siat + (($dataset[$i] - $centroid[$i])**2);
    }

    return sqrt($siat);
}


// Iterasi K-Means
function iterasi_kmeans($dataset, $centroid, $banyak_dataset, $banyak_centroid, $banyak_kolom){

    $dataset_label = [];
    // $label_cluster = [];
    for ($i=0; $i < $banyak_dataset; $i++) { // 50X
        $hasil_euclid = [];
        for ($j=0; $j < $banyak_centroid; $j++) { // 5X
            $hasil_euclid[$j] = hitung_euclidean_distance($dataset[$i], $centroid[$j], $banyak_kolom);
        }
        // print_r($hasil_euclid);
        // echo "<br/>";
        $nilai_terkecil = min($hasil_euclid);
        // print_r($nilai_terkecil);
        // echo "<br/>";
        $index_nilai_terkecil = array_search($nilai_terkecil, $hasil_euclid);
        // print_r($index_nilai_terkecil);
        // $label_cluster[$i] = $index_nilai_terkecil;
        $dataset_label[$i] = $index_nilai_terkecil;
    
    }
    
    // print_r($dataset_label);
    // echo "<br/>";
    
    
    // Mengelompokkan dataset berdasarkan cluster
    $isi_cluster = [];  // array 3 dimensi
    for ($x=0; $x < $banyak_centroid; $x++) { 
        $array_siat = [];
        for ($y=0; $y < $banyak_dataset; $y++) { 
            if($dataset_label[$y] == $x) {  
              array_push($array_siat, $dataset[$y]);   
            }
        }
        $isi_cluster[$x] = $array_siat;
    }
    // print_r($isi_cluster) ;


    // Menghitung centroid_baru tiap cluster
    $new_centroid = [];
    for ($i=0; $i < $banyak_centroid; $i++) { // 5 X
        $hasil_centroid = [];
        for ($j=0; $j < $banyak_kolom; $j++) {  // 2 X
            $temp_centroid = 0;
            for ($k=0; $k < sizeof($isi_cluster[$i]); $k++) { // sebanyak tiap2 cluster        
                $temp_centroid = $temp_centroid + $isi_cluster[$i][$k][$j];
            }
            $hasil_centroid[$j] = $temp_centroid/sizeof($isi_cluster[$i]);
        }
        $new_centroid[$i] = $hasil_centroid;
    }
     
    return [$new_centroid, $dataset_label];
}

// Print tabel hasil cluster
function print_hasil_cluster($dataset_label, $dataset, $banyak_dataset){
    echo "<h3>HASIL CLUSTERING</h3>";
    echo "<table border='1' >";
    echo "<tr>";
    echo "<th>Data Ke</th>";
    echo "<th>Label Cluster</th>";
    echo "</tr>";
    
    for ($i=0; $i < $banyak_dataset; $i++) {     
      echo "<tr>";
      echo "<td align='center'>$i</td>";
      echo("<td align='center'>$dataset_label[$i]</td>");
      echo "</tr>";
    }
    echo "</table>";
}

    
//-------------------------------------------------------------------   
//--------------------------- Start Metode --------------------------
//-------------------------------------------------------------------

echo "Centroid Awal : ";
print_r($centroid);echo "<br/>";

// Perulangan iterasi kmeans
$centroid_temp = [];
$ulang = TRUE;
while ($ulang) {
    # code...
    $centroid_dan_datasetlabel = iterasi_kmeans($dataset, $centroid, $banyak_dataset, $banyak_centroid, $banyak_kolom);
    $centroid = $centroid_dan_datasetlabel[0];  // centroid akan berubah-ubah saat perulangan
    $dataset_label = $centroid_dan_datasetlabel[1];  // dataset_label akan berubah-ubah saat perulangan

    if($centroid == $centroid_temp){ // Membandingkan nilai centroid, jika sama iterasi berhenti.
        $ulang = FALSE;
    }

    $centroid_temp = $centroid;

    echo "<br/>";
    echo "Centroid baru : ";
    print_r($centroid); // tampilkan nilai centroid
    echo "<br/>";
    echo "<br/>";

}

print_hasil_cluster($dataset_label, $dataset, $banyak_dataset); // Menampilakn Hasil Cluster


//tes ganti2
