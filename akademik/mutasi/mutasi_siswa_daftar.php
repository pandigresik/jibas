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

$departemen = $_REQUEST['departemen'];
$idtingkat = $_REQUEST['idtingkat'];
$idtahunajaran = $_REQUEST['idtahunajaran'];
$idkelas = $_REQUEST['idkelas'];
$jenis = $_REQUEST['jenis'];
$nis = $_REQUEST['nis'];
$nama = $_REQUEST['nama'];
$pilihan = "";	
if (isset($_REQUEST['pilihan']))
	$pilihan = $_REQUEST['pilihan'];	

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
if ($jenis <> ""){
	if ($jenis == "text") {			
		if ($nama == "")
			$string = "WHERE s.nis LIKE '%$nis%'";
		if ($nis == "")
			$string = "WHERE s.nama LIKE '%$nama%'";
		if ($nis <> "" && $nama <> "")
			$string = "WHERE s.nis LIKE '%$nis%' AND s.nama LIKE '%$nama%'";
	} elseif ($jenis == "combo") { 			
		$string = "WHERE s.idkelas = '".$idkelas."'";
	} else {
		$string = "WHERE s.aktif = 1";
	}
}

$ERROR_MSG = "";
$op = $_REQUEST['op'];
$pindah = $_REQUEST['pindah'];
								
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pindah Kelas[Menu]</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function change_kelas() {	
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;	
	var idkelas = document.getElementById("idkelas").value;	
		
	parent.mutasi_siswa_menu.location.href = "mutasi_siswa_menu.php?idtahunajaran="+idtahunajaran+"&idtingkat="+idtingkat+"&departemen="+departemen+"&idkelas="+idkelas;
}

function mulai_pindah(id) {
	newWindow('siswa_mutasi_add.php?nis='+id, 'MutasiSiswa','400','450','resizable=1,scrollbars=1,status=0,toolbar=0')	
}

function change_urutan(urut,urutan) {	
	var jenis = document.getElementById("jenis").value;
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "mutasi_siswa_daftar.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pindah=0&urut="+urut+"&urutan="+urutan+"&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}	

function change_page(page) {
	var jenis = document.getElementById("jenis").value;
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "mutasi_siswa_daftar.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pindah=0&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var jenis = document.getElementById("jenis").value;
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="mutasi_siswa_daftar.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pindah=0&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var jenis = document.getElementById("jenis").value;
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "mutasi_siswa_daftar.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pindah=0&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function refresh_daftar() {
	var jenis = document.getElementById("jenis").value;
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var nis = document.getElementById("nis").value;
	var nama = document.getElementById("nama").value
	
	document.location.href= "mutasi_siswa_daftar.php?idkelas="+idkelas+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pindah=0&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	parent.mutasi_siswa_tujuan.refresh_isi();
}
</script>
</head>
<body topmargin="0" leftmargin="0">
<form name="pilih" id="pilih">
<input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="idtahunajaran" id="idtahunajaran" value="<?=$idtahunajaran?>" />
<input type="hidden" name="nis" id="nis" value="<?=$nis; ?>">
<input type="hidden" name="nama" id="nama" value="<?=$nama; ?>">
<input type="hidden" name="idkelas" id="idkelas" value="<?=$idkelas; ?>">

<?php 	if ($jenis <> ""){ 
		OpenDb();
		$sql_tot = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid from jbsakad.siswa s, kelas k $string AND k.idtingkat = '$idtingkat' AND s.idkelas = k.replid AND k.idtahunajaran = '$idtahunajaran' AND s.aktif=1 ORDER BY $urut $urutan";  
		
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;	
		
		$sql_siswa = "SELECT s.nis,s.nama,s.idkelas,k.kelas,s.replid from jbsakad.siswa s, kelas k $string AND k.idtingkat = '$idtingkat' AND s.idkelas = k.replid AND k.idtahunajaran = '$idtahunajaran' AND s.aktif=1 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
		$result_siswa = QueryDb($sql_siswa);
		
		if (@mysqli_num_rows($result_siswa)>0) {
?>	
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <tr height="30" class="header" align="center">
    	<td width="6%">No</td>
    	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nis','<?=$urutan?>')" >N I S <?=change_urut('s.nis',$urut,$urutan)?></td> 
    	<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.nama','<?=$urutan?>')" >Nama <?=change_urut('s.nama',$urut,$urutan)?></td> 
    	<td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urutan('s.idkelas','<?=$urutan?>')" >Kelas <?=change_urut('s.idkelas',$urut,$urutan)?></td> 
    	<td width="10%"></td>
  	</tr>

<?php 	if ($page==0)
			$cnt_siswa = 0;
		else 
			$cnt_siswa = (int)$page*(int)$varbaris;
			
		while ($row_siswa = @mysqli_fetch_array($result_siswa)) {
			$nis=$row_siswa['nis'];
			$nama=$row_siswa['nama'];
			$idkelas=$row_siswa['idkelas'];
			$kelas=$row_siswa['kelas'];
?>
  	<tr height="25"> 
  		<td align="center"><?=++$cnt_siswa?></td>
    	<td align="center"><?=$nis?></td>
    	<td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replid']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$nama?></a></td>
    	<td align="center"><?=$kelas?></td>
    	<td align="center"><input type="button" value=">" class="but" onClick="mulai_pindah('<?=$nis?>')" onMouseOver="showhint('Klik untuk mutasi!', this, event, '80px')"/></td>
  	</tr>
<?php
				
		} CloseDb();
?>	</table>
	<script language='JavaScript'>
		Tables('table', 1, 0);
 	</script>
    <?php if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
		}
		if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
		}
	?>
   	</td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center" cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left">Hal
        <select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
		<?php 
     // Navigasi halaman berikutnya dan sebelumnya
        ?>
        </td>
    	<!--<td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td>-->
        <td width="30%" align="right">Jml baris per hal
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>		
<?php 		
		} else {
?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">

    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. 
        <br />Tambah data siswa pada departemen <?=$departemen?> di menu Kesiswaan pada bagian Pendataan Siswa.
       	</b></font>
        
		</td>
	</tr>
	</table>
<?php 		} 
	} else {	
?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">    	  	
    	<font size="2" color="#757575"><b>Klik pada tombol &quot;Tampil&quot; atau &quot;Cari&quot; untuk
      menampilkan daftar siswa yang akan dimutasi &nbsp;</b></font>
 	</td>
	</tr>
	</table>
<?php 	} ?>
</td>
</tr>
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>