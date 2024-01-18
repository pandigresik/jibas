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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];	
if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];
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
		
$tglawal = "$th1-$bln1-$tgl1";
if (isset($_REQUEST['tglawal']))
	$tglawal = $_REQUEST['tglawal'];	
$tglakhir = "$th2-$bln2-$tgl2";
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];	


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
	var nip = document.getElementById('nip').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	if (confirm("Apakah anda yakin akan menghapus data presensi ini?"))
		document.location.href = "lap_pengajar_footer.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&nip="+nip+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&tahunajaran="+tahunajaran+"&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nip = document.getElementById('nip').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	newWindow('lap_pengajar_cetak.php?nip='+nip+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&tahunajaran='+tahunajaran+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanPresensiPengajar','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nip = document.getElementById('nip').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	
	newWindow('lap_pengajar_excel.php?nip='+nip+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&tahunajaran='+tahunajaran+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanPresensiPengajar','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var nip = document.getElementById('nip').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lap_pengajar_footer.php?nip="+nip+"&tahunajaran="+tahunajaran+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan;
	
}
</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="nip" id="nip" value="<?=$nip?>">
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut?>">
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td>
    <?php 		
	OpenDb();
	$sql = "SELECT DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, k.kelas, l.nama, s.status, p.keterlambatan, p.jumlahjam, p.materi, p.keterangan, p.replid FROM presensipelajaran p, kelas k, pelajaran l, statusguru s WHERE p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = '$nip' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.jenisguru = s.replid AND k.idtahunajaran = '$tahunajaran' ORDER BY $urut $urutan";
	//echo $sql;
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
	?>  
     <table width="100%" border="0" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td align="right">
      	
		<!--<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:excel()"><img src="../images/ico/excel.png" border="0" onmouseover="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;-->
        <a href="JavaScript:cetak('<?=$nip?>')"><img src="../img/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;        </td>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <tr class="header" align="center" height="30">		
		<td width="5%">No</td>
      	<td width="8%">Tgl</td>
      	<td width="5%">Pkl</td>            
      	<td width="7%">Kelas</td>
      	<td width="15%">Pelajaran</td>
      	<td width="14%">Status</td>
      	<td width="7%">Telat</td>
      	<td width="6%">Jam</td>
      	<td width="17%" height="30" align="center" class="header">Materi</td>
      	<td width="*" height="30" align="center" class="header">Keterangan</td>
		<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>      	
        <!--<td width="3%" height="30" align="center" class="header"></td>-->
		<?php //} ?>
	</tr>
	<?php 
    $cnt = 0;
    while ($row = @mysqli_fetch_row($result)) {					
    ?>	
    <tr>        			
        <td height="25" align="center"><?=++$cnt?></td>
        <td height="25" align="center"><?=$row[0].'-'.$row[1].'-'.substr((string) $row[2],2,2)?></td>
        <td height="25" align="center"><?=substr((string) $row[3],0,5)?></td>
        <td height="25" align="center"><?=$row[4]?></td>
        <td height="25"><?=$row[5]?></td>
        <td height="25"><?=$row[6]?></td>
        <td height="25" align="center"><?=$row[7]?> menit</td>
        <td height="25" align="center"><?=$row[8]?></td>
        <td height="25"><?=$row[9]?></td>
        <td height="25"><?=$row[10]?></td>
	<?php //if (SI_USER_LEVEL() != $SI_USER_STAFF) {  ?>            
        <!--<td height="25" align="center"> 
        <a title="Hapus" href="JavaScript:hapus(<?=$row[11] ?>)"><img src="../images/ico/hapus.png" border="0" /></a>
   		</td>--> 
	<?php //} ?>    
    </tr>
<?php 	
	} 
	CloseDb();	?>
	</table>
	<script language='JavaScript'>
		Tables('table', 1, 0);
	</script>       
	<br />
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="400" bordercolor="#000000">
    <tr>
		<td width="200" height="30" class="header">&nbsp;</td>
		<td width="100" height="30" align="center" class="header">Pertemuan</td>
		<td width="100" height="30" align="center" class="header">Jumlah Jam</td>
	</tr>
<?php 	OpenDb();	
	$sql = "SELECT replid, status FROM statusguru ORDER BY status" ;
	$result = QueryDb($sql);	
	while ($row = @mysqli_fetch_array($result)) {
		$replid = $row['replid'];
		
		$sql1 = "SELECT COUNT(*), SUM(p.jumlahjam) FROM presensipelajaran p, pelajaran l, kelas k WHERE p.gurupelajaran = '$nip' AND tanggal BETWEEN '$tglawal' AND '$tglakhir' AND jenisguru = '$replid' AND p.idpelajaran = l.replid AND p.idkelas = k.replid AND k.idtahunajaran = '$tahunajaran' ";
		$result1 = QueryDb($sql1);	
		$row1 = @mysqli_fetch_row($result1);
?>
		<tr>	
    		<td height="25"><strong><?=$row['status']?></strong></td>
   		  <td height="25" align="center"><?=$row1[0]?></td> 	
		  <td height="25" align="center"><?=$row1[1]?></td>    
		</tr>
<?php 	} CloseDb(); ?>
	</table>
    <script language='JavaScript'>
		Tables('table', 1, 0);
	</script> 	 
<?php 	} else { ?>

	 <table width="100%" border="0" align="center">         
	<tr>
		<td align="center" valign="middle" height="250">
    	<span class="err">Tidak ditemukan adanya data. </span></td>
	</tr>
	</table>
<?php } ?>
	</td>
</tr>      
<!-- END OF TABLE UTAMA -->
</table>
</body>
</html>