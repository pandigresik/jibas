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
require_once("../include/theme.php");
require_once('../cek.php');

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$kalender = CQ($_REQUEST['kalender']);
$aktif = $_REQUEST['aktif'];
$filter = "";

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan']))
{
	OpenDb();
	
	$sql_simpan_cek = "SELECT replid
								FROM jbsakad.kalenderakademik
							  WHERE kalender = '$kalender'
							    AND departemen = '".$departemen."'"; 
	$result_simpan_cek = QueryDb($sql_simpan_cek);	
	if (mysqli_num_rows($result_simpan_cek) > 0)
	{
		CloseDb();
		$ERROR_MSG = $kalender . " sudah digunakan!";
	}
	else
	{
		$sql = "SELECT COUNT(replid)
				    FROM jbsakad.kalenderakademik
				   WHERE departemen = '".$departemen."'";
		$res = QueryDb($sql);
		$row = mysqli_fetch_row($res);
		$aktif = (int)$row[0] == 0 ? 1 : 0;
		
		$sql_simpan = "INSERT INTO jbsakad.kalenderakademik
							   SET kalender = '$kalender', idtahunajaran = '$tahunajaran',
									 departemen = '$departemen', aktif = $aktif"; 
		$result_simpan = QueryDb($sql_simpan);
		
		if ($result_simpan)
		{
			$sql1 = "SELECT LAST_INSERT_ID()";
			$result1 = QueryDb($sql1);
			$row1 = mysqli_fetch_row($result1); 
			?>
			<script language="javascript">						
				opener.refresh('<?=$row1[0]?>');				
				window.close();
			</script>
			<?php
		}
	}	
	CloseDb();
}

OpenDb();
$sql = "SELECT idtahunajaran
			 FROM kalenderakademik
			WHERE departemen='$departemen'
			ORDER BY aktif DESC, replid DESC";
$result = QueryDb($sql);
CloseDb();
if (mysqli_num_rows($result) > 0)
{
	$filter = "";
	while ($row = @mysqli_fetch_array($result))
	{
		$filter .= " AND replid <> " . $row['idtahunajaran'];
	}
}
?>
<html>
<head>
<title>JIBAS SIMAKA [Tambah Kalender Akademik]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="text/javascript" src="../script/tables.js"></script>
<script type="text/javascript" language="javascript" src="../script/tools.js"></script>
<script type="text/javascript" language="javascript" src="../script/validasi.js"></script>
<script type="text/javascript" language="javascript">
function validate()
{
	return validateEmptyText('tahunajaran', 'Tahun Ajaran') &&
			 validateEmptyText('kalender', 'Kalender Akademik'); 
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
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('kalender').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Tambah Kalender Akademik :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
	<form name="main" id="main" action="kalender_add.php" onSubmit="return validate()">
    
	<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
    <!-- TABLE CONTENT -->
    <tr>
        <td width="40%"><strong>Departemen</strong></td>
        <td><input type="text" name="dept" size="10" maxlength="50" class="disabled" readonly value="<?=$departemen?>"/>
            <input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
        </td>
    </tr>
    <tr>
        <td><strong>Tahun Ajaran</strong></td>
        <td>  
			<select name="tahunajaran" id="tahunajaran" style="width:200px;" onKeyPress="return focusNext('kalender', event)">
<?php 		OpenDb();
			$sql = "SELECT replid, tahunajaran, aktif
						 FROM tahunajaran
						WHERE departemen='$departemen'
								$filter
						ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result))
			{
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
					
				if ($row['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = ''; ?>
				<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
<?php 		}	?>
			</select>
        </td>
    </tr>
    <tr>
      	<td><strong>Periode</strong></td>
        <td>
        <?php 	
		if ($tahunajaran <> "" ) {
			OpenDb();
			$sql = "SELECT * FROM jbsakad.tahunajaran WHERE replid='$tahunajaran'";
			$result = QueryDb($sql);
			$row = mysqli_fetch_array($result);
			$periode = TglTextLong($row['tglmulai']).' s/d '.TglTextLong($row['tglakhir']);
			$aktif = $row['aktif'];
		} 
		?> 
        <input type="text" name="periode" size="30" value="<?=$periode ?>" readonly class="disabled"/>
        <input type="hidden" name="aktif" id="aktif" value="<?=$aktif ?>" />
        </td>
  	</tr>    
    <tr>
        <td><strong>Kalender Akademik</strong></td>
        <td><input type="text" name="kalender" id="kalender"  size="30" value="<?=$kalender?>" onKeyPress="return focusNext('Simpan', event)"></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="submit" name="Simpan" value="Simpan" class="but" id="Simpan">
            <input type="button" name="Tutup" value="Tutup" class="but" onClick="window.close()">
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
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');		
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
var spryselect1 = new Spry.Widget.ValidationSelect("tahunajaran");
var sprytextfield1 = new Spry.Widget.ValidationTextField("kalender");
</script>