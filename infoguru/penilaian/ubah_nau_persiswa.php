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
require_once('../include/theme.php');
require_once('../include/db_functions.php');


if(isset($_REQUEST["replid"]))
	$replid = $_REQUEST["replid"];

OpenDb();	
$query = "SELECT s.nama, s.nis, n.nilaiAU FROM jbsakad.nau n , jbsakad.siswa s WHERE n.replid = '$replid' AND n.nis = s.nis";
$result = QueryDb($query);
$row = @mysqli_fetch_array($result);
$nis = $row['nis'];
$nama = $row['nama'];
$nilai = $row['nilaiAU']; 
	
if(isset($_REQUEST["nilai"]))
	$nilai = $_REQUEST["nilai"];	
if(isset($_REQUEST["nasli"]))
	$nasli = $_REQUEST["nasli"];	
if(isset($_REQUEST["alasan"]))
	$alasan = $_REQUEST["alasan"];	

if(isset($_REQUEST["ubah"]))
{
	$pengguna = SI_USER_ID() . " - ". SI_USER_NAME();
	
	$sql = "SELECT p.nama AS pelajaran, ju.jenisujian, u.deskripsi, DATE_FORMAT(u.tanggal, '%d-%m-%Y') AS tanggal, s.nis, s.nama
				 FROM nilaiujian nu, ujian u, pelajaran p, jenisujian ju, siswa s
				WHERE nu.replid = '$replid' AND nu.idujian = u.replid AND u.idpelajaran = p.replid
				  AND u.idjenis = ju.replid AND nu.nis = s.nis;";
	$res2 = QueryDb($sql);
	if (mysqli_num_rows($res2) > 0)
	{
		$row2 = mysqli_fetch_array($res2);
		$info = "Nilai Ujian ".$row2['jenisujian']." ".$row2['pelajaran']." tanggal ".$row2['tanggal']." siswa ".$row2['nis']." ".$row2['nama'];
	}				  
	
	$sql_simpan = "INSERT INTO jbsakad.auditnilai SET jenisnilai='nau', idnilai='$replid', 
						nasli='$nasli', nubah='$nilai', alasan='$alasan', pengguna='$pengguna', informasi='$info'";
   QueryDb($sql_simpan);
	
	$sql_simpan = "UPDATE jbsakad.nau SET nilaiAU='$nilai', keterangan='Nilai Akhir diubah manual' WHERE replid='$replid'";
	$result_simpan = QueryDb($sql_simpan);
	if($result_simpan) 
	{?>
		<script language="javascript">
           	opener.refresh();
            window.close();
        </script>
<?php }
}	

?>
<html>
<head>
<title>JIBAS SIMAKA [Ubah Nilai Akhir Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/validasi.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript">
function cek_form() {
  	var nilai = document.getElementById("nilai").value;
	if(nilai.length == 0) {
		alert("Nilai tidak boleh kosong!");		
		document.getElementById("nilai").value;
		return false;
	} else {
		if (isNaN(nilai)){
			alert ('Nilai Akhir harus berupa bilangan!');		
			document.getElementById("nilai").focus();
			return false;
		}
		if (parseInt(nilai)>100){
			alert ('Rentang Nilai Akhir antara 0 - 100!');			
			document.getElementById("nilai").focus();
			return false;
		}
	}
	
	return validateEmptyText('alasan', 'Alasan Perubahan Nilai');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" onLoad="document.getElementById('nilai').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr>
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
    <form action="ubah_nau_persiswa.php" method="post" name="ubah_nilai_au" onSubmit="return cek_form()">
    <input type="hidden" name="replid" value="<?=$replid ?>">
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
   	<!-- TABLE CONTENT -->
    <tr height="25">
        <td colspan="2" class="header" align="center">Ubah Nilai Akhir Ujian</td>
    </tr> 
        <td><strong>NIS</strong></td>
        <td><input class="disabled" type="text" size="15" name="nis" value="<?=$nis ?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td><input class="disabled" type="text" size="50" name="nama" value="<?=$nama ?>" readonly></td>
    </tr>
   
    <tr>
        <td><strong>Nilai</strong></td>
        <td>
        <input type="hidden" name="nasli" id="nasli" value="<?=$nilai?>">
        <input type="text" name="nilai" id="nilai" size="5" value="<?=$nilai ?>" maxlength="8">
        </td>
    </tr>
    <tr>
        <td><strong>Alasan Perubahan Nilai</strong></td>
        <td><input type="text" name="alasan" id="alasan" size="50" value="<?=$alasan ?>" onKeyPress="return focusNext('ubah',event)"></td>
    </tr>
    <tr>
        <td align="center" colspan="2">          
             <input type="submit" value="Simpan" name="ubah" class="but">
             <input type="button" value="Batal" name="batal" class="but" onClick="window.close();">
        </td>
   	</tr>
    </table>
    </form>
 	<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
</html>
<script type="text/javascript">
	var sprytextfield1 = new Spry.Widget.ValidationTextField("nilai");
</script>