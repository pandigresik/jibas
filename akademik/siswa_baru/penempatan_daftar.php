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

$proses = $_REQUEST['proses'];
$kelompok = $_REQUEST['kelompok'];
$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['angkatan'])) 
	$angkatan = $_REQUEST['angkatan'];	
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['no']))
	$no = $_REQUEST['no'];
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
if (isset($_REQUEST['aktif']))
	$aktif = $_REQUEST['aktif'];
if (isset($_REQUEST['cari']))
	$cari = $_REQUEST['cari'];

if (isset($_REQUEST['warna']))
	$warna = $_REQUEST['warna'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
	
$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	BeginTrans();
	$success=0;
	
	$sql = "DELETE FROM siswa WHERE replid = '".$_REQUEST['replid']."'";
	QueryDbTrans($sql,$success);	
	
	$sql_calon="UPDATE calonsiswa SET replidsiswa=NULL WHERE replidsiswa = '".$_REQUEST['replid']."'";
	if ($success)
		QueryDbTrans($sql_calon,$success);
	
	$sql_dept="DELETE FROM riwayatdeptsiswa WHERE nis='".$_REQUEST['nis']."'";
	if ($success)
		QueryDbTrans($sql_dept,$success);

	$sql_kls="DELETE FROM riwayatkelassiswa WHERE nis='".$_REQUEST['nis']."'";
	if ($success)
		QueryDbTrans($sql_kls,$success);
	
	if ($success) {
		CommitTrans();		
	} else {
		RollbackTrans();
		CloseDb();
	}
	CloseDb();
}	

