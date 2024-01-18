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
require_once('../library/departemen.php');
require_once('../cek.php');

$departemen = $_REQUEST['departemen'];
$idtingkat = $_REQUEST['idtingkat'];
$idtahunajaran = $_REQUEST['idtahunajaran'];
$idkelas = $_REQUEST['idkelas'];
$jenis = $_REQUEST['jenis'];
$nis = $_REQUEST['nis'];
$nama = $_REQUEST['nama'];

$pilihan = "";	
if (isset($_REQUEST['pilihan']))
	$pilihan = $_REQUEST['pilihan'];	

$input_awal = match ($jenis) {
    'combo' => "onload=\"document.getElementById('idkelas').focus()\"",
    'text' => "onload=\"document.getElementById('nis2').focus()\"",
    default => "onload=\"document.getElementById('idkelas').focus()\"",
};	
								
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pindah Kelas[Menu]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function change_kelas() {	
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;	
	var idkelas = document.getElementById("idkelas").value;	
		
	parent.siswa_pindah_menu.location.href = "siswa_pindah_menu.php?idtahunajaran="+idtahunajaran+"&idtingkat="+idtingkat+"&departemen="+departemen+"&idkelas="+idkelas;
	parent.siswa_pindah_daftar.location.href = "blank_pindah_daftar.php";
}

function cari_siswa_text() {	
	var nis2 = document.getElementById("nis2").value;
	var nama2 = document.getElementById("nama2").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var jenis = "text";
		
	if (nis2.length == 0 && nama2.length == 0){
		alert ("Masukkan NIS atau nama siswa untuk melakukan pencarian!");
		document.getElementById("nis2").focus();
		return false;
	} else {
		
		if ((nis2.length < 3 && nis2.length != 0) || (nama2.length < 3 && nama2.length != 0)){
			alert ('Keyword tidak boleh kurang dari 3 karakter');
			return false;
		}		
	}
	
	parent.siswa_pindah_menu.location.href = "siswa_pindah_menu.php?nis="+nis2+"&jenis="+jenis+"&nama="+nama2+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pilihan=2";
	parent.siswa_pindah_daftar.location.href = "siswa_pindah_daftar.php?nis="+nis2+"&jenis="+jenis+"&nama="+nama2+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran;
}

function cari_siswa_combo() {
	var idkelas = document.getElementById("idkelas").value;
	var departemen = document.getElementById("departemen").value;
	var idtahunajaran = document.getElementById("idtahunajaran").value;
	var idtingkat = document.getElementById("idtingkat").value;
	var jenis = "combo";
	//var warna_combo = "d5fcca";
	
	if (idkelas.length==0){
		alert ('Belum ada kelas yang aktif pada tingkat yang terpilih!');
		document.getElementById('idkelas').focus();
		return false;
	}
	
	document.location.href = "siswa_pindah_menu.php?idkelas="+idkelas+"&jenis="+jenis+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pilihan=1";
	parent.siswa_pindah_daftar.location.href = "siswa_pindah_daftar.php?idkelas="+idkelas+"&jenis="+jenis+"&departemen="+departemen+"&idtingkat="+idtingkat+"&idtahunajaran="+idtahunajaran+"&pilihan=1";
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
<body topmargin="0" leftmargin="0" style="background-color:#EEEEEE" <?=$input_awal?>>
<input type="hidden" name="idtingkat" id="idtingkat" value="<?=$idtingkat?>" />
<input type="hidden" name="jenis" id="jenis" value="<?=$jenis?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="idtahunajaran" id="idtahunajaran" value="<?=$idtahunajaran?>" />

   	<fieldset>
	<legend>Tampilkan daftar siswa berdasarkan</legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr <?php if ($pilihan=="1") { ?> style="background-color:#C0C0C0" <?php } ?>>
    	<td width="10" align="center" valign="middle">
        	<img src="../images/ico/titik.png" width="5" height="5" align="top"/></td>
        <td width="15%">Kelas</td>
        <td width="*">
       	 	<select name="idkelas" id="idkelas" onChange="change_kelas()" style="width:145px;" onKeyPress="return focusNext('tampil', event)">        
			<?php OpenDb();
                $sql = "SELECT replid, kelas, kapasitas FROM kelas where idtingkat='$idtingkat' AND idtahunajaran='$idtahunajaran' AND aktif = 1 ORDER BY kelas";
                $result = QueryDb($sql);
                CloseDb();
                while ($row = @mysqli_fetch_array($result)) {
                    if ($idkelas == "")
                        $idkelas = $row['replid'];
            ?>
                <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $idkelas)?> >
                <?=$row['kelas']?>
                </option>
            <?php } ?>
            </select></td>
     	<td width="50">
        	<input type="button" value="Tampil" id="tampil" class="but" onClick="cari_siswa_combo()" style="width:70px;" onMouseOver="showhint('Tampilkan daftar siswa berdasarkan kelas!', this, event, '135px')"/></td>
    </tr>
    <tr <?php if ($pilihan=="2") { ?> style="background-color:#C0C0C0" <?php } ?>>
      	<td align="center" valign="middle">
        	<img src="../images/ico/titik.png" width="5" height="5" align="top" /></td>
      	<td>Pencarian </td>
        <td><b>NIS</b>&nbsp;&nbsp;<input type="text" name="nis2" id="nis2" size="10" value="<?=$nis?>"  onkeypress="return focusNext('cari', event)" />&nbsp;&nbsp;
        <b>Nama</b>&nbsp;&nbsp;<input type="text" name="nama2" id="nama2" size="16" value="<?=$nama?>"  onkeypress="return focusNext('cari', event)" /></td>
        <td><input type="button" value="Cari" id="cari" class="but" onClick="cari_siswa_text()" style="width:70px;" onmouseover="showhint('Tampilkan daftar siswa berdasarkan pencarian!', this, event, '150px')"/></td>   	
    </tr>
    </table>
    </fieldset>	
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("nama2");
var sprytextfield2 = new Spry.Widget.ValidationTextField("nis2");
var spryselect12 = new Spry.Widget.ValidationSelect("idkelas");
</script>