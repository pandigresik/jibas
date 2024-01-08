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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php'); 
require_once('../cek.php');

$mode = $_REQUEST['mode'];
$title = $mode == "new" ? "Tambah" : "Ubah";
if ($mode == "new")
{
	$id = 0;
	$judul = "";
	$pengantar = "";
}
else
{
	$id = $_REQUEST['id'];
	
	OpenDb();
	$sql = "SELECT judul, pengantar
			  FROM jbsumum.lampiransurat
			 WHERE replid = $id";
	$res = QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$judul = $row[0];
	$pengantar = $row[1];
	CloseDb();		 
}

$departemen = $_REQUEST['departemen'];
$status = $_REQUEST['status'];

$_SESSION["uploaddept"] = $departemen;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="penyusunan.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Lampiran Surat]</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script language="javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" src="lampiran.editor.js"></script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" >

<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top" background="../images/ico/b_daftarmutasi.png" style="margin:0;padding:0;background-repeat:no-repeat;">
	<br>
	
    <form name="main" method="post" action="lampiran.editor.save.php" onSubmit="return validate()">
	<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
	<input type="hidden" name="id" id="id" value="<?=$id?>">
	<input type="hidden" name="status" id="status" value="<?=$status?>">
    <table border="0" width="75%" cellpadding="2" cellspacing="2" align="center">
    <tr>
        <td align="right" width="120"><strong>Departemen&nbsp;:</strong></td>
        <td align="left">
            <input class='inputbox' type="text" name="departemen" id="departemen" size="10" maxlength="15"
                   value="<?= $departemen ?>" readonly  class="disabled"  style="background-color: #ddd"/>
        </td>
		<td rowspan='3' align='right' valign='top'>
			<font style='font-size: 20px; color: #666'><?=$title?> Lampiran Surat</font><br>
			<a href="../pelaporanmenu.php" target="content">
            <font size="1" face="Verdana" color="#000000"><b>Pelaporan</b></font>
			</a>&nbsp>&nbsp
			<a href="#" onclick="window.history.go(-1)" target="content">
			<font size="1" face="Verdana" color="#000000"><b>Lampiran Surat</b></font>
			</a>&nbsp>&nbsp
			<font size="1" face="Verdana" color="#000000"><b><?=$title?> Lampiran Surat</b></font>
		</td>
    </tr>
    <tr>
        <td align="right"><strong>Judul&nbsp;:</strong></td>
        <td align="left">
            <input class='inputbox' type="text" name="judul" id="judul" value="<?=$judul?>" size="70" maxlength="255"/>
        </td>
    </tr>
    <tr>
    	<td align="left"><strong>Lampiran Surat:</strong></td>
    	<td>
            &nbsp;
        </td>
    </tr>
    <tr>
    	<td align="left" colspan="3">
			
			<table border='0' cellpadding='5' width='100%'>
			<tr>
				<td width='75%' align='left' valign='top'>
					<textarea name="pengantar" id="pengantar" rows="25"
							  style="font-family: Verdana; font-size: 11px;	width:100%"><?=$pengantar?></textarea>
				</td>
				<td width='25%' align='left' valign='top'>
					<fieldset style='border-color: #8a8a00; background-color: #ffffe6;'>
					<legend><strong>AUTO FORMAT</strong></legend>
					<p style='line-height: 24px'>
					<strong>{NIS}</strong>: Nomor Induk Siswa<br>
					<strong>{NAMA}</strong>: Nama Siswa<br>
					<strong>{PIN}</strong>: PIN Siswa<br>
					<strong>{KELAS}</strong>: Kelas Siswa<br>
					<strong>{DEPARTEMEN}</strong>: Departemen Siswa<br>
					<strong>{TANGGAL}</strong>: Tanggal Sekarang<br>
					<strong>{BULAN}</strong>: Bulan Sekarang<br>
					<strong>{TAHUN}</strong>: Tahun Sekarang<br>
					</p>
					</fieldset>
				</td>
			</tr>	
			</table>
            
        </td>
    </tr>
    <tr>
    	<td colspan="2" align="center">
            <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but"/>&nbsp;
			<input type="button" name="Kembali" id="Kembali" value="Kembali" class="but" onclick="history.go(-1)" />&nbsp;
            <font style='color: blue'>Klik <img src='gambar.jpg'> untuk menambah/mengambil gambar</font>
        </td>
    </tr>
	</table>
	</form>

</td></tr>
</table>     

<?php
if ($mode == "new")
{
?>
<script>
$(function() {
	setTimeout(
		function(){
			tinyMCE.get('pengantar').setContent("<p style='font-family: Verdana; font-size: 12px; line-height: 18px'> </p>");			
		},
		100
	)
});	
</script>
<?php
}
?>
</body>
</html>