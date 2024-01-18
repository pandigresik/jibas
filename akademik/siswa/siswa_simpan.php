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

$no = $_REQUEST['no'];
$tahunmasuk = $_REQUEST['tahunmasuk'];
$nama=$_REQUEST['nama'];
$nama=str_replace("'", "`", (string) $nama);
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
$urutananak=$_REQUEST['urutananak'];;
if ($_REQUEST['urutananak']=="")
	$urutananak = 0;
$jumlahanak=$_REQUEST['jumlahanak'];
if ($_REQUEST['jumlahanak']=="")
	$jumlahanak = 0;
$bahasa=$_REQUEST['bahasa'];
$alamatsiswa=$_REQUEST['alamatsiswa'];
$kodepos=$_REQUEST['kodepos'];
$telponsiswa=$_REQUEST['telponsiswa'];
$hpsiswa=$_REQUEST['hpsiswa'];
$emailsiswa=$_REQUEST['emailsiswa'];
$dep_asal=$_REQUEST['dep_asal'];

$sekolah=$_REQUEST['sekolah'];
$sekolah_sql = "asalsekolah = '".$sekolah."'";
if ($sekolah == "")
	$sekolah_sql = "asalsekolah = NULL";

$ketsekolah=$_REQUEST['ketsekolah'];
$gol=$_REQUEST['gol'];
//$berat=$_REQUEST['berat'];
$berat = $_REQUEST['berat'];
if ($_REQUEST['berat']=="")
	$berat = 0;
$tinggi = $_REQUEST['tinggi'];
if ($_REQUEST['tinggi']=="")
	$tinggi = 0;
$kesehatan=$_REQUEST['kesehatan'];
$namaayah=$_REQUEST['namaayah'];
$almayah = $_REQUEST['almayah'];
if ($_REQUEST['almayah']<> "1")
	$almayah=0;
$namaibu=$_REQUEST['namaibu'];
$almibu = $_REQUEST['almibu'];
if ($_REQUEST['almibu']<> "1")
	$almibu=0;
$pendidikanayah=$_REQUEST['pendidikanayah'];
$pendidikanayah_sql = "pendidikanayah = '".$pendidikanayah."'";
if ($pendidikanayah == "")
	$pendidikanayah_sql = "pendidikanayah = NULL";
$pendidikanibu=$_REQUEST['pendidikanibu'];
$pendidikanibu_sql = "pendidikanibu = '".$pendidikanibu."'";
if ($pendidikanibu == "")
	$pendidikanibu_sql = "pendidikanibu = NULL";
$pekerjaanayah=$_REQUEST['pekerjaanayah'];
$pekerjaanayah_sql = "pekerjaanayah = '".$pekerjaanayah."'";
if ($pekerjaanayah == "")
	$pekerjaanayah_sql = "pekerjaanayah = NULL";
$pekerjaanibu=$_REQUEST['pekerjaanibu'];
$pekerjaanibu_sql = "pekerjaanibu = '".$pekerjaanibu."'";
if ($pekerjaanibu == "")
	$pekerjaanibu_sql = "pekerjaanibu = NULL";
$penghasilanayah = $_REQUEST['penghasilanayah'];
if ($_REQUEST['penghasilanayah']=="")
	$penghasilanayah = 0;
$penghasilanibu = $_REQUEST['penghasilanibu'];
if ($_REQUEST['penghasilanibu']=="")
	$penghasilanibu = 0;
$namawali=$_REQUEST['namawali'];
$alamatortu=$_REQUEST['alamatortu'];
$telponortu=$_REQUEST['telponortu'];
$hportu=$_REQUEST['hportu'];
$emailortu=$_REQUEST['emailortu'];
$alamatsurat=$_REQUEST['alamatsurat'];
$keterangan=$_REQUEST['keterangan'];
$departemen=$_REQUEST['departemen'];
$proses=$_REQUEST['proses'];
$kelompok=$_REQUEST['kelompok'];

$nama_file = $_FILES['nama_foto']['name'];
$ukuran    = $_FILES['nama_foto']['size'];
$tipe_file = $_FILES['nama_foto']['type'];
$file_data = $_FILES['nama_foto']['tmp_name'];
		
