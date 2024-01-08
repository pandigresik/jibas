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
require_once('../cek.php');

$depasal=$_REQUEST['depasal'];
$departemenawal=$_REQUEST['departemenawal'];
$tahunajaranawal=$_REQUEST['tahunajaranawal'];
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
if (isset($_REQUEST['angkatan']))
	$angkatan=$_REQUEST['angkatan'];
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
if (isset($_REQUEST['pilihan']))
	$pilihan=(int)$_REQUEST['pilihan'];
	
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

OpenDb();
$sql_ajaran = "SELECT replid,tglmulai FROM jbsakad.tahunajaran WHERE replid=$tahunajaranawal";
$result_ajaran = QueryDb($sql_ajaran);
$row_ajaran = mysqli_fetch_array($result_ajaran);
$tglmulai = $row_ajaran['tglmulai'];

if (isset($_REQUEST['op']))
	$op=$_REQUEST['op'];

if ($op=="hgiu82kjs98uqjq89wuj89sga"){
	$nis=$_REQUEST['nis'];
	
	OpenDb();
	//ambil nis lama
	$sql_lulus_getnislama="SELECT nislama FROM jbsakad.riwayatdeptsiswa WHERE nis='$nis'";
	$result_nislama=QueryDbTrans($sql_lulus_getnislama,$success);
	$row_nislama=mysqli_fetch_array($result_nislama);
	$nis_lama = $row_nislama['nislama'];
	
	$sql_lulus_get_nis="SELECT * FROM jbsakad.riwayatdeptsiswa WHERE nis='$nis_lama' ORDER BY mulai DESC LIMIT 1";
	$result_nis=QueryDbTrans($sql_lulus_get_nis, $success);
	$row_nis=mysqli_fetch_array($result_nis);	
	$replid_dept = $row_nis['replid'];
	
	$sql_lulus_get_kelas="SELECT r.replid, r.idkelas, k.idtingkat, k.idtahunajaran FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k WHERE r.nis='$nis_lama' AND r.idkelas = k.replid ORDER BY r.mulai DESC LIMIT 1";
	$result_kelas=QueryDbTrans($sql_lulus_get_kelas,$success);
	$row_kelas=mysqli_fetch_array($result_kelas);
	$replid_kelas = $row_kelas['replid'];
	$kelas_lama = $row_kelas['idkelas'];
	$tingkat_lama = $row_kelas['idtingkat'];
	$ajaran_lama = $row_kelas['idtahunajaran'];
		
	if ($nis_lama == $nis) {//kalo ada record nislama
		BeginTrans();
		$success=0;
				
		$sql_lulus_del_dept="DELETE FROM jbsakad.riwayatdeptsiswa WHERE nis='$nis' AND departemen='$departemen' AND aktif = 1";
		QueryDbTrans($sql_lulus_del_dept, $success);
		
		if ($success){//aktifkan lagi nis di dep sebelumnya
			$sql_lulus_update_dept="UPDATE jbsakad.riwayatdeptsiswa SET aktif=1 WHERE replid = '$replid_dept'";			
			QueryDbTrans($sql_lulus_update_dept, $success);
		}		
		
		if ($success){//hapus dept yg sekarang
			$sql_lulus_del_kelas="DELETE FROM jbsakad.riwayatkelassiswa WHERE nis='$nis' AND idkelas='$kelas' AND aktif = 1";
			QueryDbTrans($sql_lulus_del_kelas, $success);
		}		
		
		if ($success){//aktifkan lagi nis di dep sebelumnya
			$sql_lulus_update_kelas="UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE replid='$replid_kelas'";
			QueryDbTrans($sql_lulus_update_kelas, $success);
		}	
		
		if ($success){
			$sql1="DELETE FROM jbsakad.alumni WHERE nis='$nis_lama'";
			QueryDbTrans($sql1, $success);
		}
				
		if ($success){//aktifkan lagi nis di siswa sebelumnya
			$sql_lulus_update_siswa="UPDATE jbsakad.siswa SET aktif=1, idkelas='$kelas_lama' WHERE nis='$nis_lama'";
			QueryDbTrans($sql_lulus_update_siswa, $success);
		}
		
	} else { 
		
		if ($success){//hapus dept yg sekarang
			$sql_lulus_del_dept="DELETE FROM jbsakad.riwayatdeptsiswa WHERE nis='$nis' AND departemen='$departemen' AND aktif = 1";
			
			QueryDbTrans($sql_lulus_del_dept, $success);
		}		
		if ($success){//aktifkan lagi nis di dep sebelumnya
			$sql_lulus_update_dept="UPDATE jbsakad.riwayatdeptsiswa SET aktif=1 WHERE replid='$replid_dept'";
			
			QueryDbTrans($sql_lulus_update_dept, $success);
		}		
		if ($success){//hapus dept yg sekarang
			$sql_lulus_del_kelas="DELETE FROM jbsakad.riwayatkelassiswa WHERE nis='$nis' AND idkelas='$kelas' AND aktif = 1";
			
			QueryDbTrans($sql_lulus_del_kelas, $success);
		}		
		if ($success){//aktifkan lagi nis di dep sebelumnya
			$sql_lulus_update_kelas="UPDATE jbsakad.riwayatkelassiswa SET aktif=1 WHERE replid='$replid_kelas'";
			QueryDbTrans($sql_lulus_update_kelas, $success);
		}	
		
		if ($success){
			$sql1="DELETE FROM jbsakad.alumni WHERE nis='$nis_lama'";
			QueryDbTrans($sql1, $success);
		}
		
		if ($success){
			$sql1="DELETE FROM jbsakad.siswa WHERE nis='$nis'";
			QueryDbTrans($sql1, $success);
		}	
						
		if ($success){//aktifkan lagi nis di siswa sebelumnya
			$sql_lulus_update_siswa="UPDATE jbsakad.siswa SET aktif = 1, alumni = 0 WHERE nis='$nis_lama'";			
			QueryDbTrans($sql_lulus_update_siswa, $success);
		}
	}
	
	if ($success){
		CommitTrans();
		?>
		<script language="javascript">
		var idtingkat = parent.siswa_lulus_menu.document.menu.tingkat.value;
		var idkelas = parent.siswa_lulus_menu.document.menu.kelas.value; 
		var idtahunajaran = parent.siswa_lulus_menu.document.menu.tahunajaran.value;
		var pilihan = parent.siswa_lulus_menu.document.menu.pilihan.value; 
		var jenis = parent.siswa_lulus_menu.document.menu.jenis.value;
		var nis = parent.siswa_lulus_menu.document.menu.nis.value; 
		var nama = parent.siswa_lulus_menu.document.menu.nama.value;
		
		if (idtingkat == <?=$tingkat_lama?> && idtahunajaran == <?=$ajaran_lama?>) {
		
			parent.siswa_lulus_menu.location.href="siswa_lulus_menu.php?kelas=<?=$kelas_lama?>&departemen=<?=$departemenawal?>&tahunajaran=<?=$tahunajaranawal?>&tingkat=<?=$tingkat_lama?>&pilihan=2";
			parent.siswa_lulus_pilih.location.href="siswa_lulus_pilih.php?kelas=<?=$kelas_lama?>&pilihan=2&jenis=combo&departemen=<?=$departemenawal?>&tahunajaran=<?=$tahunajaranawal?>&tingkat=<?=$tingkat_lama?>";		
		} else {
			parent.siswa_lulus_menu.location.href="siswa_lulus_menu.php?kelas="+idkelas+"&departemen=<?=$departemenawal?>&tahunajaran=<?=$tahunajaranawal?>&tingkat="+idtingkat+"&jenis="+jenis+"&nis="+nis+"&nama="+nama+"&pilihan="+pilihan;
			if (jenis == 'combo')
				parent.siswa_lulus_menu.lihat_siswa();
			else if (jenis == 'text')
				parent.siswa_lulus_menu.cari_siswa();
			else if (jenis == 'button')
				parent.siswa_lulus_menu.lihat_siswa();
						//parent.siswa_lulus_pilih.location.href="siswa_lulus_pilih.php?kelas="+idkelas+"&pilihan=2&jenis=combo&departemen=<?=$departemenawal?>&tahunajaran=<?=$tahunajaranawal?>&tingkat="+idtingkat;		
		}
		</script>
		<?php
	} else {
		RollBackTrans();
	}
}
CloseDb();


