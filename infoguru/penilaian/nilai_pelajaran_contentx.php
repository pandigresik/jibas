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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
OpenDb();
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
if(isset($_REQUEST["idaturan"]))
	$idaturan = $_REQUEST["idaturan"];
if(isset($_REQUEST["perubahan"]))
	$perubahan = $_REQUEST["perubahan"];
if(isset($_REQUEST["manual"]))
	$manual = $_REQUEST["manual"];
if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];
	
$sql="SELECT p.nama, p.replid AS pelajaran, a.dasarpenilaian, j.jenisujian, j.replid AS jenis FROM jbsakad.aturannhb a, jbsakad.pelajaran p, jenisujian j WHERE a.replid='$idaturan' AND p.replid = a.idpelajaran AND a.idjenisujian = j.replid";
$result=QueryDb($sql);

$row=@mysqli_fetch_array($result);
$namapel = $row['nama'];
$pelajaran = $row['pelajaran'];
$aspek = $row['dasarpenilaian'];
$namajenis = $row['jenisujian'];
$jenis = $row['replid'];

if ($op=="jfd84rkj843h834jjduw3"){
	$sql_hapus_ujian="DELETE FROM jbsakad.ujian WHERE replid='".$_REQUEST['replid']."'";
	$result_hapus_ujian=QueryDb($sql_hapus_ujian);
	$sql_hapus_nau="DELETE FROM jbsakad.nau WHERE idaturan='".$_REQUEST['idaturan']."'";
	$result_hapus_nau=QueryDb($sql_hapus_nau);
	$sql_hapus_rataus="DELETE FROM jbsakad.rataus ".
					//"WHERE nis='".$row_siswa['nis']."' ".
					"WHERE idkelas='$kelas' ".
					"AND idsemester='$semester' ".
					"AND idpelajaran='$pelajaran' ".
					"AND idjenis='$jenis' ";
					//"AND idjenis='$jenis_penilaian' ";
	//echo $sql_hapus_rataus;
	$result_hapus_rataus=QueryDb($sql_hapus_rataus);
	if ($result_hapus_rataus && $result_hapus_ujian && $result_hapus_nau){
	?>
    <!--
	<script language = "javascript" type = "text/javascript">
                alert("Ujian udah diapus");
				//alert("Data Nilai Pelajaran berhasil diinput");
				//parent.opener.segarkan();
				//window.close();     
    </script>
	-->
    <?php
	}
}
if (isset($_REQUEST['hitung'])){
$x=1;
	$sql_get_ujian="SELECT * FROM jbsakad.ujian WHERE idpelajaran='$pelajaran' AND idjenis='$jenis' AND idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan' ORDER by tanggal ASC";
	//echo $sql_get_ujian."<br>";
	$result_get_ujian=QueryDb($sql_get_ujian);
	//$i=1;
	while ($row_get_ujian=@mysqli_fetch_array($result_get_ujian)){
	$sql_hapus_bobotnau="DELETE FROM jbsakad.infobobotnau WHERE idujian='".$row_get_ujian['replid']."'";
	//echo $sql_hapus_bobotnau."<br>";
	$result_hapus_bobotnau=QueryDb($sql_hapus_bobotnau);
	if ($result_hapus_bobotnau){
	?>
    <!--<script language = "javascript" type = "text/javascript">
                alert("Infobobotnau dihapus");
				//alert("Data Nilai Pelajaran berhasil diinput");
				//parent.opener.segarkan();
				//window.close();     
    </script>
	-->
	<?php
	}
	}

	while ($x<=$_REQUEST['jumujian']){
	if ($_REQUEST['bobot'.$x]<>""){
		$idujian=$_REQUEST['jenisujian'.$x];
		$bobot=$_REQUEST['bobot'.$x];
		$sql_insert_info_bobot="INSERT INTO jbsakad.infobobotnau SET idujian='$idujian',idaturan='$idaturan'";
		$result_insert_info_bobot=QueryDb($sql_insert_info_bobot);
		
		$sql_get_last_id_info="SELECT LAST_INSERT_ID(replid) FROM jbsakad.infobobotnau ORDER BY replid DESC LIMIT 1";
		$result_get_last_id_info=QueryDb($sql_get_last_id_info);
		$row_get_last_id_info=@mysqli_fetch_row($result_get_last_id_info);
		$last_id_info=$row_get_last_id_info[0];
		
		$sql_insert_bobot="INSERT INTO jbsakad.bobotnau ".
		" SET idinfo='$last_id_info',idujian='$idujian',bobot='$bobot'";
		$result_insert_bobot=QueryDb($sql_insert_bobot);
		if(@mysqli_affected_rows($conn)($conn) >= 0) {
    ?> <!--
    <script language = "javascript" type = "text/javascript">
                alert("Bobotnau udah diisi");
				//alert("Data Nilai Pelajaran berhasil diinput");
				//parent.opener.segarkan();
				//window.close();     
        </script>
    --><?php


   } else {
    ?>
        <script language = "javascript" type = "text/javascript">
               alert("Gagal menambah data");
               //parent.opener.segarkan();
			   //window.close();
		</script>
    <?php
    }
	}
	$x++;
	}
	//Mulai ngoprek disini
	///Ambil nis dari siswa berdasarkan kelas
	$sql_get_nis_siswa="SELECT nis FROM jbsakad.siswa WHERE idkelas='$kelas'";
	//echo $sql_get_nis_siswa."<br>";
	$result_get_nis_siswa=QueryDb($sql_get_nis_siswa);
	$culip=1;
	while ($row_get_nis_siswa=@mysqli_fetch_array($result_get_nis_siswa)){
	//Ambil idujian
	$sql_get_ujian="SELECT replid FROM jbsakad.ujian WHERE idpelajaran='$pelajaran' AND idkelas='$kelas' AND idsemester='$semester' AND idjenis='$jenis_penilaian' AND idaturan='$idaturan'";
	//echo $sql_get_ujian."<br>";
	$result_get_ujian=QueryDb($sql_get_ujian);
	$ujian_culip=0;
	while ($row_get_ujian=@mysqli_fetch_array($result_get_ujian)){
	//Ambil info bobot
	$sql_get_info_bobot="SELECT replid FROM jbsakad.infobobotnau WHERE idujian='".$row_get_ujian['replid']."'";
	//echo $sql_get_info_bobot."<br>";
	$result_get_info_bobot=QueryDb($sql_get_info_bobot);
	$row_get_info_bobot=@mysqli_fetch_array($result_get_info_bobot);
	//Ambil bobot
	$sql_get_bobot="SELECT bobot FROM jbsakad.bobotnau WHERE idinfo='".$row_get_info_bobot['replid']."'";
	//echo $sql_get_bobot."<br>";
	$result_get_bobot=QueryDb($sql_get_bobot);
	$row_get_bobot=@mysqli_fetch_array($result_get_bobot);
	//Ambil nilai ujian
	$sql_get_nilai="SELECT nilaiujian FROM jbsakad.nilaiujian WHERE idujian='".$row_get_ujian['replid']."' AND nis='".$row_get_nis_siswa['nis']."'";
	//echo $sql_get_nilai."<br>";
	$result_get_nilai=QueryDb($sql_get_nilai);
	$row_get_nilai=@mysqli_fetch_array($result_get_nilai);
	//Hitung NA
	$na[$ujian_culip]=(int)$row_get_bobot['bobot']*(int)$row_get_nilai['nilaiujian'];
	$bbt[$ujian_culip]=(int)$row_get_bobot['bobot'];
	//echo "NAU=".$na[$ujian_culip]."<br>";
	//echo "bobot=".$bbt[$ujian_culip]."<br>";
	//Bulatkan NA
	$nakhir=round($na,10,2);
	
	
	//echo "<hr>";
	$ujian_culip++;
	
	}
	//$tetel=$ujian_culip;
	$jum=0;
	foreach ($na as $value){
	$jum=$jum+$value;
	}
	$jumbbt=0;
	foreach ($bbt as $values){
	$jumbbt=$jumbbt+$values;
	}
	$ratanya=$jum/$jumbbt;
	$ratabulat=round($ratanya,2); 
	//echo " ,  Jumlah Nilai=".$jum;
	//echo " ,  Jumlah Bobot=".$jumbbt;
	//echo " ,  Nilai Rata-rata=".$ratanya;
	//echo " ,  Nilai Rata-rata Bulat=".$ratabulat;
	//Hapus dari tabel nau
	$sql_hapus_nau="DELETE FROM jbsakad.nau WHERE nis='".$row_get_nis_siswa['nis']."' AND idkelas='$kelas' AND idsemester='$semester' AND idjenis='$jenis_penilaian' AND idaturan='$idaturan'";
	//echo $sql_hapus_nau."<br>";
	$result_hapus_nau=QueryDb($sql_hapus_nau);
	
	//Insert ke tabel nau
	$sql_insert_nau="INSERT INTO jbsakad.nau SET nis='".$row_get_nis_siswa['nis']."',idkelas='$kelas',idsemester='$semester',idjenis='$jenis_penilaian',idpelajaran='$pelajaran',nilaiAU='$ratabulat',idaturan='$idaturan'";
	//echo $sql_insert_nau."<br>";
	$result_insert_nau=QueryDb($sql_insert_nau);
	if ($result_insert_nau){
		$manual="";
		$perubahan="";
	?>
        <!--<script language = "javascript" type = "text/javascript">
               alert("Nilai akhir dunkz...<?=$ratabulat?>");
               segarkan();
			   //window.close();
		</script>-->
    <?php
	
	}
	}
	
//Ngoprek...............
}
//Hapus Nilai Akhir
if ($op=="osdiui4903i03j490dj"){
	$sql_hapus_nau="DELETE FROM jbsakad.nau WHERE idpelajaran='$pelajaran' AND idkelas='$kelas' AND idsemester='$semester' AND idjenis='$jenis_penilaian' AND idaturan='$idaturan'";
	$result_hapus_nau=QueryDb($sql_hapus_nau);
	if ($result_hapus_nau){
	?>
    <script language = "javascript" type = "text/javascript">
                //alert("Data Nilai akhir udah diapus");
				//alert("Data Nilai Pelajaran berhasil diinput");
				segarkan();
				//window.close();     
    </script>
    <?php
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<title>Aturan Perhitungan Nilai Rapor['Menu']</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function segarkan(){
	var manual=document.tampil_nilai_pelajaran.manual.value;
	if (manual.length>0){
	document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&manual=1";
	} else {
	document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>";
	}
}

function segarkan_juga(){
document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&manual=1";
}

function segarkan_ada(){
	var manual=document.tampil_nilai_pelajaran.manual.value;
	if (manual.length>0){
document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&perubahan=1&manual=1";
	} else {
	document.location.href="nilai_pelajaran_content.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&perubahan=1";
	}
}

function tambah(){
newWindow('nilai_pelajaran_add.php?idaturan=<?=$idaturan?>&semester=<?=$semester?>&kelas=<?=$kelas?>&tahun=<?=$tahun?>','TambahNilai',590,462,'resizable=1,scrollbars=1,status=1,toolbar=0');
}
function clist(cno) {
	//alert(cno);
	//document.tampil_nilai_pelajaran.check.value = cno;
}
/*
function validate(){
var pilih;
	pilih = document.tampil_nilai_pelajaran.pilih.value;
	cek = document.tampil_nilai_pelajaran.jumujian.value;
//	t_max = document.tampil_nilai_pelajaran.t_max.value;

	if(pilih.length == 0){
		alert("Anda harus menentukan jenis perhitungan untuk menghitung nilai akhir");
		return false;
	}
	else if(pilih == 1){
		if(cek.length == 0){
			alert("Anda harus menentukan jenis penilaian untuk menghitung rata-rata nilai");
		return false;
		}
		eval("bobot = document.tampil_nilai_pelajaran.bobot" + cek + ".value;");
		if(bobot.length == 0){
			alert("Anda harus mengisi bobot jenis penilaian untuk menghitung rata-rata nilai");
		return false;
		}
	}
	return true;
}
*/
function validate(){
//alert ('Cek dulu ah');
var jumuji = document.tampil_nilai_pelajaran.jumujian.value;
var yy=1;
//alert ('Jumuji = '+jumuji+'Yy='+yy);
while (yy<=jumuji){
	var yyy = "jenisujian"+yy;
	var bob = "bobot"+yy;
	var cek = document.getElementById(yyy).checked;
	var bobod = document.getElementById(bob).value;
	//alert ('Cek ke '+cek);//+cek+', Bobot='+bob);
	//alert ('Bobot '+bobod);
	if (cek==true && bobod.length==0){
		alert ('Bobot ujian yang di ceklist harus diisi !');
		document.getElementById(bob).focus();
		return false;
	}
	if (bobod.length!=0 && cek==false){
		alert ('Bobot ujian yang diisi harus di ceklist !');
		document.getElementById(yyy).focus();
		return false;
	}
	if (bobod.length!=0 && cek==true){
		if (isNaN(bobod)){
			alert ('Bobot ujian harus berupa bilangan !');
			document.getElementById(bob).value=="";
			document.getElementById(bob).focus();
			return false;
		} 
	}
	yy++;
}
}
function hitungmanual(){
alert('Asup manual');
//document.location.href="input_manual_nau.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>";
}
function hapus(replid,i,nama) {
	//alert(cno);
	if (confirm('Anda yakin akan menghapus nilai '+nama+'-'+i+' ini ?')){
	document.location.href="nilai_pelajaran_content.php?op=jfd84rkj843h834jjduw3&dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>&replid="+replid;
	}
}

function ubah_nau(replid){
newWindow('ubah_nau_persiswa.php?replid='+replid,'UbahNilaiAkhirUjian',447,252,'resizable=1,scrollbars=0,status=1,toolbar=0');
}

function hapus_nau(){
	if (confirm('Anda yakin akan menghapus data nilai akhir ini?')){
	document.location.href="nilai_pelajaran_content.php?op=osdiui4903i03j490dj&dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>";
	}
}

</script>
</head>
<body topmargin="0" leftmargin="0">
<form name="tampil_nilai_pelajaran" action="nilai_pelajaran_content.php" method="post" onSubmit="return validate();">
<input type="hidden" name="semester" value="<?=$semester?>" />
<input type="hidden" name="kelas" value="<?=$kelas?>" />
<input type="hidden" name="idaturan" value="<?=$idaturan?>" />
<input type="hidden" name="manual" value="<?=$manual?>" />
<input type="hidden" name="perubahan" value="<?=$perubahan?>" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%">
   	<tr>
    	<td>
        <table width="100%" border="0">
        <tr>
        	<!--<td width="20%" rowspan="4"></td>-->
            <td width="17%"><strong>Pelajaran</strong></td>
            <td><strong>: <?=$namapel ?> </strong></td>
            <td rowspan="2"></td>
        </tr>
        <tr>
            <td><strong>Aspek Penilaian</strong></td>
            <td><strong>: <?=$aspek?></strong></td>            
        </tr>
    	<tr>
            <td><strong>Jenis Pengujian</strong></td>
            <td><strong>: <?=$namajenis?></strong></td>            
           	<td align="right">
            <a href="JavaScript:cetak_excel()"><img src="../images/ico/excel.png" border="0" onMouseOver="showhint('Cetak Excel!', this, event, '50px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;           
            <a href="#" style="cursor:pointer" onClick="<?php if (\PERUBAHAN==1) { ?> segarkan_ada(); <?php } else { ?>segarkan() <?php } ?>"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh', this, event, '120px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
	  		<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>
	  		<a href="#" style="cursor:pointer" onClick="tambah();" ><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah Jenis Ujian Baru', this, event, '120px')"/>&nbsp;Tambah</a>&nbsp;
	  		<?php  }  ?>
      		</td>
  		</tr>
        </table>
        <br />
	<?php
    $sql_cek_ujian = "SELECT * FROM jbsakad.ujian WHERE idaturan='$idaturan' AND idkelas='$kelas' AND idsemester='$semester' ORDER by tanggal ASC";
    
    $result_cek_ujian=QueryDb($sql_cek_ujian);
    $jumlahujian=@mysqli_num_rows($result_cek_ujian);
            
    ?>
  		<table border="1" width="100%" id="table" class="tab">
       	<tr>
            <td height="30" class="headerlong" align="center" width="4%">No.</td>
            <td height="30" class="headerlong" align="center" width="10%">N I S</td>
            <td height="30" class="headerlong" align="center" width="*">Nama</td>
		<?php
       
	$i=1;
	while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
		$idujian[$i] = $row_cek_ujian['replid'];
		$tgl = explode("-",(string) $row_cek_ujian['tanggal']);
	?>
	   <td height="30" width="30" class="headerlong" align="center" onMouseOver="showhint('Deskripsi :\n <?=$row_cek_ujian['deskripsi']?>', this, event, '120px')"><?=$namajenis."-".$i?>
		<br /><?=$tgl[2]."/".$tgl[1]."/".substr($tgl[0],2)?><br />
	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>
		<a href="JavaScript:edit()"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Ujian!', this, event, '50px')" /></a>&nbsp;
		<a href="JavaScript:hapus(<?=$row_cek_ujian['replid']?>, <?=$i?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Ujian!', this, event, '50px')" /></a>
	<?php } ?>
    		</td>
	<?php
		$i++;
	}
    ?>
            <td height="30" class="headerlong" align="center">Rata2 Siswa</td>
            <td height="30" class="headerlong" align="center">NA <?=$namajenis?>
