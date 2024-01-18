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
require_once('../include/config.php');
require_once('../include/db_functions.php');
include("../library/class/jpgraph.php");
include("../library/class/jpgraph_pie.php");
include("../library/class/jpgraph_pie3d.php");
OpenDb();
$idproses=(int)$_REQUEST['idproses'];
$departemen=$_REQUEST['departemen'];
$iddasar = $_REQUEST['iddasar'];
//query untuk kelamin pria
/**/
if ($departemen=="-1" && $idproses<0)
	$kondisi=" AND a.replid=s.idproses ";
if ($departemen<>"-1" && $idproses<0)
	$kondisi=" AND a.departemen='$departemen' AND a.replid=s.idproses ";
if ($departemen<>"-1" && $idproses>0)
	$kondisi=" AND s.idproses='$idproses' AND a.replid=s.idproses AND a.departemen='$departemen' ";
if ($iddasar=="12"){	
		$query1 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu<1000000 $kondisi";
		$query2 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=1000000 AND s.penghasilanayah+s.penghasilanibu<2500000 $kondisi";
		$query3 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=2500000 AND s.penghasilanayah+s.penghasilanibu<5000000 $kondisi";
		$query4 = "SELECT COUNT(*) As Jum FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE a.replid=s.idproses  AND s.aktif = '1' AND s.penghasilanayah+s.penghasilanibu>=5000000 $kondisi";
	
$result1 = QueryDb($query1);
$row1 = @mysqli_fetch_array($result1);
$j1 = $row1['Jum'];


$result2 = QueryDb($query2);
$row2 = @mysqli_fetch_array($result2);
$j2 = $row2['Jum'];

$result3 = QueryDb($query3);
$row3 = @mysqli_fetch_array($result3);
$j3 = $row3['Jum'];

$result4 = QueryDb($query4);
$row4 = @mysqli_fetch_array($result4);
$j4 = $row4['Jum'];


$sum = $j1 + $j2 +$j3 + $j4;
$data = [$j1, $j2, $j3, $j4];
$leg = ["< Rp 1.000.000", "Rp 1.000.000 s/d Rp 2.500.000", "Rp 2.500.000 s/d Rp 5.000.000", "> Rp 5.000.000"];
}
if ($iddasar=="1"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Agama";
$query1 = "SELECT COUNT(s.replid) As Jum, s.agama FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.agama";
}

if ($iddasar=="2"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Asal Sekolah";
$query1 = "SELECT COUNT(s.replid) As Jum, s.asalsekolah FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.asalsekolah";
}

if ($iddasar=="3"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Golongan Darah";
$query1 = "SELECT COUNT(s.replid) As Jum, s.darah FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.darah";
}

if ($iddasar=="4"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Jenis Kelamin";
$query1 = "SELECT COUNT(s.replid) As Jum, s.kelamin FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.kelamin";
}

if ($iddasar=="5"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Kewarganegaraan";
$query1 = "SELECT COUNT(s.replid) As Jum, s.warga FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.warga";
}

if ($iddasar=="6"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Kode Pos Siswa";
$query1 = "SELECT COUNT(s.replid) As Jum, s.kodepossiswa FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.kodepossiswa";
}

if ($iddasar=="7"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Kondisi Siswa";
$query1 = "SELECT COUNT(s.replid) As Jum, s.kondisi FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.kondisi";
}

if ($iddasar=="8"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Pekerjaan Ayah";
$query1 = "SELECT COUNT(s.replid) As Jum, s.pekerjaanayah FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.pekerjaanayah";
}

if ($iddasar=="9"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Pekerjaan Ibu";
$query1 = "SELECT COUNT(s.replid) As Jum, s.pekerjaanibu FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.pekerjaanibu";
}

if ($iddasar=="10"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Pendidikan Ayah";
$query1 = "SELECT COUNT(s.replid) As Jum, s.pendidikanayah FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.pendidikanayah";
}

