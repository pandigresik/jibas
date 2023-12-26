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

if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];	
if (isset($_REQUEST['tgl1']))
	$tgl1 = $_REQUEST['tgl1'];	
if (isset($_REQUEST['bln1']))
	$bln1 = $_REQUEST['bln1'];	
if (isset($_REQUEST['th1']))
	$th1 = $_REQUEST['th1'];	
if (isset($_REQUEST['tgl2']))
	$tgl2 = $_REQUEST['tgl2'];		
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];	
if (isset($_REQUEST['th2']))
	$th2 = $_REQUEST['th2'];	
$urut = "p.tanggal";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
$urut1 = "p.tanggal";	
if (isset($_REQUEST['urut1']))
	$urut1 = $_REQUEST['urut1'];	
$urutan1 = "ASC";	
if (isset($_REQUEST['urutan1']))
	$urutan1 = $_REQUEST['urutan1'];	
	
	
$tglawal = "$th1-$bln1-$tgl1";
if (isset($_REQUEST['tglawal']))
	$tglawal = $_REQUEST['tglawal'];	
$tglakhir = "$th2-$bln2-$tgl2";
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];	


$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM ppsiswa WHERE replid = '".$_REQUEST['replid']."'";
	QueryDb($sql);
	CloseDb();	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Siswa</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">
function hapus(replid) {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nis = document.getElementById('nis').value;
	if (confirm("Apakah anda yakin akan menghapus data presensi ini?"))
		document.location.href = "lap_siswa_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&nis="+nis+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut=<?=$urut?>&urutan=<?=$urutan?>&urut1=<?=$urut1?>&urutan1=<?=$urutan1?>";
}

