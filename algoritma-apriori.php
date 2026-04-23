<?php

// Mengihtung Soal Algoritma Apriori
// by Wahyu Hidayat
// NIM : 221091750045
// github : https://github.com/wahyuhidayattz

require_once __DIR__ . '/vendor/autoload.php';

// Config Program
$tampilkan_cli = false;
$tampilkan_pdf = true;

// Data Transaksi
$array_transaksi = [
    "1000" => "M,O,N,K,E,Y",
    "2000" => "D,O,N,K,E,Y",
    "4000" => "M,A,K,E",
    "5000" => "M,U,C,K,Y",
    "6000" => "C,O,O,K,I,E",
    "7000" => "D,O,N,M,O,N"
];

$golden_rule = 2;

// Data Contoh
// Data Transaksi
// $array_transaksi = [
//     "1000" => "M,O,N,K,E,Y",
//     "2000" => "D,O,N,K,E,Y",
//     "4000" => "M,A,K,E",
//     "5000" => "M,U,C,K,Y",
//     "6000" => "C,O,O,K,I,E",
// ];

// $golden_rule = 3;

// Tampilkan dalam bentuk pdf
$html_pdf = '<h3>Tugas Data Mining | Algoritma Apriori</h3>';
$html_pdf .= "<br>";
$html_pdf .= "Nama : Wahyu Hidayat<br>";
$html_pdf .= "NIM : 221091750045<br>";
$html_pdf .= "Kelas : 07 SISE 001<br>";
$html_pdf .= "<br>";
$html_pdf .= "<ol>";

// kita ambil unique itemnya menggunakan foreach loop
$unique_items = [];
foreach ($array_transaksi as $d) {
    $arr = explode(",", $d);
    foreach ($arr as $item) {
        @$unique_items[$item] = $item;
    }
}

// Ambil jumlah berapa kali transaksi tiap itemnya
$tabel_3 = [];
$tabel_4 = [];
foreach ($unique_items as $d) {
    $jumlahTransaksi = 1;
    foreach ($array_transaksi as $dt) {
        $arr = explode(",", $dt);
        if (in_array($d, $arr)) {
            @$tabel_3[$d] = $jumlahTransaksi;

            // Tabel 4 adalah tabel 3 dengan filter jumlah transaksi >= 2 / golden rule
            if ($jumlahTransaksi >= $golden_rule) {
                @$tabel_4[$d] = $jumlahTransaksi;
            }
            $jumlahTransaksi++;
        }
    }
}

$tabel_5 = [];
$jumlahTabel4 = count($tabel_4);
for ($i = 0; $i < $jumlahTabel4; $i++) {
    for ($j = $i + 1; $j < $jumlahTabel4; $j++) {
        $keys = array_keys($tabel_4);
        @$tabel_5[] = $keys[$i] . "," . $keys[$j];
    }
}

// Mencari jumlah transasksi pada 2 item untuk di jadikan tabel 6
$tabel_6 = [];
$tabel_7 = [];
foreach ($tabel_5 as $d) {
    $arr = explode(",", $d);
    $jumlahTransksi = 1;
    foreach ($array_transaksi as $trx) {
        $arrTrx = explode(",", $trx);
        if (in_array($arr[0], $arrTrx) && in_array($arr[1], $arrTrx)) {
            @$tabel_6[$d] = $jumlahTransksi;
            if ($jumlahTransksi >= $golden_rule) {
                @$tabel_7[$d] = $jumlahTransksi;
            }
            $jumlahTransksi++;
        }
    }
}

// Membuat tabel 8 (Jumlah 3 item pada transaksi yang sama)
// Mmebuat unique huruf awalan
$unique_tabel_7_awalan = [];
foreach ($tabel_7 as $k => $d) {
    $arr = explode(",", $k);
    $arr1 = $arr[0];
    $unique_tabel_7_awalan[$arr1][] = $k;
}

$unique_tabel_7_awlan_duatransksi = [];
foreach ($unique_tabel_7_awalan as $k => $d) {
    if (count($d) >= 2) {
        $unique_tabel_7_awlan_duatransksi[$k] = $d;
    }
}

$tabel_8 = [];
foreach ($unique_tabel_7_awlan_duatransksi as $k => $d) {
    $jumlahAnak = count($d);
    for ($i = 0; $i < $jumlahAnak; $i++) {
        for ($j = $i + 1; $j < $jumlahAnak; $j++) {
            $data = $k . "," . explode(",", $d[$i])[1] . "," . explode(",", $d[$j])[1];
            @$tabel_8[$data] = $data;
        }
    }
}

$tabel_8_final = [];
$tabel_9 = [];
foreach ($tabel_8 as $d) {
    $arr = explode(",", $d);
    $jumlahTransksi = 1;
    foreach ($array_transaksi as $trx) {
        $arryTrx = explode(",", $trx);
        if (in_array($arr[0], $arryTrx) && in_array($arr[1], $arryTrx) && in_array($arr[2], $arryTrx)) {
            @$tabel_8_final[$d] = $jumlahTransksi;
            if ($jumlahTransksi >= $golden_rule) {
                @$tabel_9[$d] = $jumlahTransksi;
            }
            $jumlahTransksi++;
        }
    }
}


// Menghitung tingkat keyakinan (Confidence) dan Frequet Item Set