<?php $sql_get_nau_per_kelas="SELECT nilaiAU,keterangan FROM jbsakad.nau WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan'";
		
	$result_get_nau_per_kelas=QueryDb($sql_get_nau_per_kelas);
	if (@mysqli_num_rows($result_get_nau_per_kelas)<>0){           
		$manual=@mysqli_num_rows($result_get_ket_nau_per_kelas);          		
		if (SI_USER_LEVEL() != $SI_USER_STAFF) {	?>	
             
             <br /><a href="JavaScript:hapus_nau()"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Nilai Akhir Ujian!', this, event, '100px')" /></a>&nbsp;
            <!--<img src="../images/ico/hapus.png" onClick="hapus_nau()" style="cursor:pointer" onMouseOver="showhint('Hapus Nilai Akhir Ujian', this, event, '120px')"/>-->
		<?php 
        }
    }	
?>
    		</td>
		</tr>
<?php $sql_siswa="SELECT * FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif=1 ORDER BY nama ASC";
	$result_siswa=QueryDb($sql_siswa);
	$cnt=1;
	
	while ($row_siswa=@mysqli_fetch_array($result_siswa)){
		$nilai = 0;
?>
  		<tr height="25">
            <td align="center"><?=$cnt?></td>
            <td align="center">
            <a href="JavaScript:detail('<?=$row_siswa['replid']?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Lihat Detail Siswa!', this, event, '100px')" /><?=$row_siswa['nis']?></a>
           	</td>
            <td><?=$row_siswa['nama']?></td>
            <td>
	<?php          
        $uji=1;			
        for ($j=1;$j<=count($idujian);$j++) {		
            $sql_cek_nilai_ujian="SELECT * FROM jbsakad.nilaiujian WHERE idujian='".$idujian[$j]."' AND nis='".$row_siswa['nis']."'";
            //echo $sql_cek_nilai_ujian;
            $result_cek_nilai_ujian=QueryDb($sql_cek_nilai_ujian);
            $row_cek_nilai_ujian=@mysqli_fetch_array($result_cek_nilai_ujian);
            $nilai = $nilai+$row_cek_nilai_ujian['nilaiujian'];					
            
            if (@mysqli_num_rows($result_cek_nilai_ujian)>0){
                if (SI_USER_LEVEL() != $SI_USER_STAFF) {
    ?>
			 <a href="JavaScript:ubah_nilai()"><?=$row_cek_nilai_ujian['nilaiujian']?></a>
             
			<?php } else { ?>
        			<?=$row_cek_nilai_ujian['nilaiujian']?>
			<?php } 
            
				if ($row_cek_nilai_ujian['keterangan']<>"")
					echo "<strong><font color='blue'>)*</font></strong>";
           	} else {                 
				if (SI_USER_LEVEL() != $SI_USER_STAFF) {
        	?>
			<a href="JavaScript:tambah_nilai()"><img src="../images/ico/tambah.png" border="0"></a>
			<?php
				}
			}
			
			$uji++;
		} //endfor
			?>
            </td>
    		<td align="center"><?=round($nilai/count($idujian),2); ?></td>
    		<td align="center">
	<?php $sql_get_nau_per_nis="SELECT nilaiAU,replid,keterangan FROM jbsakad.nau WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan' ";
                
        $result_get_nau_per_nis=QueryDb($sql_get_nau_per_nis);
        $row_get_nau_per_nis=@mysqli_fetch_array($result_get_nau_per_nis);
        if (SI_USER_LEVEL() != $SI_USER_STAFF) {
    ?>
        	<a href="#" onMouseOver="showhint('Ubah Nilai Akhir Ujian', this, event, '120px')" onClick="ubah_nau('<?=$row_get_nau_per_nis['replid']?>')"><?=$row_get_nau_per_nis['nilaiAU']?></a>
    <?php } else { ?>
            <?=$row_get_nau_per_nis['nilaiAU']?>
    <?php  }
    
        if ($row_get_nau_per_nis['keterangan']<>"")
            echo "<font color='#067900'><strong>)*</strong></font>";
    ?>
			</td>
    	</tr>
<?php
 	$cnt++;
  	}
