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
require_once('../include/theme.php');
require_once('../include/sessioninfo.php');
require_once('smsmanager.func.php');

$jenis = $_REQUEST['jenis'];
$nis = $_REQUEST['nis'];
$nama = $_REQUEST['nama'];
$siswa = "$nama ($nis)";
$penerimaan = $_REQUEST['penerimaan'];
$tunggakan = $_REQUEST['tunggakan'];
$departemen = $_REQUEST['departemen'];
$sms = $_REQUEST['sms'];

if (isset($_REQUEST['Kirim']))
{
    OpenDb();

    $success = true;
    BeginTrans();

    CreateSMSTunggakan($jenis, $departemen, $nis, $nama, $sms, $success);

    if ($success)
    {
        CommitTrans();
        CloseDb();
        $msg = "SMS / Telegram Informasi Tunggakan telah disiapkan";
    }
    else
    {
        RollbackTrans();
        CloseDb();
        $msg = "Tidak ada nomor HP untuk pengiriman SMS Tunggakan!";
    }

    ?>
    <script language = "javascript" type = "text/javascript">
        alert("<?=$msg?>");
        window.close();
    </script>
<?php  exit();
}

OpenDb();
$sql = "SELECT COUNT(replid)
          FROM jbsfina.formatsms
         WHERE departemen = '$departemen'
           AND jenis = 'SISTUNG'";
$ndata = FetchSingle($sql);
if ($ndata == 0)
{
    $format = "Kami informasikan {NAMA} masih memiliki tunggakan sebesar {TUNGGAKAN} untuk {PEMBAYARAN} - Bag. Keuangan";
    $sql = "INSERT INTO jbsfina.formatsms
               SET jenis = 'SISTUNG', departemen = '$departemen', format = '".$format."'";
    QueryDb($sql);
}

$sql = "SELECT format
          FROM jbsfina.formatsms
         WHERE departemen = '$departemen'
           AND jenis = 'SISTUNG'";
$format = FetchSingle($sql);

$sms = $format;
$sms = str_replace("{NAMA}", $siswa, (string) $sms);
$sms = str_replace("{TUNGGAKAN}", FormatRupiah($tunggakan), $sms);
$sms = str_replace("{PEMBAYARAN}", $penerimaan, $sms);

CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JIBAS KEU [Kirim SMS Tunggakan]</title>
    <script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/validasi.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah.js"></script>
    <script type="text/javascript">
        function validasi()
        {
            return validateEmptyText('sms', 'SMS Tunggakan') &&
                   confirm("Pesan notifikasi sudah benar?");
        }
    </script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('nama').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr height="58">
        <td width="28" background="../images/default/bgpop_01.jpg">&nbsp;</td>
        <td width="*" background="../images/default/bgpop_02a.jpg">
            <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
                .: Notifikasi SMS | Telegram | Jendela Sekolah :.
            </div>
        </td>
        <td width="28" background="../images/default/bgpop_03.jpg">&nbsp;</td>
    </tr>
    <tr height="150">
        <td width="28" background="../images/default/bgpop_04a.jpg">&nbsp;</td>
        <td align="center" style="background-color:#FFFFFF">

            <form name="main" method="post" onSubmit="return validasi();">
                <input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>">
                <input type="hidden" name="nis" id="nis" value="<?=$nis?>">
                <input type="hidden" name="nama" id="nama" value="<?=$nama?>">
                <input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
                <textarea rows="5" id="sms" name="sms" cols="45"><?=$sms?></textarea><br><br>
                <input type="submit" id="Kirim" name="Kirim" value="Kirim Notifikasi" class="but">
            </form>
            <!-- END OF CONTENT //--->
        </td>
        <td width="28" background="../images/default/bgpop_06a.jpg">&nbsp;</td>
    </tr>
    <tr height="28">
        <td width="28" background="../images/default/bgpop_07.jpg">&nbsp;</td>
        <td width="*" background="../images/default/bgpop_08a.jpg">&nbsp;</td>
        <td width="28" background="../images/default/bgpop_09.jpg">&nbsp;</td>
    </tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
    <script language="javascript">
        alert('<?=$mysqli_ERROR_MSG?>');
    </script>
<?php } ?>
</body>
</html>