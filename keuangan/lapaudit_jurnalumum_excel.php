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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/getheader.php'); 

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Laporan_Audit_Jurnal_Umum.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];

$tgl1 = explode(' ',(string) $tanggal1);
$tgl2 = explode(' ',(string) $tanggal2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Laporan Audit Perubahan Data Jurnal Umum]</title>
</head>

<body>
<center><font size="4"><strong><font face="Verdana">LAPORAN AUDIT PERUBAHAN DATA JURNAL UMUM</font></strong></font><font face="Verdana"><br /> 
  </font>
</center>
<br /><br />

<table border="0">
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Departemen </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=$departemen ?>
    </strong></font></td>
</tr>
<tr>
	<td width="90"><font size="2" face="Arial"><strong>Tanggal </strong></font></td>
    <td><font size="2" face="Arial"><strong>: 
      <?=LongDateFormat($tgl1[0]) . " s/d 	" . LongDateFormat($tgl2[0]) ?>
    </strong></font></td>
</tr>
</table>
<br />
<table class="tab" id="table" border="1" width="100%" align="left" cellpadding="5" cellspacing="0" bordercolor="#000000">
<tr height="30" align="center">
	<td class="header" width="4%"><strong><font size="2" face="Arial">No</font></strong></td>
    <td class="header" width="10%"><strong><font size="2" face="Arial">Status Data</font></strong></td>
    <td class="header" width="10%"><strong><font size="2" face="Arial">Tanggal</font></strong></td>
    <td class="header" width="15%"><strong><font size="2" face="Arial">Keterangan</font></strong></td>
    <td class="header" width="*"><strong><font size="2" face="Arial">Detail Jurnal</font></strong></td>
    <td class="header" width="15%"><strong><font size="2" face="Arial">Petugas</font></strong></td>
</tr>
<?php
OpenDb();
$sql = "SELECT DISTINCT ai.petugas AS petugasubah, j.transaksi, date_format(ai.tanggal, '%d-%b-%Y %H:%i:%s') as tanggalubah, aj.replid AS id, aj.idaudit, aj.status, aj.nokas, date_format(aj.tanggal, '%d-%b-%Y') AS tanggal, aj.petugas, aj.keterangan, aj.petugas, ai.alasan FROM auditjurnal aj, auditinfo ai, jurnal j WHERE aj.idaudit = ai.replid AND ai.idsumber = j.replid AND ai.departemen = '$departemen' AND ai.sumber='jurnalumum' AND ai.tanggal BETWEEN '$tanggal1 00:00:00' AND '$tanggal2 23:59:59' ORDER BY aj.idaudit DESC, ai.tanggal DESC, aj.status ASC";
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
		$bgcolor = "#FFFFFF";
	}
		
	if ($cnt % 2 == 0) { ?>
	<tr>
		<td rowspan="4" align="center"><font size="2" face="Arial"><strong>
	    <?=++$no ?>
		</strong></font></td>
        <td colspan="6" align="left"><font size="2" face="Arial"><em><strong>Perubahan dilakukan oleh
        <?=$row['petugasubah'] . " tanggal " . $row['tanggalubah'] ?>
        </strong></em></font></td>
	</tr>
    <tr>
    	<td colspan="6">
        	<table cellpadding="0" cellspacing="0" style="border-collapse:collapse" width="100%" >
            <tr>
                <td width="20%"><font size="2" face="Arial"><strong>No. Jurnal : </strong>
                <?=$row['nokas'] ?>
                </font>
                <td valign="top" width="7%"><font size="2" face="Arial"><strong>Alasan : </font></td>
                <td rowspan="2" valign="top"><font size="2" face="Arial"><?=$row['alasan']?>
                </font></td>
            </tr>
            <tr>
                <td><font size="2" face="Arial"><strong>Transaksi : </strong>
                <?=$row['transaksi'] ?>
                </font></td>
            </tr>
            </table>      	</td>
    </tr>
<?php  } ?>
	<tr>
		<td><font size="2" face="Arial">
	    <?=$statusdata ?>
		</font></td>
	    <td align="center"><font size="2" face="Arial">
        <?=$row['tanggal'] ?>
	    </font></td>
	    <td align="left"><font size="2" face="Arial">
        <?=$row['keterangan'] ?>
	    </font></td>
	    <td bgcolor="#E8FFE8">
            <table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" width="100%" bgcolor="#FFFFFF">
    <?php 	$nokas = $row['nokas'];
            $sql = "SELECT ajd.koderek, ra.nama, ajd.debet, ajd.kredit FROM auditjurnaldetail ajd, jurnal j, rekakun ra WHERE ajd.idjurnal = j.replid AND ajd.koderek = ra.kode AND j.nokas = '$nokas' AND ajd.status = '$status' AND idaudit='$idaudit' ORDER BY ajd.replid";
			$result2 = QueryDb($sql);            
			while ($row2 = mysqli_fetch_row($result2)) {  ?>
            <tr>
            	<td width="*"><font size="2" face="Arial">
           	    <?=$row2[0] . " " . $row2[1] ?>
            	</font></td>
                <td width="30%" align="right"><font size="2" face="Arial">
                <?=$row2[2] ?>
                </font></td>
                <td width="30%" align="right"><font size="2" face="Arial">
                <?=$row2[3] ?>
                </font></td>
            </tr>
	<?php 	} ?>            
            </table>        </td>
	    <td align="center"><font size="2" face="Arial">
        <?=$row['petugas']; ?>
	    </font></td>
	</tr>
<?php
	$cnt++;
}
CloseDb();
?>
</table>
</body>
</html>
<script language="javascript">window.print();</script>