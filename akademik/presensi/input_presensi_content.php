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

$id = "";
$filter = "";
if (isset($_REQUEST['id'])) 
	$id = $_REQUEST['id'];
		
if ($id <> "") {	
	OpenDb();
	$sql = "SELECT DAY(tanggal1) AS tgl1, MONTH(tanggal1) AS bln, YEAR(tanggal1) AS th, DAY(tanggal2) AS tgl2, hariaktif FROM presensiharian WHERE replid = '".$id."'";
	$result = QueryDb($sql);
	CloseDb();
	$row = mysqli_fetch_array($result);
	$tgl1 = $row['tgl1'];
	$tgl2 = $row['tgl2'];
	$bln = $row['bln'];
	//$bln2 = $row['bln2'];
	$th = $row['th'];
	$hariaktif = $row['hariaktif'];
	//$th2 = $row['th2'];
	$filter = " UNION SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif FROM siswa s,phsiswa p, kelas k WHERE  p.nis = s.nis AND s.idkelas = k.replid AND p.idpresensi = '$id' ";
}

if (isset($_REQUEST['tgl1']))
	$tgl1 = $_REQUEST['tgl1'];
if (isset($_REQUEST['bln']))
	$bln = $_REQUEST['bln'];
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];
//if (isset($_REQUEST['bln2']))
//	$bln2 = $_REQUEST['bln2'];
if (isset($_REQUEST['th']))
	$th = $_REQUEST['th'];
//if (isset($_REQUEST['th2']))
//	$th2 = $_REQUEST['th2'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['hariaktif']))
	$hariaktif = $_REQUEST['hariaktif'];
	
OpenDb();
$sql = "SELECT t.tahunajaran, t.tglmulai, t.tglakhir, k.kelas FROM tahunajaran t, kelas k WHERE k.idtahunajaran = t.replid AND k.replid = '".$kelas."'"; 
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$awal = explode('-',(string) $row[1]);
$akhir = explode('-',(string) $row[2]);
$tanggal1 = $awal[2];
$tanggal2 = $akhir[2];
$bulan1 = (int)$awal[1];
$bulan2 = (int)$akhir[1];
$tahun1 = $awal[0];
$tahun2 = $akhir[0];

$nama_kelas= $row[3];

if ($tgl1 == "") 
	$tgl1 = date("j");
if ($tgl2 == "") 
	$tgl2 = date("j");


/*$thn1 = $th1;
$thn2 = $th2;


$nowbln = date("n");
$nowthn = date("Y");
$nowtgl = date("j");

if ($id == "") {
	if ($bln1 == $nowbln && $th1 == $nowthn) {	
		
		if (strlen($tgl1) > 0 && strlen($tgl2) > 0) {
			if ($nowtgl > $tgl1 || $nowtgl < $tgl2) {
				$tgl1 = $nowtgl;
				$tgl2 = $nowtgl;
			} 
		} else { 
			$tgl1 = $nowtgl;		
			$tgl2 = $nowtgl;
		}
	} else {
		if (strlen($_REQUEST['tgl1']) == 0 && strlen($_REQUEST['tgl2']) == 0) {
			$tgl1 = 1;
			$tgl2 = 1;
		}		
	}
} */
	
$n = JmlHari($bln,$th);

$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	$replid=(int)$_REQUEST['replid'];
	OpenDb();
	$sql = "DELETE FROM phsiswa WHERE idpresensi = $replid";
	QueryDb($sql);
	$sql = "DELETE FROM presensiharian WHERE replid = $replid";
	QueryDb($sql);
	
	
	if(mysqli_affected_rows($conn) > 0) {
	?>
    <script language = "javascript" type = "text/javascript">
        
		parent.menu.location.href = "input_presensi_menu.php?semester=<?=$semester?>&kelas=<?=$kelas?>&th=<?=$th?>&bln=<?=$bln?>";	
		parent.isi.location.href = "blank_presensi.php?tipe='isi'";
	</script>
<?php } else { ?>
    <script language = "javascript" type = "text/javascript">
        alert('Data presensi harian gagal dihapus, periksalah apakah data sudah terpakai');	
	</script>
<?php CloseDb();
	}
}


