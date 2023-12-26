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
require_once('../include/theme.php');
require_once("../include/sessioninfo.php");

$id = $_REQUEST['id'];

$tgl = $_REQUEST['cbTgl'];
$bln = $_REQUEST['cbBln'];
$thn = $_REQUEST['txThn'];
$nip = $_REQUEST['txNIP'];
$nama = $_REQUEST['txNama'];
$jenis = $_REQUEST['txJenis'];
$ket = $_REQUEST['txKeterangan'];

if (isset($_REQUEST['btSubmit'])) {
	$d = $_REQUEST['cbTgl'];
	$m = $_REQUEST['cbBln'];
	$y = $_REQUEST['txThn'];
	$tanggal = "$y-$m-$d";
	
	$nip = $_REQUEST['txNIP'];
	$jenis = $_REQUEST['cbAgenda'];
	$ket = $_REQUEST['txKeterangan'];
	
	OpenDb();
	$sql = "UPDATE jadwal SET nip='$nip',tanggal='$tanggal',jenis='$jenis',keterangan='$ket',aktif=1 WHERE replid=$id";
	QueryDb($sql);
	CloseDb(); ?>	
    <script language="javascript">
		opener.RefreshAllAgenda();
		window.close();
    </script> <?php
	exit();
} else {
	$sql = "SELECT j.nip, p.gelarawal, p.nama, p.gelarakhir, j.tanggal, j.keterangan, j.jenis FROM jadwal j, pegawai p WHERE j.nip = p.nip AND j.replid = $id";
	OpenDb();
	$result = QueryDb($sql);
	$row = mysqli_fetch_array($result);
	$tgl = GetDatePart($row['tanggal'], "d");
	$bln = GetDatePart($row['tanggal'], "m");
	$thn = GetDatePart($row['tanggal'], "y");
	$nip = $row['nip'];
	$nama = $row['gelarawal'] . " " . $row['nama'] . " " . $row['gelarakhir'];
	$jenis = $row['jenis'];
	$ket = $row['keterangan'];
	CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ubah Agenda Kepegawaian</title>
<link rel="stylesheet" href="../style/style<?=GetThemeDir2()?>.css" />
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function CariPegawai() {
	var addr = "pilihpegawai.php";
    newWindow(addr, 'PilihPegawai','550','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function AcceptPegawai(nip, nama) {
	document.getElementById('txNIP').value = nip;
	document.getElementById('txNama').value = nama;
}

function validate() {
	return validateEmptyText('txThn', 'Tanggal Jadwal Agenda') && 
  		   validateInteger('txThn', 'Bulan Jadwal Agenda') && 
		   validateLength('txThn', 'Tahun Jadwal Agenda', 4) && 
		   validateEmptyText('txNIP', 'NIP Pegawai') &&
		   validateEmptyText('txKeterangan', 'Keterangan Agenda') &&
		   confirm("Data sudah benar?");
}
</script>
</head>

<body>
<form name="main" method="post" onSubmit="return validate()">
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="tgl" id="tgl" value="<?=$tgl?>" />
<input type="hidden" name="bln" id="bln" value="<?=$bln?>" />
<input type="hidden" name="thn" id="thn" value="<?=$thn?>" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr height="30">
	<td width="100%" class="header" align="center">Ubah Agenda Kepegawaian</td>
</tr>
<tr><td width="100%" align="center">
    
    <table border="0" cellpadding="3" cellspacing="2" width="100%">
    <tr>
        <td align="right" width="90" valign="top"><strong>Tanggal :</strong></td>
        <td width="*" align="left" valign="top">
       	 <select id="cbTgl" name="cbTgl" onKeyPress="return focusNext('cbBln', event)">
 		   <?php for ($i = 1; $i <= 31; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $tgl)?>><?=$i?></option>	
		    <?php } ?>    
        </select>
        <select id="cbBln" name="cbBln" onKeyPress="return focusNext('txThn', event)">
		    <?php for ($i = 1; $i <= 12; $i++) { ?>    
            <option value="<?=$i?>" <?=IntIsSelected($i, $bln)?>><?=NamaBulan($i)?></option>	
		    <?php } ?>    
        </select>
        <input type="text" name="txThn" onKeyPress="return focusNext('txKeterangan', event)" id="txThn" size="4" maxlength="4" value="<?=$thn?>"/>       </td>
	</tr>
    <tr>
        <td align="right" valign="top"><strong>Pegawai :</strong></td>
        <td width="*" align="left" valign="top">
        	<input type="text" name="txNIP" id="txNIP" size="10" readonly="readonly" style="background-color:#CCCCCC" value="<?=$nip?>" />
            <input type="text" name="txNama" id="txNama" size="30" readonly="readonly" style="background-color:#CCCCCC" value="<?=$nama?>" />
            <input type="button" class="but" name="txCariPegawai" value="..." onClick="JavaScript:CariPegawai()" />
        </td>
	</tr>
    <tr>
        <td align="right" valign="top"><strong>Agenda :</strong></td>
        <td width="*" align="left" valign="top">
        	<select name="cbAgenda" id="cbAgenda">
<?php 		OpenDb();
			$sql = "SELECT nama, agenda FROM jenisagenda ORDER BY urutan";            
			$result = QueryDb($sql);
			while ($row = mysqli_fetch_row($result)) { ?>
            	<option value="<?=$row[1]?>" <?=StringIsSelected($row[1], $jenis)?>><?=$row[0]?></option>
<?php 		} 
			CloseDb(); ?>      
            </select>
        </td>
	</tr>
    <tr>
        <td align="right" valign="top"><strong>Keterangan :</strong></td>
        <td width="*" align="left" valign="top">
        	<textarea id="txKeterangan" name="txKeterangan" rows="3" cols="50"><?=$ket?></textarea>
        </td>
	</tr>
    <tr>
    	<td colspan="2" align="center">
        	<input type="submit" name="btSubmit" id="btSubmit" class="but" value="Simpan" />&nbsp;
            <input type="button" class="but" value="Tutup" onClick="window.close()"/>
        </td>
    </tr>
    </table>
    
</td></tr>
</table>
</form>

</body>
</html>