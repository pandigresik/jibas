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

$no=$_REQUEST['kode'].$_REQUEST['no'];
$tahunmasuk = $_REQUEST['tahunmasuk'];
$nama=$_REQUEST['nama'];
$panggilan=$_REQUEST['panggilan'];
$kelamin=$_REQUEST['kelamin'];
$tmplahir=$_REQUEST['tmplahir'];
$tgllahir=$_REQUEST['tgllahir'];
$blnlahir=$_REQUEST['blnlahir'];
$thnlahir=$_REQUEST['thnlahir'];;
$lahir=$thnlahir."-".$blnlahir."-".$tgllahir;
$suku=$_REQUEST['suku'];
$agama=$_REQUEST['agama'];
$status=$_REQUEST['status'];
$kondisi=$_REQUEST['kondisi'];
$warga=$_REQUEST['warga'];
//$urutananak=$_REQUEST['urutananak'];
if ($_REQUEST['urutananak']=="")
	$urutananak = 0;
else
	$urutananak = $_REQUEST['urutananak'];
//$jumlahanak=$_REQUEST['jumlahanak'];
if ($_REQUEST['jumlahanak']=="")
	$jumlahanak = 0;
else
	$jumlahanak = $_REQUEST['jumlahanak'];
$bahasa=$_REQUEST['bahasa'];
$foto=$_REQUEST['foto'];
$alamatsiswa=$_REQUEST['alamatsiswa'];
$kodepos=$_REQUEST['kodepos'];
$telponsiswa=$_REQUEST['telponsiswa'];
$hpsiswa=$_REQUEST['hpsiswa'];
$emailsiswa=$_REQUEST['emailsiswa'];
$departemen=$_REQUEST['departemen'];
$sekolah=$_REQUEST['sekolah'];
$ketsekolah=$_REQUEST['ketsekolah'];
$gol=$_REQUEST['gol'];
//$berat=$_REQUEST['berat'];
if ($_REQUEST['berat']=="")
	$berat = 0;
else
	$berat = $_REQUEST['berat'];

//$tinggi=$_REQUEST['tinggi'];
if ($_REQUEST['tinggi']=="")
	$tinggi = 0;
else
	$tinggi = $_REQUEST['tinggi'];

$kesehatan=$_REQUEST['kesehatan'];
$namaayah=$_REQUEST['namaayah'];
$namaibu=$_REQUEST['namaibu'];
$pendidikanayah=$_REQUEST['pendidikanayah'];
$pendidikanibu=$_REQUEST['pendidikanibu'];
$pekerjaanayah=$_REQUEST['pekerjaanayah'];
$pekerjaanibu=$_REQUEST['pekerjaanibu'];
//$penghasilanayah=(int)$_REQUEST['penghasilanayah'];
if ($_REQUEST['penghasilanayah']=="")
	$penghasilanayah = 0;
else
	$penghasilanayah = (int)$_REQUEST['penghasilanayah'];

//$penghasilanibu=(int)$_REQUEST['penghasilanibu'];
if ($_REQUEST['penghasilanibu']=="")
	$penghasilanibu = 0;
else
	$penghasilanibu = (int)$_REQUEST['penghasilanibu'];

$namawali=$_REQUEST['namawali'];
$alamatortu=$_REQUEST['alamatortu'];
$telponortu=$_REQUEST['telponortu'];
$hportu=$_REQUEST['hportu'];
$emailortu=$_REQUEST['emailortu'];
$alamatsurat=$_REQUEST['alamatsurat'];
$keterangan=$_REQUEST['keterangan'];
if ($_REQUEST['almayah']=="on"){
	$almayah=1;
} else {
	$almayah=0;
}
if ($_REQUEST['almibu']=="on"){
	$almibu=1;
} else {
	$almibu=0;
}

$departemen=$_REQUEST['departemen'];
$proses=$_REQUEST['proses'];
$kelompok=$_REQUEST['kelompok'];
?>
<html>
<head>

</head>
<body>
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="proses" id="proses" value="<?=$proses ?>" />
<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok ?>" />
<?php
OpenDb();
if ($_REQUEST['action'] == 'ubah') {
	$sql="UPDATE calonsiswa SET nopendaftaran='$no',nama='$nama',panggilan='$panggilan',tahunmasuk=$tahunmasuk,idproses=$proses,idkelompok=$kelompok,suku='$suku',agama='$agama',status='$status',kondisi='$kondisi',kelamin='$kelamin',tmplahir='$tmplahir',tgllahir='$lahir',warga='$warga',anakke=$urutananak,jsaudara=$jumlahanak,bahasa='$bahasa',berat=$berat,tinggi=$tinggi,darah='$gol',foto='$foto',alamatsiswa='$alamatsiswa',kodepossiswa='$kodepos',telponsiswa='$telponsiswa',hpsiswa='$hpsiswa',emailsiswa='$emailsiswa',kesehatan='$kesehatan',asalsekolah='$sekolah',ketsekolah='$ketsekolah',namaayah='$namaayah',namaibu='$namaibu',almayah=$almayah,almibu=$almibu,pendidikanayah='$pendidikanayah',pendidikanibu='$pendidikanibu',pekerjaanayah='$pekerjaanayah',pekerjaanibu='$pekerjaanibu',wali='$namawali',penghasilanayah=$penghasilanayah,penghasilanibu=$penghasilanibu,alamatortu='$alamatortu',telponortu='$telponortu',hportu='$hportu',emailortu='$emailortu',alamatsurat='$alamatsurat',keterangan='$keterangan' WHERE replid='".$_REQUEST['replid']."'";

$result = QueryDb($sql);
CloseDb();

if ($result) { ?>

<script language="javascript">
		
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	var kelompok = document.getElementById("kelompok").value;
	opener.refresh();
	//parent.footer.location.href = "calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok;
	window.close();
</script> 
<?php }
} else {
	$sql="INSERT INTO calonsiswa SET nopendaftaran='$no',nama='$nama',panggilan='$panggilan',tahunmasuk=$tahunmasuk,idproses=$proses,idkelompok=$kelompok,suku='$suku',agama='$agama',status='$status',kondisi='$kondisi',kelamin='$kelamin',tmplahir='$tmplahir',tgllahir='$lahir',warga='$warga',anakke=$urutananak,jsaudara=$jumlahanak,bahasa='$bahasa',berat=$berat,tinggi=$tinggi,darah='$gol',foto='$foto',alamatsiswa='$alamatsiswa',kodepossiswa='$kodepos',telponsiswa='$telponsiswa',hpsiswa='$hpsiswa',emailsiswa='$emailsiswa',kesehatan='$kesehatan',asalsekolah='$sekolah',ketsekolah='$ketsekolah',namaayah='$namaayah',namaibu='$namaibu',almayah=$almayah,almibu=$almibu,pendidikanayah='$pendidikanayah',pendidikanibu='$pendidikanibu',pekerjaanayah='$pekerjaanayah',pekerjaanibu='$pekerjaanibu',wali='$namawali',penghasilanayah=$penghasilanayah,penghasilanibu=$penghasilanibu,alamatortu='$alamatortu',telponortu='$telponortu',hportu='$hportu',emailortu='$emailortu',alamatsurat='$alamatsurat',keterangan='$keterangan'";

$result = QueryDb($sql);
CloseDb();

if ($result) { ?>

<script language="javascript">
		
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	var kelompok = document.getElementById("kelompok").value;
	
	parent.footer.location.href = "calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok;
	window.close();
</script> 
<?php  } 
}
?>

</body>
</html>