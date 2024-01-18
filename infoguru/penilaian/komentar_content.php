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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once("../sessionchecker.php");

OpenDb();

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
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
if (isset($_REQUEST['komentar']))
	$komentar = $_REQUEST['komentar'];
	
if (isset($_REQUEST['Simpan']))
{
	$komentar = $_REQUEST['komentar'];
	$predikat = $_REQUEST['predikat'];
	$sql_update = "UPDATE jbsakad.komennap SET komentar='$komentar', predikat='$predikat' WHERE replid='".$_REQUEST['replid']."'";
	$result_update = QueryDb($sql_update);
	if ($result_update)
	{	?>
		<script language="javascript" type="text/javascript">
			//alert ('Berhasil menyimpan komentar');
			document.location.href="komentar_content.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&tahunajaran=<?=$tahunajaran?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&nis=<?=$nis?>";
		</script>
<?php }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" type="text/javascript" src="../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple",
	});
	
	function OpenUploader() {
	    var addr = "UploaderMain.aspx";
	    newWindow(addr, 'Uploader','720','630','resizable=1,scrollbars=1,status=0,toolbar=0');
    }
	
	function simpan() {
	}
    
</script>
</HEAD>

<BODY>
<?php
$sql = "SELECT k.komentar, k.replid, k.predikat
		    FROM jbsakad.komennap k, jbsakad.infonap i 
		   WHERE k.nis='$nis' AND i.replid=k.idinfo AND i.idpelajaran='$pelajaran' 
		     AND i.idsemester='$semester' AND i.idkelas='$kelas'";
$result_get_comment = QueryDb($sql);
$row_get_comment = @mysqli_fetch_row($result_get_comment);
$ada_get_comment = @mysqli_num_rows($result_get_comment);
?>
<form name="frm_komentar" id="frm_komentar" action="komentar_content.php" method="POST">
<table width="100%" border="0" height="100%">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<tr height="50">
	<td valign="bottom">
		<table width="100%" border="0">
		  <tr>
			<td width="6%"><strong>NIS</strong></td>
			<td width="1%"><strong>:</strong></td>
			<td width="33%"><strong>
			  <?=$nis?>
			</strong></td>
			<td width="60%" rowspan="2" valign="middle"><div align="left">
			  <input type="submit" class="but" style="width:150px;" title="Simpan Komentar" name="Simpan" value="Simpan">
			</div></td>
			</tr>
		  <tr>
			<td><strong>Nama</strong></td>
			<td><strong>:</strong></td>
			<td><strong>
			  <?php
			$sql_get_nama="SELECT nama FROM jbsakad.siswa WHERE nis='$nis'";
			$result_get_nama=QueryDb($sql_get_nama);
			$row_get_nama=@mysqli_fetch_array($result_get_nama);
			echo $row_get_nama['nama'];
			?>
			</strong></td>
		  </tr>
		</table>
	</td>
</tr>
<tr height="120">
   <td valign="top" ><textarea name="komentar" id="komentar" style="width:100%;height:100px;">
	<?=$row_get_comment[0]?>
	</textarea><br>
   <strong>Predikat:&nbsp;</strong>
   <select name="predikat" id="predikat">
   <option value="4" <?=IntIsSelected(4, $row_get_comment[2])?>>Istimewa</option>
   <option value="3" <?=IntIsSelected(3, $row_get_comment[2])?>>Baik</option>
   <option value="2" <?=IntIsSelected(2, $row_get_comment[2])?>>Cukup</option>
   <option value="1" <?=IntIsSelected(1, $row_get_comment[2])?>>Kurang</option>
   <option value="0" <?=IntIsSelected(0, $row_get_comment[2])?>>Buruk</option>
   </select>
   <input type="hidden" name="replid" id="replid" value="<?=$row_get_comment[1]?>">
    </td>
  </tr>
  <tr>
	  <td align="left"  valign="top">
<?php $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
		  	  FROM infonap i, nap n, aturannhb a, dasarpenilaian d
			 WHERE i.replid = n.idinfo AND n.nis = '$nis' 
			   AND i.idpelajaran = '$pelajaran' 
			   AND i.idsemester = '$semester' 
			   AND i.idkelas = '$kelas'
			   AND n.idaturan = a.replid 	   
			   AND a.dasarpenilaian = d.dasarpenilaian";
	$res = QueryDb($sql);
	$i = 0;
	while($row = mysqli_fetch_row($res))
	{
		$aspek[$i++] = [$row[0], $row[1]];
	} ?>
    <strong>Informasi Nilai Rapor Siswa:</strong>
	<table border="1" class="tab" id="table" bordercolor="#000000">  
	<tr align="center" height="25">
<?php for($i = 0; $i < count($aspek); $i++)
		echo "<td class='header' colspan=2 align='center' width='180'>" . $aspek[$i][1] . "</td>"; ?>
    </tr>
    <tr align="center" height="25">
<?php for($i = 0; $i < count($aspek); $i++)
		echo "<td class='header' align='center' width='80'>Angka</td><td class='header' align='center' width='80'>Huruf</td>"; ?>
    </tr>
    <tr align="center" height="25">
<?php for($i = 0; $i < count($aspek); $i++)
	{
		$na = "";
		$nh = "";
		
		$asp = $aspek[$i][0];
		
		$sql = "SELECT nilaiangka, nilaihuruf
				  FROM infonap i, nap n, aturannhb a 
				 WHERE i.replid = n.idinfo 
				   AND n.nis = '$nis' 
				   AND i.idpelajaran = '$pelajaran' 
				   AND i.idsemester = '$semester' 
				   AND i.idkelas = '$kelas'
				   AND n.idaturan = a.replid 	   
				   AND a.dasarpenilaian = '".$asp."'";
		$res = QueryDb($sql);
		if (mysqli_num_rows($res) > 0)
		{
			$row = mysqli_fetch_row($res);
			$na = $row[0];
			$nh = $row[1];
		}
		echo "<td align='center'>$na</td><td align='center'>$nh</td>"; 
	} ?>
    </tr>    
    </table>
 	</td>
  </tr>
</table>
</form>
</BODY>
</HTML>
<?php
CloseDb();
?>