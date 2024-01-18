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
require_once("../include/theme.php"); 
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../library/dpupdate.php');
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['nip']))
	$nip = $_REQUEST['nip'];
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
	
$aspek = "";
if (isset($_REQUEST['aspek']))
	$aspek = $_REQUEST['aspek'];
$aturan = "";
if (isset($_REQUEST['aturan']))
	$aturan = $_REQUEST['aturan'];
$fokus = "aspek";
if (isset($_REQUEST['fokus']))
	$fokus = $_REQUEST['fokus'];	

$ERROR_MSG = "<br><br>";

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Cetak Form Nilai Akhir]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript">

function validate(){	
	var aspek=document.getElementById("aspek").value;
	var aturan=document.getElementById("aturan").value;
	var semester=document.getElementById("semester").value;
	var pelajaran=document.getElementById("pelajaran").value;
	var kelas=document.getElementById("kelas").value;
	var tingkat=document.getElementById("tingkat").value;
	var nip=document.getElementById("nip").value;
	newWindow('form_akhir_cetak.php?idaturan='+aturan+'&semester='+semester+'&kelas='+kelas+'&nip='+nip, 'CetakFormPengisianNilaiAkhirSiswa',790,850,'resizable=1,scrollbars=1,status=0,toolbar=0');
	window.close();	
	//document.location.href = "form_akhir_cetak_verifikasi.php?aspek="+aspek+"&kelas="+kelas+"&semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&nip="+nip+"&aturan="+aturan+"&cetak=1";	
}

function change_aspek(){
	var aspek=document.getElementById("aspek").value;
	var semester=document.getElementById("semester").value;
	var pelajaran=document.getElementById("pelajaran").value;
	var kelas=document.getElementById("kelas").value;
	var tingkat=document.getElementById("tingkat").value;
	var nip=document.getElementById("nip").value;
	
	document.location.href = "form_akhir_cetak_verifikasi.php?aspek="+aspek+"&kelas="+kelas+"&semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&nip="+nip+'&fokus=aspek';
	
}

function change_aturan(){
	var aspek=document.getElementById("aspek").value;
	var semester=document.getElementById("semester").value;
	var pelajaran=document.getElementById("pelajaran").value;
	var kelas=document.getElementById("kelas").value;
	var tingkat=document.getElementById("tingkat").value;
	var nip=document.getElementById("nip").value;
	var aturan=document.getElementById("aturan").value;
	
	document.location.href = "form_akhir_cetak_verifikasi.php?aspek="+aspek+"&kelas="+kelas+"&semester="+semester+"&pelajaran="+pelajaran+"&tingkat="+tingkat+"&nip="+nip+'&aturan='+aturan+'&fokus=aturan';
	
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
<style type="text/css">
<!--
.style2 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#fff" onLoad="document.getElementById('<?=$fokus?>').focus();">
    <!-- CONTENT GOES HERE //--->
    <form name="main" method="post">  
    <input type="hidden" name="semester" id="semester" value="<?=$semester?>">
    <input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
    <input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
    <input type="hidden" name="pelajaran" id="pelajaran" value="<?=$pelajaran?>">
	<input type="hidden" name="nip" id="nip" value="<?=$nip?>">
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
	<tr height="25">
        <td colspan="2" class="header" align="center">Cetak Form Nilai Akhir Berdasarkan Jenis Pengujian</td>
    </tr>
    <tr>
    	<td><strong>Aspek Penilaian</strong></td>
    	<td><select name="aspek" id="aspek" onChange="change_aspek()" style="width:150px" onKeyPress="return focusNext('aturan', event)">
			<?php 
			$sql_daspen="SELECT DISTINCT a.dasarpenilaian, dp.keterangan
						   FROM jbsakad.aturannhb a, dasarpenilaian dp 
						  WHERE a.dasarpenilaian = dp.dasarpenilaian AND a.idtingkat = '$tingkat' 
						    AND a.idpelajaran = '$pelajaran' AND a.aktif = 1 
							AND a.nipguru = '$nip' 
					   ORDER BY	keterangan";
			$result_daspen = QueryDb($sql_daspen);
			$num_daspen = @mysqli_num_rows($result_daspen);
			while ($row_daspen=@mysqli_fetch_array($result_daspen))
			{
				if ($aspek=="")
					$aspek=$row_daspen['dasarpenilaian']; ?>	
				<option value="<?=$row_daspen['dasarpenilaian']?>" <?=StringIsSelected($row_daspen['dasarpenilaian'], $aspek) ?>><?=$row_daspen['keterangan']?></option>
		<?php } ?>
  		    </select>
  		</td>
  	</tr>
<?php if ($num_daspen>0)
	{	?>
  	<tr>
    	<td><strong>Jenis Pengujian</strong></td>
    	<td>
        	<select name="aturan" id="aturan" style="width:150px" onKeyPress="return focusNext('cetak', event)" onChange="change_aturan()">
			<?php
			$sql_jenispengujian = 
				"SELECT a.replid, j.jenisujian 
				   FROM jbsakad.aturannhb a, jbsakad.jenisujian j 
				  WHERE a.idtingkat='$tingkat' AND a.idpelajaran='$pelajaran' AND a.aktif=1 
				    AND a.dasarpenilaian='$aspek' AND nipguru='$nip' AND j.replid = a.idjenisujian
 		       ORDER BY jenisujian";
			$result_jenispengujian=QueryDb($sql_jenispengujian);
			$num_jenispengujian=@mysqli_num_rows($result_jenispengujian);
			while ($row_jenispengujian=@mysqli_fetch_array($result_jenispengujian))
			{
				if ($aturan=="")
					$aturan=$row_jenispengujian['replid'];	?>
    		    <option value="<?=urlencode((string) $row_jenispengujian['replid'])?>" <?=IntIsSelected($row_jenispengujian['replid'], $aturan) ?>><?=$row_jenispengujian['jenisujian']?>
   		        </option>
		<?php  } ?>
  		    </select>
 		</td>
	</tr>
	<?php 	if ($num_jenispengujian!=0)
        { 
            $sql = "SELECT * FROM ujian 
                    WHERE idkelas = '$kelas' AND idaturan = '$aturan' AND idsemester = '".$semester."'";
            $result = QueryDb($sql);
                
            if (mysqli_num_rows($result) > 0) 
            {	
                $but = "<input class=\"but\" type=\"button\" name=\"cetak\" id=\"cetak\" value=\"Cetak\" onClick=\"validate();\"  />";
            } else {
                $ERROR_MSG = "Belum ada data ujian pada aspek penilaian dan jenis pengujian ini!";		
            }
        } 
		else 
		{
            $ERROR_MSG = "Belum ada aturan penilaian yang tersimpan!";
        }
	} 
	else 
	{ 
		$ERROR_MSG = "Belum ada aspek penilaian yang tersimpan!"; ?>
<?php 	} ?>
  <tr><td colspan="2" align="center">
  <?=$but?>&nbsp;&nbsp;<input class="but" type="button" name="tutup" value="Tutup" onClick="window.close()" />
  </td></tr>
<?php if (strlen($ERROR_MSG) > 0) 
	{ ?>
    <tr>
	<td colspan="2" align="center">
    	<span class="style2"><?=$ERROR_MSG?></span>    
    </td>
	</tr>
<?php  } ?>

</table>
	</form>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("aspek");
var spryselect2 = new Spry.Widget.ValidationSelect("jenis");
</script>
<?php
CloseDb();
?>