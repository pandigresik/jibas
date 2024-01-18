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
 
$sql = "SELECT DISTINCT t.idtabungan, dt.nama
          FROM jbsfina.tabunganp t, jbsfina.datatabunganp dt
         WHERE t.idtabungan = dt.replid
           AND t.nip = '$nip'
           AND t.tanggal BETWEEN '$datetime1' AND '$datetime2'";
           
$res = QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
   $lsTab[] = [$row[0], $row[1]];
}

if (count($lsTab) == 0)
{
   echo "<i>Belum ada data tabungan!</i>";
}
else
{
   for($i = 0; $i < count($lsTab); $i++)
   {
       $idTab = $lsTab[$i][0];
       $nmTab = $lsTab[$i][1];
       
       $totsetor = 0;
       $tottarik = 0;
       $saldo = 0;
       $sql = "SELECT SUM(debet), SUM(kredit)
                 FROM jbsfina.tabunganp
                WHERE idtabungan = '$idTab'
                  AND nip = '".$nip."'";
       $res = QueryDb($sql);
       if ($row = mysqli_fetch_row($res))
       {
           $tottarik = $row[0];
           $totsetor = $row[1];
           $saldo = $totsetor - $tottarik;
       }
       
       $subsetor = 0;
       $subtarik = 0;
       $sql = "SELECT SUM(debet), SUM(kredit)
                 FROM jbsfina.tabunganp
                WHERE idtabungan = '$idTab'
                  AND nip = '$nip'
                  AND tanggal BETWEEN '$datetime1' AND '$datetime2'";
       $res = QueryDb($sql);
       if ($row = mysqli_fetch_row($res))
       {
           $subtarik = $row[0];
           $subsetor = $row[1];
       }
       
       $lastsetor = 0;
       $tgllastsetor = "";
       $sql = "SELECT kredit, DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s')
                 FROM jbsfina.tabunganp
                WHERE idtabungan = '$idTab'
                  AND nip = '$nip'
                  AND tanggal BETWEEN '$datetime1' AND '$datetime2'
                  AND kredit <> 0
                ORDER BY replid DESC
                LIMIT 1";
       $res = QueryDb($sql);
       if ($row = mysqli_fetch_row($res))
       {
           $lastsetor = $row[0];
           $tgllastsetor = $row[1];
       }
       
       $lasttarik = 0;
       $tgllasttarik = "";
       $sql = "SELECT debet, DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s')
                 FROM jbsfina.tabunganp
                WHERE idtabungan = '$idTab'
                  AND nip = '$nip'
                  AND tanggal BETWEEN '$datetime1' AND '$datetime2'
                  AND debet <> 0
                ORDER BY replid DESC
                LIMIT 1";
       $res = QueryDb($sql);
       if ($row = mysqli_fetch_row($res))
       {
           $lasttarik = $row[0];
           $tgllasttarik = $row[1];
       }        
       
        //echo "$idTab $nmTab $subsetor $subtarik $lastsetor $tgllastsetor $lasttarik $tgllasttarik $totsetor $tottarik<br>";
       ?>
        <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
        <tr height="35">
            <td colspan="5" bgcolor="#99CC00"><font size="2"><strong><em><?=$nmTab?></em></strong></font></td>
        </tr>
        <tr height="25">
            <td width="17%" bgcolor="#CCFF66"><strong>Jumlah Setoran</strong> </td>
            <td width="*" bgcolor="#FFFFFF" align="right"><strong><?=FormatRupiah($subsetor) ?></strong></td>
            <td width="22%" bgcolor="#CCFF66" align="center"><strong>Total Setoran</strong></td>
            <td width="22%" bgcolor="#CCFF66" align="center"><strong>Total Tarikan</strong></td>
            <td width="22%" bgcolor="#CCFF66" align="center"><strong>Saldo Tabungan</strong></td>
        </tr>
        <tr height="25">
            <td bgcolor="#CCFF66"><strong>Setoran Terakhir</strong> </td>
            <td bgcolor="#FFFFFF" align="right"><strong><?=FormatRupiah($lastsetor) ?></strong><br><i><?=$tgllastsetor?></i></td>
            <td bgcolor="#FFFFFF" align="right" valign="top" rowspan="3">
              <font style='font-size:14px; font-weight: bold;'><?=FormatRupiah($totsetor) ?></font>
            </td>
            <td bgcolor="#FFFFFF" align="right" valign="top" rowspan="3">
              <font style='font-size:14px; font-weight: bold;'><?=FormatRupiah($tottarik) ?></font>
            </td>
            <td bgcolor="#FFFFFF" align="right" valign="top" rowspan="3">
              <font style='font-size:14px; font-weight: bold;'><?=FormatRupiah($saldo) ?></font>
            </td>
        </tr>
        <tr height="25">
            <td bgcolor="#CCFF66"><strong>Jumlah Tarikan</strong> </td>
            <td bgcolor="#FFFFFF" align="right"><strong><?=FormatRupiah($subtarik) ?></strong></td>
        </tr>
        <tr height="25">
            <td bgcolor="#CCFF66"><strong>Tarikan Terakhir</strong> </td>
            <td bgcolor="#FFFFFF" align="right"><strong><?=FormatRupiah($lasttarik)?></strong><br><i><?=$tgllasttarik?></i></td>
        </tr>
        </table>
        <br>
<?php
    }
 }
 ?>
