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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../script/as-diagrams.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../include/getheader.php');
if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];
if(isset($_REQUEST["rpp"]))
	$rpp = $_REQUEST["rpp"];
if(isset($_REQUEST["ujian"]))
	$ujian = $_REQUEST["ujian"];

OpenDb();
$sql="SELECT DISTINCT r.rpp, p.nama, t.departemen, r.idpelajaran, r.idsemester, j.jenisujian, s.semester, t.tingkat FROM pelajaran p, rpp r, ujian u, jenisujian j, kelas k, semester s, tingkat t WHERE p.replid=r.idpelajaran AND r.replid= u.idrpp AND j.replid = '$ujian' AND u.idrpp = '$rpp' AND u.idjenis = j.replid AND u.idkelas = k.replid AND u.idsemester = s.replid AND k.idtingkat = t.replid AND t.replid = $tingkat";

$result=QueryDb($sql);
$row = mysqli_fetch_array($result);
$materi = $row['rpp'];
$namapel = $row['nama'];
$pelajaran = $row['idpelajaran'];
$semester = $row['idsemester'];
$jenisujian = $row['jenisujian'];
$departemen = $row['departemen'];
$namasemester = $row['semester'];
$namatingkat = $row['tingkat'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<title></title>
</head>
<body>

<table width="100%" border="0">
  <tr>
    <td><?=getHeader($departemen)?></td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
  <tr>
    <td width="8%">Departemen</td>
    <td width="27%">:&nbsp;<?=$departemen?></td>
    <td width="6%">Pelajaran</td>
    <td width="59%">:&nbsp;<?=$namapel?></td>
  </tr>
  <tr>
    <td>Semester</td>
    <td>:&nbsp;<?=$namasemester?></td>
    <td>Ujian</td>
    <td>:&nbsp;<?=$jenisujian?></td>
  </tr>
  <tr>
    <td>Tingkat</td>
    <td>:&nbsp;<?=$namatingkat?></td>
    <td>RPP</td>
    <td>:&nbsp;<?=$materi?></td>
  </tr>
  <tr>
   		<td align="center" colspan="4">
        <?php 	
		OpenDb();
		$sql1 = "SELECT k.kelas, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)*COUNT(DISTINCT s.nis)),2) FROM nilaiujian n, siswa s, ujian u, jenisujian j, kelas k, tahunajaran a WHERE n.idujian = u.replid AND u.idsemester = '$semester' AND u.idkelas = k.replid AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran = '$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = k.replid AND s.aktif = 1 AND k.idtingkat = '$tingkat' AND k.aktif = 1 AND k.idtahunajaran = a.replid AND a.aktif = 1 GROUP BY k.replid ORDER BY k.kelas, u.tanggal, s.nama";
       
		$result1 = QueryDb($sql1);		
		$data_title = "<font size='4'>Statistik Rata-rata Nilai Ujian Kelas per RPP</font>"; // title for the diagram

        // sample data array
        //$data = array();

        while($row1 = mysqli_fetch_row($result1)) {
            //$data[] = array($row1[1],$row1[2],$row1[3],$row1[4],$row1[5]);			
            $legend_x[] = $row1[0];			
			$data[] = [$row1[1]];
			//$data[] = $row1[1];
        }
		$legend_y = ['Rata'];
		//$legend_y = 'Rata';
				
        $graph = new CAsBarDiagram;
        $graph->bwidth = 10; // set one bar width, pixels
        //$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
        $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
        
		$graph->precision = 1;  // decimal precision
        // call drawing function
        $graph->DiagramBar($legend_x, $legend_y, $data, $data_title);
		//$graph->DiagramBar('X-A', 'rata2', '47.47', $data_title);
		?>
      
		
        </td>
  
  </tr>
</table></td>
  </tr>
</table>


</body>
<script language="javascript">
window.print();
</script>
</html>
<?php
CloseDb();
?>