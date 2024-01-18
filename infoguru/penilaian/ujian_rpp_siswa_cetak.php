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
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../script/as-diagrams.php');
require_once('../include/getheader.php');
if(isset($_REQUEST["rpp"]))
	$rpp = $_REQUEST["rpp"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["ujian"]))
	$ujian = $_REQUEST["ujian"];
	
OpenDb();
$sql="SELECT DISTINCT r.rpp, p.nama, t.departemen, r.idpelajaran, r.idsemester, j.jenisujian, k.kelas, s.semester, t.tingkat FROM pelajaran p, rpp r, ujian u, jenisujian j, kelas k, semester s, tingkat t WHERE p.replid=r.idpelajaran AND r.replid= u.idrpp AND j.replid = '$ujian' AND u.idrpp = '$rpp' AND u.idjenis = j.replid AND k.replid = '$kelas' AND u.idkelas = k.replid AND u.idsemester = s.replid AND k.idtingkat = t.replid";

$result=QueryDb($sql);
$row = mysqli_fetch_array($result);
$materi = $row['rpp'];
$namapel = $row['nama'];
$pelajaran = $row['idpelajaran'];
$semester = $row['idsemester'];
$jenisujian = $row['jenisujian'];
$departemen = $row['departemen'];
$namakelas = $row['kelas'];
$namasemester = $row['semester'];
$namatingkat = $row['tingkat'];
	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
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
    <td>
    <table width="100%" border="0">
  <tr>
    <td width="8%">Departemen</td>
    <td width="39%">:&nbsp;<?=$departemen?></td>
    <td width="4%">Pelajaran</td>
    <td width="49%">:&nbsp;<?=$namapel?></td>
  </tr>
  <tr>
    <td>Semester</td>
    <td>:&nbsp;<?=$namasemester?></td>
    <td>Ujian</td>
    <td>:&nbsp;<?=$jenisujian?></td>
  </tr>
  <tr>
    <td>Kelas</td>
    <td>:&nbsp;<?=$namatingkat." - ".$namakelas?></td>
    <td>RPP</td>
    <td>:&nbsp;<?=$materi?></td>
  </tr>  
  <tr>
    	<td align="center" colspan="4">
 
<?php       
		OpenDb();
		$sql1 = "SELECT s.nis, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2) FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = '$semester' AND u.idkelas = '$kelas' AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran = '$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.idkelas = '$kelas' AND s.aktif = 1 GROUP BY s.nis ORDER BY s.nama";
		
		$result1 = QueryDb($sql1);		
		//echo $sql1;
		$data_title = "<font size='4'>Statistik Rata-rata Nilai Ujian Siswa per RPP</font>"; // title for the diagram

        // sample data array
        //$data = array();
		$cnt = 0;
        while($row1 = mysqli_fetch_row($result1)) {
            //$data[] = array($row1[1],$row1[2],$row1[3],$row1[4],$row1[5]);			
            //$legend_x[] = $row1[0];			
			$legend_x[] = ++$cnt;
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
        <!--<img src="statistik_batang.php?semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&ujian=<?=$ujian?>&rpp=<?=$rpp?>" />-->
        
	
        </td>
	</tr>
    
  
  <tr>
    <td colspan="4">
    	<table width="100%" border="1" class="tab" id="table" bordercolor="#000000">
  <tr>
    <td height="30" class="headerlong"><div align="center">No</div></td>
    <td height="30" class="headerlong"><div align="center">NIS</div></td>
    <td height="30" class="headerlong"><div align="center">Nama</div></td>
    <td height="30" class="headerlong"><div align="center">Rata-rata RPP</div></td>
  </tr>
  <?php
  $sql_siswa="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1 order by nama";
  $result_siswa=QueryDb($sql_siswa);
 
  	$cnt=0;
  	while ($row_siswa=@mysqli_fetch_array($result_siswa)){
  		//$sql_jum_nil="SELECT SUM(r.rataUS) as nilaiUS, COUNT(r.rataUS) as jum FROM jbsakad.rataus r,jbsakad.ujian u,jbsakad.rpp rpp WHERE r.nis='".$row_siswa['nis']."' AND r.idjenis='$ujian' AND u.idrpp='$rpp' AND u.idjenis=r.idjenis AND r.idpelajaran='$pelajaran' AND r.idkelas='$kelas'";
		$sql2 = "SELECT round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2), SUM(nilaiujian) FROM nilaiujian n, siswa s, ujian u, jenisujian j WHERE n.idujian = u.replid AND u.idsemester = = '".$semester."' AND u.idkelas = '$kelas' AND u.idjenis = '$ujian' AND u.idrpp = '$rpp' AND u.idpelajaran ='$pelajaran' AND s.nis = n.nis AND u.idjenis = j.replid AND s.nis = '".$row_siswa['nis']."' AND s.aktif = 1 ORDER BY s.nama";
  		$result2 = QueryDb($sql2);
			$row2 = mysqli_fetch_row($result2);
    ?>
      	<tr>
        	<td height="25" align="center"><?=++$cnt?></td>
        	<td height="25"><div align="center"><?=$row_siswa['nis']?></div></td>
        	<td height="25"><?=$row_siswa['nama']?></td>
            <td height="25" align="center"><?php
            	if ($row2[1] > 0) 
					echo $row2[0];
				else 
					echo '0';		
				//echo $row['rata'];
					?></td>
      	</tr>
<?php 	}  ?>
        </table>
</td>
  </tr>
</table>
    </td>
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