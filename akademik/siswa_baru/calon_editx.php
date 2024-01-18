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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');

$replid = $_REQUEST['replid'];

OpenDb();
$sql="SELECT c.nopendaftaran, c.nama, c.panggilan, c.tahunmasuk, c.idproses, c.idkelompok, c.suku, c.agama, c.status, c.kondisi as kondisi, c.kelamin, c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan, YEAR(c.tgllahir) AS tahun, c.warga, c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto, c.alamatsiswa, c.kodepossiswa, c.telponsiswa, c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah, c.namaayah, c.namaibu, c.almayah, c.almibu, c.pendidikanayah, c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu, c.wali, c.penghasilanayah, c.penghasilanibu, c.alamatortu, c.telponortu, c.hportu, c.emailortu, c.alamatsurat, c.keterangan, p.replid AS proses, p.departemen, p.kodeawalan, k.replid AS kelompok FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p WHERE c.replid=$replid AND p.replid = c.idproses AND k.replid = c.idkelompok AND p.replid = k.idproses";

$result=QueryDB($sql);
CloseDb();
$row_siswa = mysqli_fetch_array($result); 

$departemen = $row_siswa['departemen'];
$proses=$row_siswa['proses'];
$kelompok=$row_siswa['kelompok'];
$no = $row_siswa['nopendaftaran'];

$tahunmasuk = $row_siswa['tahunmasuk'];
$blnlahir = (int)$row_siswa['bulan'];
$thnlahir = (int)$row_siswa['tahun'];

/*OpenDb();
$query = "SELECT urutan FROM jbsakad.departemen WHERE departemen = '".$departemen."'";	
$hasil = QueryDb($query);	
CloseDb();
$row = mysqli_fetch_array($hasil);
$urutan = $row['urutan'];
*/

if ($row_siswa['asalsekolah'] <> NULL) {
	OpenDb();
	$query = "SELECT departemen FROM asalsekolah WHERE sekolah = '".$row_siswa['asalsekolah']."'";
	$hasil = QueryDb($query);	
	CloseDb();
	$row = mysqli_fetch_array($hasil);
	$dep_asal = $row['departemen'];
	$sekolah = $row_siswa['asalsekolah'];
} else {	
	$dep_asal = "";
	$sekolah = "";
}




if ($blnlahir == 4 || $blnlahir == 6|| $blnlahir == 9 || $blnlahir == 11 ) 
	$n = 30;
else if ($blnlahir == 2 && $thnlahir % 4 <> 0) 
	$n = 28;
else if ($blnlahir == 2 && $thnlahir % 4 == 0) 
	$n = 29;
else 
	$n = 31;

OpenDb();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Calon Siswa]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	var urutananak=document.main.urutananak.value;
	var jumlahanak=document.main.jumlahanak.value;
	var kodepos=document.main.kodepos.value;
	var telponsiswa=document.main.telponsiswa.value;
	var hpsiswa=document.main.hpsiswa.value;	
	var berat=document.getElementById('berat').value;
	var tinggi=document.main.tinggi.value;
	var penghasilanayah=document.main.penghasilanayah.value;
	var penghasilanibu=document.main.penghasilanibu.value;
	var telponortu=document.main.telponortu.value;
	var hportu=document.main.hportu.value;
	var emailsiswa = document.main.emailsiswa.value;
	var emailortu = document.main.emailortu.value;
	var tgllahir = document.getElementById("tgllahir").value;
	var blnlahir = document.getElementById("blnlahir").value;
	var thnlahir = document.getElementById("thnlahir").value;
	var nama = document.main.nama.value;
	var tmplahir = document.main.tmplahir.value;
	//var kapasitas = document.main.kapasitas.value;
	//var isi = document.main.isi.value;
	var kelompok = document.main.kelompok.value;
	var kel_lama = document.main.kel_lama.value;
	var agama = document.main.agama.value;
	var suku = document.main.suku.value;
	var status = document.main.status.value;
	var kondisi = document.main.kondisi.value;
	
	if (nama.length == 0) {
		alert("Anda harus mengisikan data untuk Nama");
		document.getElementById('nama').focus();
		return false;
	}
	
	if (tmplahir.length == 0) {
		alert("Anda harus mengisikan data untuk Tempat Lahir");
		document.getElementById('tmplahir').focus();
		return false;
	}
	
	if (tgllahir == "") {
       	alert("Anda harus mengisikan data untuk Tanggal Lahir");
		document.main.tgllahir.focus();
        return false;
   	}
	
	if (blnlahir == "") {
       	alert("Anda harus mengisikan data untuk Bulan Lahir");
		document.main.blnlahir.focus();
        return false;
   	}
	
	if (thnlahir == "") {
       	alert("Anda harus mengisikan data untuk Tahun Lahir");
		document.main.thnlahir.focus();
        return false;
   	}
	
	if (thnlahir.length > 0){	
		if(isNaN(thnlahir)) {
    		alert ('Data tahun lahir harus berupa bilangan');
			document.main.thnlahir.value="";
			document.main.thnlahir.focus();
        	return false;
		}
		if (thnlahir.length > 4 || thnlahir.length < 4) {
        	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter"); 
			document.main.thnlahir.focus();
        return false;
    	}
    }
	
	
	if(agama.length == 0) {
       	alert("Anda harus mengisikan data untuk Agama calon siswa");
		document.main.agama.focus();
        return false;
   	}
	
	if(suku.length == 0) {
       	alert("Anda harus mengisikan data untuk Suku calon siswa");
		document.main.suku.focus();
        return false;
   	}
	
	if(status.length == 0) {
       	alert("Anda harus mengisikan data untuk Status calon siswa");
		document.main.status.focus();
        return false;
   	}
	
	if(kondisi.length == 0) {
       	alert("Anda harus mengisikan data untuk Kondisi calon siswa");
		document.main.kondisi.focus();
        return false;
   	}
		
	if (urutananak.length > 0){
		if(isNaN(urutananak)) {
			alert ('Data isian anak ke harus berupa bilangan');
			document.main.urutananak.value="";
			document.main.urutananak.focus();
			return false;
		}
		if (jumlahanak.length == 0) {
			alert("Jumlah saudara tidak boleh kosong");
			document.getElementById('jumlahanak').focus();
			return false;
		}
	}

	if (jumlahanak.length > 0){
		if(isNaN(jumlahanak)) {
			alert ('Jumlah saudara harus berupa bilangan');
			document.getElementById('jumlahanak').value="";
			document.getElementById('jumlahanak').focus();
			return false;
		} 
		
		if (urutananak.length == 0) {
			alert("Urutan Anak tidak boleh kosong");
			document.getElementById('urutananak').focus();
			return false;
		}
	}

	if (parseInt(urutananak) > parseInt(jumlahanak)) {
		alert ('Urutan anak tidak boleh lebih dari jumlah saudara yang ada');
		document.getElementById ('urutananak').focus();
		return false;
	}
		
	if (kodepos.length > 0){
		if(isNaN(kodepos)) {
			alert ('Data kodepos harus berupa bilangan');
			document.getElementById('kodepos').value="";
			document.getElementById('kodepos').focus();
			return false;
		}
	}	

	if (telponsiswa.length > 0){
		if(isNaN(telponsiswa)) {
			alert ('Data nomor telepon harus berupa bilangan');
			document.getElementById('telponsiswa').value="";
			document.getElementById('telponsiswa').focus();
			return false;
		}
	}
		
	if (hpsiswa.length > 0){
		if(isNaN(hpsiswa)) {
			alert ('Data nomor HP harus berupa bilangan');
			document.getElementById('hpsiswa').value="";
			document.getElementById('hpsiswa').focus();
			return false;
		}
	}	
	
	if (berat.length > 0){
		if(isNaN(berat)) {
			alert ('Data berat harus berupa bilangan');
			document.getElementById('berat').value=0;
			document.getElementById('berat').focus();
			return false;
		}
	}		

	if (tinggi.length > 0){
		if(isNaN(tinggi)) {
			alert ('Data tinggi harus berupa bilangan');
			document.getElementById('tinggi').value="";
			document.getElementById('tinggi').focus();
			return false;
		}
	}	
	
	if (penghasilanayah.length > 0){
		if(isNaN(penghasilanayah)) {
			alert ('Data penghasilan Ayah harus berupa bilangan');
			document.getElementById('penghasilanayah').value="";
			document.getElementById('penghasilanayah').focus();
			return false;
		}
	}	
		
	if (penghasilanibu.length > 0){
		if(isNaN(penghasilanibu)) {
			alert ('Data penghasilan Ibu harus berupa bilangan');
			document.getElementById('penghasilanibu').value="";
			document.getElementById('penghasilanibu').focus();
			return false;
		}
	}

	if (telponortu.length > 0){
		if(isNaN(telponortu)) {
			alert ('Data telepon orang tua harus berupa bilangan');
			//document.getElementById('telponortu').value="";
			document.getElementById('telponortu').focus();
			return false;
		}
	}
	
	if (hportu.length > 0){
		if(isNaN(hportu)) {
			alert ('Data nomor HP orang tua harus berupa bilangan');
			//document.getElementById('hportu').value="";
			document.getElementById('hportu').focus();
			return false;
		}
	}
	
	if (emailsiswa.length > 0) {
		//return validateEmail('emailsiswa');		
	}
	
	if (emailortu.length > 0) {
		//return validateEmail('emailortu');		
	}
	/*	
	if (kapasitas == isi && kelompok != kel_lama) {
		alert("Kapasitas kelompok tidak mencukupi untuk menambah data calon baru");
		document.getElementById('kelompok').focus();
		return false;	
	}*/
		
	if (thnlahir.length != 0 && blnlahir.length != 0 && tgllahir.length != 0){
	if (thnlahir % 4 == 0){
		 if (blnlahir == 2){
			  if (tgllahir>29){
				   alert ('Maaf, silahkan masukan ulang tanggal lahir');
				   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir);	
				   document.getElementById("tgllahir").focus();
				   return false;
			  }
		 }
		 if (blnlahir != 2 || blnlahir != 4 || blnlahir != 6 || blnlahir != 9 || blnlahir != 11){
			  if (tgllahir>30){
				   alert ('Maaf, silahkan masukan ulang tanggal lahir');
				   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir);	
				   document.getElementById("tgllahir").focus();
				   return false;
			  }
		 }
	}
	if (thnlahir % 4 != 0){
		 if (blnlahir == 2){
			 if (tgllahir>28){
				   alert ('Maaf, silahkan masukan ulang tanggal lahir');
				   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir);	
				   document.getElementById("tgllahir").focus();
				   return false;
				   
			  }
		 }
		 if (blnlahir != 2 || blnlahir != 4 || blnlahir != 6 || blnlahir != 9 || blnlahir != 11){
			  if (tgllahir>30){
				   alert ('Maaf, silahkan masukan ulang tanggal lahir');
				   sendRequestText("../library/gettanggal.php", show1, "tahun="+thnlahir+"&bulan="+blnlahir+"&tgl="+tgllahir);	
				   document.getElementById("tgllahir").focus();
				   return false;
				   
			  }
		 }
	}
	
}

