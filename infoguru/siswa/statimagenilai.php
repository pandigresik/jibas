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
// start include file =============================================================================
include_once '../../vendor/autoload.php';
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = $_REQUEST['tingkat'];
$semester = $_REQUEST['semester'];
$pelajaran = $_REQUEST['pelajaran'];
$dasarpenilaian = $_REQUEST['dasarpenilaian'];	

// end include file ===============================================================================

OpenDb();
		$a=0;
		$sql	=	"SELECT MIN(nilaiangka) as nmin, MAX(nilaiangka) AS nmax ".
					"FROM nap n, aturannhb a, infonap i, kelas k ".
					"WHERE n.idaturan = a.replid ".
					"AND a.idtingkat = '$tingkat' ".
					"AND a.idpelajaran = '$pelajaran' ".
					"AND a.dasarpenilaian = '$dasarpenilaian' ".
					"AND n.idinfo = i.replid ".
					"AND i.idpelajaran = '$pelajaran' ".
					"AND i.idsemester = '$semester' ".
					"AND i.idkelas = k.replid ".
					"AND k.idtahunajaran = '$tahunajaran' ".
					"AND k.idtingkat = '$tingkat' ";	
		//echo $sql;
		$result=Querydb($sql);
		$row = @mysqli_fetch_array($result);
		
		if(($row['nmin'] >= 0) AND ($row['nmax'] <= 10)){
			$dasar = '1'; //satuan
		}else{
			$dasar = '10'; //satuan
		}
		
		$rentang = [9*$dasar, 8*$dasar, 7*$dasar, 6*$dasar, 5*$dasar, 4*$dasar, 3*$dasar, 2*$dasar, 1*$dasar];
		
		$query = "SELECT SUM(IF(nilaiangka >= $rentang[0],1,0)) as j1, ".
		         "SUM(IF(nilaiangka>=$rentang[1] AND nilaiangka<$rentang[0],1,0)) as j2, ".
				 "SUM(IF(nilaiangka>=$rentang[2] AND nilaiangka<$rentang[1],1,0)) as j3, ".
				 "SUM(IF(nilaiangka>=$rentang[3] AND nilaiangka<$rentang[2],1,0)) as j4, ".
				 "SUM(IF(nilaiangka>=$rentang[4] AND nilaiangka<$rentang[3],1,0)) as j5, ".
				 "SUM(IF(nilaiangka>=$rentang[5] AND nilaiangka<$rentang[4],1,0)) as j6, ".
				 "SUM(IF(nilaiangka>=$rentang[6] AND nilaiangka<$rentang[5],1,0)) as j7, ".
				 "SUM(IF(nilaiangka>=$rentang[7] AND nilaiangka<$rentang[6],1,0)) as j8, ".
				 "SUM(IF(nilaiangka<$rentang[8],1,0)) as j9 ".
				 "FROM nap n, aturannhb a, infonap i, kelas k ".
				 "WHERE n.idaturan = a.replid ".
					"AND a.idtingkat = '$tingkat' ".
					"AND a.idpelajaran = '$pelajaran' ".
					"AND a.dasarpenilaian = '$dasarpenilaian' ".
					"AND n.idinfo = i.replid ".
					"AND i.idpelajaran = '$pelajaran' ".
					"AND i.idsemester = '$semester' ".
					"AND i.idkelas = k.replid ".
					"AND k.idtahunajaran = '$tahunajaran' ".
					"AND k.idtingkat = '$tingkat' ";	
		//echo $query;
		$result=Querydb($query) or die(mysqli_error($mysqlconnection));
		
		if(mysqli_num_rows($result)==0){
				$data[$a]=0;	
		}else{
			
				$lab=[">=90", ">=80", ">=70", ">=60", ">=50", ">=40", ">=30", ">=20", ">=10"];
				while($fetch=@mysqli_fetch_array($result)){			
					$data = [$fetch['J1'], $fetch['J2'], $fetch['J3'], $fetch['J4'], $fetch['J5'], $fetch['J6'], $fetch['J7'], $fetch['J8'], $fetch['J9']];
				}
			
		}
		
		$color = ['#cd9b9b', '#7d26cd', '#8b1c62', '#b03060', '#faf0e6', '#ff69b4', '#d2d2d2', '#7fff00', '#00bfff', '#ff1493', '#6e8b3d', '#b8860b', '#00ffff', '#dcdcdc', '#00c5cd', '#a52a2a'];

	//=====================================================
	mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(550,275,"auto");
	$graph->SetScale("textlin");
	
	//setting kanvas
	$graph->SetShadow();
	$graph->img->SetMargin(80,40,50,40);
	$graph->xaxis->SetTickLabels($lab);
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
	
	$graph->title->Set("Statistik Perolehan Nilai Rapor");
	$graph->xaxis->title->Set("Rentang Nilai");
	$graph->yaxis->title->Set("Jumlah Siswa");
	
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	
	//Pengaturan sumbu x dan sumbu y
	$graph->yaxis->HideZeroLabel();
	$graph->ygrid->SetFill(true,'#dedede','#FFFFFF');
	
	//Menamplikan ke browser
	$graph->Stroke();

?>