if ($aktif == 1) {
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penempatan Calon Siswa</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function pindah(replid, nisn)
{	
	var departemen = document.getElementById('departemen').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;	
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var kapasitas = parent.isi.document.getElementById('kapasitas').value;		
	var isi = parent.isi.document.getElementById('isi').value;		
	var kelas = parent.isi.document.getElementById('kelas').value;
	var tingkat = parent.isi.document.getElementById('tingkat').value;
	var angkatan = parent.isi.document.getElementById('angkatan').value;	
	var tahunajaran = parent.isi.document.getElementById('tahunajaran').value;		
	
	if (kelas == "" ) {
		alert ('Belum ada kelas yang dituju!');
		return false;
	} 
	
	if (kapasitas == isi) {
		alert("Kapasitas kelas tidak mencukupi untuk menambah data siswa baru!");
		parent.isi.document.getElementById('kelas').focus();		
		return false;
	}
	
	newWindow('penempatan_simpan.php?nisn='+nisn+'&departemen='+departemen+'&angkatan='+angkatan+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat+'&kelas='+kelas+'&proses='+proses+'&kelompok='+kelompok+'&replid='+replid+'&cari='+cari+'&no='+no+'&nama='+nama, 'PenempatanCalonSiswa','465','370','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid,nis) {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	
	if (confirm("Apakah anda yakin akan membatalkan penempatan siswa ini pada kelas ini?"))
		document.location.href = "penempatan_daftar.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&nis="+nis+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_urut(urut,urutan) {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "penempatan_daftar.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function tampil(replid) {
	newWindow('../library/detail_calon.php?replid='+replid, 'DetailCalonSiswa'+replid,'800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "penempatan_daftar.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page="+page+"&hal="+page+"&varbaris="+varbaris;
}

function change_hal() {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "penempatan_daftar.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page="+hal+"&hal="+hal+"&varbaris="+varbaris;
}

function change_baris() {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "penempatan_daftar.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function refresh_daftar() {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	
	document.location.href = "penempatan_daftar.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=1&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
	parent.isi.refresh_isi();
}
	
</script>
</head>
<body topmargin="0" leftmargin="0">
<input type="hidden" name="proses" id="proses" value="<?=$proses ?>" />
<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok ?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="angkatan" id="angkatan" value="<?=$angkatan ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="cari" id="cari" value="<?=$cari ?>" />
<input type="hidden" name="no" id="no" value="<?=$no ?>" />
<input type="hidden" name="nama" id="nama" value="<?=$nama ?>" />
<?php 	
	
	OpenDb();
	$sql = "SELECT replid,nopendaftaran,nama,replidsiswa,nisn FROM calonsiswa WHERE idproses ='$proses'";  
	if ($cari == "tampil") {
		$sql = $sql." AND idkelompok = '".$kelompok."'"; 
	}
	if ($cari == "cari") {
		if ($nama <> "") 
			$sql = $sql." AND nama like '%$nama%' ";		
		if ($no <> "") 
			$sql = $sql." AND nopendaftaran like '%$no%'";
	}
	if ($cari == "lihat") {
		$sql = $sql." AND replidsiswa is NULL";
	}
	
	$sql_tot = $sql;
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;	
	
	$sql = $sql." ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);
	
	$jum = @mysqli_num_rows($result);
	
	
	if ($jum == 0) { 
		if ($cari != "lihat") {
	?>
	<script>
		var departemen = document.getElementById('departemen').value;
		var angkatan = document.getElementById('angkatan').value;
		var tahunajaran = document.getElementById('tahunajaran').value;
		var tingkat = document.getElementById('tingkat').value;
		var kelas = document.getElementById('kelas').value;	
		var proses = document.getElementById('proses').value;
		var kelompok = document.getElementById('kelompok').value;
		var cari = document.getElementById('cari').value;	
		var no = document.getElementById('no').value;
		var nama = document.getElementById('nama').value;
		parent.isi.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif=0&cari="+cari+"&no="+no+"&nama="+nama;
		
		alert ('Belum ada calon siswa yang terdaftar!');
		
	</script>
	<?php 
		} else { ?>
        <table width="100%">
        <tr height="200">
            <td align="center">
                <font size = "2" color ="red"><b>Tidak ada calon siswa yang belum memiliki kelas.
                </b></font>
            </td>
        </tr>
        </table>
<?php 	}
	} else {  ?>
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">	
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="left" bordercolor="#000000">
    <!--<table border="1" width="100%" id="table" class="tab">-->
    <!-- TABLE CONTENT -->
    <tr align="center" height="30" class="header">    	
    	<td width="4%">No</td>
        <td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No. Pendaftaran <?=change_urut('nopendaftaran',$urut,$urutan)?></td>
		<td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nisn','<?=$urutan?>')">NISN <?=change_urut('nisn',$urut,$urutan)?></td>
        <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="15%">NIS </td>
        <td width="10%">Kelas </td>
        <td width="8%"></td>
    </tr>   
    <?php 
		if ($page==0)
			$cnt = 0;
		else 
			$cnt = (int)$page*(int)$varbaris;
		
		$result = QueryDb($sql);
		while ($row = @mysqli_fetch_array($result)) {
			if ($row['replidsiswa'] <> NULL) {
				OpenDb();	
				$sql1 = "SELECT s.nis, k.kelas FROM siswa s, kelas k WHERE s.replid ='".$row['replidsiswa']."' AND s.idkelas = k.replid";  				
				$result1 = QueryDb($sql1);
				CloseDb();
				$row1 = @mysqli_fetch_array($result1);
				$nis = $row1['nis'];
				$kls = $row1['kelas'];
			} else {
				$nis = "";
				$kls = "";
			}
	?>
    <tr height="25">
       	<td align="center"><?=++$cnt ?></td>
        <td align="center" ><?=$row['nopendaftaran'] ?></td>
		<td align="center" ><?=$row['nisn'] ?></td>
        <td><a href="#" onclick="tampil('<?=$row['replid']?>')"><?=$row['nama']?></a></td>
        <td align="center"><?=$nis ?></td>
        <td align="center"	><?=$kls?></td>
        
        
        <td align="center">
		<?php if ($row['replidsiswa'] == NULL) { ?>
        <input type="button" name="pindah" id="pindah" value=" > " class="but" onClick="pindah(<?=$row['replid']?>, '<?=$row['nisn']?>')" onmouseover="showhint('Klik untuk menempatkan calon siswa!', this, event, '90px')"/>        
        <?php } ?>
        </td>
		
    	
    </tr>
<?php } 
	CloseDb(); 
?>	
	<!-- END TABLE CONTENT -->
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
    	<!--td align="center">
    <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<?php
		/*for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."')\">".($a+1)."</a> "; 
			}
				 
	    }*/
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
 		</td-->
        <td width="30%" align="right">Jml baris per hal
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>	
    </td></tr>
<!-- END TABLE CENTER -->    
</table>
<?php 		} 
	
} else { ?>
<script>
	document.location.href = "blank_penempatan_daftar.php";
</script>
<?php } ?>
</body>
</html>