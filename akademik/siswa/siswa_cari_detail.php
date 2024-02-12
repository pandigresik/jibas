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
require_once('../include/theme.php');
$nis=$_REQUEST['nis'];
OpenDb();
BeginTrans();
$success=1;
if ($success){
$sql_siswa="SELECT * FROM jbsakad.siswa WHERE nis='$nis'";
$sql_siswa_tgl="SELECT YEAR(tgllahir),MONTH(tgllahir),DAY(tgllahir) FROM jbsakad.siswa WHERE nis='$nis'";
$result=QueryDbTrans($sql_siswa, $success);
$result_tgl=QueryDbTrans($sql_siswa_tgl, $success);
$row_siswa=mysqli_fetch_array($result);
$row_siswa_tgl=mysqli_fetch_row($result_tgl);
$tglnya=$row_siswa_tgl[2];
$blnnya=$row_siswa_tgl[1];
$thnnya=$row_siswa_tgl[0];
}
if ($success){
	CommitTrans();
}Else{
	RollbackTrans();
}

	$sql_kelas="SELECT kelas FROM jbsakad.kelas WHERE replid='".$row_siswa['idkelas']."'";
	$result_kelas=QueryDB($sql_kelas);
	while ($row_kelas = mysqli_fetch_array($result_kelas)) {
	$namakelas=$row_kelas['kelas'];
	}
	CloseDb();
