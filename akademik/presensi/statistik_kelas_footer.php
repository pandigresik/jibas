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
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];	
if (isset($_REQUEST['bln1']))
	$bln1 = $_REQUEST['bln1'];	
if (isset($_REQUEST['th1']))
	$th1 = $_REQUEST['th1'];	
if (isset($_REQUEST['bln2']))
	$bln2 = $_REQUEST['bln2'];	
if (isset($_REQUEST['th2']))
	$th2 = $_REQUEST['th2'];	
	
$tglawal = "$th1-$bln1-1";
if (isset($_REQUEST['tglawal']))
	$tglawal = $_REQUEST['tglawal'];
		
if ($bln2 == 4 || $bln2 == 6|| $bln2 == 9 || $bln2 == 11) 
	$n = 30;
else if ($bln2 == 2 && $th2 % 4 <> 0) 
	$n = 28;
else if ($bln2 == 2 && $th2 % 4 == 0) 
	$n = 29;
else 
	$n = 31;
	
$tglakhir = "$th2-$bln2-$n";
if (isset($_REQUEST['tglakhir']))
	$tglakhir = $_REQUEST['tglakhir'];	
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Statistik Kehadiran Setiap Kelas</title>
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetak() {
	var tglawal = document.getElementById('tglawal').value;
	var tglakhir = document.getElementById('tglakhir').value;	
	var semester = document.getElementById('semester').value;
	var tingkat = document.getElementById('tingkat').value;
	var pelajaran = document.getElementById('pelajaran').value;	
		
	newWindow('statistik_kelas_cetak.php?tglawal='+tglawal+'&tglakhir='+tglakhir+'&semester='+semester+'&tingkat='+tingkat+"&pelajaran="+pelajaran, 'CetakStatistikKehadiranKelas','800','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>
<body>
<input type="hidden" name="tglawal" id="tglawal" value="<?=$tglawal?>">
<input type="hidden" name="tglakhir" id="tglakhir" value="<?=$tglakhir?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE UTAMA -->
<tr>
	<td>
    <?php 		
	OpenDb();
	$sql = "SELECT DISTINCT k.kelas, k.replid FROM presensipelajaran p, kelas k WHERE p.idkelas = k.replid AND k.idtingkat = '$tingkat' AND p.idsemester = '$semester' AND p.idpelajaran = '$pelajaran' AND p.tanggal BETWEEN '$tglawal' AND '$tglakhir' ORDER BY k.kelas, p.tanggal ";	
	//echo 'sql '.$sql;
	$result = QueryDb($sql);			 
	$field = mysqli_num_fields($result);
	$jum = mysqli_num_rows($result);
	
	if ($jum > 0) { 
	?> 
    	<table width="100%" border="0" align="center">
        <!-- TABLE LINK -->
        <tr>
            <td align="right"> 	
            <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onmouseover="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
            </td>
        </tr>
        </table>
        <br />
        <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">		
    	<tr height="30" align="center">		
			<td width="5%" class="header">No</td>
		  	<td width="10%" class="header">Kelas</td>
            <td width="*" class="header"></td>
		</tr>
		<?php 
		$cnt = 0;
		while ($row = @mysqli_fetch_row($result)) {		
    		
		?>	
        <tr height="25">        			
			<td align="center"><?=++$cnt?></td>
			<td align="center"><?=$row[0]?></td>
            <td align="center"><img src="statistik_batang.php?semester=<?=$semester?>&kelas=<?=$row[1]?>&pelajaran=<?=$pelajaran?>&tglakhir=<?=$tglakhir?>&tglawal=<?=$tglawal?>" />
            </td>
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
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />Tambah data presensi kelas di menu Presensi Harian atau <br />Presensi Pelajaran pada bagian Presensi.</b></font>
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