function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nis = document.getElementById('nis').value;
	newWindow('lap_siswa_cetak.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&lihat=1&urut=<?=$urut?>&urutan=<?=$urutan?>&urut1=<?=$urut1?>&urutan1=<?=$urutan1?>', 'CetakLaporanPresensiSiswaPelajaran','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nis = document.getElementById('nis').value;
	newWindow('lap_siswa_excel.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&urut=<?=$urut?>&urutan=<?=$urutan?>&urut1=<?=$urut1?>&urutan1=<?=$urutan1?>', 'CetakLaporanPresensiSiswaPelajaran','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var nis = document.getElementById('nis').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lap_siswa_footer.php?nis="+nis+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan+"&urut1=<?=$urut1?>&urutan1=<?=$urutan1?>";
	
}

function change_urut1(urut1,urutan1) {		
	var nis = document.getElementById('nis').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
		
	if (urutan1 =="ASC"){
		urutan1="DESC"
	} else {
		urutan1="ASC"
	}
	
	document.location.href = "lap_siswa_footer.php?nis="+nis+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut1="+urut1+"&urutan1="+urutan1+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
	
}
</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />
<input type="hidden" name="urut1" id="urut1" value="<?=$urut1 ?>" />
<input type="hidden" name="urutan1" id="urutan1" value="<?=$urutan1 ?>" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td colspan="2">
	
	<?php 		
	OpenDb();
	$sql = "SELECT k.kelas, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, pp.catatan, l.nama, g.nama, p.materi, pp.replid FROM presensipelajaran p, ppsiswa pp, jbssdm.pegawai g, kelas k, pelajaran l WHERE pp.idpp = p.replid AND p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = g.nip AND pp.nis = '$nis' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND pp.statushadir = 0 ORDER BY $urut $urutan" ;
	
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	
	$sql1 = "SELECT k.kelas, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, pp.catatan, l.nama, g.nama, p.materi, pp.replid FROM presensipelajaran p, ppsiswa pp, jbssdm.pegawai g, kelas k, pelajaran l WHERE pp.idpp = p.replid AND p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = g.nip AND pp.nis = '$nis' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND pp.statushadir <> 0 ORDER BY $urut1 $urutan1" ;
	$result1 = QueryDb($sql1);			 
	$jum_absen = mysqli_num_rows($result1);

if ($jum_hadir > 0 || $jum_absen > 0) { ?> 
	<table width="100%" border="0" align="center">
    <!-- TABLE LINK -->
    <tr>
   		<td align="right">
        <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="#" onclick="excel()"><img src="../images/ico/excel.png" border="0" onmouseover="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
        <a href="#" onclick="cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
        
       
        </td> 	
   	</tr>
    </table>	
<?php if ($jum_hadir > 0) { 
	?>
	
    <fieldset>
        <legend><strong>Data Kehadiran</strong></legend>
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
   	<tr height="30" align="center" class="header">		
    	<td width="5%">No</td>      	
      	<td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.tanggal','<?=$urutan?>')">Tgl <?=change_urut('p.tanggal',$urut,$urutan)?></td>            
      	<td width="6%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.jam','<?=$urutan?>')">Jam <?=change_urut('p.jam',$urut,$urutan)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
      	<td width="*" >Catatan</td>
      	<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('l.nama','<?=$urutan?>')">Pelajaran <?=change_urut('l.nama',$urut,$urutan)?></td>
      	<td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('g.nama','<?=$urutan?>')">Guru <?=change_urut('g.nama',$urut,$urutan)?></td>
      	<td width="25%" height="30" align="center" class="header">Materi</td>
    <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>  	
        <td width="3%" height="30" align="center" class="header"></td>
    <?php 	} ?>    
    </tr>
	<?php 
    $cnt = 1;
    while ($row = @mysqli_fetch_row($result)) {					
    ?>	
    <tr>        			
        <td height="25" align="center"><?=$cnt?></td>
      	
      	<td height="25" align="center"><?=$row[1].'-'.$row[2].'-'.substr((string) $row[3],2,2)?></td>
      	<td height="25" align="center"><?=substr((string) $row[4],0,5)?></td>
        <td height="25" align="center"><?=$row[0]?></td>
      	<td height="25"><?=$row[5]?></td>
      	<td height="25"><?=$row[6]?></td>
      	<td height="25"><?=$row[7]?></td>
      	<td height="25"><?=$row[8]?></td>
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>    	
        <td height="25" align="center">
        <a title="Hapus" href="JavaScript:hapus(<?=$row[9] ?>)"><img src="../images/ico/hapus.png" border="0" /></a>
  		</td>
<?php 	} ?>      
    </tr>
<?php 	$cnt++;
    } 
    CloseDb();	?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>	
    </fieldset>
    </td>
</tr>
<?php 	} ?>  
<?php 		
	OpenDb();
	
	if ($jum_absen > 0) { 
?> 
<tr>
	<td colspan="2">
   
   <br />
    <fieldset>
        <legend><strong>Data Ketidakhadiran</strong></legend>
    
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center">
    <tr align="center" class="header" height="30">		
		<td width="5%">No</td>      	
      	<td width="5%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut1('p.tanggal','<?=$urutan1?>')">Tgl <?=change_urut('p.tanggal',$urut1,$urutan1)?></td>            
      	<td width="6%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut1('p.jam','<?=$urutan1?>')">Jam <?=change_urut('p.jam',$urut1,$urutan1)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut1('k.kelas','<?=$urutan1?>')">Kelas <?=change_urut('k.kelas',$urut1,$urutan1)?></td>
      	<td width="25%">Catatan</td>
      	<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut1('l.nama','<?=$urutan1?>')">Pelajaran <?=change_urut('l.nama',$urut1,$urutan1)?></td>
      	<td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut1('g.nama','<?=$urutan1?>')">Guru <?=change_urut('g.nama',$urut1,$urutan1)?></td>
      	<td width="25%">Materi</td>
 <?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>    
      	<td width="3%" height="30" align="center" class="header"></td>
 <?php 	} ?>      

    </tr>
	<?php 
    $cnt = 1;
    while ($row1 = @mysqli_fetch_row($result1)) {					
    ?>	
    <tr>        			
        <td height="25" align="center"><?=$cnt?></td>       
        <td height="25" align="center"><?=$row1[1].'-'.$row1[2].'-'.substr((string) $row1[3],2,2)?></td>
        <td height="25" align="center"><?=substr((string) $row1[4],0,5)?></td> 
        <td height="25" align="center"><?=$row1[0]?></td>
        <td height="25"><?=$row1[5]?></td>
        <td height="25"><?=$row1[6]?></td>
        <td height="25"><?=$row1[7]?></td>
        <td height="25"><?=$row1[8]?></td>
<?php 	if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>         
		<td height="25" align="center">
        <a title="Hapus" href="JavaScript:hapus(<?=$row1[9] ?>)"><img src="../images/ico/hapus.png" border="0" /></a>
    		</td>   
<?php 	} ?>     
	</tr>
<?php 	$cnt++;
    } 
    CloseDb();	?>
	  </table>
	  <script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>
	</fieldset>   
	</td>
</tr>
<?php 	} ?> 

<tr>
	<td><br />
    <table width="100%" border="0" align="center">
    <tr>
        <td width="17%"><b>Jumlah Kehadiran</b></td>
        <td><b>: <?=$jum_hadir ?></b></td>
    </tr>
    <tr>
        <td><b>Jumlah Ketidakhadiran</b></td>
        <td><b>: <?=$jum_absen ?></b></td>
    </tr>
    <tr>
        <td><b>Jumlah Seharusnya</b></td>
        <td><b>: <?php $total = $jum_hadir+$jum_absen;
                echo $total ?></b></td>
    </tr>
    <tr>
        <td><b>Presentase Kehadiran</b></td>
        <td><b>: <?php 	if ($total == 0) 
                    $total = 1;
                $prs = (( $jum_hadir/$total)*100) ;
				
                echo round($prs,2) ?>%</b></td>
    </tr>
    </table>
<?php 	} elseif ($jum_hadir == 0 || $jum_absen == 0) { ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="300">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />Tambah data presensi siswa dengan NIS <?=$nis?> di menu Presensi Pelajaran pada bagian Presensi.</b></font>
		</td>
	</tr>
	</table>
<?php } ?>
	</td>
</tr>
<!-- END OF TABLE UTAMA -->
</table>

</body>
</html>