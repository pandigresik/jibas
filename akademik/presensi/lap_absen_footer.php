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

if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];	
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
$urut = "s.nama";	
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

$filter1 = "AND t.departemen = '".$departemen."'";
if ($tingkat <> -1) 
	$filter1 = "AND k.idtingkat = '".$tingkat."'";

$filter2 = "";
if ($kelas <> -1) 
	$filter2 = "AND k.replid = '".$kelas."'";


$op = $_REQUEST['op'];

if ($op == "xm8r389xemx23xb2378e23") {
	OpenDb();
	$sql = "DELETE FROM presensipelajaran WHERE replid = '".$_REQUEST['replid']."'";
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
<title>Laporan Data Siswa Tidak Hadir</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript">

function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var semester = document.getElementById('semester').value;
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	
	newWindow('lap_absen_cetak.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&semester='+semester+'&kelas='+kelas+'&tingkat='+tingkat+'&departemen='+departemen+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanSiswayangTidakHadir','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	
	newWindow('lap_absen_excel.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&semester='+semester+'&kelas='+kelas+'&tingkat='+tingkat+'&departemen='+departemen+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanSiswayangTidakHadir','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var departemen = document.getElementById('departemen').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lap_absen_footer.php?semester="+semester+"&kelas="+kelas+"&tingkat="+tingkat+"&departemen="+departemen+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan;
	
}
</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut?>">
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td>

    <?php 		
	OpenDb();
	$sql = "SELECT DISTINCT s.nis, s.nama, l.nama, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), pp.statushadir, pp.catatan, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, k.kelas FROM siswa s, presensipelajaran p, ppsiswa pp, pelajaran l, kelas k, tingkat t WHERE pp.idpp = p.replid AND s.idkelas = k.replid AND p.idsemester = '$semester' AND pp.nis = s.nis AND k.idtingkat = t.replid $filter1 $filter2 AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = l.replid AND pp.statushadir <> 0 ORDER BY $urut $urutan";
	//echo $sql;
	$result = QueryDb($sql);			 
	$jum_hadir = mysqli_num_rows($result);
	if ($jum_hadir > 0) { 
	?>  
     	<table width="100%" border="0" align="center">
        <!-- TABLE LINK -->
        <tr>
            <td align="right"> 	
            <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:excel()"><img src="../images/ico/excel.png" border="0" onmouseover="showhint('Cetak dalam format Excel!', this, event, '80px')"/>&nbsp;Cetak Excel</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
            
            </td>
        </tr>
        </table>
        <br />
      	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
       	<tr height="30" align="center" class="header">		
			<td width="5%">No</td>
			<td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
			<td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>            
			<?php if ($kelas == -1) { ?>
        	<td width="9%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan?>')">Kelas <?=change_urut('k.kelas',$urut,$urutan)?></td>
			<?php } ?>
            <td width="12%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('l.nama','<?=$urutan?>')">Pelajaran<?=change_urut('l.nama',$urut,$urutan)?></td>
            <td width="6%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('p.tanggal','<?=$urutan?>')">Tgl <?=change_urut('p.tanggal',$urut,$urutan)?></td>
            <td width="8%">Presensi</td>
            <td width="10%">Keterangan</td>            
            <td width="7%">Tlp Siswa</td>
            <td width="10%">HP Siswa</td>
            <td width="15%">Orang Tua</td>
            <td width="7%">Tlp Ortu</td>
            <td width="10%">HP Ortu</td>
		</tr>
		<?php 
		$cnt = 0;
		while ($row = @mysqli_fetch_row($result)) {	
			switch ($row[6]){
				case 1 : $st="Ijin";
				break;
				case 2 : $st="Sakit";
				break;	
				case 3 : $st="Alpha";
				break;
				case 4 : $st="Cuti";
				break;
			}		
							
		?>	
        <tr height="25">        			
			<td align="center"><?=++$cnt?></td>
			<td align="center"><?=$row[0]?></td>
            <td><?=$row[1]?></td>
            <?php if ($kelas == -1) { ?>
            <td align="center"><?=$row[13]?></td>
            <?php } ?>
            <td><?=$row[2]?></td>
            <td align="center"><?=$row[3].'-'.$row[4].'-'.substr((string) $row[5],2,2)?></td>
           	<td align="center"><?=$st ?></td>
            <td><?=$row[7] ?></td>
            <td align="center"><?=$row[8]?></td>
            <td align="center"><?=$row[9]?></td>
            <td><?=$row[10]?></td>
            <td align="center"><?=$row[11]?></td>
            <td align="center"><?=$row[12]?></td>            
    	</tr>
 	<?php 	
		} 
		CloseDb();	?>
		</table>
        <script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>       
		</td>
    </tr>
<?php 	} else { ?>

	 <table width="100%" border="0" align="center">          
	<tr>
		<td align="center" valign="middle" height="250">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />Tambah data presensi kelas di menu Presensi Pelajaran pada bagian Presensi.</b></font>
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