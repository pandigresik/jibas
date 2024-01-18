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
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../library/as-diagrams.php');

$nis_awal = $_SESSION["infosiswa.nis"];

$departemen = 0;
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

OpenDb();

// Dapatkan nis, jenjang dan replid sekarang dan terdahuku
$check_nis = $nis_awal;
do
{
	$sql = "SELECT replid, departemen, IF(nislama IS NULL, '', nislama) AS nislama
			  FROM riwayatdeptsiswa
			 WHERE nis='$check_nis'";
	$result = QueryDb($sql);
	$nrow = mysqli_num_rows($result);
	if ($nrow > 0)
	{
		$row = mysqli_fetch_array($result);
		$dep[] = [$row['departemen'], $check_nis];
		
		if (strlen((string) $row['nislama']) > 0)
			$check_nis = $row['nislama'];
		else
			$nrow = 0;
	}
}
while($nrow > 0);

$nis = $dep[$departemen][1];

$sql_ajaran  = 	"SELECT DISTINCT(t.replid), t.tahunajaran
				   FROM riwayatkelassiswa r, kelas k, tahunajaran t
				  WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtahunajaran = t.replid
				  ORDER BY t.replid DESC";
$result_ajaran = QueryDb($sql_ajaran);
$k = 0;
while ($row_ajaran = @mysqli_fetch_array($result_ajaran))
{
	$ajaran[$k] = [$row_ajaran['replid'], $row_ajaran['tahunajaran']];
	$k++;
}

$sql_kls = 	"SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, k.idtahunajaran
			   FROM riwayatkelassiswa r, kelas k, tingkat t
			  WHERE r.nis = '$nis' AND r.idkelas = k.replid AND k.idtingkat = t.replid ";
$result_kls = QueryDb($sql_kls);
$j = 0;
while ($row_kls = @mysqli_fetch_array($result_kls))
{
	$kls[$j] = [$row_kls['idkelas'], $row_kls['kelas'], $row_kls['tingkat'], $row_kls['idtahunajaran']];
	$j++;
}

$tahunajaran = 0;
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
	
$kelas = 0;
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<div align="left" style="margin-left:1px">
<br>
<form name="panelph" id="panelph" method="post">
<input type="hidden" name="nis" id="nis" value="<?=$nis?>">
<input type="hidden" name="nis_awal" id="nis_awal" value="<?=$nis_awal?>">
<input type="hidden" name="current_nis" id="current_nis" value="<?=$nis?>">
<table width="100%" cellspacing="0" cellpadding="0" align="left">    
<tr >
	<td width="0">
    <!-- CONTENT GOES HERE //--->	
    <table border="0" cellpadding="2"cellspacing="2" width="100%" style="color:#000000">
    <tr>
        <td width="*"><strong class="news_content1">Th. Ajaran</strong>
        <select name="departemen" class="cmbfrm" id="departemen" style="width:80px" onChange="ChangePresensiHarianOption2('departemen')">
<?php 		for ($i=0; $i<sizeof($dep); $i++) { ?>        	
            <option value="<?=$i ?>" <?=IntIsSelected($i, $departemen) ?> > <?=$dep[$i][0] ?> </option>
<?php 		} ?>
		</select>
        <select name="tahunajaran" class="cmbfrm" id="tahunajaran" style="width:125px" onChange="ChangePresensiHarianOption2('tahunajaran')">
<?php 		for($k=0; $k<sizeof($ajaran); $k++)
		{
			if ($tahunajaran == 0)
				$tahunajaran = $ajaran[0][0];
			?>
			<option value="<?=$ajaran[$k][0] ?>" <?=IntIsSelected($ajaran[$k][0], $tahunajaran) ?> > <?=$ajaran[$k][1]?> </option>
<?php 		} ?>
    	</select>    
		&nbsp;&nbsp;<strong class="news_content1">Riwayat Kelas</strong>
        <select name="kelas" class="cmbfrm" id="kelas" style="width:125px" onChange="ChangePresensiHarianOption2('kelas')">
<?php 		for ($j=0; $j<sizeof($kls); $j++)
		{
			if ($kls[$j][3] == $tahunajaran)
			{
				if ($kelas == 0)
					$kelas = $kls[$j][0]; ?>
				<option value="<?=$kls[$j][0] ?>" <?=IntIsSelected($kls[$j][0], $kelas) ?> ><?=$kls[$j][2]." - ".$kls[$j][1] ?> </option>
<?php  		}
		} ?>
    	</select>    
		</td>
        <td align="right" width="12%"><a href="javascript:CetakPresensiHarian4()"><img src="../images/ico/print.png" border="0" />&nbsp;Cetak</a>    	</td>
  	</tr>
	