$ERROR_MSG = "";
if ($op=="x2378e23dkofh73n25ki9234"){
	//cek kapasitas kelas tujuan
	$nis=$_REQUEST['nis'];
	$nisbaru=CQ($_REQUEST['nisbaru']);
	$kelasawal=$_REQUEST['kelasawal'];
	$ket=CQ($_REQUEST['ket']);

	OpenDb();
	$sql_kap_kelas_tujuan="SELECT kapasitas, idtingkat FROM jbsakad.kelas WHERE replid='$kelas'";
	$result_kap_kelas_tujuan=QueryDb($sql_kap_kelas_tujuan);
	$row_kap_kelas_tujuan=mysqli_fetch_array($result_kap_kelas_tujuan);
	$kap_kelas_tujuan=$row_kap_kelas_tujuan['kapasitas'];
	$tingkatawal=$row_kap_kelas_tujuan['idtingkat'];
	
	$sql_jum_kelas_tujuan="SELECT COUNT(nis) FROM jbsakad.siswa WHERE idkelas='$kelas' AND aktif = 1";
	$result_jum_kelas_tujuan=QueryDb($sql_jum_kelas_tujuan);
	$row_jum_kelas_tujuan=mysqli_fetch_row($result_jum_kelas_tujuan);
	$jum_siswa_tujuan = $row_jum_kelas_tujuan[0];
	CloseDb();
	
	if ((int)$kap_kelas_tujuan <= (int)$jum_siswa_tujuan){
		$ERROR_MSG = "Kapasitas kelas tujuan sudah penuh. Silahkan pilih kelas tujuan lain!";
	} else { 
		OpenDb();
		//$sql_lulus_getnislama="SELECT * FROM jbsakad.riwayatdeptsiswa WHERE nis='$nis'";
		$sql_lulus_getnislama="SELECT * FROM siswa s WHERE s.nis='$nisbaru' ";
		$result_nislama=QueryDb($sql_lulus_getnislama);
		
		CloseDb();
		if (mysqli_num_rows($result_nislama) > 0) { 
			$ERROR_MSG = "NIS Siswa sudah digunakan!";
		} else {
			$tahunsekarang=$_REQUEST['th'];
			$bulansekarang=$_REQUEST['bln'];
			$tanggalsekarang=$_REQUEST['tgl'];
			$sekarang=$tahunsekarang."-".$bulansekarang."-".$tanggalsekarang;
			//CEK DULU NISNYA, BRUBAH ATO NGGAK.....
			
			if ($nis == $nisbaru){
				OpenDb();
				BeginTrans();
				$success=0;
				$sql_lulus_update_dep="UPDATE jbsakad.riwayatdeptsiswa SET aktif=0 WHERE nis='$nis' AND departemen='$departemenawal' AND aktif = 1";
				QueryDbTrans($sql_lulus_update_dep, $success);
				
				if ($success){
					$sql_lulus_insert_dep="INSERT INTO jbsakad.riwayatdeptsiswa SET nis='$nisbaru', departemen='$departemen', mulai='$sekarang', status=1, keterangan='$ket', nislama='$nis'";				
					QueryDbTrans($sql_lulus_insert_dep, $success);
				}				
				if ($success){
					$sql_alumni="INSERT INTO jbsakad.alumni SET nis='$nis', tgllulus='$sekarang', tktakhir='$tingkatawal', klsakhir='$kelasawal', departemen = '".$departemenawal."'";
					QueryDbTrans($sql_alumni,$success);
				}				
				if ($success){
					$sql_lulus_update_kelas="UPDATE jbsakad.riwayatkelassiswa SET aktif=0 WHERE nis='$nis' AND idkelas='$kelasawal' AND aktif = 1";
					QueryDbTrans($sql_lulus_update_kelas, $success);
				}
				if ($success){
					$sql_lulus_insert_kelas="INSERT INTO jbsakad.riwayatkelassiswa SET nis='$nisbaru', idkelas='$kelas', mulai='$sekarang', keterangan='$ket', aktif=1, status=1";
					QueryDbTrans($sql_lulus_insert_kelas, $success);
				}
				if ($success){
					$sql_lulus_update_siswa="UPDATE jbsakad.siswa SET idkelas='$kelas', idangkatan='$angkatan', tahunmasuk='$tahunsekarang', frompsb = 0 WHERE nis='$nis'";
					QueryDbTrans($sql_lulus_update_siswa, $success);
				}				
				if ($success)
					CommitTrans(); 
				else 
					RollbackTrans();
			
				CloseDb();	
			}
			else
			{
				//KALO TERNYATA NISNYA BERUBAH....
				OpenDb();
						
				BeginTrans();
				$success=0;				
				
				$sql_lulus_insert_siswa="INSERT INTO jbsakad.siswa
				(nis,nisn,nama,panggilan,aktif,tahunmasuk,idangkatan,idkelas,suku,agama,`status`,kondisi,kelamin,tmplahir,tgllahir,warga,anakke,jsaudara,bahasa,berat,tinggi,darah,
				alamatsiswa,kodepossiswa,telponsiswa,hpsiswa,emailsiswa,kesehatan,ketsekolah,namaayah,namaibu,almayah,almibu,wali,penghasilanayah,penghasilanibu,alamatortu,telponortu,
				hportu,emailayah,emailibu,alamatsurat,keterangan,pinsiswa,pinortu,pinortuibu,asalsekolah,pendidikanayah,pendidikanibu,pekerjaanayah,pekerjaanibu,foto,info1,info2,info3) 

				(SELECT '$nisbaru',nisn,nama,panggilan,1,'$tahunsekarang','$angkatan','$kelas',suku,agama,`status`,kondisi,kelamin,tmplahir,tgllahir,warga,anakke,jsaudara,bahasa,berat,tinggi,darah,
				alamatsiswa,kodepossiswa,telponsiswa,hpsiswa,emailsiswa,kesehatan,ketsekolah,namaayah,namaibu,almayah,almibu,wali,penghasilanayah,penghasilanibu,alamatortu,telponortu,
				hportu,emailayah,emailibu,alamatsurat,keterangan,pinsiswa,pinortu,pinortuibu,asalsekolah,pendidikanayah,pendidikanibu,pekerjaanayah,pekerjaanibu,foto,info1,info2,info3
				FROM siswa WHERE nis='$nis')";

				QueryDbTrans($sql_lulus_insert_siswa, $success);
				
				if ($success)
				{					
					$sql_siswa_update="UPDATE jbsakad.siswa SET aktif=0, alumni=1 WHERE nis='$nis'"; 
					QueryDbTrans($sql_siswa_update, $success);
				}
				
				if ($success)
				{
					$sql_lulus_update_kelas="UPDATE jbsakad.riwayatkelassiswa SET aktif=0 WHERE nis='$nis' AND idkelas='$kelasawal' AND aktif = 1";
					QueryDbTrans($sql_lulus_update_kelas, $success);
				}
				
				if ($success)
				{
					$sql_lulus_insert_kelas="INSERT INTO jbsakad.riwayatkelassiswa SET nis='$nisbaru', aktif=1, status=1, idkelas='$kelas', keterangan='$ket', mulai='$sekarang'";
					QueryDbTrans($sql_lulus_insert_kelas, $success);
				}
				
				if ($success)
				{
					$sql_lulus_update_dept="UPDATE jbsakad.riwayatdeptsiswa SET aktif=0 WHERE nis='$nis' AND departemen='$departemenawal' AND aktif = 1";
					QueryDbTrans($sql_lulus_update_dept, $success);
				}
				
				if ($success)
				{
					$sql_alumni="INSERT INTO jbsakad.alumni SET nis='$nis', tgllulus='$sekarang', tktakhir='$tingkatawal', klsakhir='$kelasawal', departemen = '".$departemenawal."'";
					QueryDbTrans($sql_alumni,$success);
				}
				
				if ($success)
				{
					$sql_lulus_insert_dept="INSERT INTO jbsakad.riwayatdeptsiswa SET nis='$nisbaru', departemen='$departemen', mulai='$sekarang', aktif=1, nislama='$nis', status=1";
					QueryDbTrans($sql_lulus_insert_dept, $success);
				}
				
				if ($success)
					CommitTrans(); 
				else
					RollbackTrans();
				CloseDb();
				?>
				<script language="javascript">
					var pilihan=parent.siswa_lulus_pilih.document.getElementById("pilihan").value;
					var departemen=parent.siswa_lulus_pilih.document.getElementById("departemen").value;
					var tingkat=parent.siswa_lulus_pilih.document.getElementById("tingkat").value;
					var tahunajaran=parent.siswa_lulus_pilih.document.getElementById("tahunajaran").value;
					var kelas=parent.siswa_lulus_pilih.document.getElementById("kelas").value;
					var nisdicari=parent.siswa_lulus_pilih.document.getElementById("nisdicari").value;
					var namadicari=parent.siswa_lulus_pilih.document.getElementById("namadicari").value;
					var jenis=parent.siswa_lulus_pilih.document.getElementById("jenis").value;
					var ket = parent.siswa_lulus_pilih.document.getElementById("ket_"+i).value;
					var nisbaru = parent.siswa_lulus_pilih.document.getElementById("nis_"+i).value;
					parent.siswa_lulus_pilih.location.href="siswa_lulus_pilih.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&nisdicari="+nisdicari+"&namadicari="+namadicari+"&departemen="+departemen+"&kelas="+kelas+"&urut=<?=$urut?>&urutan=<?=$urutan?>&jenis="+jenis+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&count="+i+"&ket="+ket+"&nisbaru="+nisbaru;
				</script>
				<?php
			}//end cek nis berubah 
		}//end cek sudah ada nis
	}//end cek kapasasitas kelas     
}

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<title>Kelulusan Siswa [Tujuan]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript">