$hari = 0;
function jumhari($tgl1, $tgl2) {
	OpenDb();
	$sql = "SELECT DATEDIFF('$tgl2', '$tgl1')+1";
	$result = QueryDb($sql);
	
	$row = mysqli_fetch_row($result);
	$GLOBALS['hari'] = $row[0];
	$waktu = $row[0];
	//echo "<input type='hidden' name='hari' id='hari' value=$waktu />";
	return true;
}

$jumhariaktif = ($tgl2-$tgl1)+1;
if (isset($_REQUEST['jumhariaktif']))
	$jumhariaktif = $_REQUEST['jumhariaktif'];

OpenDb();



 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Presensi Harian</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function refresh() {
	document.location.reload();
}

function hapus(replid) {	
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;
	var tgl1 = parseInt(document.main.tgl1.value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var bln = parseInt(document.getElementById('bln').value);	 
	var th = parseInt(document.getElementById('th').value);	 
	
	if (confirm("Apakah anda yakin akan menghapus data presensi ini?"))		
		document.location.href = "input_presensi_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&semester="+semester+"&kelas="+kelas+"&bln="+bln+"&th="+th+"&tgl1="+tgl1+"&tgl2="+tgl2;

}

function change_tgl1() {

	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	var id = document.getElementById('replid').value;
	var th = parseInt(document.getElementById('th').value);
	var bln = parseInt(document.getElementById('bln').value);
	var tgl = parseInt(document.main.tgl1.value);
	var tgl1 = parseInt(document.main.tgl2.value);	
	
	if (tgl > tgl1) {
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		document.main.tgl1.focus();
		return false;
	} 
	
	document.location.href = "input_presensi_content.php?kelas="+kelas+"&semester="+semester+"&bln="+bln+"&th="+th+"&tgl1="+tgl+"&tgl2="+tgl1+"&id="+id+"&hariaktif="+hari();
	//sendRequestText("../library/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl);		
}

function change_tgl2() {
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	var id = document.getElementById('replid').value;
	var tgl1 = parseInt(document.main.tgl1.value);
	var th = parseInt(document.getElementById('th').value);
	var bln = parseInt(document.getElementById('bln').value);
	var tgl = parseInt(document.main.tgl2.value);
	
	if (tgl1 > tgl) {
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		document.main.tgl2.focus();
		return false;
	} 
	
	document.location.href = "input_presensi_content.php?kelas="+kelas+"&semester="+semester+"&bln="+bln+"&th="+th+"&tgl1="+tgl1+"&tgl2="+tgl+"&id="+id+"&hariaktif="+hari();

	//sendRequestText("../library/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl);
}

/*
function show1(x) {
	document.getElementById("tgl1Info").innerHTML = x;
}

function show2(x) {
	document.getElementById("tgl2Info").innerHTML = x;
}*/

function hari() {	
	var tgl1 = parseInt(document.main.tgl1.value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var bln = parseInt(document.getElementById('bln').value);
	var th = parseInt(document.getElementById('th').value);
	
	selisih = (tgl2 - tgl1)+1;
	return selisih;
}

function cek(status,jum) {	
	var aktif = document.getElementById("ck"+status).checked;
	var nilai = document.getElementById("def"+status).value;
	var tgl1 = document.main.tgl1.value;
	var tgl2 = document.main.tgl2.value;
	var hari = parseInt(document.getElementById('hariaktif').value);
			
	if (aktif) {
		if (nilai.length == 0) {
			alert ("Masukkan jumlah kehadiran pada kolom "+status);
			document.getElementById("def"+status).focus();
			document.getElementById("ck"+status).checked = false;		
			return false;			
		} else {
			if (isNaN(nilai)) {
				alert ("Jumlah presensi harus berupa bilangan");
				document.getElementById("def"+status).focus();
				document.getElementById("ck"+status).checked = false;		
				return false;
			} else {
				//if (hari() > 0) {
				if (hari > 0) {
					//if (nilai > hari()) {
					if (nilai > hari) {
						//alert ("Jumlah presensi kehadiran tidak boleh lebih dari "+hari()+ " hari ");
						alert ("Jumlah presensi kehadiran tidak boleh lebih dari "+hari+ " hari ");
						document.getElementById("def"+status).focus();
						document.getElementById("ck"+status).checked = false;		
						return false;
					} else { 				
						for (i=1;i<=jum;i++) {
							document.getElementById(status+i).value = nilai;
						}
					}
				} else {
					alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
					document.main.tgl2.focus();
					document.getElementById("ck"+status).checked = false;
					return false;	
				}
			}
		}
	} 
}

function validate() {	
	var jum = parseInt(document.getElementById("jum").value);
	var status = Array("hadir","ijin","sakit","cuti","alpa");	
	var tgl1 = parseInt(document.main.tgl1.value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var bln = parseInt(document.getElementById("bln").value);
	var th = parseInt(document.getElementById("th").value);
	var tanggal1 = parseInt(document.getElementById("tanggal1").value);
	var tanggal2 = parseInt(document.getElementById("tanggal2").value);
	var bulan1 = parseInt(document.getElementById("bulan1").value);
	var bulan2 = parseInt(document.getElementById("bulan2").value);
	var tahun1 = parseInt(document.getElementById("tahun1").value);
	var tahun2 = parseInt(document.getElementById("tahun2").value);
	var hari = parseInt(document.getElementById('hariaktif').value);
	
	
	if (tahun1 <= th && tahun2 >= th) { 				
		if (bulan1 == bln && th == tahun1) {
			
			if (tgl1 < tanggal1) {					
				alert ('Pastikan waktu presensi tidak melebihi batas periode tahun ajaran \nantara '+tanggal1+'-'+bulan1+'-'+tahun1+' s/d '+tanggal2+'-'+bulan2+'-'+tahun2+'!');
				document.main.tgl1.focus();
				return false;
			}
		}		
		
		if (bulan2 == bln && th == tahun2) {
			if (tgl2>tanggal2) {		
				//alert ('tgl2='+tgl2+'>tanggal2='+tanggal2);			
				alert ('Pastikan waktu presensi tidak melebihi batas periode tahun ajaran \n antara'+tanggal1+'-'+bulan1+'-'+tahun1+' s/d '+tanggal2+'-'+bulan2+'-'+tahun2+'!');
				document.main.tgl2.focus();
				return false;
			} 
		}
	
	}

	/*if (hari() < 0) {	
		alert ('Pastikan batas tanggal akhir tidak kurang dari batas tanggal awal');
		document.main.tgl2.focus();
		return false;	
	}*/
	
	for (i=1;i<=jum;i++) {
		sum = 0;
		for (j=0;j<5;j++) {			
			var isi = parseInt(document.getElementById(status[j]+i).value);
			/*if (isi.length == 0) {
				alert ("Jumlah presensi tidak boleh kosong");
				document.getElementById(status[j]+i).focus(); 						
				return false;
			 } else { 	*/
			 	if (isNaN(isi)) {
					alert ("Jumlah presensi harus berupa bilangan");
					document.getElementById(status[j]+i).focus(); 						
					return false;	
				} 
				sum = sum + isi;
			//}
		}
				
		//if (sum > hari()) {
		//if (sum > hari) {
		
		//var aktif = parseInt(document.getElementById("aktif"+i).value);
		var aktif = parseInt(document.getElementById("aktif1").value);
		
		if (sum != hari && aktif == 1) {
			//alert ("Jumlah presensi kehadiran untuk tiap siswa tidak boleh lebih dari "+hari()+ " hari!");
			alert ("Jumlah presensi untuk tiap siswa harus berjumlah "+hari+ " hari!");	
			document.getElementById(status[0]+i).focus(); 
								
			return false;
		}
		
		/*if (sum == 0) {
			alert ("Jumlah presensi kehadiran untuk tiap siswa tidak boleh kosong!");
			document.getElementById(status[0]+i).focus(); 						
			return false;
		}*/
	}
	
	document.getElementById('main').submit();
	
	/*for (j=0;j<5;j++) {
		for (i=1;i<=jum;i++) {
			var isi = document.getElementById(status[j]+i).value;
			if (isNaN(isi)){
				alert("Jumlah presensi harus berupa bilangan");
				document.getElementById(status[j]+i).focus();				
				return false;
			} else {
				if (isi > hari()) {
					alert ("Jumlah presensi kehadiran tidak boleh lebih dari "+hari()+ " hari ");
					document.getElementById(status[j]+i).focus(); 
					return false;
				}
			}
		}		
	}*/
}

function tes(status) {	
	document.getElementById("ck"+status).checked = false;	
}

function cetak() {
	var tgl1 = parseInt(document.main.tgl1.value);
	var tgl2 = parseInt(document.main.tgl2.value);
	var bln = parseInt(document.getElementById('bln').value);
	var th = parseInt(document.getElementById('th').value);
	var replid = document.getElementById('replid').value;
	var kelas = document.getElementById('kelas').value;
	
	
	newWindow('input_presensi_cetak.php?replid='+replid+'&tgl1='+tgl1+'&tgl2='+tgl2+'&bln='+bln+'&th='+th+'&kelas='+kelas, 'CetakLaporanPresensiHarianSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
<style type="text/css">
<!--
.style1 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
</head>

<body topmargin="0" leftmargin="0">
 	
<form name="main" id="main" method="post" enctype="multipart/form-data" action="input_presensi_simpan.php" >
<input type="hidden" name="semester" id="semester" value="<?=$semester ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="bln" id="bln" value="<?=$bln ?>" />
<input type="hidden" name="th" id="th" value="<?=$th ?>" />
<input type="hidden" name="replid" id="replid" value="<?=$id ?>" />
<input type="hidden" name="tanggal1" id="tanggal1" value="<?=$tanggal1 ?>" />
<input type="hidden" name="bulan1" id="bulan1" value="<?=$bulan1 ?>" />
<input type="hidden" name="tahun1" id="tahun1" value="<?=$tahun1 ?>" />

<input type="hidden" name="tanggal2" id="tanggal2" value="<?=$tanggal2 ?>" />
<input type="hidden" name="bulan2" id="bulan2" value="<?=$bulan2 ?>" />
<input type="hidden" name="tahun2" id="tahun2" value="<?=$tahun2 ?>" />



<?php
	OpenDb();
	$sql = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif FROM siswa s, kelas k WHERE s.idkelas = '$kelas' AND s.aktif = 1 AND s.alumni=0 AND k.replid = s.idkelas $filter ORDER BY nama";
	
	$result = QueryDb($sql);		
	$cnt = 1;
	$jum = @mysqli_num_rows($result);

	if ($jum > 0) {
?>

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td width="*"><strong>Tanggal</strong>
       	<select name="tgl1" id = "tgl1Info" onChange="change_tgl1();" onKeyPress="focusNext('tgl2Info',event)">
		<?php 	for($i=1;$i<=$n;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl1, $i)?>><?=$i?></option>
		<?php } ?>
		</select>
        <?=NamaBulan($bln).' '.$th?>
       s/d
        <select name="tgl2" id = "tgl2Info" onChange="change_tgl1()" onKeyPress="focusNext('hariaktif',event)">
		
		<?php 	for($i=1;$i<=$n;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($tgl2, $i)?>><?=$i?></option>
		<?php } ?>
		</select>
        <?=NamaBulan($bln).' '.$th?>
        
   	</td>
    <td align="right" width="25%">   
    	<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <?php
		if ($id <> ""){
	?>
    	<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
	<?php } ?>	      
    </td>
</tr>
<tr>
	<td colspan="2"><strong>Jumlah hari aktif belajar </strong>
        <select name="hariaktif" id = "hariaktif" onKeyPress="focusNext('defhadir',event)">
		<?php 	for($i=1;$i<=$jumhariaktif;$i++){   ?>      
		    <option value="<?=$i?>" <?=IntIsSelected($hariaktif, $i)?>><?=$i?></option>
		<?php } ?>
		</select>
    </td>
</tr>
<tr>
	<td colspan="2"> <p>
    <table border="1" width="100%" id="table" class="tab" bordercolor="#000000">
		<tr height="15">		
			<td width="3%" align="center" class="headerlong" rowspan="2">No</td>
			<td width="8%" align="center" class="headerlong" rowspan="2">N I S</td>
			<td width="*" align="center" class="headerlong" rowspan="2">Nama</td>
            <td width="9%" align="center" class="header">Hadir</td>
            <td width="9%" align="center" class="header">Ijin</td>
            <td width="9%" align="center" class="header">Sakit</td>
            <td width="9%" align="center" class="header">Alpa</td>
            <td width="9%" align="center" class="header">Cuti</td>
            <td width="21%" align="center" class="headerlong" rowspan="2">Keterangan</td>
		</tr>
        <tr height="15">       	          
        	<td align="center" class="header"> 
       	<input type="text" name="defhadir" id="defhadir" size="1" maxlength="2" value="0" onKeyUp="tes('hadir')" />
        <input type="checkbox" name="ckhadir" id="ckhadir" value="1" onClick="cek('hadir',<?=$jum?>)" ></td>
            <td align="center" class="header">
       	<input type="text" name="defijin" id="defijin" size="1" maxlength="2" value="0" onKeyUp="tes('ijin')"/>
        <input type="checkbox" name="ckijin" id="ckijin" value="1" onClick="cek('ijin',<?=$jum?>)" ></td>
            <td align="center" class="header">
        <input type="text" name="defsakit" id="defsakit" size="1" maxlength="2" value="0" onKeyUp="tes('sakit')"/>       
        <input type="checkbox" name="cksakit" id="cksakit" value="1" onClick="cek('sakit',<?=$jum?>)" ></td>
            <td align="center" class="header"> 
        <input type="text" name="defalpa" id="defalpa" size="1" maxlength="2" value="0" onKeyUp="tes('alpa')"/>
        <input type="checkbox" name="ckalpa" id="ckalpa" value="1" onClick="cek('alpa',<?=$jum?>)" ></td>					
        	<td align="center" class="header">
        <input type="text" name="defcuti" id="defcuti" size="1" maxlength="2" value="0" onKeyUp="tes('cuti')"/>
        <input type="checkbox" name="ckcuti" id="ckcuti" value="1" onClick="cek('cuti',<?=$jum?>)" ></td>
                      
        </tr>
        
		<?php 
		$aktif = 1;
		while ($row = @mysqli_fetch_array($result)) {		
			$hadir = 0;
			$ijin = 0;
			$sakit = 0;
			$cuti = 0;
			$alpa = 0;
			$ket = "";
				
			$color = "black";
			$pesan = "";
			if ($row['idkelas'] <> $kelas) {
				$color = "red";					
				$pesan = "Pindah kelas ke ".$row['kelas'];
			} else if ($row['aktif'] == 0) {
				$color = "red";
				$pesan = "Siswa tidak aktif";
			} 	
			
			if ($id <> "") {						
				$sql1 = "SELECT * FROM phsiswa WHERE idpresensi = '$id' AND nis='".$row['nis']."'";
				$result1 = QueryDb($sql1);
				$row1 = mysqli_fetch_array($result1);
				if (mysqli_num_rows($result1) > 0) {
					$hadir = $row1['hadir'];
					$ijin = $row1['ijin'];
					$sakit = $row1['sakit'];
					$cuti = $row1['cuti'];
					$alpa = $row1['alpa'];
					$ket = $row1['keterangan'];
					$aktif = 1;
					/*if ($row['aktif'] == 1) {
						if ($row1['keterangan'] == "Siswa baru pindah kelas")
							$ket = "";
					}*/
				} else {
					//$cuti = $hariaktif;
					$ket = "Siswa baru pindah kelas";
					$aktif = 0;
				}
			}
		?>	
        <tr height="25"> 
        	     			
		<?php if ($row['idkelas'] <> $kelas) { ?>
        	<td align="center" onMouseOver="showhint('Kelas tetap di <?=$row['kelas']?>', this, event, '80px')">
            <font color="#FF0000"><?=$cnt?></font></td>  
            <td align="center" onMouseOver="showhint('Kelas tetap di <?=$row['kelas']?>', this, event, '80px')">
            <font color="#FF0000"><?=$row['nis']?></font></td>
            <td onMouseOver="showhint('Kelas tetap di <?=$row['kelas']?>', this, event, '80px')">
            <font color="#FF0000"><?=$row['nama']?></font></td>			
		<?php } else if ($row['aktif'] == 0) { ?>			
            <td align="center" onMouseOver="showhint('Status siswa tidak aktif lagi!', this, event, '80px')">
            <font color="#FF0000"><?=$cnt?></font></td>
            <td align="center" onMouseOver="showhint('Status siswa tidak aktif lagi!', this, event, '80px')">
            <font color="#FF0000"><?=$row['nis']?></font></td>
            <td onMouseOver="showhint('Status siswa tidak aktif lagi!', this, event, '80px')">
            <font color="#FF0000"><?=$row['nama']?></font></td>
		<?php } else {	?>
            <td align="center"><?=$cnt?></td>
            <td align="center"><?=$row['nis']?></td>
            <td><?=$row['nama']?></td>
       	<?php } ?>
           	
           	<td align="center">
            <input type="hidden" name="nis<?=$cnt?>" value="<?=$row['nis']?>">
            <input type="hidden" name="aktif<?=$cnt?>" id="aktif<?=$cnt?>" value="<?=$aktif?>">
            <input type="text" name="hadir<?=$cnt?>" id="hadir<?=$cnt?>" size="1" maxlength="2" value="<?=$hadir?>" onKeyPress="focusNext('ijin<?=$cnt?>',event) "/>
           <?php //echo $hari;?>
            </td>
           	<td align="center">
            <input type="text" name="ijin<?=$cnt?>" id="ijin<?=$cnt?>" size="1" maxlength="2" value="<?=$ijin?>" onKeyPress="focusNext('sakit<?=$cnt?>',event)"/></td>
          	<td align="center">
            <input type="text" name="sakit<?=$cnt?>" id="sakit<?=$cnt?>" size="1" maxlength="2" value="<?=$sakit?>" onKeyPress="focusNext('alpa<?=$cnt?>',event)"/></td>
           	<td align="center">
            <input type="text" name="alpa<?=$cnt?>" id="alpa<?=$cnt?>" size="1" maxlength="2" value="<?=$alpa?>" onKeyPress="focusNext('cuti<?=$cnt?>',event)"/></td>
            <td align="center">
            <input type="text" name="cuti<?=$cnt?>" id="cuti<?=$cnt?>" size="1" maxlength="2" value="<?=$cuti?>" onKeyPress="focusNext('ket<?=$cnt?>',event)"/></td>
          	
            <td align="center">
           <textarea name="ket<?=$cnt?>" id="ket<?=$cnt?>" rows="1" cols="18" <?php if ($cnt == $jum) { ?> onKeyPress="focusNext('simpan',event)" <?php } else { ?> onKeyPress="focusNext('hadir<?=$cnt+1 ?>',event)" <?php } ?> ><?=$ket?></textarea>
            </td>
    	</tr>
 	<?php $cnt++;
		} 
		CloseDb();	?>
      	</table>
        <!-- END TABLE CONTENT -->
      
    <script language='JavaScript'>
	   	Tables('table', 1, 0);
    </script>   	  
    	</td>
    	<input type="hidden" name="jum" id="jum" value="<?=$jum?>">	
        <input type="hidden" name="hari" id="hari" value="<?=$hari ?>" />		
		
    
	</tr>
    <tr>    
    	<td align="right" colspan="2">
       	<input type="button" name="simpan" id="simpan" value="Simpan" class="but" style="width:100px; " onClick="return validate();<?php ///jumhari($tanggal1, $tanggal2);?>"/>
        <?php
			if($id <> ""){
				$action = "Update";
		?>
			<input type="button" value="Hapus Data" class="but" onClick="hapus(<?=$id ?>)" style="width:100px;">
			<!--<input type="button" value="(+) Tambah Siswa" class="but" onClick="tambah(<?=$id ?>)">-->
			<?php
			}else{
				$action = "Simpan"; 
				
			}
			?>
        </td>
        <input type="hidden" name="action" id="action" value="<?=$action?>">	
	</tr>
    
<!-- END TABLE CENTER -->    
</table>
<?php } else {  ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="300">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data siswa di kelas <?=$nama_kelas?>.<br />Tambah data siswa di menu Pendataan Siswa pada bagian Kesiswaan.
        </b></font>
	</td>
	</tr>
	</table> 
<?php  } ?> 
</form>

</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("tgl1Info");
	var spryselect4 = new Spry.Widget.ValidationSelect("tgl2Info");
	for (x=1;x<=<?=$jum?>;x++){
		var sprytextfield1 = new Spry.Widget.ValidationTextField("hadir"+x);
		var sprytextfield2 = new Spry.Widget.ValidationTextField("ijin"+x);
		var sprytextfield3 = new Spry.Widget.ValidationTextField("sakit"+x);
		var sprytextfield4 = new Spry.Widget.ValidationTextField("cuti"+x);
		var sprytextfield5 = new Spry.Widget.ValidationTextField("alpa"+x);
		var sprytextarea = new Spry.Widget.ValidationTextarea("ket"+x);
	}
</script>