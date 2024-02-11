<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
include_once '../../vendor/autoload.php';
require_once('../include/config.php');
require_once('../include/db_functions.php');

$tahunakhir=(int)$_REQUEST['tahunakhir'];
$tahunawal=(int)$_REQUEST['tahunawal'];
$departemen=$_REQUEST['departemen'];

OpenDb();
//query untuk kelamin pria
$querysuku = "SELECT COUNT(*) as Jum, j.jenismutasi as jenismutasi FROM jbsakad.mutasisiswa m,jbsakad.siswa s,jbsakad.kelas k,jbsakad.tahunajaran ta,jbsakad.tingkat ti,jbsakad.jenismutasi j WHERE s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ta.departemen='$departemen' AND m.jenismutasi=j.replid AND s.statusmutasi=m.jenismutasi AND m.nis=s.nis AND YEAR(m.tglmutasi)<='$tahunakhir' AND YEAR(m.tglmutasi)>='$tahunawal' GROUP BY jenismutasi";

$resultsuku = QueryDb($querysuku);
$num = @mysqli_num_rows($resultsuku);

while ($rowsuku = @mysqli_fetch_assoc($resultsuku)) {
    $data[] = $rowsuku['Jum'];
    $suku[] = $rowsuku['jenismutasi'];
    $color = ['red', 'black', 'green', 'blue', 'gray', 'darkblue', 'gold', 'yellow', 'navy', 'orange', 'darkred', 'darkgreen', 'pink'];
}

if($num == 0) {
  //echo "Gak ada data";
  //echo "../images/ico/blank_statistik.png";
}else {

//Buat grafik
mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(450,300,"auto");
$graph->SetScale("textlin");

//setting kanvas
$graph->SetShadow();
$graph->img->SetMargin(50,40,50,40);
$graph->xaxis->SetTickLabels($suku);
$graph->xaxis->SetTickSide(SIDE_LEFT);

//Create bar plots
$plot = new BarPlot($data);
$plot->SetFillColor($color);

$plot->SetShadow('darkgray@0.5');

$plot->value->Show();
//$plot->value->SetFont(FF_FONT1,FS_BOLD);

$plot->value->SetFormat('%d');
//$plot->value->SetAlign('center','center');

//memasukkan kedalam grafik
$graph->Add($plot);

$graph->title->Set("Statistik Mutasi Siswa Departemen $departemen \n Tahun Mutasi $tahunawal s/d $tahunakhir");
$graph->xaxis->title->Set("Jenis Mutasi");
$graph->yaxis->title->Set("Jumlah Siswa");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

//Pengaturan sumbu x dan sumbu y
$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#dedede','#FFFFFF');

//Menamplikan ke browser
$graph->Stroke();
}
?>