function FormatRupiah($value) {
	if (!is_numeric($value)) 
		return $value;
	
	$value = (int)$value;
	$negatif = false;
	if ($value < 0) {
		$negatif = true;
		$value = abs($value);
	}
		
	$duit = (string)$value;
	// $value = trim((string) \DUIT);
	if (strlen($duit) == 0) return "";
	$len = strlen($duit);
	$nPoint = (int)($len / 3);
	if (($len % 3) == 0)
		$nPoint--;
	
	$rp = "";
	for ($i = 0; $i < $nPoint; $i++) {
		$j = 0;
		$temp = "";
		while((strlen($duit) >= 0) && ($j++ < 3)) {
			$temp = substr($duit, strlen($duit) - 1, 1) . $temp;
			if (strlen($duit) >= 2)
				$duit = substr($duit, 0, strlen($duit) - 1);
			else
				$duit = "";
		}
		if (strlen($rp) > 0)
			$rp = $temp . "." . $rp;
		else
			$rp = $temp;		
	}
	if (strlen($duit) > 0)
		$rp = $duit . "." . $rp;
	
	if ($negatif)
		return "(Rp " . $rp . ")";
	else
		return "Rp " . $rp . " ,-";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>Tampil Siswa</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript">
function tutup(){
window.close();
}
</script>
<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-weight: bold;
}
.style2 {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    
<table width="100%">
<tr><td align="left" valign="top">

<center>
  <span class="style1">DATA SISWA</span><br />
 </center>
<br />
<br /><left>
  <div align="right"><span class="style1"><a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" />&nbsp;Refresh</a>&nbsp;&nbsp;
    <a  href="#" onClick="newWindow('siswa_cetak_detail.php?nis=<?=$nis?>', 'DetailSiswa','782','864','resizable=1,scrollbars=1,status=0,toolbar=0')"><img src="../images/ico/print.png" border="0" />Cetak</a>&nbsp;&nbsp;</span><br />
   </div>
</left>

<table width="100%" border="0" cellspacing="5">
  <tr>
    <td width="13%" valign="top"><div align="center"><img src="../library/gambar.php?replid=<?=$row_siswa['replid']?>&table=siswa" width="100" height="120"  border="0"/></div></td>
    <td width="87%"><table width="100%" border="0" cellspacing="0" bordercolor="#666666" id="table" class="tab">
  <tr >
    <td width="3%" class="header"><strong>A.</strong></td>
    <td colspan="4" class="header"><strong>KETERANGAN PRIBADI</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td width="3%" >1.</td>
    <td width="22%" >Nama Peserta Didik</td>
    <td colspan="2" >&nbsp;</td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >a. Lengkap :</td>
    <td colspan="2" ><?=$row_siswa['nama']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>

    <td >&nbsp;</td>
    <td >b. Panggilan :</td>
    <td colspan="2" ><?=$row_siswa['nama']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >2.</td>
    <td >Jenis Kelamin</td>
    <td colspan="2" >
    <?php if ($row_siswa['kelamin']=="l"){
	echo "Laki-laki"; }
	if ($row_siswa['kelamin']=="p"){
	echo "Perempuan"; }
	?>    </td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >3.</td>
    <td >Tempat Lahir</td>
    <td colspan="2" ><?=$row_siswa['tmplahir']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >4.</td>
    <td >Tanggal Lahir</td>
    <td colspan="2" ><?php
    switch ($blnnya){
				case 1 : $namabulan="Januari";
				break;
				case 2 : $namabulan="Pebruari";
				break;
				case 3 : $namabulan="Maret";
				break;
				case 4 : $namabulan="April";
				break;
				case 5 : $namabulan="Mei";
				break;
				case 6 : $namabulan="Juni";
				break;
				case 7 : $namabulan="Juli";
				break;
				case 8 : $namabulan="Agustus";
				break;
				case 9 : $namabulan="September";
				break;
				case 10 : $namabulan="Oktober";
				break;
				case 11 : $namabulan="Nopember";
				break;
				case 12 : $namabulan="Desember";
				break;
	}
	echo $tglnya." - ".$namabulan." - ".$thnnya;
	?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >5.</td>
    <td >Agama</td>
    <td colspan="2" ><?=$row_siswa['agama']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >6.</td>
    <td >Kewarganegaraan</td>
    <td colspan="2" ><?=$row_siswa['warga']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >7.</td>
    <td >Anak ke berapa</td>
    <td colspan="2" ><?=$row_siswa['anakke']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >8.</td>
    <td >Jumlah Saudara</td>
    <td colspan="2" ><?=$row_siswa['jsaudara']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >9.</td>
    <td >Kondisi Siswa</td>
    <td colspan="2" ><?=$row_siswa['kondisi']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >10.</td>
    <td >Status Siswa</td>
    <td  colspan="2"><?=$row_siswa['status']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >11.</td>
    <td >Bahasa Sehari-hari</td>
    <td  colspan="2"><?=$row_siswa['bahasa']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td class="header"><strong>B.</strong></td>
    <td  colspan="4" class="header"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td >12.</td>
    <td >Alamat</td>
    <td  colspan="2"><?=$row_siswa['alamatsiswa']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >13.</td>
    <td >Telpon</td>
    <td  colspan="2"><?=$row_siswa['telponsiswa']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >14.</td>
    <td >HP</td>
    <td  colspan="2"><?=$row_siswa['hpsiswa']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >15.</td>
    <td >Email</td>
    <td  colspan="2"><?=$row_siswa['emailsiswa']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td class="header"><strong>C.</strong></td>
    <td class="header" colspan="4"><strong>KETERANGAN KESEHATAN</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td >16.</td>
    <td >Berat Badan</td>
    <td  colspan="2"><?=$row_siswa['berat']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >17.</td>
    <td >Tinggi Badan</td>
    <td  colspan="2"><?=$row_siswa['tinggi']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >18.</td>
    <td >Golongan Darah</td>
    <td  colspan="2"><?=$row_siswa['darah']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >19.</td>
    <td >Riwayat Penyakit</td>
    <td  colspan="2"><?=$row_siswa['kesehatan']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td class="header"><strong>D.</strong></td>
    <td class="header" colspan="4"><strong>KETERANGAN PENDIDIKAN SEBELUMNYA</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td >20.</td>
    <td >Asal Sekolah</td>
    <td  colspan="2"><?=$row_siswa['asalsekolah']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >21.</td>
    <td >Keterangan</td>
    <td  colspan="2"><?=$row_siswa['ketsekolah']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td class="header"><strong>E.</strong></td>
    <td class="header" colspan="4"><strong>KETERANGAN ORANG TUA</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >Orang Tua</td>
    <td  width="27%"><div align="center">Ayah</div></td>
    <td  width="25%"><div align="center">Ibu</div></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >22.</td>
    <td >Nama</td>
    <td ><?=$row_siswa['namaayah']?></td>
    <td ><?=$row_siswa['namaibu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >23.</td>
    <td >Pendidikan</td>
    <td ><?=$row_siswa['pendidikanayah']?></td>
    <td ><?=$row_siswa['pendidikanibu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >24.</td>
    <td >Pekerjaan</td>
    <td ><?=$row_siswa['pendidikanibu']?></td>
    <td ><?=$row_siswa['pendidikanibu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >25.</td>
    <td >Penghasilan</td>
    <td >
    <?php
	$value=$row_siswa['penghasilanayah'];  
	echo FormatRupiah($row_siswa['penghasilanayah']); ?>    </td>
    <td ><?php
	$value=$row_siswa['penghasilanibu'];  
	echo FormatRupiah($row_siswa['penghasilanibu']); ?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >26. </td>
    <td >Nama Wali</td>
    <td  colspan="2"><?=$row_siswa['wali']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >27.</td>
    <td >Alamat</td>
    <td  colspan="2"><?=$row_siswa['alamatortu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >28.</td>
    <td >Telpon</td>
    <td  colspan="2"><?=$row_siswa['telponortu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >29.</td>
    <td >HP</td>
    <td  colspan="2"><?=$row_siswa['hportu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >30.</td>
    <td >Email</td>
    <td  colspan="2"><?=$row_siswa['emailortu']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td  colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td class="header"><strong>F.</strong></td>
    <td class="header" colspan="4"><strong>KETERANGAN LAINNYA</strong></td>
    </tr>
  <tr >
    <td >&nbsp;</td>
    <td >31.</td>
    <td >Alamat Surat</td>
    <td  colspan="2"><?=$row_siswa['alamatsurat']?></td>
  </tr>
  <tr >
    <td >&nbsp;</td>
    <td >32.</td>
    <td >Keterangan</td>
    <td  colspan="2"><?=$row_siswa['keterangan']?></td>
  </tr>
</table></td>
  </tr>
</table>
<table width="100%">
<tr>
<td align="right"><input type="button"  name="tutup" id="tutup" value="Tutup" class="but"  onclick="tutup()"/>
</td>
</tr>
</table>
<script language='JavaScript'>
	    Tables('table', 1, 0);
</script>
</td></tr>


</table>
<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>

</body>
</html>