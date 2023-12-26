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
require_once('../inc/common.php');
require_once('../inc/sessioninfo.php');
require_once('../pjm/pinjam.config.php');

if (isset($_REQUEST['Simpan']))
{
    $siswa = $_REQUEST['siswa'];
    $pegawai = $_REQUEST['pegawai'];
    $other = $_REQUEST['other'];
    
    $content  = '<?';
    $content .= "\r\n";
    $content .= '$WaktuPinjamSiswa = ' . $siswa . ';';
    $content .= "\r\n";
    $content .= '$WaktuPinjamPegawai = ' . $pegawai . ';';
    $content .= "\r\n";
    $content .= '$WaktuPinjamLain = ' . $other . ';';
    $content .= "\r\n";
    $content .= '?>';
    
    file_put_contents('../pjm/pinjam.config.php', $content);
    ?>
    <script>
        window.close();
    </script>
    <?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script type="text/javascript" src="../scr/jquery/jquery-1.2.6.js"></script>
<script type="text/javascript">
Validate = function()
{
    var siswa = $.trim($('#siswa').val());
    if (siswa.length == 0)
    {
        alert('Anda harus memasukkan nilai waktu peminjaman siswa!');
        $('#siswa').focus();
        
        return false;
    }
    if (isNaN(siswa))
    {
        alert('Waktu peminjaman siswa harus berupa angka!');
        $('#siswa').focus();
        
        return false;
    }
    
    var pegawai = $.trim($('#pegawai').val());
    if (pegawai.length == 0)
    {
        alert('Anda harus memasukkan nilai waktu peminjaman pegawai!');
        $('#pegawai').focus();
        
        return false;
    }
    if (isNaN(pegawai))
    {
        alert('Waktu peminjaman pegawai harus berupa angka!');
        $('#pegawai').focus();
        
        return false;
    }
    
    var other = $.trim($('#other').val());
    if (other.length == 0)
    {
        alert('Anda harus memasukkan nilai waktu peminjaman anggota non sekolah!');
        $('#other').focus();
        
        return false;
    }
    if (isNaN(other))
    {
        alert('Waktu peminjaman pegawai harus berupa anggota non sekolah!');
        $('#other').focus();
        
        return false;
    }
    
    return confirm('Data sudah benar?');
}
</script>
</head>

<body leftmargin="0" topmargin="0">
<div id="title" align="right">
    <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
  <font style="font-size:18px; color:#999999">Konfigurasi Waktu Peminjaman</font><br /><br />
</div>
<div id="content">
<fieldset>
    <legend>Default Waktu Peminjaman Yang Dapat Dilakukan</legend>
    <form action="waktu.pinjam.config.php" method="post" onsubmit="return Validate();">
        
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
        <td width="40%" align="right">Siswa</td>
        <td width="60%">
            <input type="text" name="siswa" id="siswa" class="inptxt-small-text"
                   value="<?=$WaktuPinjamSiswa?>" style="width:40px"/>&nbsp;hari
        </td>
    </tr>
    <tr>
        <td align="right">Pegawai</td>
        <td>
            <input type="text" name="pegawai" id="pegawai" class="inptxt-small-text"
                   value="<?=$WaktuPinjamPegawai?>" style="width:40px" />&nbsp;hari
        </td>
    </tr>
    <tr>
        <td align="right">Anggota Luar Sekolah</td>
        <td>
            <input type="text" name="other" id="other" class="inptxt-small-text"
                   value="<?=$WaktuPinjamLain?>" style="width:40px" />&nbsp;hari
        </td>
    </tr>
    <tr>
<?php $disabled = "";
	if (!IsAdmin())
		$disabled = "disabled='disabled'"; ?>
        <td colspan="2" align="center">
            <input type="submit" name="Simpan" value="Simpan" class="cmbfrm2" <?=$disabled?> />&nbsp;&nbsp;
            <input type="button" onClick="window.close()" value="Tutup"  class="cmbfrm2"/>
        </td>
    </tr>
</table>
    
</form>
</fieldset>

</div>
</body>
</html>