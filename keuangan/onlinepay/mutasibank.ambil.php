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
require_once('onlinepay.util.func.php');

OpenDb();

$departemen = $_REQUEST["departemen"];
$bankNo = $_REQUEST["bankno"];
?>
<span style="font-size: 20px; color: #999;">Mutasi Pengambilan Dana (-)</span><br><br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="bankno" value="<?=$bankNo?>">

<table border="0" cellpadding="10" cellspacing="0">
<tr>
<td width="*" valign="top" align="left">
    <table id="tabMutasiAmbil" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid #efefef; border-collapse: collapse;">
    <tr>
        <td width="120"><strong>Tanggal Pengambilan:</strong></td>
        <td width="250">
            <input type="hidden" id="dttanggal" value="<?= date('Y-m-d') ?>">
            <input type="text" id="tanggal" class="inputbox" value="<?= formatInaMySqlDate(date('Y-m-d')) ?>" style="width: 140px" onclick="showDatePicker()">
        </td>
    </tr>
    <tr>
        <td><strong>Nomor Bukti Pengambilan:</strong></td>
        <td>
            <input type="text" id="nomortransfer" class="inputbox" style="width: 350px">
        </td>
    </tr>
    <tr>
        <td>Gambar Bukti Pengambilan:</td>
        <td>
            <input type="file" id="buktitransfer" class="inputbox" style="width: 350px" onchange="validateBuktiTransfer()">
            <input type="hidden" id="buktitransfervalid" value="0">
            <span id="spFileInfo"></span>
        </td>
    </tr>
    <tr>
        <td valign="top">Keterangan:</td>
        <td>
            <textarea rows="2" cols="45" class="inputbox" id="keterangan"></textarea>
        </td>
    </tr>
    </table>
</td>
<td width="500px" valign="top" align="left">
    <div style="width: 500px; height: 200px; overflow: auto">
        <img id="imagePreview" style="width: 450px">
    </div>
</td>
</tr>
</table>


<br><br>

<table id="tabMutasiAmbilRincian" border="1" cellpadding="5" cellspacing="0" style="border: 1px solid #efefef;">
<tr style="height: 25px">
    <td class="header" width="35" align="center">No</td>
    <td class="header" width="200" align="center">Simpanan</td>
    <td class="header" width="180" align="center">Saldo</td>
    <td class="header" width="150" align="center">Pengambilan</td>
    <td class="header" width="400" align="center">Keterangan</td>
</tr>
<?php
$sql = "SELECT replid, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit,  saldo,
               DATE_FORMAT(lasttime, '%d %b %Y %H:%i') AS flasttime 
          FROM jbsfina.banksaldo
         WHERE departemen = '$departemen'
           AND bankno = '$bankNo'
         ORDER BY kelompok";
$res = QueryDb($sql);

$totalSaldo = 0;
$no = 0;
while($row = mysqli_fetch_array($res))
{
    $no += 1;

    $kategori = $row["kategori"];
    $idPenerimaan = 0;
    if ($kategori == "JTT" || $kategori == "SKR")
        $idPenerimaan = $row["idpenerimaan"];
    else if ($kategori == "SISTAB")
        $idPenerimaan = $row["idtabungan"];
    else if ($kategori == "PEGTAB")
        $idPenerimaan = $row["idtabunganp"];
    else if ($kategori == "DPST")
        $idPenerimaan = $row["iddeposit"];

    $namaPenerimaan = NamaPenerimaan($kategori, $idPenerimaan);
    $namaKategori = NamaKategori($kategori);

    $saldo = $row["saldo"];
    $rp = FormatRupiah($saldo);
    $totalSaldo += $saldo;

    echo "<tr>";
    echo "<td align='center' style='background-color: #efefef'>$no</td>";
    echo "<td align='left'><strong>$namaPenerimaan</strong><br><i>$namaKategori</i>";
    echo "<input type='hidden' id='kate-$no' value='$kategori'>";
    echo "<input type='hidden' id='idpen-$no' value='$idPenerimaan'>";
    echo "<input type='hidden' id='pen-$no' value='$namaPenerimaan'>";
    echo "<input type='hidden' id='saldo-$no' value='$saldo'>";
    echo "</td>";
    echo "<td align='right'><span style='font-size: 13px; font-weight: bold'>$rp</span></td>";
    $el = "jum-$no";
    echo "<td align='left'>";
    if ($kategori <> "LB")
        echo "<input type='text' id='$el' class='inputbox' style='width: 180px' value='Rp 0' onblur='formatRupiah(\"$el\"); calcJumAmbil();' onfocus='unformatRupiah(\"$el\")'>";
    else
        echo "<input type='text' id='$el' class='inputbox' style='width: 180px; background-color: #dedede;' value='Rp 0' readonly>";
    echo "</td>";
    echo "<td align='left'><input type='text' id='ket-$no' class='inputbox' style='width: 380px' maxlength='255'></td>";
    echo "</tr>";
}
?>
<tr style="height: 40px;">
    <td colspan="2" align="right" style="background-color: #ffc038;"><strong>Total</strong></td>
    <td align="right" style="background-color: #ffc038;">
        <span style="font-size: 14px; font-weight: bold;">
        <?= FormatRupiah($totalSaldo) ?>
        </span>
    </td>
    <td align="right" style="background-color: #ffc038;">
        <span id="spTotal" style="font-size: 14px">Rp 0</span>
    </td>
    <td style="background-color: #ffc038;">&nbsp;</td>
</tr>
</table>
<input type="hidden" id="ndata" value="<?=$no?>">
<br>
<div>
    <input type="hidden" id="jumvalid" value="0">
    <input type="button" id="btMutasiAmbil" class="but" style="width: 180px; height: 40px;" value="Ambil Dana dari Bank" onclick="simpanMutasiAmbil()">
    <span id="spInfo"></span>
</div>

<?php
CloseDb();
?>
