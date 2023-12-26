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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function show_detail(id) 
{
	parent.content.location.href = "lappengeluaran_jenis_content.php?departemen=<?=$departemen?>&idtahunbuku=<?=$idtahunbuku?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&idpengeluaran="+id;
}

function cetak() {
	var addr = "lappengeluaran_jenis_rekapcetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	newWindow(addr, 'CetakRekapLapPengeluaran','780','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left">
    
<?php  OpenDb();   
	$sql = "SELECT d.replid AS id, d.nama, SUM(p.jumlah) AS jumlah 
			  FROM pengeluaran p, datapengeluaran d, jurnal j
			 WHERE p.idpengeluaran = d.replid AND d.departemen = '$departemen' 
			   AND p.idjurnal = j.replid AND j.idtahunbuku = '$idtahunbuku' 
			   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2' GROUP BY d.replid, d.nama ORDER BY d.nama";
    
    $result = QueryDb($sql);    
  	if (mysqli_num_rows($result) > 0) {   
?>    
    <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
        <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
        <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
        </td>
    </tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
    <tr height="30" align="center">
        <td width="10%" class="header">No</td>
        <td width="50%" class="header">Pengeluaran</td>
        <td width="*" class="header">Jumlah</td>
    </tr>
    <?php
    
    $cnt = 0;
    $total = 0;
    while ($row = mysqli_fetch_array($result)) {
        $total += $row['jumlah'];
    ?>
    <tr height="25" onclick="show_detail(<?=$row['id'] ?>)" style="cursor:pointer">
        <td align="center"><?=++$cnt ?></td>
        <td align="left"><strong><u><?=$row['nama'] ?></u></strong></td>
        <td align="right"><?=FormatRupiah($row['jumlah']) ?></td>
    </tr>
    <?php
    }
    CloseDb();
    ?>
    <tr height="30">
        <td bgcolor="#999900" colspan="2" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td bgcolor="#999900" align="right">
        	<font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font>		</td>
    </tr>
    </table>
 	<script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
   

<?php } else { ?>	

    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="300">    
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data.         
            <br />Tambah data pengeluaran pada departemen <?=$departemen?> antara tanggal <?=LongDateFormat($tanggal1)." s/d ".LongDateFormat($tanggal2) ?> di menu Pembayaran Pengeluaran pada bagian Pengeluaran.
            </b></font>
        </td>
    </tr>
    </table>  
<?php } ?>
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table> 
</body>
</html>