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
//require_once('../include/errorhandler.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');

$nis_awal = $_REQUEST['nis_awal'];

$departemen = 0;
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
//echo 'departemen '.$departemen;

OpenDb();

$sql = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '$nis_awal'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$dep[0] = [$row['departemen'], $nis_awal];
//$no[1] = $row['nislama'];
if ($row['nislama'] <> "") {
	$sql1 = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '".$row['nislama']."'";
	$result1 = QueryDb($sql1);
	$row1 = @mysqli_fetch_array($result1);	
	$dep[1] = [$row1['departemen'], $row['nislama']];
	//$no[2] = $row1['nislama'];	
	if ($row1['nislama'] <> "") {				
		$sql2 = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '".$row1['nislama']."'";
		$result2 = QueryDb($sql2);
		$row2 = @mysqli_fetch_array($result2);					
		$dep[2] = [$row2['departemen'], $row1['nislama']] ;
	}	
}		

$nis = $dep[$departemen][1];

$sql_ajaran = "SELECT DISTINCT(t.replid), t.tahunajaran FROM riwayatkelassiswa r, kelas k, tahunajaran t WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtahunajaran = t.replid ORDER BY t.aktif DESC";

$result_ajaran = QueryDb($sql_ajaran);
$k = 0;
while ($row_ajaran = @mysqli_fetch_array($result_ajaran)) {
	$ajaran[$k] = [$row_ajaran['replid'], $row_ajaran['tahunajaran']];
	$k++;
}

$tahunajaran = $ajaran[0][0];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];

$sql_kls = "SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, k.idtahunajaran FROM riwayatkelassiswa r, kelas k, tingkat t WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtingkat = t.replid";

$result_kls = QueryDb($sql_kls);
$j = 0;
while ($row_kls = @mysqli_fetch_array($result_kls)) {
	$kls[$j] = [$row_kls['idkelas'], $row_kls['kelas'], $row_kls['tingkat'], $row_kls['idtahunajaran']];
	if ($row_kls['idtahunajaran']==$tahunajaran)
		$kelas = $row_kls['idkelas'];
	$j++;
}



//$kelas = $kls[0][0];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Penilaian Pelajaran</title>
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript">

function change_dep() {
	var nis = document.getElementById("nis").value;
	var nis_awal = document.getElementById("nis_awal").value;
	var departemen = document.getElementById("departemen").value;		
	document.location.href = "lap_pelajaran_menu.php?departemen="+departemen+"&nis="+nis+"&nis_awal="+nis_awal;
	parent.isi.location.href = "blank_lap_pelajaran.php";
}

/*function change() {
	var nis = document.getElementById("nis").value;
	var nis_awal = document.getElementById("nis_awal").value;
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var kelas = document.getElementById("kelas").value;
	
	document.location.href = "lap_pelajaran_menu.php?departemen="+departemen+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&nis="+nis+"&nis_awal="+nis_awal;
	parent.isi.location.href = "blank_lap_pelajaran.php";
}*/
function change_kls() {
	var nis = document.getElementById("nis").value;
	var nis_awal = document.getElementById("nis_awal").value;
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var kelas = document.getElementById("kelas").value;
	
	document.location.href = "lap_pelajaran_menu.php?departemen="+departemen+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&nis="+nis+"&nis_awal="+nis_awal;
	parent.isi.location.href = "blank_lap_pelajaran.php";
}

function change_ta() {
	var nis = document.getElementById("nis").value;
	var nis_awal = document.getElementById("nis_awal").value;
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	//var kelas = document.getElementById("kelas").value;
	
	document.location.href = "lap_pelajaran_menu.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&nis="+nis+"&nis_awal="+nis_awal;
	parent.isi.location.href = "blank_lap_pelajaran.php";
}

function refresh() {	
	document.location.reload();
}
function tampil(pelajaran,kelas,nis,departemen) {	
	parent.isi.location.href="lap_pelajaran_content.php?pelajaran="+pelajaran+"&kelas="+kelas+"&nis="+nis+"&departemen="+departemen;
}

</script>
</head>

<body>
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="nis_awal" id="nis_awal" value="<?=$nis_awal?>">
<table border="0" width="100%" align="center" >
<!-- TABLE CENTER -->
<tr>	
	<td width="38%"><strong>Departemen </strong></td>
    <td width="*"> 
    	<select name="departemen" id="departemen" onChange="change_dep()" style="width:100%">
		<?php for ($i=0;$i<sizeof($dep);$i++) {	?>        	
            <option value="<?=$i ?>" <?=IntIsSelected($i, $departemen) ?> > <?=$dep[$i][0] ?> </option>
		<?php } ?>
		</select>
    </td>
</tr>

<tr>
	<td><strong>Tahun Ajaran</strong></td>
   	<td><select name="tahunajaran" id="tahunajaran" onchange="change_ta()" style="width:100%">
   		<?php for($k=0;$k<sizeof($ajaran);$k++) {?>
			<option value="<?=$ajaran[$k][0] ?>" <?=IntIsSelected($ajaran[$k][0], $tahunajaran) ?> > 
			<?=$ajaran[$k][1]?> </option>
		<?php } ?>
    	</select>    
	</td>
</tr>
<tr>
	<td><strong>Kelas </strong></td>
   	<td><select name="kelas" id="kelas" onchange="change_kls()" style="width:100%">
   		<?php for ($j=0;$j<sizeof($kls);$j++) {
				if ($kls[$j][3] == $tahunajaran) {
		?>
			<option value="<?=$kls[$j][0] ?>" <?=IntIsSelected($kls[$j][0], $kelas) ?> > <?=$kls[$j][2]." - ".$kls[$j][1] ?> </option>
		<?php 		}
			} ?>
    	</select>    
	</td>
</tr>   
<tr>
	<td colspan="2"><br />
    <?php
	if ($kelas!=""){
	?>
	<table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="left" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    
    <tr height="30">    	
    	<td width="5%" class="header" align="center">No</td>
        <td width="94%" class="header" align="center">Pelajaran</td>
        <td width="1%" class="header" align="center">&nbsp;</td>
    </tr>
    <?php 	OpenDb();		
		$sql = "SELECT DISTINCT p.replid, p.nama FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY p.nama";
		//echo '<br> sql '.$sql;
		$result = QueryDb($sql); 				
		while ($row = @mysqli_fetch_array($result)) {
	?>
    <tr>   	
       	<td height="25" align="center" onclick="tampil('<?=$row[0]?>','<?=$kelas?>','<?=$nis?>','<?=$dep[$departemen][0]?>')" style="cursor:pointer"><?=++$cnt?></td>
        <td height="25" onclick="tampil('<?=$row[0]?>','<?=$kelas?>','<?=$nis?>','<?=$dep[$departemen][0]?>')" style="cursor:pointer">
        <?=$row[1]?>	</td>
        <td onclick="tampil('<?=$row[0]?>','<?=$kelas?>','<?=$nis?>','<?=$dep[$departemen][0]?>')" style="cursor:pointer"><img src="../../images/ico/panahkanan.png" width="16" height="16" /></td>
    </tr>
    <!-- END TABLE CONTENT -->
    <?php 	} ?>
	</table>
	  
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
	    <?php
	} else {
	?>
	    <div align="center" class="text_merah">Tidak ada data</div>
	    <?php
	}
	?>
    </td>	
</tr>
<!-- END TABLE CENTER -->    
</table> 
   

</body>
</html>