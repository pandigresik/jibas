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
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/theme.php');
require_once('include/sessioninfo.php');

$id = $_REQUEST['id'];
$idkategori = $_REQUEST['idkategori'];
$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['simpan']))
{
	OpenDb();
	$sql = "SELECT replid FROM datapenerimaan WHERE nama = '".$_REQUEST['nama']."' AND replid <> '$id'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0)
	{
		$mysqli_ERROR_MSG = "Nama {$_REQUEST['nama']} sudah digunakan!";
	}
	else
	{
		$besar = $_REQUEST['besar'];
		$besar = UnformatRupiah($besar);
		$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;
		$sql = "UPDATE datapenerimaan
				   SET nama='".CQ($_REQUEST['nama'])."',
					   rekkas='".$_REQUEST['norekkas']."',
					   rekpendapatan='".$_REQUEST['norekpendapatan']."',
					   rekpiutang='".$_REQUEST['norekpiutang']."',
					   info1='".$_REQUEST['norekdiskon']."',
					   keterangan='".CQ($_REQUEST['keterangan'])."',
					   info2='$smsinfo'
				 WHERE replid=$id";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result)
		{ ?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
	}
} 

OpenDb();

$sql = "SELECT kategori FROM kategoripenerimaan WHERE kode='$idkategori'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$kategori = $row[0];

$sql = "SELECT * FROM datapenerimaan WHERE replid = '".$id."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$nama = $row['nama'];
$besar = FormatRupiah($row['besar']);
$rekkas = $row['rekkas'];
$rekpendapatan = $row['rekpendapatan'];
$rekpiutang = $row['rekpiutang'];
$rekdiskon = $row['info1'];
$keterangan = $row['keterangan'];
$smsinfo = (int)$row['info2'];

if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
	
if (isset($_REQUEST['keterangan']))
	$keterangan = $_REQUEST['keterangan'];	

$sql = "SELECT nama FROM rekakun WHERE kode = '".$rekkas."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namarekkas = $row[0];

$sql = "SELECT nama FROM rekakun WHERE kode = '".$rekpendapatan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namarekpendapatan = $row[0];

$sql = "SELECT nama FROM rekakun WHERE kode = '".$rekpiutang."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namarekpiutang = $row[0];

$sql = "SELECT nama FROM rekakun WHERE kode = '".$rekdiskon."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namarekdiskon = $row[0];

// ========================================================
// CHECK WHETHER THIS DATA HAS BEEN USED?
$idIsUsed = false;

$sql = "SELECT EXISTS(SELECT replid FROM jbsfina.besarjtt WHERE idpenerimaan = '$id' LIMIT 1)";
$idIsUsed = (1 == (int)FetchSingle($sql));

if (!$idIsUsed)
{
	$sql = "SELECT EXISTS(SELECT replid FROM jbsfina.besarjttcalon WHERE idpenerimaan = '$id' LIMIT 1)";
	$idIsUsed = (1 == (int)FetchSingle($sql));
}

if (!$idIsUsed)
{
	$sql = "SELECT EXISTS(SELECT replid FROM jbsfina.penerimaaniuran WHERE idpenerimaan = '$id' LIMIT 1)";
	$idIsUsed = (1 == (int)FetchSingle($sql));
}

if (!$idIsUsed)
{
	$sql = "SELECT EXISTS(SELECT replid FROM jbsfina.penerimaaniurancalon WHERE idpenerimaan = '$id' LIMIT 1)";
	$idIsUsed = (1 == (int)FetchSingle($sql));
}

if (!$idIsUsed)
{
	$sql = "SELECT EXISTS(SELECT replid FROM jbsfina.penerimaanlain WHERE idpenerimaan = '$id' LIMIT 1)";
	$idIsUsed = (1 == (int)FetchSingle($sql));
}
// ========================================================

CloseDb();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Jenis Penerimaan]</title>
<script language = "javascript" type = "text/javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/rupiah.js"></script>
<script language="javascript">
function validasi()
{
	return validateEmptyText('nama', 'Nama Jenis Penerimaan') 
		&& validateEmptyText('rekkas', 'Rekening Kas')
		&& validateEmptyText('rekpendapatan', 'Rekening Pendapatan')
		&& validateEmptyText('rekpiutang', 'Rekening Piutang')
		&& validateEmptyText('rekdiskon', 'Rekening Diskon')
		&& validateMaxText('keterangan', 255, 'Keterangan Jenis Penerimaan');
}

function accept_rekening(kode, nama, flag)
{
	if (flag == 1)
	{
		document.getElementById('rekkas').value = kode + " " + nama;
		document.getElementById('norekkas').value = kode;
	}
	else if (flag == 2)
	{
		document.getElementById('rekpendapatan').value = kode + " " + nama;
		document.getElementById('norekpendapatan').value = kode;
	}
	else if (flag == 3)
	{
		document.getElementById('rekpiutang').value = kode + " " + nama;
		document.getElementById('norekpiutang').value = kode;
	}
	else if (flag == 4)
	{
		document.getElementById('rekdiskon').value = kode + " " + nama;
		document.getElementById('norekdiskon').value = kode;
	}
}

function cari_rek(flag, kategori)
{
	newWindow('carirek.php?option=ro&flag='+flag+'&kategori='+kategori, 'CariRekening','550','438','resizable=1,scrollbars=1,status=0,toolbar=0')
}


