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
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');
require_once('library/repairdatajttcalon.php');

$dept = $_REQUEST['dept'];
$departemen = $dept;
$idkategori = $_REQUEST['idkategori'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];
$petugas = $_REQUEST['petugas'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/tooltips.js" language="javascript"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function cetak() 
{
	var addr = "laprekap_cetak.php?dept=<?=$dept?>&idkategori=<?=$idkategori?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>"
	newWindow(addr, 'RekapCetak','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() 
{
	var addr = "laprekap_excel.php?dept=<?=$dept?>&idkategori=<?=$idkategori?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>"
	newWindow(addr, 'RekapExcel','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ShowDetail(dept, idtahunbuku, idkategori, idpenerimaan, tanggal1, tanggal2, petugas)
{
	var addr = "laprekap_detail.php?dept="+dept+"&idtahunbuku="+idtahunbuku+"&idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&petugas="+petugas;
	newWindow(addr, 'DetailLapRekap','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');	
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<table border="0" width="100%" align="center">
<tr height="300">
	<td align="left" valign="top" background="images/uang_trans.png" style="background-repeat:no-repeat">
    <table width="100%" border="0" height="100%"><tr><td align="center">

<table border="0" cellpadding="2" cellspacing="0" align="center">
<tr>
	<td align="left" valign="top">
    <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;
    <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
    </td>
</tr>
</table>        
<table cellpadding="5" border="1" style="border-width:1px; border-color:#999; border-collapse:collapse;" cellspacing="0" align="center">

<?php
OpenDb();

if ($dept == "ALL")
{
	$sql = "SELECT departemen FROM jbsakad.departemen ORDER BY urutan";
	$dres = QueryDb($sql);
	$k = 0;
	while ($drow = mysqli_fetch_row($dres))
		$darray[$k++] = $drow[0];
}
else
{
	$darray = [$dept];
}


if ($petugas == "ALL")
	$sql_idpetugas = "";
elseif ($petugas == "landlord")
	$sql_idpetugas = " AND j.idpetugas IS NULL ";
else
	$sql_idpetugas = " AND j.idpetugas = '$petugas' ";

$total = 0;
for($k = 0; $k < count($darray); $k++)
{ 
	$dept = $darray[$k];
	$cnt = 0;
	
	$sql = "SELECT COUNT(replid) FROM tahunbuku WHERE departemen='$dept' AND aktif=1";
	$ntb = FetchSingle($sql);
	
	if ($ntb == 0)
		continue;
	
	$sql = "SELECT replid FROM tahunbuku WHERE departemen='$dept' AND aktif=1";
	$idtahunbuku = FetchSingle($sql);
	
	$subtotal = 0;
	$rarray = [];
	$sql = "SELECT replid, nama FROM jbsfina.datapenerimaan WHERE departemen='$dept' AND aktif=1 AND idkategori='$idkategori'";
	$pres = QueryDb($sql);
	while($prow = mysqli_fetch_row($pres))
	{
		$idp = $prow[0];
		$pen = $prow[1];
		
		if ($idkategori == "JTT")
		{
			$sql = "SELECT SUM(p.jumlah), SUM(p.info1) 
			          FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
			         WHERE p.idbesarjtt = b.replid
					   AND b.idpenerimaan = dp.replid 
					   AND p.idjurnal = j.replid 
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND dp.replid = '$idp'
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "SKR")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan dp, jbsfina.jurnal j
			         WHERE p.idpenerimaan = dp.replid
					   AND p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku' 
					   AND dp.replid = '$idp'
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "CSWJB")
		{
			$sql = "SELECT SUM(p.jumlah), SUM(p.info1)
			          FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
			         WHERE p.idbesarjttcalon = b.replid
					   AND b.idpenerimaan = dp.replid
					   AND p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND dp.replid = '$idp'
					   AND dp.departemen = '$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "CSSKR")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaaniurancalon p, jbsfina.datapenerimaan dp, jbsfina.jurnal j
			         WHERE p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND p.idpenerimaan = dp.replid
					   AND dp.replid = '$idp' 
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		elseif ($idkategori == "LNN")
		{
			$sql = "SELECT SUM(p.jumlah), 0 
			          FROM jbsfina.penerimaanlain p, jbsfina.datapenerimaan dp , jbsfina.jurnal j
			         WHERE p.idjurnal = j.replid
					   AND j.idtahunbuku = '$idtahunbuku'
					   AND p.idpenerimaan = dp.replid
					   AND dp.replid = '$idp' 
					   AND dp.departemen='$dept'
					   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					   $sql_idpetugas";
		}
		
		$jres = QueryDb($sql);
		$jrow = mysqli_fetch_row($jres);
		$jumlah = 0;
		if (!is_null($jrow[0]))
			$jumlah = $jrow[0];
		
		$subtotal = $subtotal + $jumlah;
		$rarray[$cnt][0] = $pen;
		$rarray[$cnt][1] = $jumlah;
		$rarray[$cnt][2] = $idp;
			
		$cnt++;	
	}
	
	$total = $total + $subtotal;
	
	for($i = 0; $i < $cnt; $i++)
	{
		$pen = $rarray[$i][0];
		$jumlah = $rarray[$i][1];
		$idpen = $rarray[$i][2];
		
		if ($i == 0) 
		{ ?>
        <tr>
        	<td colspan="4" align="right" bgcolor="#660099">
            <font color="#FFFFFF"><strong><em><?=$dept?></em></strong></font>
            </td>
        </tr>
<?php      } ?>
        <tr>
        	<td width="25" align="center" valign="top" bgcolor="#CCCCCC"><?=$i + 1?></td>
            <td width="350" align="left" valign="top"><?=$pen?></td>
			<?php if ($jumlah == 0) { ?>
				<td width="120" align="right" valign="top"><?=FormatRupiah($jumlah)?></td>
			<?php } else { ?>
				<td width="120" align="right" valign="top">
					<a style='color: blue; font-weight: normal;'
					   href="JavaScript:ShowDetail('<?=$departemen?>', <?=$idtahunbuku?>, '<?=$idkategori?>', <?=$idpen?>, '<?=$tanggal1?>', '<?=$tanggal2?>', '<?=$petugas?>')">
					<?=FormatRupiah($jumlah)?>
					</a>
				</td>
			<?php } ?>
<?php 	if ($i == 0)
		{ ?>
        	<td width="120" rowspan="<?=$cnt?>" valign="middle" align="right" bgcolor="#FFECFF"><strong><?=FormatRupiah($subtotal)?></strong></td>
<?php 	} ?>        
        </tr>
<?php  } 
}
CloseDb();
?>
		<tr height="40">
        	<td colspan="3" align="right" valign="middle" bgcolor="#333333">
            <font color="#FFFFFF"><strong>T O T A L</strong></font>
            </td>
            <td valign="middle" align="right" bgcolor="#333333">
            <font color="#FFFFFF"><strong><?=FormatRupiah($total)?></strong></font>
            </td>
        </tr>
	</table>
</td></tr></table></td></tr></table>    
</body>
</html>