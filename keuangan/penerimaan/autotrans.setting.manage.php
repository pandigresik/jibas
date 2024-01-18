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
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('autotrans.setting.manage.func.php');

if (getLevel() == 2)
{ ?>
    <script language="javascript">
        alert('Maaf, anda tidak berhak mengakses halaman ini!');
        document.location.href = "penerimaan.php";
    </script>
    <?php 	exit();
} // end if

$departemen = $_REQUEST["departemen"];
$idAutoTrans = $_REQUEST["idautotrans"];

$title = $idAutoTrans == 0 ? "Tambah Konfigurasi Pembayaran" : "Ubah Konfigurasi Pembayaran";

OpenDb();

$kelompok = 1;
$smsinfo = 0;
if ($idAutoTrans != 0)
{
    $sql = "SELECT judul, urutan, keterangan, kelompok, smsinfo
              FROM jbsfina.autotrans
             WHERE replid = $idAutoTrans";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $judul = $row[0];
    $urutan = $row[1];
    $keterangan = $row[2];
    $kelompok = $row[3];
    $smsinfo = $row[4];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$title?></title>
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language="javascript" src="../script/jquery-1.9.0.js"></script>
    <script language="javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script language="javascript" src="autotrans.setting.manage.js"></script>
</head>

<body>

<table border="0" cellpadding="10" width="100%">
<tr><td>

<form name="main" action="autotrans.setting.manage.save.php" onsubmit="return saveForm()">
<span style="font-size: 18px"><?=$title?></span><br><br>
<input type="hidden" id="idautotrans" name="idautotrans" value="<?=$idAutoTrans?>">
<input type="hidden" id="departemen" name="departemen" value="<?=$departemen?>">
<input type="hidden" id="lsPenerimaan" name="lsPenerimaan" value="">
<table border="0" cellpadding="5" cellspacing="0" width="100%" height="100%">
<tr>
    <td width="100"><strong>Departemen:</strong></td>
    <td><input type="text" id="dept" name="dept" class="inputbox" value="<?=$departemen?>" readonly style="width: 300px; font-size: 12px;"></td>
</tr>
<tr>
    <td width="100"><strong>Judul:</strong></td>
    <td><input type="text" id="judul" name="judul" class="inputbox" value="<?=$judul?>" style="width: 300px; font-size: 12px;"></td>
</tr>
<tr>
    <td width="100"><strong>Kelompok:</strong></td>
    <td>
        <select id="kelompok" name="kelompok">
            <option value="1" <?= $kelompok == 1 ? "selected" : "" ?>>Siswa</option>
            <option value="2" <?= $kelompok == 2 ? "selected" : "" ?>>Calon Siswa</option>
        </select>
    </td>
</tr>
<tr>
    <td width="100"><strong>Urutan:</strong></td>
    <td><input type="text" id="urutan" name="urutan" class="inputbox" value="<?=$urutan?>" style="width: 50px; font-size: 12px;"></td>
</tr>
<tr>
    <td width="100"><strong>SMS:</strong></td>
    <td align="left">
        <input type="checkbox" id="smsinfo" name="smsinfo" class="inputbox" <?= $smsinfo == 1 ? "checked" : "" ?>>
        Notifikasi SMS | Telegram | Jendela Sekolah
    </td>
</tr>
<tr>
    <td valign="top">Keterangan:</td>
    <td><textarea id="keterangan" name="keterangan" class="inputbox" rows="2" cols="45"><?=$keterangan?></textarea></td>
</tr>
</table>

<br>
<a href="#" onclick="tambahPenerimaan()"><img src="../images/ico/tambah.png" title="tambah">&nbsp;tambah penerimaan</a><br><br>
<table id="tabDaftar" border="1" cellpadding="5" style="border-collapse: collapse; border-width: 1px;" cellspacing="0" width="100%" height="100%">
<thead>
<tr style="height: 30px;">
    <td class="header" width="30" align="center">No</td>
    <td class="header" width="220">Penerimaan</td>
    <td class="header" width="120" align="right">Besar</td>
    <td class="header" width="60" align="center">Aktif</td>
    <td class="header" width="60" align="center">Urutan</td>
    <td class="header" width="*">Keterangan</td>
    <td class="header" width="60">&nbsp;</td>
</tr>
</thead>
<tbody>
<?php
if ($idAutoTrans != 0)
{
    $sql = "SELECT ad.replid, ad.idpenerimaan, dp.nama, ad.besar, ad.aktif, ad.urutan, ad.keterangan
              FROM jbsfina.autotransdata ad, jbsfina.datapenerimaan dp
             WHERE ad.idpenerimaan = dp.replid
               AND ad.idautotrans = $idAutoTrans
             ORDER BY ad.urutan";
    $res = QueryDb($sql);
    $ix = 0;
    while($row = mysqli_fetch_array($res))
    {
        $imgAktif = $row["aktif"] == 1 ? "../images/ico/aktif.png" : "../images/ico/nonaktif.png";

        AddAutoTransData($row["replid"], $row["idpenerimaan"], $row["aktif"], $row["besar"], $row["urutan"], $row["keterangan"]);
        ?>
        <tr id='tabDaftarRow-<?=$ix?>' style='height: 25px'>
            <td align='center'><?=$ix + 1?></td>
            <td align='left'><?=$row['nama']?></td>
            <td align='right'><?=FormatRupiah($row['besar'])?></td>
            <td align='center'><a onclick='setAktif(<?=$ix?>)' style='cursor: pointer'><img id='imgAktif-<?=$ix?>' src='<?=$imgAktif?>'></a></td>
            <td align='center'><?=$row["urutan"]?></td>
            <td align='left'><?=$row["keterangan"]?></td>
            <td align='center'><a onclick='hapusData(<?=$ix?>)' style='cursor: pointer'><img src='../images/ico/hapus.png' title='hapus'></a></td>
        </tr>
<?php
        $ix += 1;
    }

    $json = json_encode($lsAutoTransData, JSON_THROW_ON_ERROR);
    echo "<input type='hidden' id='jsonData' value='$json'>";
}
?>
</tbody>
</table>

<br>
<input type="submit" class="but" value="Simpan" style="height: 30px; width: 80px;">&nbsp;
<input type="button" class="but" value="Tutup" style="height: 30px; width: 80px;" onclick="tutup()">
</form>

</td></tr>
</table>

</body>
</html>
<?php
if ($idAutoTrans != 0) {
    ?>
    <script language = "javascript" type = "text/javascript">
        var jsonData = $("#jsonData").val();
        lsPenerimaan = JSON.parse(jsonData);
    </script>
    <?php
}
?>
<?php
CloseDb();
?>
