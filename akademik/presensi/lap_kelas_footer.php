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

if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];		
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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Presensi Siswa Per Kelas</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function lihat(nis) {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var pelajaran = document.getElementById('pelajaran').value;	
	newWindow('lap_siswa_cetak.php?nis='+nis+'&tglawal='+tglawal+'&tglakhir='+tglakhir+'&lihat=0&urut=p.tanggal&urutan=ASC&urut1=p.tanggal&urutan1=ASC'+'&pelajaran='+pelajaran, 'CetakLaporanPresensiSiswaPelajaran','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var pelajaran = document.getElementById('pelajaran').value;	
	var kelas = document.getElementById('kelas').value;	
	var semester = document.getElementById('semester').value;	
	
	newWindow('lap_kelas_cetak.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&pelajaran='+pelajaran+'&kelas='+kelas+'&semester='+semester+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanPresensiSiswaPerKelas','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var pelajaran = document.getElementById('pelajaran').value;	
	var kelas = document.getElementById('kelas').value;	
	var semester = document.getElementById('semester').value;	
	
	newWindow('lap_kelas_excel.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&pelajaran='+pelajaran+'&kelas='+kelas+'&semester='+semester+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakLaporanPresensiSiswaPerKelas','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	var pelajaran = document.getElementById('pelajaran').value;
	var kelas = document.getElementById('kelas').value;
	var semester = document.getElementById('semester').value;
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href = "lap_kelas_footer.php?pelajaran="+pelajaran+"&kelas="+kelas+"&semester="+semester+"&tglawal="+tglawal+"&tglakhir="+tglakhir+"&urut="+urut+"&urutan="+urutan;
	
}

</script>
</head>

<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="urut" id="urut" value="<?=$urut?>">
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan?>">


<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td align="left">
    <?php 		
	OpenDb();
	if ($pelajaran == -1) {		
		$sql = "SELECT DISTINCT s.nis, s.nama, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, s.aktif FROM siswa s, presensipelajaran p, ppsiswa pp, kelas k WHERE pp.idpp = p.replid AND pp.nis = s.nis AND s.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' ORDER BY $urut $urutan";
		
	} else {
		$sql = "SELECT DISTINCT s.nis, s.nama, s.telponsiswa, s.hpsiswa, s.namaayah, s.telponortu, s.hportu, s.aktif FROM siswa s, presensipelajaran p, ppsiswa pp, kelas k WHERE pp.idpp = p.replid AND pp.nis = s.nis AND s.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran' ORDER BY $urut $urutan"; 
	}	
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
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">		
    <tr height="30" class="header" align="center">		
        <td width="5%">No</td>
        <td width="8%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
        <td width="15%" onMouseOver="background='../style/formbg2agreen.gif';height=30;" onMouseOut="background='../style/formbg2.gif';height=30;" background="../style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>            
        <td width="5%">Jml Hadir</td>
        <td width="8%">Jml Tak Hadir</td>
        <td width="5%">Jml Total</td>
        <td width="5%">%</td>            
        <td width="7%">Tlp Siswa</td>
        <td width="10%">HP Siswa</td>
        <td width="15%">Orang Tua</td>
        <td width="7%">Tlp Ortu</td>
        <td width="7%">HP Ortu</td>
        <td width="5%"></td>
    </tr>
    <?php 
    $cnt = 0;
    while ($row = @mysqli_fetch_row($result)) {	    
     if ($row[7] == 0) { 
		$pesan = "Status siswa tidak aktif lagi!";
	?>
	<tr height="25" style="color:#FF0000">
	<?php } else { 
		$pesan = "Lihat!";
	?>
    <tr height="25">
    <?php } ?>        		          			
        <td align="center"><?=++$cnt?></td>
        <td align="center"><?=$row[0]?></td>
        <td><?=$row[1]?></td>
        <td align="center">
    <?php  if ($pelajaran == -1) {		
            $sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' " ;	
        } else {
            $sql1 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir = 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;	
        }
    
            //echo $sql1;			
            $result1 = QueryDb($sql1);
            $row1 = @mysqli_fetch_array($result1);
            $hadir = $row1[0];
            echo $row1[0]; 	?></td>
        <td align="center">
    <?php 	if ($pelajaran == -1) {		
            $sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir'  " ;
        } else {
            $sql2 = "SELECT COUNT(*) FROM ppsiswa pp, presensipelajaran p WHERE pp.nis = '".$row[0]."' AND pp.statushadir <> 0 AND pp.idpp = p.replid AND p.idkelas = '$kelas' AND p.idsemester = '$semester' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' AND p.idpelajaran = '$pelajaran'" ;					
        }
            $result2 = QueryDb($sql2);
            $row2 = @mysqli_fetch_array($result2);
            $absen = $row2[0];
            echo $row2[0]; ?></td>
        <td align="center">
        <?php 	$tot = $hadir + $absen;
            echo $tot;	?></td>
        <td align="center">
        <?php 	if ($tot == 0) 
                $tot = 1;
            $prs = (( $hadir/$tot)*100); 
            echo round($prs,2).'%'; ?></td>
        <td align="center"><?=$row[2]?></td>
        <td align="center"><?=$row[3]?></td>    
        <td><?=$row[4]?></td>
        <td align="center"><?=$row[5]?></td>    
        <td align="center"><?=$row[6]?></td> 
        <td align="center"><a href="JavaScript:lihat('<?=$row[0]?>')"><img src="../images/ico/lihat.png" border="0" onmouseover="showhint('<?=$pesan?>', this, event, '80px')"/></a></td>    
    </tr>
<?php 	
    } 
    CloseDb();	?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
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