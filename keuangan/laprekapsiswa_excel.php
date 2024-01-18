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
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');
require_once('library/repairdatajttcalon.php');

/**/
header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Rekapitulasi_Tunggakan_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];
	
if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Rekapitulasi Tunggakan Siswa</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function refresh() 
{	
	document.location.href = "laprekapsiswa_content.php?idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtahunbuku=<?=$idtahunbuku ?>&idtingkat=<?=$idtingkat?>&departemen=<?=$departemen?>";	
}

function excel() 
{
	var addr = "laprekapsiswa_excel.php?idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idtahunbuku=<?=$idtahunbuku ?>&idtingkat=<?=$idtingkat?>&departemen=<?=$departemen?>";
	newWindow(addr, 'CetakLapRekapTunggakSiswa','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body topmargin="0" leftmargin="0">
<?php
OpenDb();

// Ambil penerimaan di departemen terpilih
$arrpen = [];
$sql = "SELECT replid, nama FROM datapenerimaan WHERE departemen='$departemen' AND idkategori='JTT'";
$res = QueryDb($sql);
$i = 0;
while($row = mysqli_fetch_row($res))
{
	$arrpen[$i][0] = $row[0];
	$arrpen[$i][1] = $row[1];
	$i++;
}
$n_arrpen = $i;

// Ambil data siswa
if ($idtingkat == -1) 
{		
	// semua tingkat & kelas
	$sqlsiswa = "SELECT DISTINCT s.nis, s.nama, s.pinsiswa, t.tingkat, k.kelas, s.alamatsiswa, s.kodepossiswa, s.namaayah, s.namaibu, s.telponortu, s.hportu
			 	   FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsfina.besarjtt b
				  WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis = b.nis AND b.besar <> 0 AND b.lunas = 0 AND b.info2 = '$idtahunbuku' AND 
				        t.departemen = '$departemen' AND s.idangkatan = '$idangkatan'
   		       ORDER BY t.urutan, k.kelas, s.nama";
} 
else 
{ 
	if ($idkelas == -1) 
	{			
		// semua kelas di tingkat terpilih
		$sqlsiswa = "SELECT DISTINCT s.nis, s.nama, s.pinsiswa, t.tingkat, k.kelas, s.alamatsiswa, s.kodepossiswa, s.namaayah, s.namaibu, s.telponortu, s.hportu
				 	   FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsfina.besarjtt b
				 	  WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis = b.nis AND b.besar <> 0 AND b.lunas = 0 AND b.info2 = '$idtahunbuku' AND 
					        t.replid = '$idtingkat' AND t.departemen = '$departemen' AND s.idangkatan = '$idangkatan'
			  	   ORDER BY t.urutan, k.kelas, s.nama";
	} 
	else 
	{
		// tingkat & kelas terpilih
		$sqlsiswa = "SELECT DISTINCT s.nis, s.nama, s.pinsiswa, t.tingkat, k.kelas, s.alamatsiswa, s.kodepossiswa, s.namaayah, s.namaibu, s.telponortu, s.hportu
				 	   FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsfina.besarjtt b
				 	  WHERE s.idkelas = k.replid AND k.idtingkat = t.replid AND s.nis = b.nis AND b.besar <> 0 AND b.lunas = 0 AND b.info2 = '$idtahunbuku' AND 
					        k.replid = '$idkelas' AND t.replid = '$idtingkat' AND t.departemen = '$departemen' AND s.idangkatan = '$idangkatan'
	  			   ORDER BY t.urutan, k.kelas, s.nama";
	}
}

$width = 1180 + $n_arrpen * 600;
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
    
    <!-- TABLE CONTENT //-->
	<table class="tab" width="<?=$width?>" id="table" border="1" cellpadding="5" style="border-collapse:collapse" cellspacing="0" width="<?=$table_width?>" align="left" bordercolor="#000000">
    <tr align="center" class="header">
        <td width="30" rowspan="2">No</td>
        <td width="80" rowspan="2">NIS</td>
        <td width="140" rowspan="2">Nama</td>
        <td width="60" rowspan="2">PIN</td>
        <td width="75" rowspan="2">Tingkat</td>
        <td width="75" rowspan="2">Kelas</td>
        <td width="140" rowspan="2">Ayah</td>
        <td width="140" rowspan="2">Ibu</td>
        <td width="200" rowspan="2">Alamat</td>
        <td width="80" rowspan="2">Kode Pos</td>
        <td width="80" rowspan="2">Telpon Ortu</td>
        <td width="80" rowspan="2">HP Ortu</td>
        <?php for ($i = 0; $i < $n_arrpen; $i++) { ?>
        <td width="800" colspan="8"><?=$arrpen[$i][1]?></td>
        <?php } ?> 
	</tr>
    <tr align="center" class="header">
 	    <?php for ($i = 0; $i < $n_arrpen; $i++) { ?>
        <td width="100">Cicilan</td>
        <td width="100">Total</td>
        <td width="100">Pembayaran</td>
		<td width="100">Diskon</td>
        <td width="100">Sisa</td>
        <td width="100">Tgl.Akhir</td>
        <td width="100">Bay.Akhir</td>
        <td width="100">Ket.Akhir</td>
        <?php } ?> 
    </tr>
<?php $res = QueryDb($sqlsiswa);
	$n = 0;

	$arrtotal = [];
	for($i = 0; $i < $n_arrpen; $i++)
		for($j = 0; $j < 8; $j++)
			if ($j > 0 && $j < 5)
				$arrtotal[$i * 8 + $j] = 0;
			else
				$arrtotal[$i * 8 + $j] = "";

	while ($row = mysqli_fetch_array($res))
	{ 
		$n++; 
		$nis = $row['nis'];
		
		$color = "#FFF";
		$color2 = "#FFFFD5";
		if ($n % 2 == 0)
		{
			$color = "#EEE";
			$color2 = "#FFFFB3";
		}
		?>
	<tr>
    	<td style="background-color:<?=$color?>" align="center"><?=$n?></td>
        <td style="background-color:<?=$color?>" align="center"><?=$row['nis']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['nama']?></td>
        <td style="background-color:<?=$color?>" align="center"><?=$row['pinsiswa']?></td>
        <td style="background-color:<?=$color?>" align="center"><?=$row['tingkat']?></td>
        <td style="background-color:<?=$color?>" align="center"><?=$row['kelas']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['namaayah']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['namaibu']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['alamatsiswa']?></td>
        <td style="background-color:<?=$color?>" align="center"><?=$row['kodepossiswa']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['telponortu']?></td>
        <td style="background-color:<?=$color?>" align="left"><?=$row['hportu']?></td>
        <?php for ($i = 0; $i < $n_arrpen; $i++) 
		{ 
			$idpenerimaan = $arrpen[$i][0];
			$sql = "SELECT b.nis, b.besar, SUM(p.jumlah) AS jumlah, b.cicilan, SUM(p.info1) AS diskon
			          FROM besarjtt b, penerimaanjtt p
					 WHERE b.replid = p.idbesarjtt AND b.idpenerimaan = '$idpenerimaan' AND b.nis = '$nis' AND b.info2 = '$idtahunbuku'
				  GROUP BY b.nis";
			$res2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($res2);
			$besar = $row2[1];
			$jumlah = $row2[2] + $row2[4];
			$bcicilan = $row2[3];
			$diskon = $row2[4];
			$sisa = $besar - $jumlah;
			if (0 == mysqli_num_rows($res2))
			{
				$sql = "SELECT b.besar, b.cicilan
					 	  FROM besarjtt b
						 WHERE b.idpenerimaan = '$idpenerimaan' AND b.nis = '$nis' AND b.info2 = '".$idtahunbuku."'";
				$res2 = QueryDb($sql);
				if (0 != mysqli_num_rows($res2))
				{
					$row2 = mysqli_fetch_row($res2);
					$besar = $row2[0];
					$jumlah = 0;
					$bcicilan = $row2[1];
					$diskon = 0;
					$sisa = $besar;		 
				}
			}
			
			$sql = "SELECT DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS tanggal, p.jumlah, p.keterangan
				      FROM besarjtt b, penerimaanjtt p 
					 WHERE b.replid = p.idbesarjtt AND b.idpenerimaan = '$idpenerimaan' AND b.nis = '$nis' AND b.info2 = '$idtahunbuku'
				  ORDER BY tanggal DESC, p.replid DESC
				     LIMIT 1";
			$res2 = QueryDb($sql);
			$row2 = mysqli_fetch_row($res2);
			$tglakhir = $row2[0];
			$jumakhir = $row2[1];
			$ketakhir = $row2[2];
			
			if ($sisa != 0)
			{
				$idx = $i * 8;
				$arrtotal[$idx] += $bcicilan;
				$arrtotal[$idx + 1] += $besar;
				$arrtotal[$idx + 2] += $jumlah;
				$arrtotal[$idx + 3] += $diskon;
				$arrtotal[$idx + 4] += $sisa;
			}
						
			if ($sisa == 0)
			{
				echo  "<td style='background-color:$color'>&nbsp;</td>
					   <td style='background-color:$color'>&nbsp;</td>
					   <td style='background-color:$color'>&nbsp;</td>
					   <td style='background-color:$color'>&nbsp;</td>
					   <td style='background-color:$color'>&nbsp;</td>
					   <td style='background-color:$color2'>&nbsp;</td>
					   <td style='background-color:$color2'>&nbsp;</td>
					   <td style='background-color:$color2'>&nbsp;</td>";
			}
			else
			{	?>
	            <td align="right" style="background-color:<?=$color?>"><?=$bcicilan?></td>
                <td align="right" style="background-color:<?=$color?>"><?=$besar?></td>
                <td align="right" style="background-color:<?=$color?>"><?=$jumlah?></td>
				<td align="right" style="background-color:<?=$color?>"><?=$diskon?></td>
                <td align="right" style="background-color:<?=$color?>"><?=$sisa?></td>
                <td align="center" style="background-color:<?=$color2?>"><?=$tglakhir?></td>
                <td align="right" style="background-color:<?=$color2?>"><?=$jumakhir?></td>
                <td align="left" style="background-color:<?=$color2?>"><?=$ketakhir?></td>
    <?php 		}
		} ?> 
    </tr>        
<?php } ?>
	<tr height="25">
    	<td colspan="12" style="background-color:#3CF" align="right"><strong>T O T A L</strong></td>
<?php 	for($i = 0; $i < $n_arrpen; $i++) 
			for($j = 0; $j < 8; $j++)
				if ($j < 5)
					echo  "<td align='right' style='background-color:#3CF'>" . $arrtotal[$i * 8 + $j] . "</td>";	
				else
					echo  "<td style='background-color:#3CF'>&nbsp</td>"; ?>
    </tr>
    
    </table>
    
    </td>
</tr>
</table>
<?php

?>

</form>
</body>
</html>