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
function ShowSelectDepartemen()
{
    global $departemen;

    $dep = getDepartemen(getAccess());

    echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDept(); clearContent();'>";
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowBankSaldo()
{
    global $departemen;

    $sql = "SELECT b.bank, bs.bankno, SUM(bs.saldo) AS saldo
              FROM jbsfina.bank b, jbsfina.banksaldo bs
             WHERE b.bankno = bs.bankno";
    if ($departemen != "ALL")
        $sql .= " AND bs.departemen = '".$departemen."'";
    $sql .= " GROUP BY b.bank, bs.bankno";

    $res = QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "<br>Belum ada saldo bank";
        return;
    }

    echo "<div id='dvBankSaldoContent'>";
    echo "<table id='tabBankSaldo' border='1' cellspacing='0' cellpadding='5' style='border: 1px solid #efefef;'>";
    echo "<tr>";
    echo "<td class='header' align='center' width='175'>Bank</td>";
    echo "<td class='header' align='center' width='175'>Saldo</td>";
    echo "</tr>";
    while($row = mysqli_fetch_array($res))
    {
        $saldo = $row["saldo"];
        $rp = FormatRupiah($saldo);

        echo "<tr style='cursor: pointer;' onclick='showMutasiBank(\"{$row['bank']}\",\"{$row['bankno']}\")'>";
        echo "<td><strong>".$row['bank']."</strong><br><i>".$row['bankno']."</i></td>";
        echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>