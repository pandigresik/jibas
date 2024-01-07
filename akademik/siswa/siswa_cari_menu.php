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
require_once('../cek.php');

/*	$cari=$_REQUEST['cari'];
	$jenis=$_REQUEST['jenis'];
	$departemen=$_REQUEST['departemen'];
	$urutan=$_REQUEST['urutan'];
		if ($urutan=="") {
			$urutan="nis"; }
$urutan=$_REQUEST['urutan'];
*/
$departemen = $_REQUEST['departemen'];
$jenis = $_REQUEST['jenis'];
$cari = $_REQUEST['cari'];

$urut = "kelompok";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pencarian Siswa['Menu']</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {
	var departemen = document.getElementById('departemen').value;
	var jenis= document.getElementById('jenis').value;
	var cari= document.getElementById('cari').value;
	
	document.location.href = "siswa_cari.menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari;	
}

function tampil(replid) {
	newWindow('calon_tampil.php?replid='+replid,'TampilCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urutan(urutan) {
	var cari = document.getElementById("cari").value;
	var departemen = document.getElementById("departemen").value;
	var jenis = document.getElementById("jenis").value;
	//var urutan = document.getElementById("urutan").value;
	parent.cari_siswa_menu.location.href = "siswa_cari_menu.php?departemen="+departemen+"&jenis="+jenis+"&cari="+cari+"&urutan="+urutan;
	}

</script>
</head>
<body background="../images/bk_scroll_1000.jpg" class="headerlink">
<form name="kiri">

<table width="303" border="0" cellspacing="0" class="tab" id="table">
  
 
 
  <?php
  
	
	?>
		<input type="hidden" name="departemen" id="departemen" value="<?=$departemen; ?>">
		<input type="hidden" name="cari" id="cari" value="<?=$cari; ?>">
		<input type="hidden" name="jenis" id="jenis" value="<?=$jenis; ?>">
	<?php
    OpenDb();
		$sql_siswa = "SELECT nis,nama,idkelas from jbsakad.siswa WHERE $jenis LIKE '%$cari%' ORDER BY $urutan ASC"; 
		$result_siswa = QueryDb($sql_siswa);
		$cnt_siswa = 1;
		if ($jumlah = mysqli_num_rows($result_siswa)>0) {
			?>
			<tr>
    <td class="header">No</td>
    <td class="headerlink2" onclick="change_urutan('nis')" style="cursor:pointer" onmouseover="showhint('Urutkan berdasarkan NIS', this, event, '120px')">NIS
	<?php 
	if ($urutan=="nis")
	echo "&nbsp;&nbsp;<img src='../images/ico/ascending copy.png' width='13' height='9' />";
	?>
	</td>
    <td class="headerlink2" onclick="change_urutan('nama')" style="cursor:pointer" onmouseover="showhint('Urutkan berdasarkan Nama', this, event, '120px')">Nama
	<?php 
	if ($urutan=="nama")
	echo "&nbsp;&nbsp;<img src='../images/ico/ascending copy.png' width='13' height='9' />";
	?>
	</td>
    <td class="headerlink2" onclick="change_urutan('idkelas')" style="cursor:pointer;" onmouseover="showhint('Urutkan berdasarkan Kelas', this, event, '120px')"  >Kelas
	<?php 
	if ($urutan=="idkelas")
	echo "&nbsp;&nbsp;<img src='../images/ico/ascending copy.png' width='13' height='9' />";
	?>
	</td>
    <td class="header">&nbsp;</td>
  </tr>
			<?php //onmouseover="showhint('Urutkan berdasarkan Kelas', this, event, '120px')"
		while ($row_siswa = @mysqli_fetch_array($result_siswa)) {
		$nis=$row_siswa['nis'];
		$nama=$row_siswa['nama'];
		$idkelas=$row_siswa['idkelas'];
				$sql_gabung = "SELECT t.replid,t.departemen,k.replid,k.kelas,k.idtingkat from jbsakad.tingkat t,jbsakad.kelas k WHERE k.replid='$idkelas' AND t.replid=k.idtingkat AND t.departemen = '".$departemen."'"; 
				$result_gabung = QueryDb($sql_gabung);
				if ($row_gabung = @mysqli_fetch_row($result_gabung)) {
				$kelas=$row_gabung[3];
				
		?>
  <tr> 
  	<td><?=$cnt_siswa?></td>
    <td><?=$nis?></td>
    <td><?=$nama?></td>
    <td><?=$kelas?></td>
    <td><a href="siswa_cari_detail.php?nis=<?=$nis?>&departemen=<?=$departemen?>" target="cari_siswa_content" ><img src="../images/ico/lihat.png" alt="Lihat Detail Siswa" border="0"/></a></td>
  </tr>
  <?php
			}
			$cnt_siswa++;
		}
		} else {
			?>
<tr><td align="center" class="header">Tidak ada data yang sesuai dengan pencarian</td></tr>
<?php
		
 }
		CloseDb();

?>

</table>


</form>

<script language='JavaScript'>
	    Tables('table', 1, 0);
 		</script>	
        <br />
        <br />
		
<br />


</body>
 
</html>