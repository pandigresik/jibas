<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 2.6.0 (January 14, 2012)
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
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once("../include/sessionchecker.php");

$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['idkategori'])){
?>
<script language="javascript">
	parent.catatansiswacontent.location.href="catatansiswacontent.php?idkategori=<?=$_REQUEST['idkategori']?>&tahunajaran=<?=$tahunajaran?>&nis=<?=$nis?>";
</script>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function chg(){
	var nis = document.getElementById('nis').value;
	var ta = document.getElementById('tahunajaran').value;
	parent.catatansiswacontent.location.href="../blank.php";
	document.location.href="catatansiswamenu.php?nis="+nis+"&tahunajaran="+ta;
}
function willshow(idkategori){
	var nis = document.getElementById('nis').value;
	var ta = document.getElementById('tahunajaran').value;
	document.location.href="catatansiswamenu.php?nis="+nis+"&tahunajaran="+ta+"&idkategori="+idkategori;
}
function show(idkategori){
	var nis = document.getElementById('nis').value;
	var ta = document.getElementById('tahunajaran').value;
	parent.catatansiswacontent.location.href="catatansiswacontent.php?idkategori="+idkategori+"&tahunajaran="+ta+"&nis="+nis;
}
function inputbaru(){
	var nis = document.getElementById('nis').value;
	parent.catatansiswacontent.location.href="catatansiswaadd.php?nis="+nis;
}
</script>
</head>

<body leftmargin="2" topmargin="10" style="background-color: #d7efff;">

<center>
<input name="inputbaru" onClick="inputbaru()" type="button" class="but"
       value="Catatan Siswa Baru (+)"
       style="font-size: 14px; height: 24px;"/>
</center>

<input name="nis" id="nis" type="hidden"  value="<?=$nis?>" />
<br /><br />
<fieldset style="border-color: #ededed;">
<legend style="font-size: 14px;">Riwayat Catatan Siswa</legend>
<br>
<table width="320" border="0" cellspacing="0">
  <tr>
    <td width="102"><strong>Tahun Ajaran</strong></td>
    <td width="214">
	<select name="tahunajaran" id="tahunajaran" onChange="chg()">
    <?php
	OpenDb();
	$sql="SELECT t.replid,t.tahunajaran,t.aktif FROM jbsakad.tahunajaran t, jbsakad.kelas k, jbsakad.riwayatkelassiswa r WHERE k.replid=r.idkelas AND k.idtahunajaran=t.replid AND r.nis='$nis' GROUP BY t.replid ";
	$result=QueryDb($sql);
	if (@mysqli_num_rows($result)==0){
		echo "<option value=''>Tidak ada Data</option>";
	} else {
		while ($row=@mysqli_fetch_array($result)){
			if ($tahunajaran=="")
				$tahunajaran=$row[0];
			$akt="";
			if ($row['aktif']==1)
				$akt="(A)";
			echo "<option value='".$row[0]."'".StringIsSelected($row[0],$tahunajaran).">".$row[1]." ".$akt."</option>";
		}
	}
	CloseDb();
	?>
	</select>
	</td>
  </tr>
</table>
<br>
<table width="320" border="1" cellspacing="0" class="tab" id="table" bordercolor="#000000" >
  <tr>
    <td width="29" height="30" class="header"><div align="center">No.</div></td>
    <td width="221" height="30" class="header">Kategori</td>
    <td width="38" height="30" class="header"><div align="center">#</div></td>
    <td width="24" height="30" class="header"><div align="center"></div></td>
  </tr>
  <?php
	OpenDb();
	$sql = "SELECT * FROM jbsvcr.catatankategori WHERE aktif=1 ORDER BY replid";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0){
	$cnt=1;
	while ($row=@mysqli_fetch_array($result)){
		$sql_catsiswa="SELECT COUNT(c.replid) as jumlah FROM jbsvcr.catatansiswa c, jbsakad.kelas k, jbsakad.tahunajaran t WHERE c.idkategori='".$row['replid']."' AND c.nis='$nis' AND c.idkelas=k.replid AND t.replid='$tahunajaran' AND k.idtahunajaran=t.replid";
		//echo $sql_catsiswa;
		$res_catsiswa=QueryDb($sql_catsiswa);
		$row_catsiswa=@mysqli_fetch_row($res_catsiswa);
  ?>
  <tr>
    <td height="25"><div align="center"><?=$cnt?></div></td>
    <td height="25"><?=$row['kategori']?></td>
    <td height="25"><div align="center"><?=$row_catsiswa[0]?></div></td>
    <td height="25"><div align="center"><img src="../images/ico/panahkanan.png" style="cursor:pointer" onClick="show('<?=$row['replid']?>')" /><!--<input style="width:20px;" type="button" onClick="show('<?=$row['replid']?>')" class="but" value="&gt;" />-->
    </div></td>
  </tr>
  <?php $cnt++; } } else { ?>
  <tr>
    <td colspan="4"><div align="center">Tidak ada kategori catatan</div></td>
  </tr>
  <?php } CloseDb(); ?>
</table>

</fieldset>
</body>
</html>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>