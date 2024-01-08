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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

OpenDb();

if (SI_USER_LEVEL() == $SI_USER_STAFF) {
	$dis_text="disabled class='disabled'";
	$dis="disabled";
}

$urut = $_REQUEST['urut'];
if ($urut=="")
	$urut="nama";
else 
	$urut = $_REQUEST['urut'];

$urutan = $_REQUEST['urutan'];
if ($urutan=="")
	$urutan="asc";
else 
	$urutan = $_REQUEST['urutan'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['pelajaran'])) 
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['jum']))
	$jum = $_REQUEST['jum'];

if (isset($_REQUEST['hapus'])) 
{
	for ($i = 1; $i <= $jum; $i++) 
	{
		$replid = $_REQUEST['replid'.$i];
		$sql = "UPDATE jbsakad.komennap SET komentar='' WHERE replid = '".$replid."'";
		//echo $sql;
		$res=QueryDb($sql);
	}
	
	if ($res)
	{
		?>
		<script language="javascript" type="text/javascript">
			alert ('Komentar sudah dihapus');
		</script>
		<?php 
	}
}
		
$sql = "SELECT s.nis, s.nama
			 FROM siswa s
			WHERE s.idkelas = $kelas AND aktif = 1  
		ORDER BY $urut $urutan";
$result = QueryDb($sql);		
$cnt = 1;
$jum = @mysqli_num_rows($result); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/aTR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Komentar Nilai Rapor</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() 
{	
	document.location.href="komentar_lihat.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function ubah(replid,state) 
{	
	newWindow('ubah_komentar.php?replid='+replid+'&state='+state,'UbahKomentar',684,394,'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function ver() 
{
	if (confirm('Anda yakin akan menghapus seluruh komentar di Kelas ini?'))	
		return true;
	else 
		return false;
}

function change_urut(urut,urutan) 
{
	if (urutan == "asc")
		urutan = "desc";
	else 
		urutan = "asc";
	document.location.href="komentar_lihat.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&urut="+urut+"&urutan="+urutan;
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
}
.style2 {color: #FFFF00}
.style3 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>
<body topmargin="0" leftmargin="0">
<form name="main" method="post" action="komentar_lihat.php" enctype="multipart/form-data" onSubmit="return ver()">
<div align="right"><input <?=$dis?> type="submit" name="hapus" class="but" value="Hapus Komentar Kelas Ini" onClick="hapus()" /></div><br>
<table width="100%" border="1" cellspacing="0" class="tab" id="table" style="border-width: 1px; border-collapse: collapse; border-color: #f5f5f5;">
  <tr>		
	<th width="3%" height="30" align="center"  background="../style/formbg2.gif" ><span class="style3">No</span></td>
	<th height="30" onMouseOver="background='../style/formbg2agreen.gif';height=30;"
        onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;"
        onClick="change_urut('nis','<?=$urutan?>');"><div align="center" class="style1"><strong><span class="style2">NIS&nbsp;</span>&nbsp;
         <?php if ($urut=="nis"){
				if ($urutan=="asc"){
					echo "<img src='../images/ico/descending copy.png' />";
				} else {
					echo "<img src='../images/ico/ascending copy.png' />";
				} 
			} else {
				echo "<img src='../images/ico/blank.gif' />";
			} ?>
			</strong></div></td>
		  <th height="30" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>');"><div align="center" class="style1"><strong><span class="style2">Nama&nbsp;</span>&nbsp;
	          <?php if ($urut=="nama"){
		if ($urutan=="asc"){
			echo "<img src='../images/ico/descending copy.png' />";
		} else {
			echo "<img src='../images/ico/ascending copy.png' />";
		} } else {
		echo "<img src='../images/ico/blank.gif' />";
		}
		?>
		  </strong></div></td>
          <th background="../style/formbg2.gif"><div align="center"><span class="style3">Komentar</span></div></td>
          <th background="../style/formbg2.gif">&nbsp;</td>
		</tr>
		 <?php 	if ($jum > 0) { 
		while ($row = @mysqli_fetch_array($result)) {	?>
		<tr>        			
			<td height="25" align="center"><?=$cnt?></td>
			<td height="25" align="center">
         	<div align="left"><?=$row['nis']?></div>
            <input type="hidden" name="nis<?=$cnt?>" value="<?=$row['nis']?>">
         </td>
  			<td height="25"><div align="left"><?=$row['nama']?></div></td>
            <td height="25"><div align="left">
<?php 			$sql = "SELECT k.komentar, k.replid 
                       FROM jbsakad.komennap k, jbsakad.infonap i 
							 WHERE k.nis = '".$row['nis']."' AND i.replid = k.idinfo 
							   AND i.idpelajaran = '$pelajaran' AND i.idsemester = '$semester' AND i.idkelas = '".$kelas."'";
				$res2 = QueryDb($sql);
				$row2 = @mysqli_fetch_row($res2);
				$ada_komentar = $row2[0];
            if ($ada_komentar <> "")
				{
				   echo "<input type='hidden' name='replid$cnt' value='".$row2[1]."'>";
				   echo $row2[0];
				}
			  	else 
				{
				   echo "<input type='hidden' name='replid$cnt' value='0'>";
 				   echo "<font color='#9a9a9a'>Belum ada komentar</font>";  
				}
?>
            </div></td>
            <td height="25"><div align="center">
              <?php
			//if (SI_USER_LEVEL() != $SI_USER_STAFF) {
			if ($ada_komentar<>"")
			{ ?>
              <img src="../images/ico/ubah.png" border="0" onClick="ubah('<?=$row2[1]?>','1')" style="cursor:pointer" title="Ubah Komentar Siswa Ini"/>	
<?php 	   } 
			else
			{ ?>
				<img src="../images/ico/tambah.png" border="0" onClick="ubah('<?=$row2[1]?>','0')" style="cursor:pointer" title="Input Komentar Siswa Ini"/>
<?php 		} ?>            	
		  </div></td>
        </tr>
		<?php 	$cnt++;
		}
		} else { 
  ?> 
  <tr><td colspan="5" ><div align="center">Tidak ada siswa</div></td></tr>
<?php } ?>
</table>

<input type="hidden" name="jum" id="jum" value="<?=$cnt-1?>">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />   	
</form>
</body>
</html>  

<?php
CloseDb();
?>