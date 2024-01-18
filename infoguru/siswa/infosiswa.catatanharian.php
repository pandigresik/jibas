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

$nis = $_SESSION["infosiswa.nis"];
$bulan_pjg = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

OpenDb();
$sql_thn = "SELECT ph.tanggal1,ph.tanggal2,phsiswa.keterangan
              FROM jbsakad.phsiswa phsiswa, jbsakad.presensiharian ph
             WHERE phsiswa.nis='$nis' AND phsiswa.idpresensi=ph.replid
             GROUP BY YEAR(tanggal1)";
$res_thn = QueryDb($sql_thn);

$s = "SELECT DATE(now())";
$re = QueryDb($s);
$r = @mysqli_fetch_row($re);
$d = explode("-", (string) $r[0]);
$now = $d[2]."-".$d[1]."-".$d[0];
if ($d[1]==1)
    $y=12;
else
	$y=$d[1]-1;
if (strlen((string) $y)==1)
	$y="0".$y;
$ytd = $d[2]."-".$y."-".$d[0];
CloseDb();
?>
<form name="panel5">
<table width="100%" border="0" cellspacing="5">
  <tr>
    <td valign="top">
    <fieldset><legend>Periode</legend>
    <table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="50%">
      <input type="hidden" id="niscthar" name="niscthar" value="<?=$nis?>">  
      <input title="Klik untuk membuka kalendar!" type="text" size="10" id="tglawal" readonly onClick="showCal('CalendarISCATPHAR1')"  value="<?=$ytd?>"/>
      &nbsp;<img src="../images/ico/calendar.png" name="btnawal" id="btnawal" title="Klik untuk membuka kalendar!" onClick="showCal('CalendarISCATPHAR1')"/>
      &nbsp;s.d.&nbsp;
      <input title="Klik untuk membuka kalendar!" type="text" size="10" id="tglakhir" readonly onclick="showCal('CalendarISCATPHAR2')" value="<?=$now?>"/>
      &nbsp;<img src="../images/ico/calendar.png" id="btnakhir" onClick="showCal('CalendarISCATPHAR2')" title="Klik untuk membuka kalendar!"/></td>
    <td width="50%"><img title="Klik untuk menampilkan Presensi Harian" style="cursor:pointer;" src="../images/ico/view.png" width="32" height="32" onclick="ShowCatatanHarian()" /></td>
  </tr>
  <tr>
    
    </tr>
</table>
    </fieldset></td>
    
  </tr>
  <tr>
    <td  valign="top"><div id="contentph">		</div></td>
  </tr>
</table>
</form>