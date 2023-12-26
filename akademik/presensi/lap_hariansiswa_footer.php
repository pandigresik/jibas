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
	
$tglawal = "$th1-$bln1-$tgl1";
if (isset($_REQUEST['tglawal']))
	$tglawal = $_REQUEST['tglawal'];	
$tglakhir = "$th2-$bln2-$tgl2";
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];	

$urut = "p.tanggal1";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Harian Siswa</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">

function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nis = document.getElementById('nis').value;
	
	newWindow('lap_hariansiswa_cetak.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&lihat=1&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanPresensiHarianSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function excel() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var nis = document.getElementById('nis').value;
	
	newWindow('lap_hariansiswa_excel.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&urut=<?=$urut?>&urutan<?=$urutan?>', 'CetakLaporanPresensiHarianSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
	
	document.location.href = "lap_hariansiswa_footer.php?nis="+nis+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan;
	
}

</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td>
    <?php 		
	OpenDb();
	
	$sql = "SELECT DAY(p.tanggal1), MONTH(p.tanggal1), YEAR(p.tanggal1), DAY(p.tanggal2), MONTH(p.tanggal2), YEAR(p.tanggal2),
				   ph.hadir, ph.ijin, ph.sakit, ph.alpa, ph.cuti, ph.keterangan, s.nama, m.semester, k.kelas
			  FROM presensiharian p, phsiswa ph, siswa s, semester m, kelas k
			 WHERE ph.idpresensi = p.replid AND ph.nis = s.nis AND ph.nis = '$nis'
			   AND p.idsemester = m.replid AND p.idkelas = k.replid AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) ORDER BY $urut $urutan ";
	
	$result = QueryDb($sql);			 
	$jum = mysqli_num_rows($result);
	if ($jum > 0) { 
	?>  
	<table width="100%" border="0" align="center">
    <!-- TABLE LINK -->
    <tr>
    	<td align="right">
    	<a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:excel()"><img src="../images/ico/excel.png" border="0" onmouseover="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;</td>         
	</tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <!-- TABLE CONTENT -->
    <tr height="30" align="center" class="header">		
        <td width="5%">No</td>
        <td width="20%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.tanggal1','<?=$urutan?>')">Tanggal <?=change_urut('p.tanggal1',$urut,$urutan)?></td>
        <td width="10%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('m.semester','<?=$urutan?>')">Semester <?=change_urut('m.semester',$urut,$urutan)?></td>
        <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ph.hadir','<?=$urutan?>')">Hadir <?=change_urut('ph.hadir',$urut,$urutan)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ph.ijin','<?=$urutan?>')">Ijin <?=change_urut('ph.ijin',$urut,$urutan)?></td>            
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ph.sakit','<?=$urutan?>')">Sakit <?=change_urut('ph.sakit',$urut,$urutan)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ph.alpa','<?=$urutan?>')">Alpa <?=change_urut('ph.alpa',$urut,$urutan)?></td>
        <td width="7%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('ph.cuti','<?=$urutan?>')">Cuti <?=change_urut('ph.cuti',$urut,$urutan)?></td>
        <td width="*">Keterangan</td>
    </tr>
    <?php 
    $cnt = 0;
	$h=0;
	$i=0;
	$s=0;
	$a=0;
	$c=0;
    while ($row = @mysqli_fetch_row($result)) {	
		$nama = $row[12];
		
		
    ?>	
    <tr height="25">        			
        <td align="center"><?=++$cnt?></td>
        <td align="center"><?=$row[0].' '.$bulan[$row[1]].' '.$row[2].' - '.$row[3].' '.$bulan[$row[4]].' '.$row[5]?></td>
        <td align="center"><?=$row[13]?></td>
        <td align="center"><?=$row[14]?></td>
        <td align="center"><?=$row[6]?></td>
        <td align="center"><?=$row[7]?></td>
        <td align="center"><?=$row[8]?></td>
        <td align="center"><?=$row[9]?></td>
        <td align="center"><?=$row[10]?></td>
        <td><?=$row[11]?></td>
    </tr>
<?php
	$h+=$row[6];
	$i+=$row[7];
	$s+=$row[8];
	$a+=$row[9];
	$c+=$row[10];	
    } 
    CloseDb();	?>
    <tr height="25">        			
        <td colspan="4" bgcolor="#CCCCCC" align="right"><b>Jumlah : </b>&nbsp;&nbsp;</td>
        <td align="center" bgcolor="#FFFFFF"><?=$h?></td>
        <td align="center" bgcolor="#FFFFFF"><?=$i?></td>
        <td align="center" bgcolor="#FFFFFF"><?=$s?></td>
        <td align="center" bgcolor="#FFFFFF"><?=$a?></td>
        <td align="center" bgcolor="#FFFFFF"><?=$c?></td>
        <td bgcolor="#CCCCCC"></td>
    </tr>
	</table>
	<script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
   
<?php 	} else { ?>
	<table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="300">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />Tambah data presensi siswa dengan NIS <?=$nis?> di menu Presensi Harian pada bagian Presensi.</b></font>
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