function konversiKeTabel($array, $judul = "Items | Jml Trx")
{
    $text = $judul . "\n";
    $text .= "==============\n";
    foreach ($array as $k => $d) {
        $text .= $k . " | " . $d . "\n";
    }
    return $text . "\n\n";
}

$hasil_tingkat_confidence = '';


$no = 1;
// Mencari Asosiasi
foreach ($tabel_9 as $k => $d) {
    $arr = explode(",", $k);
    for ($i = 0; $i < count($arr); $i++) {
        $depan = $arr[$i];
        $belakang = $arr;
        unset($belakang[$i]);
        $belakang = array_values($belakang);
        $transaksiAwal = $tabel_4[$depan];
        $transaksiAkhir = $tabel_6[implode(",", $belakang)];
        $transaksiAkhir = min($golden_rule, $transaksiAkhir);
        $hasil = $transaksiAkhir / $transaksiAwal * 100;
        $hasil_tingkat_confidence .= $no . ". " . $depan . " => " . implode(",", $belakang) . " | Keyakinannya adalah : ";
        $hasil_tingkat_confidence .= $transaksiAkhir . " / " . $transaksiAwal . " * 100 = " . number_format($hasil) . "% \n";
        $no++;
    }
}

echo "\n";

$no = 1;
// Mencari Asosiasi
foreach ($tabel_9 as $k => $d) {
    $arr = explode(",", $k);
    for ($i = 0; $i < count($arr); $i++) {
        $depan = $arr[$i];
        $belakang = $arr;
        unset($belakang[$i]);
        $belakang = array_values($belakang);
        $duahuruf = implode(",", $belakang);
        $awal = $tabel_4[$depan];
        $awal = min($awal, $golden_rule);
        $akhir = $tabel_6[$duahuruf];
        $akhir = min($akhir, $golden_rule);
        $hasil = $awal / $akhir * 100;
        $hasil_tingkat_confidence .= $no . ". " . implode(",", $belakang)  . " => " . $depan . " | Keyakinannya adalah : ";
        $hasil_tingkat_confidence .= $awal . " / " . $akhir . " * 100 = " . number_format($hasil) . "% \n";
        $no++;
    }
}


if ($tampilkan_cli) {
    echo "Tabel 3\n";
    echo "Banyaknya transaksi setiap item\n";
    print_r(konversiKeTabel($tabel_3));

    echo "Tabel 4\n";
    echo "Golden Rule atas tabel 3\n";
    print_r(konversiKeTabel($tabel_4));

    echo "Tabel 5\n";
    echo "Pasangan 2 Items\n";
    print_r(konversiKeTabel($tabel_5, "Index | Items"));

    echo "Tabel 6\n";
    echo "Jumlah Transksi pada tabel 5\n";
    print_r(konversiKeTabel($tabel_6));

    echo "Tabel 7\n";
    echo "Golden Rule pada tabel 6\n";
    print_r(konversiKeTabel($tabel_7));

    echo "Tabel 8\n";
    echo "Pasangan 3 Items\n";
    print_r(konversiKeTabel($tabel_8_final));

    echo "Tabel 9\n";
    echo "Golden Rule atas tabel 8\n";
    print_r(konversiKeTabel($tabel_9));

    echo "Hasil Kombinasi 3 Item adalah :\n\n";
    echo $hasil_tingkat_confidence;
}

if ($tampilkan_pdf) {
    $html_pdf .= "<li>";
    $html_pdf .= "Data Penjualan Item";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>ID Penjualan</td>";
    $html_pdf .= "<td>Item Yang Dijual</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($array_transaksi as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Jumlah Transaksi Tiap-tiap Item";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_3 as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Jumlah Transaksi Tiap-tiap Item (Minimal 2 Transasksi)";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_4 as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Transaksi Pasangan 2 Item";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_6 as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Transaksi Pasangan 2 Item (Minimal 2 Transaksi)";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_7 as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Transaksi Pasangan 3 Item";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_8_final as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";

    $html_pdf .= "<li>";
    $html_pdf .= "Transaksi Pasangan 3 Item (Minimal 2 Transaksi)";
    $html_pdf .= '<table border="1" cellspacing="0" cellpadding="5">';
    $html_pdf .= "<thead>";
    $html_pdf .= "<tr>";
    $html_pdf .= "<td>Items</td>";
    $html_pdf .= "<td>Jumlah Transaksi</td>";
    $html_pdf .= "</tr>";
    $html_pdf .= "</thead>";
    $html_pdf .= "<tbody>";
    foreach ($tabel_9 as $k => $d) {
        $html_pdf .= "<tr>";
        $html_pdf .= "<td>$k</td>";
        $html_pdf .= "<td>$d</td>";
        $html_pdf .= "</tr>";
    }
    $html_pdf .= "</tbody>";
    $html_pdf .= "</table><br>";
    $html_pdf .= "</li>";
    $html_pdf .= "<li>";
    $html_pdf .= "Hasil Nilai Keyakinan / Confidence<br>";
    $html_pdf .= str_replace("\n", "<br>", $hasil_tingkat_confidence);
    $html_pdf .= "</li><br><br>";
    $html_pdf .= "Note : dibuat menggunakan bahasa pemerograman PHP, jika di tulis kebanyakan bu :D";

    $html_pdf .= "</ol>";
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->setFooter('By Wahyu Hidayat - 221091750045 | | Halaman : {PAGENO}');
    $mpdf->WriteHTML($html_pdf);
    $mpdf->Output('laporan.pdf', 'I');
}