?>
		<tr>
        	<td height='25' class='header' align='center' colspan='3'>Rata-rata Kelas</td>
            
<?php  $sql_cek_ujian_ratakelas="SELECT replid FROM jbsakad.ujian WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan' ORDER by tanggal ASC";
	$result_cek_ujian_ratakelas=QueryDb($sql_cek_ujian_ratakelas);
	while ($row_cek_ujian_ratakelas=@mysqli_fetch_array($result_cek_ujian_ratakelas)){
		$sql_hitung_ratakelas="SELECT SUM(nilaiujian) as nilaiujian, COUNT(*) as jumlahnya FROM jbsakad.nilaiujian WHERE idujian='".$row_cek_ujian_ratakelas['replid']."'";
		//echo $sql_hitung_ratakelas;
		$result_hitung_ratakelas=QueryDb($sql_hitung_ratakelas);
		$row_hitung_ratakelas=@mysqli_fetch_array($result_hitung_ratakelas);
		$rataratakelas=$row_hitung_ratakelas['nilaiujian']/$row_hitung_ratakelas['jumlahnya'];
		echo "<td align='center' bgcolor='#FFFFFF'>".round($rataratakelas,2)."</td>";
  	}
?>
	</tr>
<?php
	//} else {
	//	$tdasli=5;
	//echo "<tr><td height='25' align='center' colspan='(int)$tdasli+$jumlahujian'>Tidak ada data nilai ujian</td></tr>";
	//}
