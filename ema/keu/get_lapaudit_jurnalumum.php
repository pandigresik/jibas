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
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

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
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function cetak() {	
	var addr = "lapaudit_jurnalumum_cetak.php?departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	newWindow(addr, 'CetakAuditJurnalUmum','1000','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body topmargin="0" marginheight="0" >
<br />
<table width="100%" border="0" align="center">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
  	<table width="100%" border="0" height="100%" cellspacing="0" cellpadding="0">
   	<tr>
    	<td class="news_title1">Perubahan Data Jurnal Umum</td>
    	<td align="right">    	
            <!--<a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')" />&nbsp;Refresh</a>&nbsp;-->
            <a href="JavaScript:cetak('jurnalumum')"><img src="../img/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;    	</td>
    </tr>
    </table>
    <br />
    <table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="4%">No</td>
        <td class="header" width="10%">Status Data</td>
        <td class="header" width="10%">Tanggal</td>
        <td class="header" width="15%">Keterangan</td>
        <td class="header" width="*">Detail Jurnal</td>
        <td class="header" width="15%">Petugas</td>
    </tr>
    <?php
    OpenDb();
  	$sql = "SELECT DISTINCT ai.petugas AS petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, 
	                aj.replid AS id, aj.idaudit, aj.status, aj.nokas, date_format(aj.tanggal, '%d-%b-%Y') AS tanggal,  
				       aj.petugas, aj.keterangan, aj.petugas, ai.alasan 
				  FROM jbsfina.auditjurnal aj, jbsfina.auditinfo ai, jbsfina.jurnal j 
				 WHERE aj.idaudit = ai.replid AND ai.idsumber = j.replid AND j.idtahunbuku = '$idtahunbuku' AND ai.departemen = '$departemen' 
				   AND ai.sumber='jurnalumum' AND ai.tanggal BETWEEN '$tanggal1 00:00:00' AND '$tanggal2 23:59:59' 
			 ORDER BY aj.idaudit DESC, ai.tanggal DESC, aj.status ASC";
    $result = QueryDb($sql);
	
    $cnt = 0;
    $no = 0;
    while ($row = mysqli_fetch_array($result)) {
			
        $status = $row['status'];
        $idaudit = $row['idaudit'];
        $statusdata = "Data Lama";
		$bgcolor = "#FFFFFF";
        if ($row['status'] == 1) {
            $statusdata = "Data Perubahan";
			$bgcolor = "#FFFFB7";
		}             
		
        if ($cnt % 2 == 0) { ?>
        
    <tr>
        <td rowspan="4" align="center" bgcolor="#CCCC66"><strong><?=++$no ?></strong></td>
        <td colspan="6" align="left" bgcolor="#CCCC66"><font size="2"><em><strong>Perubahan dilakukan oleh <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?></strong></em></font></td>
    </tr>
    <tr>
        <td colspan="6" bgcolor="#E5E5E5">
            <table cellpadding="0" cellspacing="0" style="border-collapse:collapse" width="100%" >
            <tr>
                <td width="30%"><strong>No. Jurnal : </strong><?=$row['nokas'] ?>
                <td valign="top" width="10%"><strong>Alasan : </td>
                <td rowspan="2" valign="top"><strong><?=$row['alasan']?></strong></td>
            </tr>
            <tr>
                <td><strong>Transaksi : </strong><?=$row['transaksi'] ?></td>
            </tr>
            </table>
        </td>     
    </tr>
    <?php  } ?>
        
    <tr bgcolor="<?=$bgcolor?>">
        <td><?=$statusdata ?></td>
        <td align="center" ><?=$row['tanggal'] ?></td>
        <td align="left"><?=$row['keterangan'] ?></td>
        <td bgcolor="#E8FFE8">
            <table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" width="100%" bgcolor="#FFFFFF">
    <?php 	$nokas = $row['nokas'];
            $sql = "SELECT ajd.koderek, ra.nama, ajd.debet, ajd.kredit FROM $db_name_fina.auditjurnaldetail ajd, $db_name_fina.jurnal j, $db_name_fina.rekakun ra WHERE ajd.idjurnal = j.replid AND ajd.koderek = ra.kode AND j.nokas = '$nokas' AND ajd.status = '$status' AND idaudit='$idaudit' ORDER BY ajd.replid";
            $result2 = QueryDb($sql);            
            while ($row2 = mysqli_fetch_row($result2)) {  ?>   
            <tr>
                <td width="*"><?=$row2[0] . " " . $row2[1] ?></td>
                <td width="30%" align="right"><?=FormatRupiah($row2[2]) ?></td>
                <td width="30%" align="right"><?=FormatRupiah($row2[3]) ?></td>
            </tr>
    <?php 	} ?>            
            </table>
			
        </td>
        <td align="center"><?=$row['petugas']; ?></td>
    </tr>
    <?php
        $cnt++;
    }
    CloseDb();
    ?>
    </table>
    </td>
</tr>
</table>
</body>
</html>