<?php if ($kelas <> "" )
   { 
		$sql = "SELECT tglmulai, tglakhir FROM tahunajaran WHERE replid = '".$tahunajaran."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_array($result);
		$tglawal = $row['tglmulai'];
		$tglakhir = $row['tglakhir'];
		
		$sql1 = "SELECT CONCAT(DATE_FORMAT(tanggal1,'%b'),' ',YEAR(tanggal1)) AS blnthn,
						SUM(hadir), SUM(ijin), SUM(sakit),  SUM(alpa),SUM(cuti), MONTH(tanggal1)
				   FROM presensiharian p, phsiswa ph
				  WHERE ph.nis = '$nis' AND ph.idpresensi = p.replid AND p.idkelas = '$kelas'
					    AND ((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') AND
						     (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) 
				  GROUP BY blnthn
				  ORDER BY YEAR(tanggal1), MONTH(tanggal1)";
		$result1 = QueryDb($sql1);
		
      	$num = mysqli_num_rows($result1);
		echo "<input type='hidden' name='num' id='num' value=$num>";
		if ($num > 0) {
?>
    <tr>
    	<td colspan="3">
        <table border="0" cellpadding="2" cellspacing="2" width="100%" align="center" bgcolor="#FFFFFF">
        <tr>
        	<td align="center">       	<?php 	
        
		$data_title = "<span class=\"nav_title\">STATISTIK PRESENSI HARIAN</span>"; // title for the diagram

        // sample data array
        $data = [];

        while($row1 = mysqli_fetch_row($result1)) {
            $data[] = [$row1[1], $row1[2], $row1[3], $row1[4], $row1[5]];
            $legend_x[] = $row1[0];			
        }
				
        //$legend_x = array('Jan','Feb','Maret','April','Mei');
        $legend_y = ['Hadir', 'Ijin', 'Sakit', 'Alpa', 'Cuti'];

        $graph = new CAsBarDiagram;
        $graph->bwidth = 10; // set one bar width, pixels
        $graph->bt_total = 'Total'; // 'totals' column title, if other than 'Totals'
       // $graph->showtotals = 0;  // uncomment it if You don't need 'totals' column
        $graph->precision = 1;  // decimal precision
        // call drawing function
        $graph->DiagramBar($legend_x, $legend_y, $data, $data_title);
		?>            </td>
        </tr>
        <tr>
        	<td>
            <table class="tab" id="table" border="1" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%" align="center">		
            <tr height="30" align="center">		
                <td width="*" class="header">Bulan</td>
                <td width="15%" class="header">Hadir</td>
                <td width="15%" class="header">Ijin</td>
                <td width="15%" class="header">Sakit</td>
                <td width="15%" class="header">Alpa</td>
                <td width="15%" class="header">Cuti</td>
            </tr>
			<?php 
            
            $result2 = QueryDb($sql1);
            while ($row2 = @mysqli_fetch_row($result2)) {		
                $waktu = explode(" ",(string) $row2[0]);
            ?>	
            <tr height="25">        			
                <td align="center"><?=NamaBulan($row2[6]).' '.$waktu[1]?></td>
                <td align="center"><?=$row2[1]?></td>
                <td align="center"><?=$row2[2]?></td>
                <td align="center"><?=$row2[3]?></td>
                <td align="center"><?=$row2[4]?></td>
                <td align="center"><?=$row2[5]?></td>
            </tr>
			<?php } ?>
            </table>
           	</td>
       	</tr>
        </table>
        </td>
  	</tr>
<?php } else { ?>                 
	<tr>
		<td align="center" valign="middle" height="120" colspan="3">
		
    	<font size = "2" color ="red"><b><span class="err">Tidak ditemukan adanya data 1.</span><br />
    	</font>
		<table id="table"></table>
		</td>
	</tr>
  	<?php } ?>
<?php } else { ?>                 
	<tr>
		<td align="center" valign="middle" height="120" colspan="3">
    	<font size = "2" color ="red"><b><span class="err">Tidak ditemukan adanya data 2.</span><br />
    	</font>
		<table id="table"></table>
		</td>
	</tr>
<?php } ?>

    </table>
     <!-- END OF CONTENT //--->
	</td>
</tr>
</table> 
</form>  
</div>
<?php
CloseDb();
?>