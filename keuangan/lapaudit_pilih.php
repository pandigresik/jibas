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

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];
	
$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
	
$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Audit Pilih</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function show_detail(lap) 
{
	if (lap == 'penerimaanjtt' || lap == 'penerimaanjttcalon') 
	{
		parent.content.location.href = "lapaudit_content_jtt.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&lap="+lap;	
	} 
	else if (lap == 'penerimaaniuran' || lap == 'penerimaaniurancalon') 
	{
		parent.content.location.href = "lapaudit_content_iuran.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&lap="+lap;
	} 
	else if (lap == 'penerimaanlain') 
	{
		parent.content.location.href = "lapaudit_content_lain.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	} 
	else if (lap == 'pengeluaran') 
	{
		parent.content.location.href = "lapaudit_content_pengeluaran.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	} 
	else if (lap == 'jurnalumum') 
	{
		parent.content.location.href = "lapaudit_content_jurnalumum.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	}
	else if (lap == 'tabungan') 
	{
		parent.content.location.href = "lapaudit_content_tabungan.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
	}
    else if (lap == 'tabunganp')
    {
        parent.content.location.href = "lapaudit_content_tabunganp.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>";
    }
    else if (lap == 'besarjtt' || lap == 'besarjttcalon')
	{
		parent.content.location.href = "lapaudit_content_besarjtt.php?idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&lap="+lap;
	} 
}
</script>
</head>
<body leftmargin="0" marginheight="0" marginwidth="0" background="">
<br />
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
<?php  OpenDb();
	
	 //// Maintenance script to update info1 in auditinfo as idjurnal for each source
	 $sql = "SELECT * FROM auditinfo WHERE info1 IS NULL";
	 $res = QueryDb($sql);
	 
	 if (mysqli_num_rows($res) > 0)
	 	  set_time_limit(1000);
		
	 while ($row = mysqli_fetch_array($res))
	 {
		 $id = $row['replid'];
		 $table = $row['sumber'];
		 $idsumber = $row['idsumber'];
		 
		 if ($table == "besarjtt")
		 	$sql = "SELECT info1 FROM besarjtt WHERE replid = '".$idsumber."'";
		 elseif ($table == "besarjttcalon")
		   $sql = "SELECT info1 FROM besarjttcalon WHERE replid = '".$idsumber."'";
		 elseif ($table == "penerimaanjtt")
		   $sql = "SELECT idjurnal FROM penerimaanjtt WHERE replid = '".$idsumber."'";
		 elseif ($table == "penerimaanjttcalon")
		   $sql = "SELECT idjurnal FROM penerimaanjttcalon WHERE replid = '".$idsumber."'";			
  		 elseif ($table == "penerimaaniuran")
		   $sql = "SELECT idjurnal FROM penerimaaniuran WHERE replid = '".$idsumber."'";
		 elseif ($table == "penerimaaniurancalon")
		   $sql = "SELECT idjurnal FROM penerimaaniurancalon WHERE replid = '".$idsumber."'";			
		 elseif ($table == "penerimaanlain")
		   $sql = "SELECT idjurnal FROM penerimaanlain WHERE replid = '".$idsumber."'";
		 elseif ($table == "pengeluaran")
		   $sql = "SELECT idjurnal FROM pengeluaran WHERE replid = '".$idsumber."'";
		 elseif ($table == "tabungan")
		   $sql = "SELECT idjurnal FROM tabungan WHERE replid = '".$idsumber."'";
         elseif ($table == "tabunganp")
           $sql = "SELECT idjurnal FROM tabunganp WHERE replid = '".$idsumber."'";
    	 elseif ($table == "jurnalumum")
		   $sql = "SELECT $idsumber";			
			
		 $res2 = QueryDb($sql);
		 if (mysqli_num_rows($res2) > 0)
		 {
			 $row2 = mysqli_fetch_row($res2);
			 $idjurnal = $row2[0];
			 
			 $sql = "UPDATE auditinfo SET info1='$idjurnal' WHERE replid='$id'";
			 QueryDb($sql);
		 }
		 else
		 {
			 $sql = "UPDATE auditinfo SET info1='na' WHERE replid='$id'";
			 QueryDb($sql);
		 }
	 }
	 
    $sql = "SELECT a.sumber, count(a.replid) 
	          FROM auditinfo a, jurnal j 
		     WHERE a.info1 = j.replid 
		       AND j.idtahunbuku = '$idtahunbuku' 
		       AND a.departemen = '$departemen' 
			   AND a.tanggal >= '$tanggal1 00:00:00' 
			   AND a.tanggal <= '$tanggal2 23:59:59' 
		  	 GROUP BY a.sumber 
			 ORDER BY a.sumber";

    $result = QueryDb($sql);

    if (mysqli_num_rows($result) > 0) 
	{
	    ?>

    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="95%" align="center" bordercolor="#000000">
    <tr height="30" align="center">
        <td class="header" width="7%">No</td>
        <td class="header" width="73%">Perubahan</td>
        <td class="header" width="10%">Jumlah</td>
    </tr>
<?php  $cnt = 0;
    while($row = mysqli_fetch_row($result)) 
	 { 
        switch($row[0]) 
		{	
            case 'jurnalumum':
                $jurnal = "Jurnal Umum"; break;
            case 'penerimaanjtt':
                $jurnal = "Penerimaan Iuran Wajib Siswa"; break;
            case 'penerimaaniuran':
                $jurnal = "Penerimaan Iuran Sukarela Siswa"; break;
            case 'penerimaanlain':
                $jurnal = "Penerimaan Lain-Lain"; break;
            case 'pengeluaran':
                $jurnal = "Pengeluaran"; break;
			case 'penerimaanjttcalon':
                $jurnal = "Penerimaan Iuran Wajib Calon Siswa"; break;
			case 'penerimaaniurancalon':
                $jurnal = "Penerimaan Iuran Sukarela Calon Siswa"; break;
			case 'besarjtt':
				$jurnal = "Pendataan Besar Iuran Wajib Siswa"; break;
			case 'besarjttcalon':
				$jurnal = "Pendataan Besar Iuran Wajib Calon Siswa"; break;
			case 'tabungan':
				$jurnal = "Tabungan Siswa"; break;
            case 'tabunganp':
                $jurnal = "Tabungan Pegawai"; break;
        } ?>
    <tr height="25" onclick="show_detail('<?=$row[0]?>')">
        <td align="center"><?=++$cnt ?></td>
        <td align="left"><strong><u><?=$jurnal ?></u></strong></td>
        <td align="center"><font size="2"><strong><?=$row[1] ?></strong></font></td>
    </tr>
<?php  }  ?>
    </table>
<script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
<?php } else { ?>	

    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="300">    
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data pada tanggal <?=LongDateFormat($tanggal1)." s/d ".LongDateFormat($tanggal2) ?>.
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