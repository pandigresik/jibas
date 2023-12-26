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
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');

OpenDb();

$departemen = $_REQUEST["departemen"];
$bankNo = $_REQUEST["bankno"];
?>
<a href="#" onclick="tambahDeposit()"><img src="../images/ico/tambah.png" border="0">&nbsp;tambah deposit</a><br><br>
<table id="tabDaftarDeposit" border="1" cellspacing="0" cellpadding="5" style="border: 1px solid #efefef; border-collapse: collapse;">
<tr>
    <td class="header" style="width: 30px" align="center">No</td>
    <td class="header" style="width: 200px" align="center">Deposit</td>
    <td class="header" style="width: 70px" align="center">Aktif</td>
    <td class="header" style="width: 300px" align="center">Keterangan</td>
    <td class="header" style="width: 70px" align="center">&nbsp;</td>
</tr>
<?php
$sql = "SELECT replid, nama, aktif, keterangan
          FROM jbsfina.bankdeposit
         WHERE departemen = '$departemen'
           AND bankno = '$bankNo'
         ORDER BY nama";
$res = QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_row($res))
{
    $no += 1;

    $idDeposit = $row[0];
    $nama = $row[1];
    $aktif = $row[2];
    $keterangan = $row[3];

    echo "<tr>";
    echo "<td align='center' style='background-color: #efefef;'>$no</td>";
    echo "<td align='left'>$nama</td>";

    echo "<td align='center'><div id='dvDepositAktif-$no'>";
    if ($aktif == 1)
        echo "<a onclick='setDepositAktif($no, $idDeposit, 0)'><img src='../images/ico/aktif.png' title='klik set non aktif'></a>";
    else
        echo "<a onclick='setDepositAktif($no, $idDeposit, 1)'><img src='../images/ico/nonaktif.png' title='klik set aktif'></a>";
    echo "</div></td>";
    echo "<td align='left'>$keterangan</td>";
    echo "<td align='center'>";
    echo "<a onclick='editDeposit($idDeposit)'><img src='../images/ico/ubah.png' title='edit'></a>&nbsp;";
    echo "<a onclick='hapusDeposit($idDeposit)'><img src='../images/ico/hapus.png' title='hapus'></a>";
    echo "</td>";
    echo "</tr>";
}
CloseDb();
?>
</table>
