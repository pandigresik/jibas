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

$idproses=(int)$_REQUEST['idproses'];
$departemen=$_REQUEST['departemen'];
$dasar = $_REQUEST['dasar'];
$tabel = $_REQUEST['tabel'];
OpenDb();
$i = 0;

if ($dasar == 'Golongan Darah') {
	$row = ['A', '0', 'B', 'AB', ''];
	$judul = [1=>'A', '0', 'B', 'AB', 'Tidak ada data'];	
	$jum = count($row);	
} elseif ($dasar == 'Jenis Kelamin') {
	$row = ['l', 'p'];
	$judul = [1=>'Laki-laki', 'Perempuan'];
	$jum = count($row);
} elseif ($dasar == 'Kewarganegaraan') {
	$row = ['WNI', 'WNA'];
	$judul = [1=>'WNI', 'WNA'];
	$jum = count($row);
} elseif ($dasar == 'Status Aktif') {
	$row = [1, 0];
	$judul = [1 => 'Aktif', 'Tidak Aktif'];
	$jum = count($row);
} elseif ($dasar == 'Kondisi Siswa') {	
	$query = "SELECT $tabel FROM jbsakad.kondisisiswa ORDER BY $tabel ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Status Siswa') {	
	$query = "SELECT $tabel FROM jbsakad.statussiswa ORDER BY $tabel ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Pekerjaan Ayah' || $dasar == 'Pekerjaan Ibu') {	
	$query = "SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Pendidikan Ayah' || $dasar == 'Pendidikan Ibu') {	
	$query = "SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan ";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);
} elseif ($dasar == 'Penghasilan Orang Tua') {		
	$batas = [0, 1_000_000, 2_500_000, 5_000_000];
	$judul = [1 => '< Rp1jt', 'Rp1jt-Rp2.5jt', 'Rp2.5jt-Rp5jt', '> Rp5jt'];
	$jum = count($judul);
} elseif ($dasar == 'Agama' || $dasar == 'Suku') {		
	$query = "SELECT $tabel FROM jbsumum.$tabel";
	$result = QueryDb($query);
	$jum = @mysqli_num_rows($result);	
} else {	
	$jum = 1;
}

for ($i=1;$i<=$jum;$i++) {	
	$field = "";
	if ($dasar == 'Golongan Darah' || $dasar == 'Jenis Kelamin' || $dasar == 'Kewarganegaraan' ) {		
		$filter = "1 AND s.$tabel = '".$row[$i-1]."'";
	} elseif ($dasar == 'Penghasilan Orang Tua' ) {			
		$field = ", penghasilanayah+penghasilanibu";
		$filter = "1 AND ".$batas[$i-1]." < penghasilanayah+penghasilanibu < ".$batas[$i]." GROUP BY penghasilanayah+penghasilanibu";
		if ($i == $jum) {
			$filter = "1 AND ".$batas[$i-1]." > penghasilanayah+penghasilanibu GROUP BY penghasilanayah+penghasilanibu";
		} 
	} elseif ($dasar == 'Status Aktif') {
		$filter = $row[$i-1];		
	} elseif ($dasar=='Agama' || $dasar=='Suku' || $dasar=='Status Siswa' || $dasar=='Kondisi Siswa' || $dasar=='Pekerjaan Ayah' || $dasar=='Pekerjaan Ibu' || $dasar=='Pendidikan Ayah' || $dasar=='Pendidikan Ibu') {
		$row = @mysqli_fetch_row($result);
		$judul[$i] = $row[0];		
		$filter = "1 AND s.$tabel = '".$row[0]."'";	
	} elseif ($dasar == 'Tahun Kelahiran') {
		$field = ", YEAR(tgllahir)";
		$filter = "1 GROUP BY YEAR(tgllahir)";	
		$j = 1;
		$jum = 0;		
	} elseif ($dasar == 'Usia') {
		$field = ", YEAR(now()) - YEAR(tgllahir)";
		$filter = "1 GROUP BY YEAR(now()) - YEAR(tgllahir)";	
		$j = 1;
		$jum = 0;		
	} else {
		$field =", s.$tabel";
		$filter = "1 GROUP BY s.$tabel";
		$j = 1;
		$jum = 0;
	}
		
	if ($departemen=="-1" && $idproses<0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses AND s.aktif = $filter"; 
	}  
	if ($departemen<>"-1" && $idproses<0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.departemen='$departemen' AND a.replid=s.idproses AND s.aktif = $filter";
	} 
	if ($departemen<>"-1" && $idproses>0) {	
		$query1 = "SELECT COUNT(*) As Jum$field FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.idproses='$idproses' AND a.replid=s.idproses AND a.departemen='$departemen' AND s.aktif = $filter ";
	}
	
	$data[$i] = 0;	
	$result1 = QueryDb($query1);
	$num = @mysqli_num_rows($result1);
	
	while ($row1 = @mysqli_fetch_row($result1)) {
   		$data[$i] = $row1[0];
		if ($dasar=="Asal Sekolah" || $dasar=="Kode Pos Siswa" || $dasar=="Tahun Kelahiran" || $dasar=="Usia") { 
			$data[$j] = $row1[0];
			$judul[$j] = $row1[1];
			$j++;
			$jum = $jum+1;
		}
	} 
}

