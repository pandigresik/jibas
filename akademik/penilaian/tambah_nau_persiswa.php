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
require_once('../cek.php');

if(isset($_REQUEST["replid"]))//1
	$replid = $_REQUEST["replid"];
$tipe = "otomatis";
if(isset($_REQUEST["tipe"]))
	$tipe = $_REQUEST["tipe"];

OpenDb();

$query = "SELECT s.nama, s.nis, j.jenisujian FROM nau n, siswa s, jenisujian j, ujian u WHERE n.replid = '$replid' AND n.nis = s.nis AND n.idjenis = j.replid AND u.idkelas = n.idkelas AND u.idsemester = n.idsemester AND u.idaturan = n.idaturan";
//$query = "SELECT s.nama, s.nis, j.jenisujian, round(SUM(b.bobot*nu.nilaiujian)/SUM(b.bobot),2) as nilai FROM nau n, siswa s, jenisujian j, ujian u, nilaiujian nu, bobotnau b WHERE n.replid = $replid AND n.nis = s.nis AND n.idjenis = j.replid AND b.idujian = u.replid AND u.idkelas = n.idkelas AND u.idsemester = n.idsemester AND u.idaturan = n.idaturan AND nu.idujian = u.replid AND nu.nis = n.nis GROUP BY nu.nis";

$result = QueryDb($query);
$row = @mysqli_fetch_array($result);
$nis = $row['nis'];
$nama = $row['nama'];
$jenis = $row['jenisujian'];

if ($tipe == "otomatis") {
	$sql  = "SELECT round(SUM(b.bobot*nu.nilaiujian)/SUM(b.bobot),2) as nilai FROM nau n, ujian u, bobotnau b, nilaiujian nu WHERE n.replid = '$replid' AND b.idujian = u.replid AND u.idkelas = n.idkelas AND u.idsemester = n.idsemester AND u.idaturan = n.idaturan AND nu.idujian = u.replid AND nu.nis = '$nis' AND n.nis = '".$nis."'";
	$result1 = QueryDb($sql);
	$row1 = @mysqli_fetch_array($result1);
	$nilai = 0;
	if ($row1['nilai']) 
		$nilai = $row1['nilai'];	
	$aktif = "class='disabled'";
	$ket = "";	
} else {
	$nilai = "";
	$aktif = "";
	$ket = ", keterangan = 'Manual'";	
}	
	
if(isset($_REQUEST["simpan"])) {
	$query = "UPDATE jbsakad.nau SET nilaiAU = '".$_REQUEST['nilai']."' $ket WHERE replid = '$replid' ";
   	$result=QueryDb($query);
	
	if ($result) {
	?>
		<script language = "javascript" type = "text/javascript">			
			opener.refresh();
			window.close();
		</script>
	<?php 	
    }	
}
?>

<html>
<head>
<title>JIBAS SIMAKA [Input Data Nilai Akhir Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/validasi.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript">
function ambil(tipe) {	
	if (tipe == "manual") {
		document.getElementById("nilai").value = "";
		document.getElementById("nilai").focus();
	}
	document.location.href = "tambah_nau_persiswa.php?replid=<?=$replid?>&tipe="+tipe;
}

function cek_form() {
  	var nilai = document.getElementById("nilai").value;
	
	if (nilai.length == 0) {
		alert ('Anda harus mengisikan data untuk Nilai Akhir!');			
		document.getElementById("nilai").focus();
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
	return true;
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" <?php if ($tipe == 'manual') {?> onLoad="document.getElementById('nilai').focus()" <?php } ?> >
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Input Nilai Akhir Ujian :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="360" valign="top">
    <!-- CONTENT GOES HERE //--->
    <form method="post" name="main" onSubmit="return cek_form()">	
    <input type="hidden" name="replid" value="<?=$replid?>">    
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
   	<!-- TABLE CONTENT -->
    <tr>
        <td><strong>NIS</strong></td>
        <td><input class="disabled" type="text" size="15" name="nis" value="<?=$nis ?>" readonly></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td><input class="disabled" type="text" size="33" name="nama" value="<?=$nama?>" readonly></td>
    </tr>
    <tr>        
       	<td><strong>Nilai Akhir</strong>
        <td>
           	<input type="text" name="nilai" id="nilai" size="5" value="<?=$nilai?>" maxlength="5" onKeyPress="return focusNext('simpan',event)" <?=$aktif ?> >
       	</td>
    </tr>
    <tr>
        <td colspan="2">
        <fieldset><legend><strong>Hitung Nilai Akhir <?=$jenis?> Berdasarkan </strong></legend>    
        <table width="100%" border="0">
        <tr>
            <td width="5%"><input type="radio" name="tipe" id="tipe" value="otomatis" onClick="ambil('otomatis')" <?php if ($tipe == 'otomatis') echo "checked"; ?> >
            </td>
            <td><strong>Perhitungan Otomatis</strong></td>
            <!--<td width="40%"><strong>B. Perhitungan Manual</strong></td>-->
        </tr>
        <tr>
    		<td></td>
            <td>
    		<table id="table" class="tab" width="100%" border="1">
			<tr height="30" class="header" align="center">				
                <td width="85%"><?=$jenis?></td>
				<td width="15%">Bobot</td>
			</tr>
     	<?php
			
			$sql="SELECT b.replid, b.bobot, u.tanggal FROM bobotnau b, ujian u, nau n WHERE b.idujian=u.replid AND u.idkelas=n.idkelas AND u.idsemester=n.idsemester AND u.idaturan=n.idaturan AND n.replid = '$replid'  ORDER by u.tanggal ASC";								
			$result=QueryDb($sql);
			$cnt = 0;
			while ($row=@mysqli_fetch_array($result)){										
		?>
    		<tr>
				<td width="85%" height="25">
                <?=$jenis."-".++$cnt." (".format_tgl($row['tanggal']).")"; ?>
                </td>
                <td align="center">
                <?=$row['bobot'];?>
                </td>
            </tr>
		<?php }	?>
            </table>
            <script language='JavaScript'>
                Tables('table', 1, 0);
            </script>
        	</td>
    		
  		</tr>
        <tr>
        	<td><input type="radio" name="tipe" id="tipe" value="manual" onClick="ambil('manual')" <?php if ($tipe == 'manual') echo "checked"; ?>>
            </td>
            <td><strong>Perhitungan Manual</strong></td>
        </tr>
       
		</table>
    	</fieldset>
        </td>
    </tr> 
    
    <tr>
        <td align="center" colspan="2">
            <input type="submit" value="Simpan" name="simpan" id="simpan" class="but">
            <input type="button" value="Tutup" name="batal" class="but" onClick="window.close();">
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