function change_dep(){
	var departemenawal=document.getElementById("departemenawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var departemen=document.getElementById("departemen").value;
	
	document.location.href="siswa_lulus_tujuan.php?pilihan=1&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&departemenawal="+departemenawal;
}

function change(){
	var departemen=document.getElementById("departemen").value;
	var angkatan=document.getElementById("angkatan").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var departemenawal=document.getElementById("departemenawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	
	document.location.href="siswa_lulus_tujuan.php?pilihan=1&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&angkatan="+angkatan+"&tingkat="+tingkat+"&departemenawal="+departemenawal;
}

function change_kelas(){
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var angkatan=document.getElementById("angkatan").value;
	var departemenawal=document.getElementById("departemenawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	
	document.location.href="siswa_lulus_tujuan.php?pilihan=1&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&angkatan="+angkatan+"&departemenawal="+departemenawal;
}

function batal_naik(nis){
	var departemen=document.getElementById("departemen").value;
	var kelas=document.getElementById("kelas").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var angkatan=document.getElementById("angkatan").value;
	var departemenawal=document.getElementById("departemenawal").value;	
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var pilihan=document.getElementById("pilihan").value;
	
	if (confirm("Apakah anda yakin akan mengembalikan siswa ini ke departemen, tingkat & kelas sebelumnya?"))
		document.location.href="siswa_lulus_tujuan.php?pilihan=1&op=hgiu82kjs98uqjq89wuj89sga&nis="+nis+"&departemen="+departemen+"&kelas="+kelas+"&tahunajaranawal="+tahunajaranawal+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&angkatan="+angkatan+"&departemenawal="+departemenawal+"&pilihan="+pilihan;	
}

function refresh() {
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var angkatan=document.getElementById("angkatan").value;
	var departemenawal=document.getElementById("departemenawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	
	document.location.href="siswa_lulus_tujuan.php?pilihan=1&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&angkatan="+angkatan+"&departemenawal="+departemenawal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function ubah_ket(nis,kelas) {
	newWindow('ubah_keterangan.php?nis='+nis+'&idkelas='+kelas, 'UbahKeterangan','417','315','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan){
	var departemen=document.getElementById("departemen").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var angkatan=document.getElementById("angkatan").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var departemenawal=document.getElementById("departemenawal").value;
	
	if (urutan =="ASC")
		urutan="DESC";
	else
		urutan="ASC";
		
	document.location.href="siswa_lulus_tujuan.php?pilihan=1&tahunajaranawal="+tahunajaranawal+"&departemenawal="+departemenawal+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&angkatan="+angkatan+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var angkatan=document.getElementById("angkatan").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "siswa_lulus_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&angkatan="+angkatan+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}

function change_hal() {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var angkatan=document.getElementById("angkatan").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="siswa_lulus_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&angkatan="+angkatan+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var departemen=document.getElementById("departemen").value;
	var tingkatawal=document.getElementById("tingkatawal").value;
	var tahunajaranawal=document.getElementById("tahunajaranawal").value;
	var tahunajaran=document.getElementById("tahunajaran").value;
	var angkatan=document.getElementById("angkatan").value;
	var tingkat=document.getElementById("tingkat").value;
	var kelas=document.getElementById("kelas").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href= "siswa_lulus_tujuan.php?tingkat="+tingkat+"&tahunajaran="+tahunajaran+"&tingkatawal="+tingkatawal+"&tahunajaranawal="+tahunajaranawal+"&departemen="+departemen+"&kelas="+kelas+"&angkatan="+angkatan+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}	
	
function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		return false;
    } 
    return true;
}



</script>
</head>

<body leftmargin="0" topmargin="0">
<form name="kanan" action="#" method="post">
<input type="hidden" name="departemenawal" id="departemenawal" value="<?=$departemenawal?>" />
<!--<input type="hidden" name="tingkatawal" id="tingkatawal" value="<?=$tingkatawal?>" />-->
<input type="hidden" name="tahunajaranawal" id="tahunajaranawal" value="<?=$tahunajaranawal?>" />
<input type="hidden" name="pilihan" id="pilihan" value="<?=$pilihan?>" />
<!--<input type="hidden" name="depasal" id="depasal" value="<?=$depasal?>" />-->
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td height="50" align="center">
    <fieldset>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" >
    <!-- TABLE TITLE -->
    <tr align="left">            
		<td width="24%"><strong>Departemen</strong></th>
      	<td><select name="departemen" id="departemen" onChange="change_dep()" style="width:228px;" onKeyPress="return focusNext('angkatan',event)">
            <?php //$dep = getDepartemen(SI_USER_ACCESS());    
				//foreach($dep as $value) {
				OpenDb();
				$sql = "SELECT departemen FROM departemen WHERE aktif=1 AND departemen <> '$departemenawal' ORDER BY urutan ";
				$result=QueryDb($sql);
				while ($row=@mysqli_fetch_array($result)){
					if ($departemen == "")
						$departemen = $row['departemen']; ?>
              	<option value="<?=$row['departemen']?>" <?=StringIsSelected($row['departemen'],$departemen)?> >
             	<?=$row['departemen'] ?>
              	</option>
            <?php } ?>
            </select>
  		</td>
	</tr>
    <tr align="left">
      	<td><strong>Angkatan</strong></div></th>
      	<td>
        	<select name="angkatan" id="angkatan" onChange="change()"  style="width:228px;" onKeyPress="return focusNext('tahunajaran',event)">
        <?php OpenDb();
			$sql_angkatan="SELECT replid,angkatan,aktif FROM jbsakad.angkatan WHERE departemen='$departemen' AND aktif=1";
			$result_angkatan=QueryDb($sql_angkatan);
			while ($row_angkatan=@mysqli_fetch_array($result_angkatan)){
				if ($angkatan=="")
					$angkatan=$row_angkatan['replid'];
		?>
			<option value="<?=$row_angkatan['replid']?>" <?=StringIsSelected($row_angkatan['replid'], $angkatan)?>>
			  <?=$row_angkatan['angkatan'];
		   ?>
			</option>
			<?php
		}
		CloseDb();
		?>
		  </select></td>	
 	</tr>
    <tr align="left">
    	<td><strong>Tahun&nbsp;Ajaran </strong></th>
      	<td><select name="tahunajaran" id="tahunajaran" onChange="change()"  style="width:228px;" onKeyPress="return focusNext('tingkat',event)">
        <?php OpenDb();
			$sql_tahunajaran="SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran WHERE departemen='$departemen' AND tglmulai > '$tglmulai' ORDER BY aktif DESC, tglmulai DESC";
			$result_tahunajaran=QueryDb($sql_tahunajaran);
			while ($row_tahunajaran=@mysqli_fetch_array($result_tahunajaran)){
				if ($tahunajaran=="")
					$tahunajaran=$row_tahunajaran['replid'];
				if ($row_tahunajaran['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';
	?>
        	<option value="<?=$row_tahunajaran['replid']?>" <?=IntIsSelected($row_tahunajaran['replid'], $tahunajaran)?>><?=$row_tahunajaran['tahunajaran'].' '.$ada?></option>
      	<?php
			}
			CloseDb();
		?>
       		</select></td>
    </tr>
    <tr align="left">
      	<td><strong>Tingkat </strong></th>
      	<td><select name="tingkat" id="tingkat" onChange="change()"  style="width:228px;" onKeyPress="return focusNext('kelas', event)">
        <?php OpenDb();
			$sql_tingkat="SELECT replid,tingkat FROM jbsakad.tingkat WHERE departemen='$departemen' ORDER BY urutan ASC";
			$result_tingkat=QueryDb($sql_tingkat);
			while ($row_tingkat=@mysqli_fetch_array($result_tingkat)){
				if ($tingkat=="")
					$tingkat=$row_tingkat['replid'];
			?>
				<option value="<?=$row_tingkat['replid']?>" <?=IntIsSelected($row_tingkat['replid'],$tingkat)?>>
				<?=$row_tingkat['tingkat']?>
				</option>
				<?php
			 }
			 CloseDb();
			 ?>
			 </select></td>
    </tr>
    <tr align="left">
    	<td><strong>Kelas&nbsp;Tujuan </strong></th>
        <td><select name="kelas" id="kelas" onChange="change_kelas()" style="width:228px;">
         <?php OpenDb();
			$sql_kelas="SELECT replid,kelas,kapasitas FROM jbsakad.kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' AND aktif=1";
			$result_kelas=QueryDb($sql_kelas);
			while ($row_kelas=@mysqli_fetch_array($result_kelas)){
				if ($kelas=="")
					$kelas=$row_kelas['replid'];
				$sql_terisi="SELECT COUNT(*) FROM jbsakad.siswa WHERE idkelas='".$row_kelas['replid']."' AND aktif=1 AND idangkatan = '".$angkatan."'";
				$result_terisi=QueryDb($sql_terisi);
				$row_terisi=@mysqli_fetch_row($result_terisi);
			?>  
           		<option value="<?=$row_kelas['replid']?>" <?=IntIsSelected($row_kelas['replid'], $kelas)?>>
				<?=$row_kelas['kelas'].", kapasitas: ".$row_kelas['kapasitas'].", terisi: ".$row_terisi[0]?>
			  	</option>
			 <?php
			 }
			 CloseDb();
			 ?> 
          	</select><?//=$sql_terisi?></td>
    </tr>
   	</table>     
    </fieldset>
   	</td>
</tr>
<tr>
	<td>
<?php 	if ($tahunajaran <> "" && $tingkat <> "" && $kelas <> "" && $angkatan <> "" ) {
		OpenDb();    
	
		//$sql_tot = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = $kelas AND k.idtahunajaran = $tahunajaran AND k.idtingkat = $tingkat AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1 AND s.idangkatan = $angkatan";
		$sql_tot = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s WHERE s.idkelas = '$kelas' AND s.aktif=1 AND s.idangkatan = '".$angkatan."'";
		//echo($sql_tot);
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;
		
		//$sql_siswa = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = $kelas AND k.idtahunajaran = $tahunajaran AND k.idtingkat = $tingkat AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1 AND s.idangkatan = $angkatan ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		$sql_siswa = "SELECT s.replid,s.nis,s.nama FROM jbsakad.siswa s WHERE s.idkelas = '$kelas' AND s.aktif=1 AND s.idangkatan = '$angkatan' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		
		$result_siswa = QueryDb($sql_siswa);
		$jum = @mysqli_num_rows($result_siswa);
		
		$sql5 = "SELECT kelas FROM jbsakad.kelas WHERE replid = $kelas ";
		$result5 = QueryDb($sql5);
		$row5 = @mysqli_fetch_array($result5);
		$nama_kelas = $row5['kelas'];
		
		if ($jum > 0) { ?>
   	<table width="100%" border="1" cellspacing="0" class="tab" id="table" bordercolor="#000000">
  	<tr align="center" height="30" class="header">
    	<td width="8%"><div align="center">No</div></td>
    	<td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')" >N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
     	<td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
    	<td width="*">Keterangan</td>
    	<td width="6%">&nbsp;</td>
  	</tr>
    <?php 
	if ($page==0)
		$cnt = 1;
	else 
		$cnt = (int)$page*(int)$varbaris+1;
		
	while ($row_siswa=@mysqli_fetch_array($result_siswa)){
		$sql_riwayat_kelas="SELECT keterangan,status FROM jbsakad.riwayatkelassiswa WHERE nis='".$row_siswa['nis']."' AND idkelas='$kelas'";
        $result_riwayat_kelas=QueryDb($sql_riwayat_kelas);
        $row_riwayat = mysqli_fetch_array($result_riwayat_kelas);
		
	?>
	<tr height="25"> 
        <td align="center"><?=$cnt?></td>
        <td align="center"><?=$row_siswa['nis']?></td>
        <td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row_siswa['replid']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row_siswa['nama']?></a></td>
        <?php if ($row_riwayat['keterangan'] <> "") { ?>
        <td><?=$row_riwayat['keterangan']?>&nbsp;&nbsp;
        	<?php if ($row_riwayat['status'] == 1) {?>
        	<a href="#" onClick="JavaScript:ubah_ket('<?=$row_siswa['nis']?>',<?=$kelas?>)"><img src="../images/ico/ubah.png" width="16" height="16" border="0" onMouseOver="showhint('Ubah Keterangan Siswa!', this, event, '100px')"/></a>    
        	<?php } ?>
        </td>
        <?php } else { ?>
        <td align="center">
       		<?php if ($row_riwayat['status'] == 1) {?>
        	<a href="#" onClick="JavaScript:ubah_ket('<?=$row_siswa['nis']?>',<?=$kelas?>)"><img src="../images/ico/ubah.png" width="16" height="16" border="0" onMouseOver="showhint('Ubah Keterangan Siswa!', this, event, '100px')"/></a>    
        	<?php } ?>
        </td>
        <?php } ?>
        
        <td align="center">
			<?php if ($row_riwayat['status']==1) {?>
        	<a href="#" onClick="javascript:batal_naik('<?=$row_siswa['nis']?>')"><img src="../images/ico/hapus.png" width="16" height="16" border="0" onMouseOver="showhint('Batalkan kelulusan!', this, event, '100px')"/></a>
        	<?php } ?>
     	</td>
  	</tr>
  	<?php $cnt++;
  	}
  	CloseDb();?>
    </table>
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
       	<td width="50%" align="left">Hal
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
    	<!--td align="center">
    <<input <?=$disback?> type="button" class="but" name="back" value="<<" onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">-->
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	    <!--<input <?=$disnext?> type="button" class="but" name="next" value=">>" onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td>-->
        <td width="50%" align="right">Jml baris per hal
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else {  ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Belum ada siswa yang terdaftar pada kelas <?=$nama_kelas?>.
        </b></font>
	</td>
	</tr>
	</table> 
<?php  } 
} else {
?>
 	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="200">
    	<?php if ($angkatan == "") { ?>    
        	<font size = "2" color ="red"><b>Belum ada angkatan yang dituju.
            <br />Tambah data angkatan pada departemen <?=$departemen?> di menu Angkatan pada bagian Referensi. 
            </b></font>
		<?php } else if ($tingkat == "") { ?>
            <font size = "2" color ="red"><b>Belum ada tingkat yang dituju.
            <br />Tambah data tingkat pada departemen <?=$departemen?> di menu Tingkat pada bagian Referensi. 
            </b></font>
		<?php } else if ($kelas == "") { ?>    
        	<font size = "2" color ="red"><b>Belum ada kelas yang dituju.
            <br />Tambah data kelas pada departemen <?=$departemen?> di menu Kelas pada bagian Referensi. 
            </b></font>
        <?php } else if ($tahunajaran == "") {	?>
            <font size = "2" color ="red"><b>Tidak ada tahun ajaran yang lebih tinggi pada departemen <?=$departemen?>.
            <br />Tambah data tahun ajaran pada departemen <?=$departemen?> di menu Tahun Ajaran pada bagian Referensi. 
            </b></font>
       	 <?php } ?>
	</td>
	</tr>
	</table> 	
<?php } ?>
</td>
</tr>
<!-- END TABLE CENTER -->    
</table>
</form>
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect = new Spry.Widget.ValidationSelect("angkatan");
	var spryselect = new Spry.Widget.ValidationSelect("tahunajaran");
	var spryselect1 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
</script>