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

OpenDb();

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['angkatan'])) 
	$angkatan = $_REQUEST['angkatan'];	
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas="";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['kelompok']))
	$kelompok = $_REQUEST['kelompok'];
if (isset($_REQUEST['proses']))
	$proses = $_REQUEST['proses'];
if (isset($_REQUEST['aktif']))
	$aktif = $_REQUEST['aktif'];
if (isset($_REQUEST['no']))
	$no = $_REQUEST['no'];
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
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

if ($op == "xm8r389xemx23xb2378e23") 
{
	$sql="SELECT * FROM calonsiswa WHERE replidsiswa = '".$_REQUEST['replid']."'";
	$result = QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$idproses = $row['idproses'];
	$idkelompok = $row['idkelompok'];
	
	BeginTrans();
	$success = 0;
	
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
	
	if ($success)
	{
		CommitTrans();	?>
    	<script language="javascript">
			var proses = parent.menu.document.penempatan_menu.proses.value;
			var kelompok = parent.menu.document.penempatan_menu.kelompok.value; 
			var cari = 'tampil';
			var warna = 'C0C0C0';	
			
			if (proses == <?=$idproses?>) 
			{ 
				parent.menu.location.href = "penempatan_menu.php?proses="+proses+"&departemen=<?=$departemen?>&kelompok=<?=$idkelompok?>&cari="+cari+"&warna="+warna;
				parent.daftar.location.href = "penempatan_daftar.php?proses="+proses+"&departemen=<?=$departemen?>&aktif=1&angkatan=<?=$angkatan?>&tahunajaran=<?=$tahunajaran?>&tingkat=<?=$tingkat?>&kelas=<?=$kelas?>&cari="+cari+"&kelompok=<?=$idkelompok?>";
			} 	
		</script>
<?php } 
	else 
	{
		RollbackTrans();
	}
	$page=0;
	$hal=0;	
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penempatan Calon Siswa</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function hapus(replid,nis) {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var aktif = document.getElementById('aktif').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	
	if (confirm("Apakah anda yakin akan menghapus siswa ini dari daftar?"))
		document.location.href = "penempatan_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&nis="+nis+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
		
}

function show(x) {
	document.getElementById("infokapasitas").innerHTML = x;
}

function change() {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	var aktif = document.getElementById('aktif').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var no = document.getElementById('no').value;	
	var nama = document.getElementById('nama').value;
	var cari = document.getElementById('cari').value;	
	var warna = document.getElementById('warna').value;
	
	sendRequestText("getkapasitas.php", show, "kelas="+kelas+"&tahunajaran="+tahunajaran+"&angkatan="+angkatan);	
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&warna="+warna;
}

function change_angkatan() {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;	
	var aktif = document.getElementById('aktif').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var no = document.getElementById('no').value;	
	var nama = document.getElementById('nama').value;
	var cari = document.getElementById('cari').value;	
	var warna = document.getElementById('warna').value;
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&warna="+warna;
}

function change_urut(urut,urutan) {	
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	var aktif = document.getElementById('aktif').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var no = document.getElementById('no').value;	
	var nama = document.getElementById('nama').value;
	var cari = document.getElementById('cari').value;	
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}

function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var angkatan = document.getElementById('angkatan').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;	
	var aktif = document.getElementById('aktif').value;
	var proses = document.getElementById('proses').value;
	var kelompok = document.getElementById('kelompok').value;
	var cari = document.getElementById('cari').value;
	var no = document.getElementById('no').value;
	var nama = document.getElementById('nama').value;
	var varbaris=document.getElementById("varbaris").value;
		
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page="+page+"&hal="+page+"&varbaris="+varbaris;
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
	var aktif = document.getElementById("aktif").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page="+hal+"&hal="+hal+"&varbaris="+varbaris;
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
	var aktif = document.getElementById('aktif').value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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

function panggil(elem){
	var lain = new Array('angkatan','tahunajaran','tingkat','kelas');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#4cff15';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

function refresh_isi() {
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
	var aktif = document.getElementById("aktif").value;
	
	document.location.href = "penempatan_content.php?departemen="+departemen+"&angkatan="+angkatan+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas+"&proses="+proses+"&kelompok="+kelompok+"&aktif="+aktif+"&cari="+cari+"&no="+no+"&nama="+nama+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="proses" id="proses" value="<?=$proses ?>" />
<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok ?>" />
<input type="hidden" name="warna" id="warna" value="<?=$warna?>" />
<input type="hidden" name="aktif" id="aktif" value="<?=$aktif ?>" />
<input type="hidden" name="cari" id="cari" value="<?=$cari ?>" />
<input type="hidden" name="no" id="no" value="<?=$no ?>" />
<input type="hidden" name="nama" id="nama" value="<?=$nama ?>" />


<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
  	<td height="50" align="center">
    <fieldset>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" >
    <!-- TABLE TITLE -->
    
    <tr>
      	<td align="left" width="24%"><strong>Angkatan</strong></td>
      	<td align="center">
        	<select name="angkatan" id="angkatan" onChange="change_angkatan()" style="width:228px;" onKeyPress="return focusNext('tahunajaran', event)" onFocus="panggil('angkatan')">
<?php 		$sql = "SELECT replid,angkatan FROM angkatan where aktif=1 AND departemen = '$departemen' ORDER BY replid DESC";
			$result = QueryDb($sql);
			while($row = mysqli_fetch_array($result)) 
			{
				if ($angkatan == "")
					$angkatan = $row['replid'];	?>
           	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $angkatan) ?>><?=$row['angkatan']?></option>
<?php 		} //while	?>
     		</select></td>
    </tr>
	<tr>
    	<td align="left"><strong>Th. Ajaran</strong></td>
      	<td align="center">
        	<select name="tahunajaran" id="tahunajaran" onChange="change_angkatan()" style="width:228px;" onKeyPress="return focusNext('tingkat', event)" onFocus="panggil('tahunajaran')">
<?php 		$sql = "SELECT replid,tahunajaran,aktif FROM tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) 
			{
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
					
				if ($row['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';	?>
	    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
<?php 		}	?>
    		</select>   		
            </td>             
    </tr>
    <tr>
    	<td align="left"><strong>Tingkat</strong></td>
        <td align="center">
        	<select name="tingkat" id="tingkat" onChange="change_angkatan()" style="width:228px;" onKeyPress="return focusNext('kelas', event)" onFocus="panggil('tingkat')">
<?php 		$sql = "SELECT replid,tingkat FROM tingkat where departemen='$departemen' AND aktif = 1 ORDER BY urutan";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) 
			{
				if ($tingkat == "") 
					$tingkat = $row['replid'];	?>
	          <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat)?> ><?=$row['tingkat']?></option>
<?php 		} ?>
        </select></td>
	</tr>
    <tr>
    	<td align="left"><strong>Kelas</strong></td>
    	<td align="center">  
        	<select name="kelas" id="kelas" onChange="change()" style="width:228px;" onKeyPress="return focusNext('tabel', event)" onFocus="panggil('kelas')">
<?php          $sql = "SELECT replid, kelas, kapasitas FROM kelas where idtingkat='$tingkat' AND idtahunajaran='$tahunajaran' AND aktif = 1 ORDER BY kelas";
            $result = QueryDb($sql);
			$nama_kelas = "";
			while ($row = @mysqli_fetch_array($result)) 
			{				
				if ($kelas == "") 
					$kelas = $row['replid'];
				
				$sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '".$row['replid']."' AND idangkatan = '$angkatan' AND aktif = 1";
				$result1 = QueryDb($sql1);
				$row1 = @mysqli_fetch_row($result1); ?>
	    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas)?> ><?=$row['kelas'].', kapasitas: '.$row['kapasitas'].', terisi: '.$row1[0]?></option>
<?php 		} ?>
    		</select>        
        
            <div id="infokapasitas">
<?php 			if ($kelas <> "" && $angkatan <> "" && $tahunajaran <> "") 
			{	
				$sql = "SELECT kapasitas FROM kelas WHERE replid = $kelas";
				$result = QueryDb($sql);
				$row = mysqli_fetch_row($result);
				$kapasitas = $row[0];
				
				$sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '$kelas' AND idangkatan = '$angkatan' AND aktif = 1";
				$result1 = QueryDb($sql1);
				$row1 = mysqli_fetch_row($result1);
				$isi = $row1[0];
			}		
			?>
            <input type="hidden" name="kapasitas" id="kapasitas" value="<?=$kapasitas ?>" />
            <input type="hidden" name="isi" id="isi" value="<?=$isi ?>" />
            
            </div>    
    	</td>
    </tr>
    
    
	</table>     
    </fieldset>
   	</td>
</tr>
<tr>
	<td>
<?php  if ($angkatan <> "" && $tahunajaran <> "" && $tingkat <> "" && $kelas <> "" ) 
	{
		$sql_tot = "SELECT s.replid,s.nis,s.nama,s.frompsb,s.nisn
					FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t 
					WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' 
					  AND s.idangkatan = '$angkatan' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1";
		$result_tot = QueryDb($sql_tot);
		$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$akhir = ceil($jumlah/5)*5;
	
		$sql = "SELECT s.replid,s.nis,s.nama,s.frompsb,s.nisn 
				FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t 
				WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' 
				AND s.idangkatan = '$angkatan' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.aktif=1 
				ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
		$result = QueryDb($sql);
		$jum = @mysqli_num_rows($result);
		
		$sql5 = "SELECT kelas FROM jbsakad.kelas WHERE replid = '$kelas' ";
		$result5 = QueryDb($sql5);
		$row5 = @mysqli_fetch_array($result5);
		$nama_kelas = $row5['kelas'];
	
		if ($jum > 0) 
		{ ?>
	
            <table border="1" width="100%" id="table" class="tab" bordercolor="#000000">
            <tr class="header" align="center" height="30">		
                <td width="8%">No</td>
				<td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nisn','<?=$urutan?>')">N I S N<?=change_urut('nisn',$urut,$urutan)?></td>
                <td width="25%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S <?=change_urut('nis',$urut,$urutan)?></td>
                <td width="*" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
                <td width="8%">&nbsp;</td>
            </tr>
<?php      	if ($page==0)
                $cnt = 0;
            else 
                $cnt = (int)$page*(int)$varbaris;
            $result = QueryDb($sql);		
			while ($row = @mysqli_fetch_array($result)) 
			{
				?>	
            <tr height="25">        			
                <td align="center"><?=++$cnt?></td>
                <td align="center"><?=$row['nisn']?></td>
				<td align="center"><?=$row['nis']?></td>
                <td><a href="#" onClick="newWindow('../library/detail_siswa.php?replid=<?=$row['replid']?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')"><?=$row['nama']?></a></td>
                <td align="center">
                <?php if ($row['frompsb'] == 1) { ?>
                    <a href="JavaScript:hapus(<?=$row['replid']?>,'<?=$row['nis']?>')"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Batalkan Penempatan!', this, event, '80px')"/></a>
                <?php } ?>
                </td>	
            </tr>
<?php 		} //while		?>			
			</table>
  			<!-- END TABLE CONTENT -->
   
			<script language='JavaScript'>
                Tables('table', 1, 0);
            </script>
		<?php if ($page==0)
			{ 
                $disback="style='visibility:hidden;'";
                $disnext="style='visibility:visible;'";
            }
            if ($page < $total && $page>0)
			{
                $disback="style='visibility:visible;'";
                $disnext="style='visibility:visible;'";
            }
            if ($page == $total-1 && $page > 0)
			{
                $disback="style='visibility:visible;'";
                $disnext="style='visibility:hidden;'";
            }
            if ($page == $total-1 && $page == 0)
			{
                $disback="style='visibility:hidden;'";
                $disnext="style='visibility:hidden;'";
            }	?>
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
        </td>
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
        <br />Belum ada calon siswa yang menempati kelas <?=$nama_kelas?>. 
       	<br />Silahkan pilih Angkatan, Tahun Ajaran, Tingkat dan Kelas yang ingin ditempati.
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
    	<?php if ($departemen <> "") {	?>
            <font size = "2" color ="red"><b>Belum ada kelas yang dituju.
            <br />Tambah data kelas pada departemen <?=$departemen?> di menu Kelas pada bagian Referensi. 
            </b></font>
        <?php } else { ?>
            <font size = "2" color ="red"><b>Belum ada data Departemen.
            <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
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
</body>
</html>
<?php 
CloseDb();
?>