if($file_data != "") {
	$foto = addslashes(fread(fopen($file_data, "r"), filesize($file_data)));
}


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
	if ($file_data <> "") {
		$sql="UPDATE jbsakad.calonsiswa SET nama='$nama', panggilan='$panggilan', idproses=$proses, idkelompok=$kelompok, suku='$suku', agama='$agama, status='$status', kondisi='$kondisi', kelamin='$kelamin', tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga', anakke=$urutananak, jsaudara=$jumlahanak, bahasa='$bahasa', berat=$berat, tinggi=$tinggi, darah='$gol', foto='$foto', alamatsiswa='$alamatsiswa', kodepossiswa='$kodepos', telponsiswa='$telponsiswa', hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, ketsekolah='$ketsekolah', namaayah='$namaayah', namaibu='$namaibu', almayah=$almayah, almibu=$almibu, $pendidikanayah_sql, $pendidikanibu_sql,  $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali', penghasilanayah=$penghasilanayah, penghasilanibu=$penghasilanibu, alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu', emailortu='$emailortu', alamatsurat='$alamatsurat', keterangan='$keterangan' WHERE replid= '".$_REQUEST['replid']."'";
	} else {
		$sql="UPDATE jbsakad.calonsiswa SET nama='$nama', panggilan='$panggilan', idproses=$proses, idkelompok=$kelompok, suku='$suku', agama='$agama', status='$status', kondisi='$kondisi', kelamin='$kelamin', tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga', anakke=$urutananak, jsaudara=$jumlahanak, bahasa='$bahasa', berat=$berat, tinggi=$tinggi, darah='$gol', alamatsiswa='$alamatsiswa', kodepossiswa='$kodepos', telponsiswa='$telponsiswa', hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, ketsekolah='$ketsekolah', namaayah='$namaayah', namaibu='$namaibu', almayah=$almayah, almibu=$almibu, $pendidikanayah_sql, $pendidikanibu_sql, $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali', penghasilanayah=$penghasilanayah, penghasilanibu=$penghasilanibu, alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu', emailortu='$emailortu', alamatsurat='$alamatsurat', keterangan='$keterangan' WHERE replid= ".$_REQUEST['replid'];
	}
} else { 
	$sql="INSERT INTO jbsakad.calonsiswa SET nopendaftaran='$no', nama='$nama', panggilan='$panggilan', tahunmasuk='$tahunmasuk', idproses='$proses', idkelompok='$kelompok', suku='$suku', agama='$agama', status='$status', kondisi='$kondisi', kelamin='$kelamin', tmplahir='$tmplahir', tgllahir='$lahir', warga='$warga',  anakke='$urutananak', jsaudara='$jumlahanak', bahasa='$bahasa', berat='$berat', tinggi='$tinggi', darah='$gol', foto='$foto', alamatsiswa='$alamatsiswa', kodepossiswa='$kodepos', telponsiswa='$telponsiswa', hpsiswa='$hpsiswa', emailsiswa='$emailsiswa', kesehatan='$kesehatan', $sekolah_sql, ketsekolah='$ketsekolah', namaayah='$namaayah', namaibu='$namaibu', almayah='$almayah', almibu='$almibu', $pendidikanayah_sql, $pendidikanibu_sql, $pekerjaanayah_sql, $pekerjaanibu_sql, wali='$namawali', penghasilanayah='$penghasilanayah', penghasilanibu='$penghasilanibu', alamatortu='$alamatortu', telponortu='$telponortu', hportu='$hportu', emailortu='$emailortu', alamatsurat='$alamatsurat', keterangan='$keterangan'";
	
}	
//echo $sql;
//exit;
$result = QueryDb($sql);
if ($result) { ?>

<script language="javascript">
	parent.opener.refresh_simpan('<?=$departemen?>','<?=$proses?>','<?=$kelompok?>');
	window.close();
</script> 
<?php  } 
//} 
//}

CloseDb();
?>

</body>
</html>