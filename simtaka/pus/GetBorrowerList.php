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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');

OpenDb();

$idperpustakaan = -1;
if (isset($_REQUEST['idperpustakaan']))
  $idperpustakaan = (int)$_REQUEST['idperpustakaan'];

$filter2="";
if ($idperpustakaan != -1)
  $filter2="AND d.perpustakaan=".$idperpustakaan;
  
$jenisanggota = $_REQUEST['jenisanggota'];
$idanggota = $_REQUEST['idanggota'];
$from = $_REQUEST['from'];
$to = $_REQUEST['to'];

if ($jenisanggota == "siswa")
  $filter = "AND p.nis = '".$idanggota."'";
elseif ($jenisanggota == "pegawai")
  $filter = "AND p.nip = '".$idanggota."'";
else
  $filter = "AND p.idmember = '".$idanggota."'";

?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab">
<tr height='25'>
  <td width='5%' align="center" class="header">No</td>
  <td width='15%' align="center" class="header">Tgl Pinjam</td>
  <td width='*' align="center" class="header">Pustaka</td>
  <td width='5%' align="center" class="header">&nbsp;</td>
</tr>
<?php
$sql = "SELECT pu.judul, p.tglpinjam, pu.replid, d.kodepustaka
          FROM pinjam p, daftarpustaka d, pustaka pu
         WHERE p.tglpinjam BETWEEN '$from' AND '$to'
               $filter $filter2
           AND p.kodepustaka=d.kodepustaka
           AND d.pustaka=pu.replid
         ORDER BY tglpinjam DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = @mysqli_fetch_row($result))
{
  $cnt += 1;  ?>
  <tr height="20">
    <td align="center"><?=$cnt?></td>
    <td align="center"><?=LongDateFormat($row[1])?></td>
    <td align='left'>
      <font style='font-size: 9px'><?=$row[3]?></font><br>
	  <font style='font-size: 11px; font-weight: bold;'><?=$row[0]?></font>
    </td>
    <td align="center">
        <a href="javascript:ViewDetail('<?=$row[2]?>')">
        <img src="../img/ico/lihat.png" width="16" height="16" border="0" />
        </a>
    </td>
  </tr>
<?php
}
CloseDb();
?>
</table>