function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13)
	{
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem)
{
	var lain = new Array('nama','keterangan');
	for (i=0;i<lain.length;i++)
	{
		if (lain[i] == elem)
		{
			document.getElementById(elem).style.background='#FFFF99';
		}
		else
		{
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('nama').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Jenis Penerimaan :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">

    <form name="main" method="post" onSubmit="return validasi();">   
    <input type="hidden" name="id" id="id" value="<?=$id ?>" />    
   	<table border="0" cellpadding="2" cellspacing="2" align="center" background="">
    <tr>
        <td align="left"><strong>Kategori</strong></td>
        <td align="left"><input type="text" name="kategori" id="kategori" maxlength="100" size="30" readonly style="background-color:#CCCC99" value="<?=$kategori ?>">
        <input type="hidden" name="idkategori" id="idkategori" value="<?=$_REQUEST['idkategori'] ?>" />
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Departemen</strong></td>
        <td align="left"><input type="text" name="departemen" id="departemen" maxlength="100" size="30" readonly style="background-color:#CCCC99" value="<?=$departemen ?>">
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Nama</strong></td>
        <td align="left"><input type="text" name="nama" id="nama" value="<?=$nama ?>" maxlength="100" size="30" onKeyPress="return focusNext('rekkas', event)" onFocus="panggil('nama');"></td>
    </tr>
    <tr>
        <td align="left"><strong>Rek. Kas</strong></td>
        <td align="left">
			<input type="text" name="rekkas" id="rekkas" value="<?=$rekkas . " " . $namarekkas ?>" readonly style="background-color:#CCCC99" maxlength="100" size="30" onFocus="panggil('rekkas')">&nbsp;
<?php 		if (!$idIsUsed) { ?>			
				<a href="#" onClick="JavaScript:cari_rek(1,'HARTA')"><img src="images/ico/lihat.png" border="0" /></a>
<?php 		} else {
				echo "<font style='color:blue'>*</font>";
			} ?>						
			<input type="hidden" name="norekkas" id="norekkas"  value="<?=$rekkas ?>" />
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Rek. Pendapatan</strong></td>
        <td align="left">
			<input type="text" name="rekpendapatan" id="rekpendapatan" value="<?=$rekpendapatan  . " " . $namarekpendapatan ?>" readonly style="background-color:#CCCC99" maxlength="100" size="30"  onFocus="panggil('rekpendapatan')">&nbsp;
<?php 		if (!$idIsUsed) { ?>						
				<a href="#" onClick="JavaScript:cari_rek(2,'PENDAPATAN')"><img src="images/ico/lihat.png" border="0" /></a>
<?php 		} else {
				echo "<font style='color:blue'>*</font>";
			} ?>						
			<input type="hidden" name="norekpendapatan" id="norekpendapatan" value="<?=$rekpendapatan ?>" />
        </td>
    </tr>
    <tr>
        <td align="left"><strong>Rek. Piutang</strong></td>
        <td align="left">
			<input type="text" name="rekpiutang" id="rekpiutang" value="<?=$rekpiutang . " " . $namarekpiutang ?>" readonly style="background-color:#CCCC99" maxlength="100" size="30" onFocus="panggil('rekpiutang')">&nbsp;
<?php 		if (!$idIsUsed) { ?>						
				<a href="#" onClick="JavaScript:cari_rek(3,'PIUTANG')"><img src="images/ico/lihat.png" border="0" /></a>
<?php 		} else {
				echo "<font style='color:blue'>*</font>";
			} ?>						
			<input type="hidden" name="norekpiutang" id="norekpiutang" value="<?=$rekpiutang ?>" />
        </td>
    </tr>
	<tr>
        <td align="left"><strong>Rek. Diskon</strong></td>
        <td align="left">
			<input type="text" name="rekdiskon" id="rekdiskon" value="<?=$rekdiskon  . " " . $namarekdiskon ?>" readonly style="background-color:#CCCC99" maxlength="100" size="30"  onFocus="panggil('rekdiskon')">&nbsp;
<?php 		if (!$idIsUsed) { ?>						
				<a href="#" onClick="JavaScript:cari_rek(4,'PENDAPATAN')"><img src="images/ico/lihat.png" border="0" /></a>
<?php 		} else {
				echo "<font style='color:blue'>*</font>";
			} ?>						
			<input type="hidden" name="norekdiskon" id="norekdiskon" value="<?=$rekdiskon ?>" />
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">Keterangan</td>
        <td align="left"><textarea name="keterangan" id="keterangan" rows="2" cols="40" onKeyPress="return focusNext('simpan', event)" onFocus="panggil('keterangan')" ><?=$keterangan ?></textarea></td>
    </tr>
	<tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left">
			<input type='checkbox' id='smsinfo' name='smsinfo' <?php if ($smsinfo == 1) echo "checked"; ?> >&nbsp;Notifikasi SMS | Telegram | Jendela Sekolah
		</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
        	<input class="but" type="submit" value="Simpan" name="simpan" id="simpan" onFocus="panggil('simpan')" >
            <input class="but" type="button" value="Tutup" onClick="window.close();"><br>
<?php 		if ($idIsUsed) {
				echo "<font style='color:#666'>* Kode rekening Jenis Penerimaan ini tidak dapat diubah karena telah digunakan dalam transaksi</font>";
			} ?>						
        </td>
    </tr>
    </table>
    </form>
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$mysqli_ERROR_MSG?>');		
</script>
<?php } ?>

</body>
</html>