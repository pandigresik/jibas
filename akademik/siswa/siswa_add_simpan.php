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

$idangkatan=(int)$_POST['angkatan'];
$tahunmasuk=(int)$_POST['tahunmasuk'];
$nis=$_POST['nis'];
$nama=str_replace("'","`",(string) $_POST['nama']);
$nama=str_replace('"',"`",$nama);
$panggilan=$_POST['panggilan'];
$kelamin=$_POST['kelamin'];
$tmplahir=$_POST['tmplahir'];
$tgllahir=$_POST['tgllahir'];
$blnlahir=$_POST['blnlahir'];
$thnlahir=$_POST['thnlahir'];;
$lahir=$thnlahir."-".$blnlahir."-".$tgllahir;
$suku=$_POST['suku'];
$agama=$_POST['agama'];
$status=$_POST['status'];
$kondisi=$_POST['kondisi'];
$warga=$_POST['warga'];
$urutananak=$_POST['urutananak'];
if ($urutananak=="")
$urutananak=0;
$jumlahanak=$_POST['jumlahanak'];
if ($jumlahanak=="")
$jumlahanak=0;
$bahasa=$_POST['bahasa'];
$alamatsiswa=$_POST['alamatsiswa'];
$kodepos=$_POST['kodepos'];
$telponsiswa=$_POST['telponsiswa'];
$hpsiswa=$_POST['hpsiswa'];
$emailsiswa=$_POST['emailsiswa'];
$departemen=$_POST['departemen'];
$sekolah=$_POST['sekolah'];
$ketsekolah=$_POST['ketsekolah'];
$gol=$_POST['gol'];
$berat=$_POST['berat'];
if ($berat=="")
$berat=0;
$tinggi=$_POST['tinggi'];
if ($tinggi=="")
$tinggi=0;
$kesehatan=$_POST['kesehatan'];
$namaayah=$_POST['namaayah'];
$namaibu=$_POST['namaibu'];
$pendidikanayah=$_POST['pendidikanayah'];
$pendidikanibu=$_POST['pendidikanibu'];
$pekerjaanayah=$_POST['pekerjaanayah'];
$pekerjaanibu=$_POST['pekerjaanibu'];
$penghasilanayah=(int)$_POST['penghasilanayah'];
if ($penghasilanayah==0)
$penghasilanayah=0;
$penghasilanibu=(int)$_POST['penghasilanibu'];
if ($penghasilanibu==0)
$penghasilanibu=0;
$namawali=$_POST['namawali'];
$alamatortu=$_POST['alamatortu'];
$telponortu=$_POST['telponortu'];
$hportu=$_POST['hportu'];
$emailortu=$_POST['emailortu'];
$alamatsurat=$_POST['alamatsurat'];
$keterangan=$_POST['keterangan'];
if ($_POST['almayah']=="on"){
	$almayah=1;
} else {
	$almayah=0;
}
if ($_POST['almibu']=="on"){
	$almibu=1;
} else {
	$almibu=0;
}
$idtahunajaran=$_POST['idtahunajaran'];
$idtingkat=$_POST['idtingkat'];
$idkelas=(int)$_POST['idkelas'];
$departemen=$_POST['departemen'];

		$foto=$_FILES["file_data"];
		$uploadedfile = $foto['tmp_name'];
		$uploadedtypefile = $foto['type'];
		$uploadedsizefile = $foto['size'];
		if (strlen((string) $uploadedfile)!=0){
			//$gantifoto=", foto='$foto_data'";
		if($uploadedtypefile=='image/jpeg')
		$src = imagecreatefromjpeg($uploadedfile);
		$filename = "x.jpg";
		[$width, $height]=getimagesize($uploadedfile);
		if ($width<$height){
		$newheight=170;
		$newwidth=113;
		} else if ($width>$height){
		$newwidth=170;
		$newheight=113;
		}
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagejpeg($tmp,$filename,100);
		imagedestroy($src);
		imagedestroy($tmp); // NOTE: menghapus file di temp
		$foto_data=addslashes(fread(fopen($filename,"r"),filesize($filename)));
		} 
		/*
		$nama_file = $_FILES['file_data']['name'];
		$ukuran    = $_FILES['file_data']['size'];
		$tipe_file = $_FILES['file_data']['type'];
		$file_data = $_FILES['file_data']['tmp_name'];
		
		if($file_data != "") {
			$foto_data = addslashes(fread(fopen($file_data, "r"), filesize($file_data)));
		}*/

