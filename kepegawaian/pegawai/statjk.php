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
require_once("../include/db_functions_2.php");
require_once("../include/common.php");
require_once("../include/chartfactory.php");
require_once("../include/as-diagrams.php");
require_once("../include/sessioninfo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
<link rel="stylesheet" href="../style/style.css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function Refresh() {
	parent.parent.location.href = "statcontent.php?stat=6";
}
function Cetak() {
	newWindow('stat_cetak.php?stat=6', 'CetakStatistikKelamin','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script>
</head>

<body>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
	<a href="JavaScript:Cetak()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>&nbsp;
    <a href="JavaScript:Refresh()"><img src="../images/ico/refresh.png" border="0" />&nbsp;Refresh</a>
</td></tr>
</table>
<br />
<table width="100%" border="0">
<tr><td>

	<div id="grafik" align="center">
	<table width="100%" border="0" align="center">
    <tr><td>
    	<div align="center">
<?php
		$sql = "SELECT j.satker, SUM(IF(p.kelamin = 'L', 1, 0)) AS Pria, SUM(IF(p.kelamin = 'P', 1, 0)) AS Wanita
				  FROM pegawai p, peglastdata pl, pegjab pj, jabatan j
				  WHERE p.aktif = 1 AND p.nip = pl.nip AND pl.idpegjab = pj.replid AND pj.idjabatan = j.replid
				  GROUP BY j.satker HAVING NOT j.satker IS NULL";
		
		OpenDb();
		$result = QueryDb($sql);
		while($row = mysqli_fetch_row($result)) {
			$data[] = [$row[1], $row[2]];
			$legend_x[] = $row[0];
		}
		CloseDb();
		
		$legend_y = ["Pria", "Wanita"];
		
		$title = "<font face='Arial' size='-1' color='black'>Banyaknya Pegawai Berdasarkan Jenis Kelamin</font>"; // title for the diagram
		
		$graph = new CAsBarDiagram;
		$graph->bwidth = 10; // set one bar width, pixels
		$graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
		// $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
		$graph->precision = 0;  // decimal precision
		// call drawing function
		$graph->DiagramBar($legend_x, $legend_y, $data, $title);
	
?>        
        </div>
    </td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>
    	<div align="center">
		<img src="<?= "statimage.php?type=pie&stat=6" ?>" />        
        </div>
    </td></tr>
	</table>
	</div>
    
</td></tr>
</table>

</body>
</html>