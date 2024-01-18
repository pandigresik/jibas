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
function ShowSearchTagihan()
{
    ?>
    <table border="0" cellspacing="0" cellpadding="2" style="background-color: #fff;">
    <tr>
        <td align="left" valign="middle">Departemen:</td>
        <td align="left" valign="middle">
<?php       ShowSelectDepartemen(); ?>
        </td>
    </tr>
    <tr>
        <td align="left" valign="middle">Nomor:</td>
        <td align="left" valign="middle">
            <input type="text" class="inputbox" name="nomor" id="nomor" style="background-color:#daefff; font-size: 14px; width: 200px">
            <input type="button" class="but" value="cari" style="width: 50px; height: 23px;" onclick="SearchTagihan()">
            <br><span id="statusCari" style="color: #ff0000"></span>
        </td>
    </tr>
    </table>
<?php
}

function ShowSelectDepartemen()
{
    global $departemen;

    OpenDb();
    echo "<select id='departemen' class='inputbox' name='departemen' style='width: 250px' onchange='changeDep()'>";
    $dep = getDepartemen(getAccess());
    foreach($dep as $value)
    {
        if ($departemen == "") $departemen = $value;
        $sel = $departemen == $value ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
    CloseDb();
}

function ShowSeachSiswa()
{
    ?>
    <table border="0" cellspacing="0" cellpadding="2" style="background-color: #fff;">
    <tr>
        <td align="left" valign="middle">NIS:</td>
        <td align="left" valign="middle">
            <input type="text" class="inputbox" name="noid" id="noid" readonly style="background-color:#daefff; font-size: 14px; width: 200px" onclick="SearchUser()">
            <input type="button" class="but" value="pilih siswa" style="width: 80px; height: 23px;" onclick="SearchUser()">
            <input type="hidden" name="kelompok" id="kelompok">
            <input type="hidden" name="kelas" id="kelas">
        </td>
    </tr>
    <tr>
        <td align="left" valign="middle">Nama:</td>
        <td align="left" valign="middle">
            <input type="text"  class="inputbox" name="nama" id="nama" readonly style="background-color:#daefff; font-size: 14px; width: 300px" onclick="SearchUser()">
        </td>
    </tr>
    </table>
<?php
}

function ShowSelectBulan($selBulan)
{
    echo "<select id='bulan' name='bulan' class='inputbox' style='width: 100px' onchange='changeTagihanSiswaSel()'>";
    for($bln = 1; $bln <= 12; $bln++)
    {
        $sel = $bln == $selBulan ? "selected" : "";
        $nama = NamaBulan($bln);
        echo "<option value='$bln' $sel>$nama</option>";
    }
    echo "</select>";
}

function ShowSelectTahun($selTahun)
{
    $currThn = date('Y');
    echo "<select id='tahun' name='tahun' class='inputbox' style='width: 70px' onchange='changeTagihanSiswaSel()'>";
    for($thn = $currThn - 1; $thn <= $currThn + 1; $thn++)
    {
        $sel = $thn == $selTahun ? "selected" : "";
        echo "<option value='$thn' $sel>$thn</option>";
    }
    echo "</select>";
}

function ShowTagihanSiswaTable()
{
    $nis = $_REQUEST["nis"];
    $nama = $_REQUEST["nama"];
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];

    echo "<table border='0' cellpadding='5' cellspacing='0' width='100%'>";
    echo "<tr><td width='100%'>";
    echo "Bulan: ";
    ShowSelectBulan();
    ShowSelectTahun();
    echo "</td></tr>";
    echo "<tr><td width='100%'>";
    echo "<div id='dvTagihanSiswa'>";
    ShowTagihanSiswa($nis, $nama, $bulan, $tahun);
    echo "</div>";
    echo "</td></tr>";
    echo "</table>";
}

function ShowTagihanSiswa($nis, $nama, $bulan, $tahun)
{
    global $PG_SERVICE_FEE;

    $sql = "SELECT t.replid, t.info, t.jumlah, t.status,
                   t.notagihan, DATE_FORMAT(t.ckdate, '&d-%b-%Y') AS fdate, IFNULL(t.ckdesc, '') AS ckdesc
              FROM jbsfina.tagihansiswainfo t
             WHERE t.nis = '$nis'
               AND t.bulan = '$bulan'
               AND t.tahun = '$tahun'
             ORDER BY t.replid DESC";
    $res = QueryDb($sql);
    $nTagihan = mysqli_num_rows($res);

    if ($nTagihan == 0)
    {
        echo "<br><br>Tidak ada data tagihan";
        return;
    }

    echo "<table id='tabTagihanInfo' border='1' cellpadding='2' cellspacing='0' style='width: 390px; border: 1px solid #ddd; background-color: #ffffff;'>";
    while($row = mysqli_fetch_array($res))
    {
        $idTagihanInfo = $row["replid"];
        $noTagihan = $row["notagihan"];

        $jumlah = $row["jumlah"];
        $jumlah += $PG_SERVICE_FEE;

        echo "<tr style='font-size: 10px; cursor: pointer;' onclick='showTagihanData(\"$nis\",\"$nama\",\"$noTagihan\",\"$idTagihanInfo\")'>";
        echo "<td style='width: 230px' valign='top' align='left'>";
        echo "<b>" . FormatRupiah($jumlah) . "</b><br>" . $row["info"] . "<br><span style='color: #800000; font-style: italic'>$noTagihan</span>" ;
        echo "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
}

function SearchTagihan()
{
    global $PG_SERVICE_FEE;

    $departemen = $_REQUEST["departemen"];
    $nomor = $_REQUEST["nomor"];

    $sql = "SELECT t.replid, t.info, t.jumlah, t.status, t.nis, s.nama,
                   t.notagihan, DATE_FORMAT(t.ckdate, '&d-%b-%Y') AS fdate, IFNULL(t.ckdesc, '') AS ckdesc
              FROM jbsfina.tagihansiswainfo t, jbsfina.tagihanset ts, jbsakad.siswa s
             WHERE t.idtagihanset = ts.replid
               AND t.nis = s.nis
               AND ts.departemen = '$departemen'
               AND t.notagihan LIKE '%$nomor%'
             ORDER BY t.replid DESC";
    $res = QueryDb($sql);

    echo "<table id='tabTagihanInfo' border='1' cellpadding='2' cellspacing='0' style='width: 390px; border: 1px solid #ddd; background-color: #ffffff;'>";
    while($row = mysqli_fetch_array($res))
    {
        $nis = $row["nis"];
        $nama = $row["nama"];
        $idTagihanInfo = $row["replid"];
        $noTagihan = $row["notagihan"];

        $jumlah = $row["jumlah"];
        $jumlah += $PG_SERVICE_FEE;

        echo "<tr style='font-size: 10px; cursor: pointer;' onclick='showTagihanData(\"$nis\",\"$nama\",\"$noTagihan\",\"$idTagihanInfo\")'>";
        echo "<td style='width: 140px' valign='top' align='left'>";
        echo "<b>$nama</b><br>$nis";
        echo "</td>";
        echo "<td style='width: 230px' valign='top' align='left'>";
        echo "<b>" . FormatRupiah($jumlah) . "</b><br>" . $row["info"] . "<br><span style='color: #800000; font-style: italic'>$noTagihan</span>" ;
        echo "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
}

?>