?>
	</table>
	<script language='JavaScript'>
		Tables('table', 1, 0);
    </script>
<?php
  if ($jumlahujian<>0){
  ?>
<div align="center"><strong><font color="blue">)*</font> => ada keterangan 
<strong>,<font color="#067900">)*</font> => Nilai Akhir Siswa mengalami perubahan </strong>
<?php
if ($perubahan==1){
	?>
	<br><strong><font color="#D69700">Ada data nilai ujian yang berubah, silakan hitung ulang nilai akhir !</font></strong>
	<?php
	}
	?></div>
    
    
    	</td>
  </tr>
  <tr>
  	<td>
    <br /><BR />
    <fieldset style="background-color:#FFFFC6"><legend>Hitung Nilai Akhir <?=$row_jenis_ujian['jenisujian']?> Berdasarkan</legend>
    
    <table width="100%" border="0">
    <tr>
    	<td class="style2">A. Perhitungan Otomatis</td>
  	</tr>
  	<tr>
   <?php
	  if (SI_USER_LEVEL() == $SI_USER_STAFF) {
		$dis="disabled class='disabled'";
		$dis_btn="disabled";
	}
	  ?>
    	<td><hr width="50%" align="left"/><input type="hidden" name="pilih" value="1">
    	<table id="table" class="tab" width="50%" border="1">
			<tr>
				<td width="85%" class="header" height="30"><?=$row_jenis_ujian['jenisujian']?></td>
				<td width="15%" class="header" align="center" height="30">Bobot</td>
			</tr>
                 <?php
	$sql_cek_ujian="SELECT * FROM jbsakad.ujian WHERE idkelas='$kelas' AND idsemester='$semester' AND idaturan='$idaturan' ORDER by tanggal ASC";
	$result_cek_ujian=QueryDb($sql_cek_ujian);
	$ibobot=1;
	while ($row_cek_ujian=@mysqli_fetch_array($result_cek_ujian)){
	$sql_get_bobotnya="SELECT b.bobot FROM jbsakad.infobobotnau i, jbsakad.bobotnau b WHERE i.idujian='".$row_cek_ujian['replid']."' AND b.idinfo=i.replid";
	//echo $sql_get_bobotnya;
	$result_get_bobotnya=QueryDb($sql_get_bobotnya);
	$nilai_bobotnya=@mysqli_fetch_row($result_get_bobotnya);
	?>
    		<tr>
				<td width="85%" height="25">
					<?php 	$stat="";
							if ($nilai_bobotnya>0){ 
							$stat="checked";
							?>
							<script language='JavaScript'>
            					//clist(<?=$ibobot?>);
      						</script>
							<?php
							}
						?>
                    <input <?=$dis?> type="checkbox" id="jenisujian<?=$ibobot ?>" name="jenisujian<?=$ibobot ?>" value="<?=$row_cek_ujian['replid'] ?>" onClick="clist(<?=$ibobot ?>);" <?=$stat?>  >
					
					<?=$namajenis."-".$ibobot." (".format_tgl($row_cek_ujian['tanggal']).")"; ?></td>
				<td width="15%" align="center" height="25">
                    <input <?=$dis?> type="text" name="bobot<?=$ibobot ?>" id="bobot<?=$ibobot ?>" size="1" maxlength="1"
					<?php
						if (@mysqli_num_rows($result_get_bobotnya)>0){ 
						echo "value='".$nilai_bobotnya[0]."'";
						} else { 
						echo "value=''";
						}
						?>
						>
                    </td>
				</tr>
				
				<?php
				$ibobot++;
				}
				?><input type="hidden" name="jumujian" id="jumujian" value="<?=$ibobot-1?>" />
        </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
            <input <?=$dis_btn?> type="Submit" name="hitung" value="Hitung dan Simpan Nilai Akhir <?=$row_jenis_ujian['jenisujian'] ?>" class="but" >
            <hr width="50%" align="left" /></td>
  		</tr>
 		<tr>
    		<td><span class="style2">B. Perhitungan Manual</span></td>
  		</tr>
  		<tr>
    		<td>
	<a class="but" <?php
				 if (SI_USER_LEVEL() != $SI_USER_STAFF) {
			?>
				href="input_manual_nau.php?dasar_penilaian=<?=$dasar_penilaian?>&departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&jenis_penilaian=<?=$jenis_penilaian?>&kelas=<?=$kelas?>&semester=<?=$semester?>&tahun=<?=$tahun?>&idaturan=<?=$idaturan?>" <?php
	}
				?>>Hitung Manual Nilai Akhir <?=$row_jenis_ujian['jenisujian'] ?></a>	
	<!--<input type="button" name="hitungmanual" value="Hitung Manual Nilai Akhir <?=$row_jenis_ujian['jenisujian'] ?>" class="but" onClick="hitungmanual();">--></td>
  		</tr>
	</table>
    </fieldset>
    </td>
</tr>
  <?php
	}
		?>
</table>
</form>
</body>
</html>
<script language="javascript">
var jumujian=document.getElementById("jumujian").value;
var x;
for (x=1;x<=jumujian;x++){
var sprytextfield1 = new Spry.Widget.ValidationTextField("bobot"+x);
}
</script>