for ($i=1;$i<=$jum;$i++) {	
	if ($i == 1) {
		$jud = $judul[$i];
	} else {
		$jud = $jud.'/'.$judul[$i];
	}
}
$jud = [$jud];

$color = [1 =>'green@0.5', 'red@0.5', 'yellow@0.5', 'blue@0.5', 'orange@0.5', 'gold@0.5', 'navy@0.5', 'darkblue@0.5', 'darkred@0.5', 'darkgreen@0.5', 'pink@0.5', 'black@0.5', 'gray@0.5'];
//$color = array(1 => "#FF9900@0.5","#e6dc29@0.5","#bd290c@0.5","#3560ae@0.5","#0e7b2d@0.5");
// kuning, merah, biru, hijau, 
//echo $queryL;
//Nih query tuk dapetin nama angkatannya===============

if ($departemen=="-1") {
	if ($idproses < 0){
		$query2 = "SELECT proses FROM jbsakad.prosespenerimaansiswa";
	} else {
		$query2 = "SELECT proses FROM jbsakad.prosespenerimaansiswa WHERE replid = '".$idproses."'";
	}
} else {		
	if ($idproses < 0){
		$query2 = "SELECT proses FROM jbsakad.prosespenerimaansiswa WHERE departemen='$departemen'";
	} else {
		$query2 = "SELECT proses FROM jbsakad.prosespenerimaansiswa WHERE replid = '$idproses' AND departemen='$departemen' ";
	}
}

//'echo 'sql1 '.$query2;


$result2 = QueryDb($query2);
$row2 = @mysqli_fetch_array($result2);
//=====================================================

//if($num == 0) {
 // echo "<table width='100%' height='100%'><tr><td align='center' valign='middle'>
//        <font size='2' face='verdana'>Grafik Batang tidak dapat ditampilkan<br> karena belum ada data siswa<br> untuk
//        Departemen <b>".$_REQUEST['departemen']</b> dan Penerimaan <b>$row2['proses']."</b></font></td></tr></table>";
//}else {


//Buat grafik
mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(450,300,"auto");
$graph->SetScale("textlin");

//seting kanvas
$lab = [$dasar];
$graph->SetShadow();
$graph->img->SetMargin(50,40,50,40);
$graph->xaxis->SetTickLabels($jud);
$graph->xaxis->SetTickSide(SIDE_LEFT);
//$graph->xaxis->SetTickLabels($judul);
//Create bar plots

/*
$plot[1] = new BarPlot($data[1]);
$plot[1]->SetFillColor($color[1]);
//$plot[$i]->SetFillGradient("#918f0d","#e6dc29@0.4",GRAD_VER);
$plot[1]->SetLegend("$judul[1] : $data[1] siswa");
$plot[1]->SetShadow('darkgray@0.5');

$plot[1]->value->Show();
//$plot->value->SetFont(FF_FONT1,FS_BOLD);

$plot[1]->value->SetFormat('%d');
$plot[1]->SetValuePos('center');

$isiplot = $plot[1];

$b1plot->SetFillColor("#e6dc29@0.5");
$b2plot = new BarPlot($data2);
$b2plot->SetFillColor("#bd290c@0.5");
$b3plot = new BarPlot($data3);
$b3plot->SetFillColor("#3560ae@0.5");
$b4plot = new BarPlot($data4);
$b4plot->SetFillColor("#0e7b2d@0.5");

*/
for ($i=1;$i<=$jum;$i++) {
$plot[$i] = new BarPlot($data[$i]);
$plot[$i]->SetFillColor($color[$i]);
//$plot[$i]->SetFillGradient("#918f0d","#e6dc29@0.4",GRAD_VER);
$plot[$i]->SetLegend("$judul[$i] : $data[$i] siswa");
//$plot[$i]->SetLegend("$judul[$i]");
$plot[$i]->SetShadow('darkgray@0.5');

$plot[$i]->value->Show();
//$plot[$i]->value->SetFont(FF_FONT1,FS_BOLD);

$plot[$i]->value->SetFormat('%d');
$plot[$i]->SetValuePos('center');
$plot[$i]->value->SetAlign('center','center');
//$graph->Add($plot[$i]);

}

$bplot = array_slice($plot,0,$jum);
$gbplot = new GroupBarPlot($bplot);

//memasukkan kedalam grafik
//$graph->Add($graph);
$graph->Add($gbplot);

$graph->title->Set("Statistik Siswa Aktif Berdasarkan $dasar");
$graph->xaxis->title->Set($dasar);
$graph->yaxis->title->Set("Jumlah Siswa");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

//Pengaturan sumbu x dan sumbu y
$graph->yaxis->HideZeroLabel();
$graph->ygrid->SetFill(true,'#dedede','#FFFFFF');

//Menamplikan ke browser
$graph->Stroke();
//}
?>