function sekolah_kiriman(sekolah, dep) {	
	var dep_asal = document.getElementById("dep_asal").value;
	if (dep_asal == dep) {
		change_departemen(sekolah);
	} else {
		setTimeout("change_departemen(0)",1);
	}
}

function change_departemen(kode) {		
	var dep_asal = document.getElementById("dep_asal").value;
	wait_sekolah();
	if (kode==0){
		sendRequestText("../siswa/siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal);		
	} else {		
		sendRequestText("../siswa/siswa_add_getsekolah.php", showSekolah, "dep_asal="+dep_asal+"&sekolah="+kode);
	}
}

function wait_sekolah() {
	show_wait("sekolahInfo");
}
function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}
function showSekolah(x) {
	document.getElementById("sekolahInfo").innerHTML = x;
}
function refresh_delete_sekolah(){
	setTimeout("change_departemen(0)",1);
}

function tambah_suku(){
	newWindow('../library/suku.php', 'tambahSuku','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_agama(){
	newWindow('../library/agama.php', 'tambahAgama','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_status(){
	newWindow('../siswa/siswa_add_status.php', 'tambahStatus','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_kondisi(){
	newWindow('../siswa/siswa_add_kondisi.php', 'tambahKondisi','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_asal_sekolah(){
	var departemen = document.getElementById("dep_asal").value;
	newWindow('../siswa/siswa_add_asalsekolah.php?departemen='+departemen, 'tambahAsalSekolah','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pendidikan(){
	newWindow('../siswa/siswa_add_pendidikan.php', 'tambahPendidikan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tambah_pekerjaan(){
	newWindow('../siswa/siswa_add_pekerjaan.php', 'tambahPekerjaan','500','425','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tutup(){
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;	
	var kelompok = document.getElementById('kelompok').value;
	parent.opener.location.href = "calon_content.php?departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok;
	window.close();
}

function ambil_alamat_ortu(){
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatortu").value=alamatsiswa;
}

function ambil_alamat_surat(){
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatsurat").value=alamatsiswa;
}

//Ajax suku ==========================================================
function suku_kiriman(suku_kiriman) {	
	suku = suku_kiriman
	//suku_kiriman2=suku_kiriman;
	setTimeout("refresh_suku(suku)",1);
}
function refresh_suku(kode){
	wait_suku();
	if (kode==0){
		sendRequestText("../library/getsuku.php", showSuku, "suku=");
	} else {
		sendRequestText("../library/getsuku.php", showSuku, "suku="+kode);
	}
}
function wait_suku() {
	show_wait("InfoSuku");
}
function showSuku(x) {
	document.getElementById("InfoSuku").innerHTML = x;
}
function refresh_delete(){
	setTimeout("refresh_suku(0)",1);
}
// end of Ajax Suku ====================================================

//ajax Agama============================================================
function kirim_agama(agama_kiriman){
	agama=agama_kiriman;
	setTimeout("refresh_agama(agama)",1);
}
function refresh_agama(kode){
	wait_agama();
	if (kode==0){
		sendRequestText("../library/getagama.php", showAgama, "agama=");
	} else {
		sendRequestText("../library/getagama.php", showAgama, "agama="+kode);
	}
}
function wait_agama() {
	show_wait("InfoAgama");
}
function showAgama(x) {
	document.getElementById("InfoAgama").innerHTML = x;
}
function ref_del_agama(){
	setTimeout("refresh_agama(0)",1);
}
// end of Ajax agama=====================================================

//------- Ajax Status====================================================
function status_kiriman(st){
	refresh_status(st);
	//setTimeout("refresh_status(st)",1);
}
function refresh_status(kode) {
	wait_status();
	if (kode==0) {
		sendRequestText("../siswa/siswa_add_getstatus.php", show_status,"status=");
	} else {
		sendRequestText("../siswa/siswa_add_getstatus.php", show_status,"status="+kode);
	}
}
function wait_status() {
	show_wait("InfoStatus"); //lihat div id 
}
function show_status(x) {
	document.getElementById("InfoStatus").innerHTML = x;
}
function ref_del_status(){
	setTimeout("refresh_status(0)",1);
}
// End of Ajax Status===================================================

//====== Ajax Kondisi ==================================================
function kondisi_kiriman(kondisi){	
	refresh_kondisi(kondisi);
	//setTimeout("refresh_kondisi(kon)",1);
}
function refresh_kondisi(kode) {	
	wait_kondisi();	
	if (kode==0){
		sendRequestText("../siswa/siswa_add_getkondisi.php", show_kondisi,"kondisi=");
	} else {
		sendRequestText("../siswa/siswa_add_getkondisi.php", show_kondisi,"kondisi="+kode);
	}
}
function wait_kondisi() {
	show_wait("InfoKondisi"); //lihat div id 
}
function show_kondisi(x) {
	document.getElementById("InfoKondisi").innerHTML = x;
}
function ref_del_kondisi(){
	setTimeout("refresh_kondisi(0)",1);
}
//======End of Ajax Kondisi==============================================

//====Ajax pendidikan====================================================
function pendidikan_kiriman(pendidikan){
	refresh_pendidikan(pendidikan);
	//pendidikan=pendidikan_kiriman;
	//setTimeout("refresh_pendidikan(pendidikan)",1);
}

function refresh_pendidikan(kode_pendidikan) {	
	if (kode_pendidikan==0) {
		sendRequestText("../siswa/siswa_add_getpendidikan.php", show_pendidikan,"pendidikan=");
	} else {
		sendRequestText("../siswa/siswa_add_getpendidikan.php", show_pendidikan,"pendidikan="+kode_pendidikan);
	}
}

function show_pendidikan(x) {
	document.getElementById("Infopendidikanibu").innerHTML = x;
	document.getElementById("Infopendidikanayah").innerHTML = x;	
}

function ref_del_pendidikan() {
	setTimeout("refresh_pendidikan(0)",1);
}
//=======End Of ajax pendidikan==========================================

//========Ajax Pekerjaan ================================================
function pekerjaan_kiriman(kerja){
	refresh_pekerjaan(kerja);
	//setTimeout("refresh_pekerjaan(pekerjaan)",1);
}

function refresh_pekerjaan(kode_pekerjaan) {
	if(kode_pekerjaan==0){
		sendRequestText("../siswa/siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan=");
	} else {
		sendRequestText("../siswa/siswa_add_getpekerjaan.php", show_pekerjaan,"pekerjaan="+kode_pekerjaan);
	}
}

function show_pekerjaan(x) {
	document.getElementById("Infopekerjaanibu").innerHTML = x;
	document.getElementById("Infopekerjaanayah").innerHTML = x;
}

function ref_del_pekerjaan(){
	setTimeout("refresh_pekerjaan(0)",1);
}
//======== End Of Ajax Pekerjaan ========================================

function change_alamat(){	
	var alamatsiswa = document.getElementById("alamatsiswa").value;
	document.getElementById("alamatortu").value=alamatsiswa;
	document.getElementById("alamatsurat").value=alamatsiswa;
}


function change_bln() {	
	alert ('Masuk');
	var thn = document.getElementById('thnlahir').value;
	var bln = parseInt(document.getElementById('blnlahir').value);	
	var tgl = parseInt(document.getElementById('tgllahir').value);
	
	if(thn.length != 0) {
    	if(isNaN(thn)) {
    		alert("Tahun lahir harus berupa angka"); 
			document.getElementById('thnlahir').focus();
        	return false;
		} else {	
			if (thn.length > 4 || thn.length < 4) {
            	alert("Tahun lahir tidak boleh lebih atau kurang dari 4 karakter"); 
				document.getElementById('thnlahir').focus();
            	return false;
			}
		}
    
	sendRequestText("../library/gettanggal.php", show1, "tahun="+thn+"&bulan="+bln+"&tgl="+tgl);
	//alert ('Maaf, silakan masukan ulang tanggal lahir');
	//document.getElementById("tgllahir").focus();
	}/**/
}
function show1(x) {
	document.getElementById("tgllahir").innerHTML = x;
}

function tampil_kelompok() {
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	
	newWindow('kelompok_tampil.php?departemen='+departemen+'&proses='+proses,'tampilKelompok','750','425','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function kelompok_kiriman(a,b,c) {	
	var departemen = document.getElementById("departemen").value;	
	change_kelompok(a,b,c);
}

function change_kelompok(kel,pro,dep) {
	var departemen = document.getElementById("departemen").value;
	var proses = document.getElementById("proses").value;
	var kelompok = document.getElementById("kelompok").value;	
	
	if (kel == 0) {
		change_kel()
		//parent.header.location.href = "calon_header.php?kelompok="+kelompok+"&proses="+proses+"&departemen="+departemen;
	} else {
		sendRequestText("getdepartemen.php", show_dep,"departemen="+dep);
		sendRequestText("getproses.php", show_proses,"departemen="+dep);
		sendRequestText("getkelompok.php", show_kelompok,"departemen="+dep+"&kelompok="+kel);	
		//wait_asal();
		sendRequestText("getasalsekolah.php", show_asalsekolah,"departemen="+dep);
				
		//parent.header.location.href = "calon_header.php?kelompok="+kel+"&proses="+proses+"&departemen="+departemen;
	}
	
}

function change_dep() {
	//wait_dep();
	var departemen = document.getElementById("departemen").value;
	sendRequestText("getproses.php", show_proses,"departemen="+departemen);
	sendRequestText("getkelompok.php", show_kelompok,"departemen="+departemen);	
	//wait_asal();
	//sendRequestText("getsekolah1.php", showSekolah1, "departemen="+departemen);	
	sendRequestText("getasalsekolah1.php", show_asalsekolah,"departemen="+departemen);	
		
}

function showSekolah1(x) {
	document.getElementById("sekolahInfo").innerHTML = x;
}

function change_kel() {
	//wait_ket();
	var kelompok = document.getElementById("kelompok").value;	
	//alert ('kelompok '+kelompok);
	sendRequestText("getketerangan.php", show_keterangan,"kelompok="+kelompok);
}

function show_dep(x) {
	document.getElementById("InfoDepartemen").innerHTML = x;
}
	
function show_proses(x) {
	document.getElementById("InfoProses").innerHTML = x;
}

function show_kelompok(x) {

	document.getElementById("InfoKelompok").innerHTML = x;
	change_kel();
}

function show_keterangan(x) {
	document.getElementById("InfoKeterangan").innerHTML = x;
}

function wait_asal() {
	show_wait("InfoSekolah"); //lihat div id 
}

function show_asalsekolah(x) {	
	document.getElementById("InfoSekolah").innerHTML = x;
	change_departemen(0);
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nama').focus()">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>

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
	<form name="main" method="post" action="calon_simpan.php" onsubmit="return validate()" enctype="multipart/form-data">
	<input type="hidden" name="replid" id="replid" value="<?=$replid?>">
	<input type="hidden" name="action" id="action" value="ubah">
    <input type="hidden" name="kel_lama" id="kel_lama" value="<?=$kelompok?>">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="25">
	<td class="header" colspan="2" align="center">Ubah Data Calon Siswa</td>
</tr>
<tr>
	<td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr>
    	<td>
       	<table width="96%" border="1" >
 		<tr>
    		<td width="27%"><strong>Departemen</strong></td>
    		<td width="*"><div id="InfoDepartemen">
            <select name="departemen" id="departemen" onchange="change_dep()" style="width:250px">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
				foreach($dep as $value) {
					if ($departemen == "")
						$departemen = $value; ?>
              <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
              <?=$value ?>
              </option>
              <?php } ?>
            </select>
            </div>
            </td>          
  		</tr>
  		
        <tr>
        	<td><strong>Proses Penerimaan</strong></td>
    		<td><div id="InfoProses">
            <?php $sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='$departemen'";				
				$result = QueryDb($sql);				
				$row = mysqli_fetch_array($result);
				$proses = $row['replid'];
			?>
            <input type="text" name="nama_proses" id="nama_proses" style="width:240px;" class="disabled" value="<?=$row['proses']?>" readonly />
            <input type="hidden" name="proses" id="proses"  value="<?=$proses?>" />
            	</div>            </td>
  		</tr>
  		<tr>
    		<td><strong>Kelompok Calon Siswa</strong></td>
    		<td>
            	<div id = "InfoKelompok">
            	<select name="kelompok" id="kelompok" onchange="change_kel()" style="width:250px">
   		 	<?php 
				$sql = "SELECT replid,kelompok,kapasitas FROM kelompokcalonsiswa WHERE idproses = $proses ORDER BY kelompok";
				$result = QueryDb($sql);
				
				while ($row = @mysqli_fetch_array($result)) {
					if ($kelompok == "")
						$kelompok = $row['replid'];
										
					$sql1 = "SELECT COUNT(replid) FROM calonsiswa WHERE idkelompok = ".$row['replid']."";
					$result1 = QueryDb($sql1);				
					$row1 = mysqli_fetch_row($result1);
					
			?>
    			<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelompok)?> ><?=$row['kelompok'].', kapasitas: '.$row['kapasitas'] .', terisi: '.$row1[0]?>
                </option>
    		<?php
				//$cnt++; 
				}	
    		?>
    			</select>&nbsp;<img src="../images/ico/tambah.png" onclick="tampil_kelompok();" onMouseOver="showhint('Tambah Kelompok!', this, event, '60px')" />            	
                	
                </div>  
           	</td>
  		</tr>
  		<tr>
    		<td valign="top"><strong>Keterangan</strong></td>
    		<td>
            	<div id = "InfoKeterangan">
			<?php OpenDb();
				$sql = "SELECT keterangan FROM kelompokcalonsiswa WHERE replid = $kelompok";
				$result = QueryDb($sql);
				CloseDb();
				$row = @mysqli_fetch_array($result);			
			?>
              <textarea name="keterangan" id="keterangan" rows="2" cols="60" readonly style="background-color:#E5F7FF" ><?=$row['keterangan'] ?>
              </textarea>
            	</div>        	</td>
  		</tr>
		</table>  
		
      	</td>
        <td align="right"><img src="../library/gambar.php?replid=<?=$replid?>&table=calonsiswa" width="120" height="120" />
        </td>
  	</tr>
	</table>
	<br />
  	<table width="100%" border="0" cellspacing="0">
 	<tr>
    	<td width="46%" valign="top"><!--Kolom Kiri-->      
		<table width="100%" border="0" cellspacing="0" id="table" class="tab">
  		<tr>
    		<td height="30" colspan="4" class="header">DATA PRIBADI SISWA</td>
    	</tr>
  		<tr>
    		<td width="20%"><strong>Pendaftaran</strong></td>
    		<td colspan="3">
            	<input type="text" name="kode" id="kode" size="20" class="disabled" value="<?=$no ?>" readonly />
                <input type="hidden" name="no" id="no" value="<?=$no?>" />
           	</td>
  		</tr>
  		<tr>
    		<td><strong>Tahun Masuk</strong></td>
    		<td colspan="3">
            	<input type="text" name="masuk" id="masuk" size="5" maxlength="4" value="<?=$tahunmasuk?>" readonly="readonly" class="disabled"/> 
            	<input type="hidden" name="tahunmasuk" id="tahunmasuk" value="<?=$tahunmasuk?>" /></td>
  		</tr>
  		<tr>
    		<td><strong>Nama</strong></td>
    		<td colspan="3"><input type="text" name="nama" id="nama" size="30" maxlength="100" onFocus="showhint('Nama Lengkap Siswa tidak boleh kosong!', this, event, '120px')" value="<?=$row_siswa['nama']?>" class="ukuranemail"  onKeyPress="return focusNext('panggilan', event)" /></td>
  		</tr>
  		<tr>
    		<td>Panggilan</td>
    		<td colspan="3"><input type="text" name="panggilan" id="panggilan" size="30" maxlength="30" onFocus="showhint('Nama Panggilan tidak boleh lebih dari 30 karakter!', this, event, '120px')" value="<?=$row_siswa['panggilan']?>" class="ukuran"  onKeyPress="return focusNext('kelamin', event)" /></td>
  		</tr>
  		<tr>
    		<td><strong>Jenis Kelamin</strong></td>
    		<td colspan="3">
    		<input type="radio" id="kelamin" name="kelamin" value="l"
			<?php if ($row_siswa['kelamin']=="l") 
				echo "checked='checked'";
			?>
			 onKeyPress="return focusNext('tmplahir', event)" />&nbsp;Laki-laki&nbsp;&nbsp;
        	<input type="radio" id="kelamin" name="kelamin" value="p" 
        	<?php if ($row_siswa['kelamin']=="p") 
				echo "checked ='checked'";
			?>
			 onKeyPress="return focusNext('tmplahir', event)" />&nbsp;Perempuan			</td>
		</tr>
  		<tr>
  		  <td><strong>Tempat Lahir</strong></td>
		  <td colspan="3"><input type="text" name="tmplahir" id="tmplahir" size="30" maxlength="50" onFocus="showhint('Tempat Lahir tidak boleh kosong!', this, event, '120px')" value="<?=$row_siswa['tmplahir']?>"  onKeyPress="return focusNext('tgllahir', event)" /></td>
  		</tr>
  		<tr>
    		<td><strong>Tanggal Lahir</strong></td>
    		<td colspan="3">
    			<select name="tgllahir" id="tgllahir" onKeyPress="return focusNext('blnlahir', event)" >                              
                <option value="">[Tgl]</option>
				<?php 	for ($tgl=1;$tgl<=$n;$tgl++){ ?>
                   	<option value="<?=$tgl?>" <?=IntIsSelected($tgl, $row_siswa['tanggal'])?>><?=$tgl?></option>
                <?php }	?>
                </select>
                <select name="blnlahir" id="blnlahir" onKeyPress="return focusNext('thnlahir', event)" onChange="change_bln()" />
               	 <option value="">[Bln]</option>
                <?php 	for ($i=1;$i<=12;$i++) { ?>
                   	<option value="<?=$i?>" <?=IntIsSelected($i, $row_siswa['bulan'] )?>><?=NamaBulan($i)?></option>	
				<?php } ?>
                </select>
                <input type="text" name="thnlahir" id="thnlahir" size="5" maxlength="4" onFocus="showhint('Tahun Lahir tidak boleh kosong!', this, event, '120px')" value="<?=$row_siswa['tahun']?>" onKeyPress="return focusNext('agama', event)"/></strong>
            </td>
  		</tr>
        <tr>
    		<td><strong>Agama</strong></td>
    		<td colspan="3">
    		<div id="InfoAgama">
    		<select name="agama" id="agama" class="ukuran"  onKeyPress="return focusNext('suku', event)" >
    		<option value="">[Pilih Agama]</option>
			<?php // Olah untuk combo agama
			OpenDb();
			$sql_agama="SELECT replid,agama,urutan FROM jbsumum.agama ORDER BY urutan";
			$result_agama=QueryDB($sql_agama);
			while ($row_agama = mysqli_fetch_array($result_agama)) {
			?>
			<option value="<?=$row_agama['agama']?>" <?=StringIsSelected($row_agama['agama'], $row_siswa['agama'])?>><?=$row_agama['agama']?></option>
			<?php
    		} 
			CloseDb();
			// Akhir Olah Data agama
			?>
    		</select>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onclick="tambah_agama();"  onMouseOver="showhint('Tambah Agama!', this, event, '50px')"/>
            <?php } ?>
            </div>
            </td>
  		</tr>
  		<tr>
    		<td><strong>Suku</strong></td>
    		<td colspan="3">
    		<div id="InfoSuku">
    		<select name="suku" id="suku" class="ukuran" onKeyPress="return focusNext('status', event)" >            
    		<option value="">[Pilih Suku]</option>
			<?php // Olah untuk combo suku
			OpenDb();
			$sql_suku="SELECT suku,urutan,replid FROM jbsumum.suku ORDER BY urutan";
			$result_suku=QueryDB($sql_suku);
			while ($row_suku = mysqli_fetch_array($result_suku)) {				
			?>
			<option value="<?=$row_suku['suku']?>" <?=StringIsSelected($row_suku['suku'], $row_siswa['suku'])?>><?=$row_suku['suku']?></option>
			<?php
    		} 
			CloseDb();
			// Akhir Olah Data suku
			?>
    		</select>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onclick="tambah_suku();"  onMouseOver="showhint('Tambah Suku!', this, event, '50px')"/>
            <?php } ?></div></td>
  		</tr>
  		
  		<tr>
    		<td><strong>Status</strong></td>
    		<td colspan="3">
    		<div id="InfoStatus">
    		<select name="status" id="status" class="ukuran"  onKeyPress="return focusNext('kondisi', event)" >
    		<option value="">[Pilih Status]</option>
			<?php // Olah untuk combo status
			OpenDb();
			$sql_status="SELECT replid,status,urutan FROM jbsakad.statussiswa ORDER BY urutan";
			$result_status=QueryDB($sql_status);
			while ($row_status = mysqli_fetch_array($result_status)) {
			?>
			<option value="<?=$row_status['status']?>" <?=StringIsSelected($row_status['status'], $row_siswa['status'])?>><?=$row_status['status']?></option>
			<?php
    		} 
			CloseDb();
			// Akhir Olah Data status
			?>
    		</select>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onclick="tambah_status();"  onMouseOver="showhint('Tambah Status!', this, event, '50px')"/>
            <?php } ?>
            </div>  </td>
 		</tr>
  		<tr>
    		<td><strong>Kondisi</strong></td>
    		<td colspan="3">
    		<div id="InfoKondisi">
    		<select name="kondisi" id="kondisi" class="ukuran"  onKeyPress="return focusNext('warga', event)" >
            <option value="">[Pilih Kondisi]</option>
                <?php // Olah untuk combo kondisi
			OpenDb();
			$sql_kondisi="SELECT kondisi,urutan FROM jbsakad.kondisisiswa ORDER BY urutan";
			$result_kondisi=QueryDB($sql_kondisi);
			while ($row_kondisi = mysqli_fetch_array($result_kondisi)) {
			?>
                <option value="<?=$row_kondisi['kondisi']?>" <?=StringIsSelected($row_kondisi['kondisi'], $row_siswa['kondisi'])?>>
                  <?=$row_kondisi['kondisi']?>
                  </option>
                <?php
    		} 
			CloseDb();
			// Akhir Olah Data kondisi
			?>
            </select>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
    		<img src="../images/ico/tambah.png" onclick="tambah_kondisi();" onMouseOver="showhint('Tambah Kondisi!', this, event, '50px')"/>
            <?php } ?>
            </div></td>
  		</tr>
  		<tr>
    		<td>Kewarganegaraan</td>
    		<td colspan="3">
    		<input type="radio" name="warga" id="warga" value="WNI"
			<?php if ($row_siswa['warga']=="WNI") 
				echo "checked='checked'";
			?>
			 onKeyPress="return focusNext('urutananak', event)" />&nbsp;WNI&nbsp;&nbsp;
    		<input type="radio" name="warga" id="warga" value="WNA" 
			<?php if ($row_siswa['warga']=="WNA") 
				echo "checked='checked'";
			?>
			 onKeyPress="return focusNext('urutananak', event)" />&nbsp;WNA</td>
  		</tr>
  		<tr>
    		<td>Anak ke</td>
    		<td colspan="3"><input type="text" name="urutananak" id="urutananak" size="3" maxlength="3" onFocus="showhint('Urutan anak tidak boleh lebih dari 3 angka!', this, event, '120px')" <?php if($row_siswa['anakke']!=0){ ?>value="<?=$row_siswa['anakke'];?>" <?php } else { ?>value=""<?php } ?> onKeyPress="return focusNext('jumlahanak', event)" />
			&nbsp;&nbsp;dari&nbsp;&nbsp;<input type="text" name="jumlahanak" id="jumlahanak" size="3" maxlength="3" onFocus="showhint('Jumlah saudara tidak boleh lebih dari 3 angka!', this, event, '120px')" value="<?=$row_siswa['jsaudara'];?>"  onKeyPress="return focusNext('bahasa', event)" />&nbsp;&nbsp;bersaudara</td>
  		</tr>
  		<tr>
    		<td>Bahasa</td>
    		<td colspan="3"><input type="text" name="bahasa" id="bahasa" size="30" maxlength="60" onFocus="showhint('Bahasa anak tidak boleh lebih dari 60 karakter!', this, event, '120px')" value="<?=$row_siswa['bahasa']?>" class="ukuran"  onKeyPress="return focusNext('alamatsiswa', event)" /></td>
  		</tr>
  		<tr>
    		<td>Foto</td>
    		<td colspan="3"><input type="file" name="nama_foto" id="file" size="25" /></td>
  		</tr>
  		<tr>
    		<td>Alamat</td>
    		<td colspan="3"><textarea name="alamatsiswa" id="alamatsiswa" rows="2" cols="30" onFocus="showhint('Alamat siswa tidak boleh lebih dari 255 karakter!', this, event, '120px')" class="Ukuranketerangan" onKeyUp="change_alamat()"  onKeyPress="return focusNext('kodepos', event)" ><?=$row_siswa['alamatsiswa']?></textarea></td>
  		</tr>
  		<tr>
    		<td>Kode Pos</td>
    		<td colspan="3"><input type="text" name="kodepos" id="kodepos" size="5" maxlength="8" onFocus="showhint('Kodepos tidak boleh lebih dari 8 angka!', this, event, '120px')" value="<?=$row_siswa['kodepossiswa'];?>"  onKeyPress="return focusNext('telponsiswa', event)" /></td>
  		</tr>
  		<tr>
    		<td>Telepon</td>
    		<td colspan="3"><input type="text" name="telponsiswa" id="telponsiswa" size="20" maxlength="25" onFocus="showhint('Nomor Telepon tidak boleh lebih dari 20 angka!', this, event, '120px')" value="<?=$row_siswa['telponsiswa'];?>" class="ukuran"  onKeyPress="return focusNext('hpsiswa', event)" /></td>
  		</tr>
  		<tr>
    		<td>Handphone</td>
    		<td colspan="3"><input type="text" name="hpsiswa" id="hpsiswa" size="20" maxlength="25" onFocus="showhint('Nomor ponsel tidak boleh lebih dari 20 angka!', this, event, '120px')" value="<?=$row_siswa['hpsiswa'];?>" class="ukuran"  onKeyPress="return focusNext('emailsiswa', event)" />      	</td>
  		</tr>
  		<tr>
    		<td>Email</td>
    		<td colspan="3"><input type="text" name="emailsiswa" id="emailsiswa" size="30" maxlength="100" onFocus="showhint('Alamat email tidak boleh lebih dari 100 karakter!', this, event, '120px')" value="<?=$row_siswa['emailsiswa']?>"  onKeyPress="return focusNext('dep_asal', event)" /></td>
  		</tr>
  		<tr>
    		<td valign="top">Asal Sekolah</td>
    		<td width="11%"><!--<div id = "InfoSekolah">-->
    		<select name="dep_asal" id="dep_asal" onchange="change_departemen(0)" onKeyPress="return focusNext('sekolah', event)">
    		<option value="">[Pilih]</option>	
			<?php // Olah untuk combo departemen
			OpenDb();
			$sql_departemen="SELECT departemen,urutan FROM jbsakad.departemen WHERE departemen <> '$departemen' ORDER BY urutan";			
			$result_departemen=QueryDB($sql_departemen);
			//$row_departemen = @mysqli_fetch_array($result_departemen);
			//$dep_asal = $row_departemen['departemen'];
			//if ($dep_asal == "") 
			//	$dep_asal = $row_departemen['departemen'];
			while ($row_departemen = mysqli_fetch_array($result_departemen)) {			
							
			?>
			<option value="<?=$row_departemen['departemen']?>" <?=StringIsSelected($row_departemen['departemen'], $dep_asal)?>><?=$row_departemen['departemen']?></option>
			<?php 	} 
			CloseDb();
			// Akhir Olah Data kondisi
			?>
    		</select><!--</div>--></td>
            <!--<input type="hidden" name="dep_asal" id="dep_asal" value="<?=$dep_asal?>" />-->
            <td width="16%"><div id="sekolahInfo">
           	<select name="sekolah" id="sekolah"  onKeyPress="return focusNext('ketsekolah', event)" >
            <option value="">[Pilih Asal Sekolah]</option>	
    		<?php // Olah untuk combo sekolah
			OpenDb();
			$sql_sekolah="SELECT sekolah FROM jbsakad.asalsekolah WHERE departemen='$dep_asal' ORDER BY sekolah";
			$result_sekolah=QueryDB($sql_sekolah);
			while ($row_sekolah = mysqli_fetch_array($result_sekolah)) {
				 //if ($sekolah=="")
            		//$sekolah=$row_sekolah['sekolah'];
			?>
      		<option value="<?=$row_sekolah['sekolah']?>" <?=StringIsSelected($row_sekolah['sekolah'], $sekolah)?>>
        	<?=$row_sekolah['sekolah']?>
        	</option>
<?php } 
			CloseDb();
			// Akhir Olah Data sekolah
			?>
    		</select></div></td>
    		<td width="48%">
            <img src="../images/ico/tambah.png" onclick="tambah_asal_sekolah();" onMouseOver="showhint('Tambah Asal Sekolah!', this, event, '80px')"/></td>
  		</tr>
 		<tr>
    		<td valign="top">Keterangan Asal <br />Sekolah      </td>
    		<td colspan="3"><textarea name="ketsekolah" id="ketsekolah" onFocus="showhint('Keterangan sekolah asal tidak boleh lebih dari 255 karakter!', this, event, '120px')" class="Ukuranketerangan"  onKeyPress="return focusNext('gol', event)" ><?=$row_siswa['ketsekolah']?></textarea></td>
  		</tr>
		</table>    
		<script language='JavaScript'>
	    	Tables('table', 1, 0);
		</script>
		</td><!-- Akhir Kolom Kiri-->
    	<td width="1%" align="center" valign="middle"><img src="../images/batas.png" width="2" height="500" /></td>
    	<td width="60%" align="center" valign="top"><!-- Kolom Kanan-->
    	<table width="100%" border="0" cellspacing="0" id="table" class="tab">
  		<tr>
    		<td height="30" colspan="3" class="header" valign="top">RIWAYAT KESEHATAN</td>
    	</tr>
  		<tr>
    		<td width="20%" valign="top">Golongan Darah</td>
    		<td colspan="2">
                <input type="radio" name="gol" id="gol" value"A" <?php 
                if ($row_siswa['darah']=="A")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;A&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="AB" <?php 
                if ($row_siswa['darah']=="AB")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;AB&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="B" <?php 
                if ($row_siswa['darah']=="B")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;B&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="O" <?php 
                if ($row_siswa['darah']=="O")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;O&nbsp;&nbsp;	
                <input type="radio" name="gol" id="gol" value="" <?php 
                if ($row_siswa['darah']=="")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;<em>(belum ada data)</em>&nbsp;         	
           	</td>
 		</tr>
  		<tr>
    		<td>Berat</td>
    		<td colspan="2">
            <input name="berat" type="text" size="6" maxlength="6" id="berat" onFocus="showhint('Berat badan tidak boleh lebih dari 6 angka!', this, event, '120px')" value="<?=$row_siswa['berat'];?>"  onKeyPress="return focusNext('tinggi', event)" />&nbsp;kg</td>
  		</tr>
  		<tr>
    		<td>Tinggi</td>
    		<td colspan="2"><input name="tinggi" type="text" size="6" maxlength="6" id="tinggi" onFocus="showhint('Tinggi badan tidak boleh lebih dari 6 angka!', this, event, '120px')" value="<?=$row_siswa['tinggi'];?>"  onKeyPress="return focusNext('kesehatan', event)" />&nbsp;cm</td>
  		</tr>
  		<tr>
    		<td valign="top">Riwayat Penyakit</td>
    		<td colspan="2"><textarea name="kesehatan" size="35" maxlength="255" id="kesehatan" onFocus="showhint('Riwayat penyakit tidak boleh lebih dari 255 karakter!', this, event, '120px')" class="Ukuranketerangan"  onKeyPress="return focusNext('namaayah', event)" ><?=$row_siswa['kesehatan']?></textarea></td>
  		</tr>
  		<tr>
    		<td height="30" colspan="3" class="header">DATA ORANG TUA SISWA</td>
    	</tr>
  		<tr>
    		<td>&nbsp;</td>
    		<td width="28%" valign="middle" align="center"><strong>Ayah</strong></td>
    		<td align="center" valign="middle"><strong>Ibu</strong></td>
  		</tr>
  		<tr>
            <td valign="top">Nama</td>
            <td><input name="namaayah" type="text" size="15" maxlength="100" id="namaayah" onFocus="showhint('Nama Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px')"  value="<?=$row_siswa['namaayah']?>" class="ukuran"  onKeyPress="return focusNext('namaibu', event)" />
            	<br />
                <input type="checkbox" name="almayah" id="almayah" value="1" title="Klik disini jika Ayah Almarhum"  
        		<?php if ($row_siswa['almayah']=="1") echo "checked";?> />
                &nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font> </td>
          	<td colspan="2"><input name="namaibu" type="text" size="15" maxlength="100" id="namaibu" onFocus="showhint('Nama Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px')" value="<?=$row_siswa['namaibu']?>" class="ukuran"  onKeyPress="return focusNext('Infopendidikanayah', event)" />
            	<br />
                <input type="checkbox" name="almibu" id="almibu" value="1" title="Klik disini jika Ibu Almarhumah"
        		<?php if ($row_siswa['almibu']=="1") echo "checked";?>/>
                &nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font> </td>
        </tr>
  		<tr>
    		<td>Pendidikan</td>
    		<td>			
			<select name="pendidikanayah" id="Infopendidikanayah" class="ukuran"  onKeyPress="return focusNext('Infopendidikanibu', event)" >
      		<option value="">[Pilih Pendidikan]</option>
			<?php // Olah untuk combo pendidikan ayah
			OpenDb();
			$sql_pend_ayah="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
			$result_pend_ayah=QueryDB($sql_pend_ayah);
			while ($row_pend_ayah = mysqli_fetch_array($result_pend_ayah)) {
			?>
      		<option value="<?=$row_pend_ayah['pendidikan']?>" <?=StringIsSelected($row_pend_ayah['pendidikan'], $row_siswa['pendidikanayah'])?>><?=$row_pend_ayah['pendidikan']?></option>
      		<?php
    		} 
			CloseDb();
			// Akhir Olah Data sekolah
			?>
    		</select></td>
    		<td width="49%">			
			<select name="pendidikanibu" id="Infopendidikanibu" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanayah', event)" >
      		<option value="">[Pilih Pendidikan]</option>
			<?php // Olah untuk combo sekolah
			OpenDb();
			$sql_pend_ibu="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
			$result_pend_ibu=QueryDB($sql_pend_ibu);
			while ($row_pend_ibu = mysqli_fetch_array($result_pend_ibu)) {
			?>
      		<option value="<?=$row_pend_ibu['pendidikan']?>" <?=StringIsSelected($row_pend_ibu['pendidikan'], $row_siswa['pendidikanibu'])?>><?=$row_pend_ibu['pendidikan']?></option>
      		<?php
    		} 
			CloseDb();
			// Akhir Olah Data sekolah
			?>
    		</select>
            <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onclick="tambah_pendidikan();" onMouseOver="showhint('Tambah Tingkat Pendidikan!', this, event, '80px')" />
            <?php } ?>
            </td>
  		</tr>
  		<tr>
    		<td>Pekerjaan</td>
    		<td>
			
			<select name="pekerjaanayah" id="Infopekerjaanayah" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanibu', event)" >
      		<option value="">[Pilih Pekerjaan]</option>
			<?php // Olah untuk combo sekolah
			OpenDb();
			$sql_kerja_ayah="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
			$result_kerja_ayah=QueryDB($sql_kerja_ayah);
			while ($row_kerja_ayah = mysqli_fetch_array($result_kerja_ayah)) {
			?>
      		<option value="<?=$row_kerja_ayah['pekerjaan']?>" <?=StringIsSelected($row_kerja_ayah['pekerjaan'], $row_siswa['pekerjaanayah'])?>><?=$row_kerja_ayah['pekerjaan']?></option>
      		<?php
    		} 
			CloseDb();
			// Akhir Olah Data sekolah
			?>
   		 	</select></td>
    		<td>			
			<select name="pekerjaanibu" id="Infopekerjaanibu" class="ukuran"  onKeyPress="return focusNext('penghasilanayah', event)" >
      		<option value="">[Pilih Pekerjaan]</option>
			<?php // Olah untuk combo sekolah
			OpenDb();
			$sql_kerja_ibu="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
			$result_kerja_ibu=QueryDB($sql_kerja_ibu);
			while ($row_kerja_ibu = mysqli_fetch_array($result_kerja_ibu)) {
			?>
      		<option value="<?=$row_kerja_ibu['pekerjaan']?>" <?=StringIsSelected($row_kerja_ibu['pekerjaan'], $row_siswa['pekerjaanibu'])?>><?=$row_kerja_ibu['pekerjaan']?></option>
      		<?php
    		} 
			CloseDb();
			// Akhir Olah Data sekolah
			?>
    		</select>
             <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            <img src="../images/ico/tambah.png" onclick="tambah_pekerjaan();" onMouseOver="showhint('Tambah Jenis Pekerjaan!', this, event, '80px')" />
            <?php } ?>
            </td>
  		</tr>
  		<tr>
    		<td>Penghasilan</td>
    		<td><input type="text" name="penghasilanayah" id="penghasilanayah" size="15" maxlength="20" value="<?=$row_siswa['penghasilanayah'];?>" class="ukuran"  onKeyPress="return focusNext('penghasilanibu', event)" ></td>
    		<td><input type="text" name="penghasilanibu" id="penghasilanibu" size="15" maxlength="20" value="<?=$row_siswa['penghasilanibu'];?>" class="ukuran"  onKeyPress="return focusNext('namawali', event)" /></td>
  		</tr>
  		<tr>
    		<td>Nama Wali</td>
    		<td colspan="2">
            <input type="text" name="namawali" id="namawali" size="30" maxlength="100" value="<?=$row_siswa['wali']?>"  onKeyPress="return focusNext('alamatortu', event)"  ></td>
  		</tr>
  		<tr>
    		<td valign="top">Alamat Ortu</td>
    		<td colspan="2">
            <textarea name="alamatortu" id="alamatortu" size="25" maxlength="100" class="Ukuranketerangan"  onKeyPress="return focusNext('telponortu', event)" ><?=$row_siswa['alamatortu']?></textarea><br /></td>
  		</tr>
  		<tr>
    		<td>Telepon Ortu</td>
    		<td colspan="2">
            <input type="text" name="telponortu" id="telponortu" class="ukuran" maxlength="20" value="<?=$row_siswa['telponortu']?>"  onKeyPress="return focusNext('hportu', event)" /></td>
  		</tr>
  		<tr>
    		<td>Handphone Ortu</td>
    		<td colspan="2"><input type="text" name="hportu" id="hportu" class="ukuran" maxlength="20" value="<?=$row_siswa['hportu']?>"  onKeyPress="return focusNext('emailortu', event)" /></td>
  		</tr>
  		<tr>
    		<td>Email Ortu</td>
    		<td colspan="2"><input type="text" name="emailortu" id="emailortu" size="30" maxlength="100" value="<?=$row_siswa['emailortu']?>"  onKeyPress="return focusNext('alamatsurat', event)" /></td>
  		</tr>
  		<tr>
    		<td height="30" colspan="3" class="header">DATA LAINNYA</td>
    	</tr>
  		<tr>
    		<td valign="top">Alamat Surat</td>
    		<td colspan="2"><textarea name="alamatsurat" id="alamatsurat" size="35" maxlength="100" class="Ukuranketerangan"  onKeyPress="return focusNext('keterangan', event)" ><?=$row_siswa['alamatsurat']?></textarea></td>
  		<tr>
    		<td valign="top">Keterangan</td>
    		<td colspan="2"><textarea name="keterangan" id="keterangan" class="Ukuranketerangan"><?=$row_siswa['keterangan']?></textarea></td>
  		</tr>
		</table>    
		<script language='JavaScript'>
	    	Tables('table', 1, 0);
		</script>
		</td><!-- Akhir Kolom Kanan-->	
    </tr>
	<tr height="50">	
		<td valign="middle" align="right">     
        <input type="Submit" value="Simpan" name="Simpan" class="but" /></td>
  		<td align="center" valign="middle"></td>
  		<td valign="middle"><input class="but" type="button" value="Tutup" name="Tutup"  onClick="tutup()" />
  		</td>           
	</tr>
	</table>
	
   	</td>
</tr>
</table>
</form>
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
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("nama");
var sprytextfield2 = new Spry.Widget.ValidationTextField("panggilan");
var sprytextfield3 = new Spry.Widget.ValidationTextField("tmplahir");
var sprytextfield4 = new Spry.Widget.ValidationTextField("thnlahir");
var sprytextfield5 = new Spry.Widget.ValidationTextField("urutananak");
var sprytextfield6 = new Spry.Widget.ValidationTextField("jumlahanak");
var sprytextfield7 = new Spry.Widget.ValidationTextField("bahasa");
var sprytextfield8 = new Spry.Widget.ValidationTextField("kodepos");
var sprytextfield9 = new Spry.Widget.ValidationTextField("telponsiswa");
var sprytextfield10 = new Spry.Widget.ValidationTextField("hpsiswa");
var sprytextfield11 = new Spry.Widget.ValidationTextField("emailsiswa");
var sprytextfield12 = new Spry.Widget.ValidationTextField("berat");
var sprytextfield13 = new Spry.Widget.ValidationTextField("tinggi");
var sprytextfield14 = new Spry.Widget.ValidationTextField("namaayah");
var sprytextfield15 = new Spry.Widget.ValidationTextField("namaibu");
var sprytextfield16 = new Spry.Widget.ValidationTextField("penghasilanayah");
var sprytextfield17 = new Spry.Widget.ValidationTextField("penghasilanibu");
var sprytextfield18 = new Spry.Widget.ValidationTextField("namawali");
var sprytextfield19 = new Spry.Widget.ValidationTextField("telponortu");
var sprytextfield20 = new Spry.Widget.ValidationTextField("hportu");
var sprytextfield21 = new Spry.Widget.ValidationTextField("emailortu");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("alamatsiswa");
var sprytextarea2 = new Spry.Widget.ValidationTextarea("ketsekolah");
var sprytextarea3 = new Spry.Widget.ValidationTextarea("kesehatan");
var sprytextarea4 = new Spry.Widget.ValidationTextarea("alamatortu");
var sprytextarea5 = new Spry.Widget.ValidationTextarea("alamatsurat");
var sprytextarea6 = new Spry.Widget.ValidationTextarea("keterangan");
var spryselect1 = new Spry.Widget.ValidationSelect("tgllahir");
var spryselect2 = new Spry.Widget.ValidationSelect("blnlahir");
var spryselect3 = new Spry.Widget.ValidationSelect("suku");
var spryselect4 = new Spry.Widget.ValidationSelect("agama");
var spryselect5 = new Spry.Widget.ValidationSelect("status");
var spryselect6 = new Spry.Widget.ValidationSelect("kondisi");
var spryselect7 = new Spry.Widget.ValidationSelect("sekolah");
var spryselect8 = new Spry.Widget.ValidationSelect("Infopendidikanayah");
var spryselect9 = new Spry.Widget.ValidationSelect("Infopendidikanibu");
var spryselect10 = new Spry.Widget.ValidationSelect("Infopekerjaanayah");
var spryselect11 = new Spry.Widget.ValidationSelect("Infopekerjaanibu");
var spryselect12 = new Spry.Widget.ValidationSelect("departemen");
var spryselect13 = new Spry.Widget.ValidationSelect("kelompok");
</script>