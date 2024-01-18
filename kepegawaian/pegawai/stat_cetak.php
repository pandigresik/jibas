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
require_once("../include/config.php");
require_once("../include/common.php");
require_once("../include/db_functions_2.php");
require_once("../include/chartfactory.php");
require_once("../include/as-diagrams.php");
require_once('../include/theme.php');

$stat = $_REQUEST['stat'];
switch ($stat)
{
	case 1:
		$judul="Satuan Kerja";
		break;
	case 2:
		$judul="Pendidikan Sekolah";
		break;
	case 3:
		$judul="Golongan";
		break;
	case 4:
		$judul="Usia";
		break;
	case 5:
		$judul="Diklat";
		break;
	case 6:
		$judul="Jenis Kelamin";
		break;
	case 7:
		$judul="Status Perkawinan";
		break;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top"><?php include("../include/headercetak.php") ?>
<center>
    <font size="4"><strong>Statistik Berdasarkan <?=$judul?></strong></font><br />
   </center><br /><br />
   
<table width="95%" border="0" cellspacing="5">
  <tr>
    <td><div id="grafikbar" align="center">
<?php if ($stat == 5)
	{
		$sql = "SELECT sk.satker, SUM(IF(NOT pl.idpegdiklat IS NULL, 1, 0)) AS Sudah, SUM(IF(pl.idpegdiklat IS NULL, 1, 0)) AS Belum
				FROM pegawai p, peglastdata pl, pegjab pj, jabatan j, satker sk
				WHERE p.aktif = 1 AND pl.nip = p.nip AND pl.idpegjab = pj.replid
				AND pj.idjabatan = j.replid AND j.satker = sk.satker
                GROUP BY sk.nama ORDER BY sk.nama";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result))
		{
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Sudah", "Belum"];
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pegawai Berdasarkan Diklat</font>"; // title for the diagram
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 0;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);
	
    }
	elseif ($stat == 6)
	{
    	$sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND NOT j.satker IS NULL
				  GROUP BY j.satker";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result))
		{
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Pria", "Wanita"];
		
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pegawai Berdasarkan Jenis Kelamin</font>"; // title for the diagram
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 1;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);

    }
	elseif ($stat == 7)
	{
		$sql = "SELECT j.satker, SUM(IF(p.nikah = 'menikah', 1, 0)) AS Nikah, SUM(IF(p.nikah = 'belum', 1, 0)) AS Belum
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
				  AND NOT j.satker IS NULL 
				  GROUP BY j.satker";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result))
		{
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Nikah", "Belum"];
		
		$title = "<font face='Arial' size='-1' color='black'>Jumlah Pegawai Berdasarkan Status Pernikahan</font>"; 
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 1;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);
	
    }
	else
	{
    ?>
    <img src="<?= "statimage.php?type=bar&stat=$stat" ?>" />
    <?php
    }
	?>
    </div></td>
  </tr>
  <tr>
    <td><div id="grafikpie" align="center">
    <img src="<?= "statimage.php?type=pie&stat=$stat" ?>" />
    </div></td>
  </tr>
  <tr>
    <td><div id="table" align="center">
    <?php if ($stat==5){ ?>
	<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
		<tr height="25">
			<td class="header" align="center" width="5%">No</td>
			<td class="header" align="center" width="60%">Eselon</td>
			<td class="header" align="center" width="15%">Sudah</td>
			<td class="header" align="center" width="15%">Belum</td>
		</tr>
		<?php
		OpenDb();
		$sql = "SELECT j.eselon, SUM(IF(NOT pl.idpegdiklat IS NULL, 1, 0)) AS Sudah, SUM(IF(pl.idpegdiklat IS NULL, 1, 0)) AS Belum
					FROM   pegawai p, peglastdata pl, pegjab pj, jabatan j, jenisjabatan jj
					WHERE p.aktif = 1 AND pl.nip = p.nip AND pl.idpegjab = pj.replid 
					AND pj.idjabatan = j.replid AND pj.jenis = jj.jenis AND jj.jabatan = 'S' GROUP BY j.eselon ORDER BY j.eselon";	
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result)) {
		?>
		<tr height="20">
			<td align="center" valign="top"><?=++$cnt?></td>
			<td align="center" valign="top"><?=$row[0]?></td>
			<td align="center" valign="top">
				
				<?=$row[1]?>
				
			</td>
			<td align="center" valign="top">
				
				<?=$row[2]?>
				
			</td>
		</tr>
		<?php
		}
		CloseDb();
		?>
		</table>
	<?php } elseif ($stat==6){
	?>
    <table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
        <tr height="25">
            <td class="header" align="center" width="5%">No</td>
            <td class="header" align="center" width="60%">Satuan Kerja</td>
            <td class="header" align="center" width="15%">L</td>
            <td class="header" align="center" width="15%">P</td>
        </tr>
        <?php
        OpenDb();
        $sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
                  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
                  WHERE p.aktif = 1  AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid  AND NOT j.satker IS NULL
                  GROUP BY j.satker";	
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_row($result)) {
        ?>
        <tr height="20">
            <td align="center" valign="top"><?=++$cnt?></td>
            <td align="center" valign="top"><?=$row[0]?></td>
            <td align="center" valign="top"><?=$row[1]?></td>
            <td align="center" valign="top"><?=$row[2]?></td>
        </tr>
        <?php
        }
        CloseDb();
        ?>
    </table>
    <?php
	} elseif ($stat==7){
	?>
    <table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
        <tr height="25">
            <td class="header" align="center" width="5%">No</td>
            <td class="header" align="center" width="60%">Satuan Kerja</td>
            <td class="header" align="center" width="15%">Nikah</td>
            <td class="header" align="center" width="15%">Belum</td>
        </tr>
        <?php
        OpenDb();
        $sql = "SELECT j.satker, SUM(IF(p.nikah = 'menikah', 1, 0)) AS Nikah, SUM(IF(p.nikah = 'belum', 1, 0)) AS Belum
                  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
                  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid  AND NOT j.satker IS NULL
                  GROUP BY j.satker";	
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_row($result)) {
        ?>
        <tr height="20">
            <td align="center" valign="top"><?=++$cnt?></td>
            <td align="center" valign="top"><?=$row[0]?></td>
            <td align="center" valign="top">
                <a href="JavaScript:ShowDetail('<?=$row[0]?>','Nikah')">
                <?=$row[1]?>
                </a>
            </td>
            <td align="center" valign="top">
                <a href="JavaScript:ShowDetail('<?=$row[0]?>','Belum Nikah')">
                <?=$row[2]?>
                </a>
            </td>
        </tr>
    <?php
    }
    CloseDb();
    ?>
    </table>
    <?php
	} else {
	
		if ($stat == 1)
		{
			$column  = "Satuan Kerja";
			$column2 = "Jumlah";
			$sql = "SELECT j.satker, count(pj.replid) FROM 
					pegjab pj, peglastdata pl, pegawai p, jabatan j 
					WHERE pl.idpegjab = pj.replid AND pj.idjabatan = j.replid AND pj.nip = p.nip 
					  AND p.aktif=1 AND NOT j.satker IS NULL GROUP BY satker;";	
		}
		elseif ($stat == 2)
		{
			$column  = "Pendidikan";
			$column2 = "Jumlah";
			$sql = "SELECT ps.tingkat, COUNT(p.nip) FROM
					pegawai p, peglastdata pl, pegsekolah ps, jbsumum.tingkatpendidikan pk
					WHERE p.nip = pl.nip AND pl.idpegsekolah = ps.replid AND ps.tingkat = pk.pendidikan AND p.aktif = 1 
					GROUP BY ps.tingkat";	
		}
		elseif ($stat == 3)
		{
			$column  = "Golongan";
			$column2 = "Jumlah";
			$sql = "SELECT pg.golongan, COUNT(p.nip) FROM pegawai p, peglastdata pl, peggol pg, golongan g
					WHERE p.nip = pl.nip AND pl.idpeggol = pg.replid AND pg.golongan = g.golongan AND p.aktif = 1 
					GROUP BY pg.golongan ORDER BY g.urutan";	
		}
		elseif ($stat == 4)
		{
			$column  = "Usia";
			$column2 = "Jumlah";
			
			$sql = "SELECT G, COUNT(nip) FROM (
					SELECT nip, IF(usia < 24, '<24',
					IF(usia >= 24 AND usia <= 29, '24-29',
					IF(usia >= 30 AND usia <= 34, '30-34',
					IF(usia >= 35 AND usia <= 39, '35-39',
					IF(usia >= 40 AND usia <= 44, '40-44',
					IF(usia >= 45 AND usia <= 49, '45-49',
					IF(usia >= 50 AND usia <= 55, '50-55', '>56'))))))) AS G FROM
					(SELECT nip, FLOOR(DATEDIFF(NOW(), tgllahir) / 365) AS usia FROM pegawai WHERE aktif = 1) AS X) AS X GROUP BY G";	
		}
		
		?>
		<table id="table" class="tab" border="1" cellpadding="2" cellspacing="0" width="100%">
		<tr height="25">
			<td class="header" align="center" width="5%">No</td>
			<td class="header" align="center" width="60%"><?=$column?></td>
			<td class="header" align="center" width="25%"><?=$column2?></td>
		</tr>
		<?php
		OpenDb();
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result))
		{
		?>
		<tr height="20">
			<td align="center" valign="top"><?=++$cnt?></td>
			<td align="center" valign="top"><?=$row[0]?></td>
			<td align="center" valign="top"><?=$row[1]?></td>
		</tr>
		<?php
		}
		CloseDb();
		?>
		</table>
	<?php } ?>
    </div></td>
  </tr>
</table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>