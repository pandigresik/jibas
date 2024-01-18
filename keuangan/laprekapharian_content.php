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
	var addr = "laprekapharian_cetak.php?dept=<?=$dept?>&idkategori=<?=$idkategori?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>";
	newWindow(addr, 'RekapHarianCetak','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() 
{
	var addr = "laprekapharian_excel.php?dept=<?=$dept?>&idkategori=<?=$idkategori?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&petugas=<?=$petugas?>";
	newWindow(addr, 'RekapHarianExcel','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ShowDetail(dept, idtahunbuku, idkategori, idpenerimaan, tanggal, petugas)
{
	var addr = "laprekapharian_detail.php?dept="+dept+"&idtahunbuku="+idtahunbuku+"&idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&tanggal="+tanggal+"&petugas="+petugas;
	newWindow(addr, 'DetailLapRekapHarian','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');	
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
	
	// Ambil tanggal-tanggal transaksi yang terjadi pada rentang terpilih
	if ($idkategori == "JTT")
	{
		$sql = "SELECT DISTINCT p.tanggal 
				  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
				 WHERE p.idbesarjtt = b.replid
				   AND b.idpenerimaan = dp.replid
				   AND j.replid = p.idjurnal
				   AND j.idtahunbuku = '$idtahunbuku'
			       AND dp.departemen = '$dept'
				   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
				   $sql_idpetugas
				 ORDER BY p.tanggal ASC";
	}
	elseif ($idkategori == "SKR")
	{
		$sql = "SELECT DISTINCT p.tanggal 
		          FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan dp, jbsfina.jurnal j
				 WHERE p.idjurnal = j.replid
				   AND j.idtahunbuku = '$idtahunbuku'
				   AND p.idpenerimaan = dp.replid 
				   AND dp.departemen='$dept'
				   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
				   $sql_idpetugas
				ORDER BY p.tanggal ASC";
	}
	elseif ($idkategori == "CSWJB")
	{
		$sql = "SELECT DISTINCT p.tanggal 
				    FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
				   WHERE p.idbesarjttcalon = b.replid
				     AND b.idpenerimaan = dp.replid
					 AND j.replid = p.idjurnal
					 AND j.idtahunbuku = '$idtahunbuku'
			         AND dp.departemen = '$dept'
					 AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
					 $sql_idpetugas
				ORDER BY p.tanggal ASC";
	}
	elseif ($idkategori == "CSSKR")
	{
		$sql = "SELECT DISTINCT p.tanggal 
		          FROM jbsfina.penerimaaniurancalon p, jbsfina.datapenerimaan dp, jbsfina.jurnal j 
				 WHERE p.idjurnal = j.replid
				   AND j.idtahunbuku = '$idtahunbuku'
				   AND p.idpenerimaan = dp.replid 
				   AND dp.departemen='$dept'
				   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
				   $sql_idpetugas
				 ORDER BY p.tanggal ASC";
	}
	elseif ($idkategori == "LNN")
	{
		$sql = "SELECT DISTINCT p.tanggal
		          FROM jbsfina.penerimaanlain p, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
				 WHERE p.idjurnal = j.replid
				   AND j.idtahunbuku = '$idtahunbuku'
				   AND p.idpenerimaan = dp.replid 
				   AND dp.departemen='$dept'
				   AND p.tanggal BETWEEN '$tanggal1' AND '$tanggal2'
				   $sql_idpetugas
				 ORDER BY p.tanggal ASC";
	}
	
	// tarray -> tanggal array
	// n -> conter tarray
	$tarray = [];
	$tres = QueryDb($sql);
	$n = 0;
	while ($trow = mysqli_fetch_row($tres))
	{
		$tarray[$n] = $trow[0];
		$n++;
	}
	
	
	if ($n > 0)
	{
		// ambil nama-nama penerimaan pada departemen terpilih
		// parray -> penerimaan array
		// m -> counter parray
		$parray = [];
		$sql = "SELECT replid, nama FROM jbsfina.datapenerimaan WHERE departemen='$dept' AND aktif=1 AND idkategori='$idkategori'";
		$pres = QueryDb($sql);
		$m = 0;
		while ($prow = mysqli_fetch_row($pres))
		{
			$parray[$m][0] = $prow[0];
			$parray[$m][1] = $prow[1];
			$m++;
		}
		
		// rarray -> result array
		$rarray = [];
		for($i = 0; $i < $m; $i++)
		{
			$idp = $parray[$i][0];
			$pen = $parray[$i][1];
			
			for($j = 0; $j < $n; $j++)
			{
				$tanggal = $tarray[$j];
				
				if ($idkategori == "JTT")
				{
					$sql = "SELECT SUM(p.jumlah), SUM(p.info1)
							  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
							 WHERE p.idbesarjtt = b.replid
							   AND b.idpenerimaan = dp.replid
							   AND j.replid = p.idjurnal
							   AND j.idtahunbuku = '$idtahunbuku'
							   AND dp.replid = '$idp'
							   AND dp.departemen='$dept'
							   AND p.tanggal = '$tanggal'
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
							   AND p.tanggal = '$tanggal'
							   $sql_idpetugas";
				}
				elseif ($idkategori == "CSWJB")
				{
					$sql = "SELECT SUM(p.jumlah), SUM(p.info1)
						  	  FROM jbsfina.penerimaanjttcalon p, jbsfina.besarjttcalon b, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
							 WHERE p.idbesarjttcalon = b.replid
							   AND b.idpenerimaan = dp.replid
							   AND j.replid = p.idjurnal
							   AND j.idtahunbuku = '$idtahunbuku'
							   AND dp.replid = '$idp'
							   AND dp.departemen='$dept'
							   AND p.tanggal = '$tanggal'
							   $sql_idpetugas";
				}
				elseif ($idkategori == "CSSKR")
				{
					$sql = "SELECT SUM(p.jumlah), 0
							  FROM jbsfina.penerimaaniurancalon p, jbsfina.datapenerimaan dp, jbsfina.jurnal j  
							 WHERE p.idpenerimaan = dp.replid
							   AND p.idjurnal = j.replid
							   AND j.idtahunbuku = '$idtahunbuku'
							   AND dp.replid = '$idp' 
							   AND dp.departemen='$dept'
							   AND p.tanggal = '$tanggal'
							   $sql_idpetugas";
				}
				elseif ($idkategori == "LNN")
				{
					$sql = "SELECT SUM(p.jumlah), 0
						      FROM jbsfina.penerimaanlain p, jbsfina.datapenerimaan dp, jbsfina.jurnal j   
							 WHERE p.idpenerimaan = dp.replid
							   AND p.idjurnal = j.replid
							   AND j.idtahunbuku = '$idtahunbuku'
							   AND dp.replid = '$idp' 
							   AND dp.departemen='$dept'
							   AND p.tanggal = '$tanggal'
							   $sql_idpetugas";
				}
				
				$jres = QueryDb($sql);
				$jrow = mysqli_fetch_row($jres);
				$jumlah = 0;
				if (!is_null($jrow[0]))
					$jumlah = $jrow[0];
				
				
				$rarray[$j][$i] = $jumlah;
			} // for j
		}  // for i
		
		?>
		
        <table cellpadding="5" border="1" style="border-width:1px; border-color:#999; border-collapse:collapse;"cellspacing="0" align="center">
        <tr>
        	<td colspan="<?=2 + $m + 1?>" align="right" valign="middle" bgcolor="#660099">
            <font color="#FFFFFF"><strong><em><?=$dept?></em></strong></font>
            </td>
        </tr>
        <tr>
        	<td bgcolor="#FFECFF" width="25" align="center" valign="middle"><strong>No</strong></td>
            <td bgcolor="#FFECFF" width="80" align="center" valign="middle"><strong>Tanggal</strong></td>
<?php 		for($i = 0; $i < $m; $i++) 
			{ 
				$pen = $parray[$i][1] ?>
				<td bgcolor="#FFECFF" width="140" align="center" valign="middle"><strong><?=$pen?></strong></td>
<?php 		} 	?>                
			<td bgcolor="#FFECFF" width="140" align="center" valign="middle"><strong>Sub Total</strong></td>
        </tr>
<?php 	$cnt = 0;
		for($i = 0; $i < $n; $i++)
		{
			$cnt++;
			$tanggal = RegularDateFormat($tarray[$i]);
			
			echo  "<tr>";
			echo  "<td align='center' valign='top'>$cnt</td>";
			echo  "<td align='center' valign='top'>$tanggal</td>";
			
			$subtotal = 0;
			for($j = 0; $j < $m; $j++)
			{
				$subtotal = $subtotal + $rarray[$i][$j];
				$jumlah = FormatRupiah($rarray[$i][$j]);
				
				if ($rarray[$i][$j] > 0)
				{
					$idpen = $parray[$j][0];
					$tgl = $tarray[$i];
					
					echo  "<td align='right' valign='top'>
						   <a href=\"JavaScript:ShowDetail('$departemen', $idtahunbuku, '$idkategori', $idpen, '$tgl', '$petugas')\" style='color: blue; font-weight: normal;'>$jumlah</a>
						   </td>";
				}
				else
				{
					echo  "<td align='right' valign='top'>$jumlah</td>";
				}
			}
			echo  "<td align='right' valign='top'>" . FormatRupiah($subtotal) . "</td>";
			echo  "</tr>";
		} 
		
		echo  "<tr height='40'>";
		echo  "<td colspan='2' align='right' valign='middle' bgcolor='#333333'><font color='#ffffff'><strong>T O T A L</strong></font></td>";
		$total = 0;
		for($i = 0; $i < $m; $i++)
		{
			$subtotal = 0;
			for($j = 0; $j < $n; $j++)
			{
				$subtotal = $subtotal + $rarray[$j][$i];
			}
			$total = $total + $subtotal;
			echo  "<td align='right' align='right' valign='middle' bgcolor='#333333'><font color='#ffffff'><strong>" . FormatRupiah($subtotal) . "</strong></font></td>";
		}
		echo  "<td align='right' valign='middle' bgcolor='#333333'><font color='#ffffff'><strong>" . FormatRupiah($total) . "</strong></font></td>";
		echo  "</tr>";
		
		echo  "</table>";
		echo  "<br><br>";	?>
		
<?php } // if date exists
} // while dept
CloseDb();
?>
</table>
</td></tr></table></td></tr></table>    
</body>
</html>