if ($iddasar=="11"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Pendidikan Ibu";
$query1 = "SELECT COUNT(s.replid) As Jum, s.pendidikanibu FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.pendidikanibu";
}



if ($iddasar=="13"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Status Aktif";
$query1 = "SELECT COUNT(s.replid) As Jum, s.aktif FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.aktif";
}

if ($iddasar=="14"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Status Siswa";
$query1 = "SELECT COUNT(s.replid) As Jum, s.status FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.status";
}

if ($iddasar=="15"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Suku";
$query1 = "SELECT COUNT(s.replid) As Jum, s.suku FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY s.suku";
}

if ($iddasar=="16"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Tahun Kelahiran";
$query1 = "SELECT COUNT(s.replid) As Jum, YEAR(s.tgllahir) as thnlahir FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY thnlahir";
}

if ($iddasar=="17"){
$titlenya="Statistik Calon Siswa Aktif Berdasarkan Usia";
$query1 = "SELECT COUNT(s.replid) As Jum, YEAR(now())-YEAR(s.tgllahir) As usia FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa a WHERE s.aktif = 1 $kondisi GROUP BY usia";
}
	if ($iddasar!="12"){
	//7/31/2008$titlenya="Statistik Calon Siswa Aktif Berdasarkan Penghasilan Orang Tua";
	$result1 = QueryDb($query1);
	$num = @mysqli_num_rows($result1);
	while ($row1 = @mysqli_fetch_row($result1)) {
	$data[] = $row1[0];
	$leg[] = $row1[1];
    $color = ['red', 'black', 'green', 'blue', 'gray', 'white'];
	}
	}
//=====================================================
if ($iddasar=="12"){
if($sum == 0) {
  echo "<table width='100%' height='100%'><tr><td align='center' valign='middle'>
        <font size='2' face='verdana'>Grafik Lingkaran tidak dapat ditampilkan<br> karena belum ada data siswa<br> untuk Departemen <b>".$_REQUEST['departemen']."</b> dan Angkatan <b>".$row['angkatan']."</b></font></td></tr></table>";
}else {
//data

//Buat grafik
$graph = new PieGraph(450,300,"auto");
$graph->img->SetAntiAliasing();

$graph->SetShadow();

$graph->title->Set("Statistik Calon Siswa Aktif Berdasarkan Penghasilan Orang Tua");

$graph->title->SetFont(FF_FONT1,FS_BOLD);

$plot = new PiePlot3D($data);
$plot->ExplodeAll();
$plot->SetTheme("pastel");
$plot->SetShadow('darkgray@0.5');
$plot->SetLegends($leg);
$plot->SetSize(0.4);
$plot->SetCenter(0.45);

// Enable and set policy for guide-lines. Make labels line up vertically

//memasukkan kedalam grafik
$graph->Add($plot);
//Menamplikan ke browser
$graph->Stroke();
}
} else {
if($num == 0) {
  echo "<table width='100%' height='100%'><tr><td align='center' valign='middle'>
        <font size='2' face='verdana'>Grafik Lingkaran tidak dapat ditampilkan<br> karena belum ada data siswa<br> untuk Departemen <b>".$_GET['departemen']."</b> dan Angkatan <b>".$row['Angkatan']."</b></font></td></tr></table>";
}else {

//Buat grafik
$graph = new PieGraph(450,300,"auto");
$graph->img->SetAntiAliasing();
$graph->SetShadow();

$graph->title->Set($titlenya);

$graph->title->SetFont(FF_FONT1,FS_BOLD);

$plot = new PiePlot($data);
$plot->ExplodeAll();
$plot->SetShadow('darkgray@0.5');
$plot->SetTheme("pastel");
$plot->SetLegends($leg);
$plot->SetCenter(0.4);

// Enable and set policy for guide-lines. Make labels line up vertically
$plot->SetGuideLines(true,false);
$plot->SetGuideLinesAdjust(1.1);

//memasukkan kedalam grafik
$graph->Add($plot);
//Menamplikan ke browser
$graph->Stroke();
}
}
?>