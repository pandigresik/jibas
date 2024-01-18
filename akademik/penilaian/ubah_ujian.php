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

OpenDb();
if(isset($_REQUEST["replid"]))//1
	$replid = $_REQUEST["replid"];

$query = "SELECT u.idrpp, u.deskripsi, u.idsemester, u.idpelajaran, u.tanggal, k.idtingkat, u.kode FROM jbsakad.ujian u, jbsakad.kelas k WHERE u.replid = '$replid' AND u.idkelas = k.replid";
$result = QueryDb($query);
$row = @mysqli_fetch_array($result);
$idrpp = $row['idrpp'];
$deskripsi = CQ($row['deskripsi']);
$tingkat = $row['idtingkat'];
$semester = $row['idsemester'];
$pelajaran = $row['idpelajaran'];
$tanggal = format_tgl_blnnmr($row['tanggal']);
$kode = $row['kode'];

if(isset($_REQUEST["deskripsi"]))
	$deskripsi = CQ($_REQUEST["deskripsi"]);
if(isset($_REQUEST["tanggal"]))
	$tanggal = $_REQUEST["tanggal"];
if(isset($_REQUEST["idrpp"]))
	$idrpp = $_REQUEST["idrpp"];
if(isset($_REQUEST["kode"]))
	$kode = $_REQUEST["kode"];

	
//$ERROR_MSG = "";
if(isset($_REQUEST["ubah"])){
	$tanggal=unformat_tgl($_REQUEST["tanggal"]);
	$deskripsi=CQ($_REQUEST["deskripsi"]);
	
	if ($idrpp == "")
		$sql_simpan="UPDATE jbsakad.ujian SET idrpp=NULL, deskripsi='$deskripsi',tanggal='$tanggal', kode = '$kode' WHERE replid='$replid'";
	else
		$sql_simpan="UPDATE jbsakad.ujian SET idrpp='$idrpp', deskripsi='$deskripsi',tanggal='$tanggal', kode = '$kode' WHERE replid='$replid'";
	$result_simpan=QueryDb($sql_simpan);
	if ($result_simpan) {
?>
	<script language="javascript">
        opener.refresh();
        window.close();
    </script>
<?php
	}
}	
?>
<html>
<head>
<title>JIBAS SIMAKA [Ubah Data Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-system.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript">
function cek_form() {	
	return validateEmptyText('deskripsi', 'Deskripsi') && 
			validateEmptyText('tanggal', 'Tanggal');
}

function refresh_rpp(idrpp){
	var replid = document.ubah_ujian.replid.value;
	
	document.location.href="ubah_ujian.php?replid="+replid+"&idrpp="+idrpp;	
}

function get_rpp(tingkat,pelajaran,semester){
	newWindow('rpp_tampil.php?tingkat='+tingkat+'&semester='+semester+'&pelajaran='+pelajaran,'TambahRPP','750','450','resizable=1,scrollbars=1,status=0,toolbar=0');
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('deskripsi').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Data Ujian :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr>
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->


    <form action="ubah_ujian.php" method="post" name="ubah_ujian" onSubmit="return cek_form()">
	<input type="hidden" name="replid" value="<?=$replid ?>">
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
    	<td>Kode</td>
        <td><input type="text" name="kode" id="kode" size="25" value="<?=$kode?>" onKeyPress="return focusNext('idrpp', event);"></td>
    </tr>   
    <tr>
        <td><strong>Tanggal</strong></td>
        <td><input name="tanggal" type="text" class="disabled" id="tanggal" value="<?=$tanggal;?>" size="25" readonly onClick="Calendar.setup()"></td>
        <td width="50%"><img src="../images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '120px')"/></td>
    </tr>
    <tr>
      	<td><strong>RPP</strong></td>
      	<td colspan="2">
        	<select name="idrpp" id="idrpp" style="width:170px;" onkeypress="return focusNext('deskripsi', event)">
            <option value="" <?=IntIsSelected("", $idrpp) ?> >Tanpa RPP</option>
      	<?php $sql_rpp="SELECT * FROM rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran' AND aktif=1 ORDER BY rpp";
      		$result_rpp=QueryDb($sql_rpp);
      		while ($row_rpp=@mysqli_fetch_array($result_rpp)){
				if ($idrpp == "")
					$idrpp = $row_rpp['replid'];
      	?>
      			<option value="<?=$row_rpp['replid'] ?>" <?=IntIsSelected($row_rpp['replid'], $idrpp) ?> ><?=$row_rpp['rpp'] ?>
          		</option>
      	<?php } ?>
     
      		</select>
     		<img src="../images/ico/tambah.png" onClick="get_rpp('<?=$tingkat?>','<?=$pelajaran?>','<?=$semester?>')" onMouseOver="showhint('Tambah RPP!', this, event, '80px')">
      	</td>
	</tr>
    <tr>
        <td><strong>Materi</strong></td>
        <td colspan="2"><input type="text" size="55" name="deskripsi" id="deskripsi" value="<?=$deskripsi ?>" onKeyPress="return focusNext('ubah', event)"></td>
    </tr>
    <tr>
        <td align="center" colspan="3">          
            <input type="submit" value="Simpan" name="ubah" class="but" id="ubah">
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
<!-- Tamplikan error jika ada -->
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
<script type="text/javascript">
  Calendar.setup(
    {
      //inputField  : "tanggalshow","tanggal"
	  inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntanggal"       // ID of the button
    }
   );
   Calendar.setup(
    {
      //inputField  : "tanggalshow","tanggal"
	  inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "tanggal"       // ID of the button
    }
   );
  
</script>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("deskripsi");
</script>