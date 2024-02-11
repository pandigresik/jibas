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

OpenDb();
$sql = "SELECT hadir, ijin, sakit, cuti, alpa FROM phsiswa WHERE replid = '".$_REQUEST['replid']."' ";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$total = 0;
for ($i=0;$i<5;$i++) {
	$total = $total + (int)$row[$i-1];
}

for ($i=0;$i<5;$i++) {
	if ($total == 0) 
		$data[] = 0;
	else
		$data[] = ($row[$i]/$total)*100;
}

$judul = ['Hadir', 'Ijin', 'Sakit', 'Cuti', 'Alpa'];

$color = ['green@0.5', 'red@0.5', 'yellow@0.5', 'blue@0.5', 'orange@0.5', 'gold@0.5', 'navy@0.5', 'darkblue@0.5', 'darkred@0.5', 'darkgreen@0.5', 'pink@0.5', 'black@0.5', 'gray@0.5'];


//Buat grafik
mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(500,280,"auto");
$graph->SetScale("textlin");

//setting kanvas
$graph->SetShadow();
$graph->img->SetMargin(50,40,50,40);
$graph->xaxis->SetTickLabels($judul);
$graph->xaxis->SetTickSide(SIDE_LEFT);

//Create bar plots
$plot = new BarPlot($data);
$plot->SetFillColor($color);

$plot->SetShadow('darkgray@0.5');

$plot->value->Show();
//$plot->value->SetFont(FF_FONT1,FS_BOLD);

$plot->value->SetFormat('%d');
$plot->SetValuePos('center');
//$plot->value->SetAlign('center','center');

//memasukkan kedalam grafik
$graph->Add($plot);

//$graph->title->Set("Statistik Siswa Aktif Berdasarkan Agama");
$graph->xaxis->title->Set("");
$graph->yaxis->title->Set("Presentase Presensi (%)");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

//Pengaturan sumbu x dan sumbu y
$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#dedede','#FFFFFF');

//Menamplikan ke browser
$graph->Stroke();

?>