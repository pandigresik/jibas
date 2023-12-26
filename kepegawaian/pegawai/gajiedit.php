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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once("../include/rupiah.php");
require_once("../include/sessioninfo.php");
require_once('../include/theme.php');

$id = $_REQUEST['id'];

$gaji = $_REQUEST['gaji'];
$tgl = $_REQUEST['tgl'];
$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];
$sk = $_REQUEST['txSK'];
$keterangan = $_REQUEST['keterangan'];
$user = getUserId();

if (isset($_REQUEST['Simpan']))
{
	OpenDb();
	
	$alasan = $_REQUEST['alasan'];
	$tanggal = "$thn-$bln-$tgl";
	$gaji = UnformatRupiah($gaji);

	$sql = "UPDATE peggaji SET gaji=$gaji, tanggal='$tanggal', sk='$sk', keterangan='$keterangan', doaudit = 1  WHERE replid=$id";
	QueryDb($sql);
	
	CloseDb(); ?>
    <script language="javascript">
		opener.Refresh();
		window.close();
    </script>
<?php exit();
}
else {
	OpenDb();
	
	$sql = "SELECT gaji, tanggal, keterangan, sk FROM peggaji WHERE replid=$id";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	
	$gaji = $row[0];
	$gaji = FormatRupiah($gaji);
	$tgl  = GetDatePart($row[1], "d");
	$bln  = GetDatePart($row[1], "m");
	$thn  = GetDatePart($row[1], "y");
	$keterangan = $row[2];
	$sk = $row[3];

	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ubah Riwayat Gaji</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function validate() {
	return validateEmptyText('gaji', 'Gaji Pegawai') && 
		   validateEmptyText('thn', 'Tahun Kenaikan Gaji Pegawai') && 
  		   validateInteger('thn', 'Tahun Kenaikan Gaji Pegawai') && 
		   validateLength('thn', 'Tahun Kenaikan Gaji Pegawai', 4);
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Ubah Riwayat Gaji</td>
</tr>
<tr>
	<td width="100%" align="center">
    
    <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
        <td align="right" valign="top" width="22%"><strong>Gaji :</strong></td>
        <td width="*" align="left" valign="top">
	        <input type="text" value="<?=$gaji?>" name="gaji" id="gaji" size="15" maxlength="15" style="text-align:right;" onBlur="formatRupiah('gaji')" onFocus="unformatRupiah('gaji')" />
        </td>
	</tr>
	<tr>
        <td align="right" valign="top"><strong>Tanggal :</strong></td>
        <td width="*" align="left" valign="top">
        <select id="tgl" name="tgl" onKeyPress="return focusNext('bln', event)">
    <?php for ($i = 1; $i <= 31; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $tgl)?>><?=$i?></option>	
    <?php } ?>    
        </select>
        <select id="bln" name="bln" onKeyPress="return focusNext('thn', event)">
    <?php for ($i = 1; $i <= 12; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $bln)?>><?=NamaBulan($i)?></option>	
    <?php } ?>    
        </select>
        <input type="text" name="thn" onKeyPress="return focusNext('keterangan', event)" id="thn" size="4" maxlength="4" value="<?=$thn?>"/>
        </td>
	</tr>
	<tr>
    	<td align="right">SK : </td>
	    <td align="left" valign="top">
        <input type="text" name="txSK" value="<?=$sk?>" id="txSK" size="30" maxlength="100" onKeyPress="return focusNext('txKeterangan', event)" />
        </td>
    </tr>
    <tr>
    	<td align="right" valign="top">Keterangan : </td>
	    <td align="left" valign="top">
        <textarea id="keterangan" name="keterangan" rows="2" cols="40"><?=$keterangan?></textarea>
        </td>
    </tr>
    <tr>
    	<td align="right" valign="top">&nbsp;</td>
	    <td align="left" valign="top">
        <input type="submit" value="Simpan" name="Simpan" class="but" />
        <input type="button" value="Tutup" onClick="window.close()" class="but" />
        </td> 
    </tr>
    </table>
    </td>
</tr>
</table>
</form>

</body>
</html>