$date=date('j');
$month=date('m');
$year=date('Y');
$kumplit=$year."-".$month."-".$date;
OpenDb();
BeginTrans();
$success=0;
$sql_cek ="SELECT nis from jbsakad.siswa  where nis ='$nis'";
$hasil_cek=QueryDb($sql_cek);
if (mysqli_fetch_row($hasil_cek)>0){
?>
<script language="javascript">
alert ('Maaf nis <?=$nis?> sudah digunakan');
//opener.close();
</script>
<?php
} else {

$sql="INSERT INTO jbsakad.siswa SET nis='$nis',nama='$nama',panggilan='$panggilan',tahunmasuk='$tahunmasuk',idangkatan='$idangkatan',idkelas=$idkelas,suku='$suku',agama='$agama',status='$status',kondisi='$kondisi',kelamin='$kelamin',tmplahir='$tmplahir',tgllahir='$lahir',warga='$warga',anakke=$urutananak,jsaudara=$jumlahanak,bahasa='$bahasa',berat=$berat,tinggi=$tinggi,darah='$gol',foto='$foto_data',alamatsiswa='$alamatsiswa',kodepossiswa='$kodepos',telponsiswa='$telponsiswa',hpsiswa='$hpsiswa',emailsiswa='$emailsiswa',kesehatan='$kesehatan',asalsekolah='$sekolah',ketsekolah='$ketsekolah',namaayah='$namaayah',namaibu='$namaibu',almayah=$almayah,almibu=$almibu,pendidikanayah='$pendidikanayah',pendidikanibu='$pendidikanibu',pekerjaanayah='$pekerjaanayah',pekerjaanibu='$pekerjaanibu',wali='$namawali',penghasilanayah=$penghasilanayah,penghasilanibu=$penghasilanibu,alamatortu='$alamatortu',telponortu='$telponortu',hportu='$hportu',emailortu='$emailortu',alamatsurat='$alamatsurat',keterangan='$keterangan'";
QueryDbTrans($sql,$success);


$sql_dept="INSERT INTO jbsakad.riwayatdeptsiswa SET nis='$nis',departemen='$departemen',mulai='$kumplit'";
if ($success)
QueryDbTrans($sql_dept,$success);


$sql_kls="INSERT INTO jbsakad.riwayatkelassiswa SET nis='$nis',idkelas=$idkelas,mulai='$kumplit'";
if ($success)
QueryDbTrans($sql_kls,$success);

if ($success){
	CommitTrans();
	?>
	<script language="javascript">
		parent.opener.refresh_after_add();
		window.close();
			</script>
	<?php
}Else{
		RollbackTrans();
		?>
		<script language="javascript">
		alert ('Gagal simpan data');
		</script>
<?php
	}
	}
	CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Siswa[ADD]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">
var angkatan=document.main.angkatan.value;
var tahunmasuk=document.main.tahunmasuk.value;
var nis=document.main.nis.value;
var nama=document.main.nama.value;
var panggilan=document.main.panggilan.value;
var kelamin=document.main.kelamin.value;
var tmplahir=document.main.tmplahir.value;
var tgllahir=document.main.tgllahir.value;
var blnlahir=document.main.blnlahir.value;
var thnlahir=document.main.thnlahir.value;
var suku=document.main.suku.value;
var agama=document.main.agama.value;
var status=document.main.status.value;
var kondisi=document.main.kondisi.value;
var warga=document.main.warga.value;
var urutananak=document.main.urutananak.value;
var jumlahanak=document.main.jumlahanak.value;
var bahasa=document.main.bahasa.value;
var foto=document.main.foto.value;
var alamatsiswa=document.main.alamatsiswa.value;
var kodepos=document.main.kodepos.value;
var telponsiswa=document.main.telponsiswa.value;
var hpsiswa=document.main.hpsiswa.value;
var emailsiswa=document.main.emailsiswa.value;
var departemen=document.main.departemen.value;
var sekolah=document.main.sekolah.value;
var ketsekolah=document.main.ketsekolah.value;
var gol=document.main.gol.value;
var berat=document.main.berat.value;
var tinggi=document.main.tinggi.value;
var kesehatan=document.main.kesehatan.value;
var namaayah=document.main.namaayah.value;
var namaibu=document.main.namaibu.value;
var pendidikanayah=document.main.pendidikanayah.value;
var pendidikanibu=document.main.pendidikanibu.value;
var pekerjaanayah=document.main.pekerjaanayah.value;
var pekerjaanibu=document.main.pekerjaanibu.value;
var penghasilanayah=document.main.penghasilanayah.value;
var penghasilanibu=document.main.penghasilanibu.value;
var namawali=document.main.namawali.value;
var alamatortu=document.main.alamatortu.value;
var telponortu=document.main.telponortu.value;
var hportu=document.main.hportu.value;
var emailortu=document.main.emailortu.value;
var alamatsurat=document.main.alamatsurat.value;
var keterangan=document.main.keterangan.value;
</script>
</head>
<body background="../images/buatdibawah_500.jpg">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />please&nbsp;wait...
</div>
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="idkelas" id="idkelas" value="<?=$idkelas?>">
<input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat?>">
<input type="hidden" name="idtahunajaran" id="idtahunajaran" value="<?=$idtahunajaran?>">

</body>
</html>