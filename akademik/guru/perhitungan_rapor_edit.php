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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');
require_once('../library/dpupdate.php');

if (isset($_REQUEST['id_tingkat']))
	$id_tingkat = $_REQUEST['id_tingkat'];

if (isset($_REQUEST['nip_guru']))
	$nip_guru = $_REQUEST['nip_guru'];
	
if (isset($_REQUEST['id_pelajaran']))
	$id_pelajaran = $_REQUEST['id_pelajaran'];	

if (isset($_REQUEST['aspek']))
	$aspek = $_REQUEST['aspek'];	

if (isset($_REQUEST['jum']))
	$jum = $_REQUEST['jum'];	

OpenDb();
$sql = "SELECT j.departemen, j.nama, p.nip, p.nama, t.tingkat 
		  FROM guru g, jbssdm.pegawai p, pelajaran j, tingkat t 
		 WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND t.departemen = j.departemen 
		   AND t.replid = '$id_tingkat' AND j.replid = '$id_pelajaran' AND g.nip = '$nip_guru'"; 
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
$pelajaran = $row[1];
$guru = $row[2].' - '.$row[3];
$tingkat = $row[4];

$sql = "SELECT keterangan FROM dasarpenilaian WHERE dasarpenilaian = '".$aspek."'";
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$aspekket = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Aturan Perhitungan Nilai Rapor]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function checkStatus(no)
{
	var cek = document.getElementById('cek'+no).checked;
	var replid = document.getElementById('replid'+no).value;
	
	document.getElementById('isdel'+no).value = 0;
	if (replid != 0 && cek == 0)
		document.getElementById('isdel'+no).value = 1;
		
	if (cek == 0)
		document.getElementById('bobot'+no).value = "";		
}

function validate() 
{
	var isi = 0;
	var jum = document.getElementById('jum').value;	
	for (i = 1; i <= jum; i++) 
	{				
		var cek = document.getElementById('cek'+i).checked;	
		var ujian = document.getElementById('ujian'+i).value;		
		var bobot = document.getElementById('bobot'+i).value;
		
		if (cek == 1) 
		{
			isi = 1;
			if (bobot.length > 0)
			{
				if (isNaN(bobot))
				{
					alert("Bobot nilai harus berupa bilangan");
					document.getElementById('bobot'+i).focus();				
					return false;									
				} 
			} 
			else 
			{
				alert ("Anda harus mengisikan data untuk bobot nilai"); 
				document.getElementById('bobot'+i).focus();				
				return false;
			} 
		}
		
		if (bobot.length > 0) 
		{
			if (cek != 1) 
			{
				alert ("Anda harus memberi centang terlebih dahulu"); 
				document.getElementById('cek'+i).focus();				
				return false;
			}
		}
	}
	
	if (isi == 0) 
	{
		alert ("Anda harus mengisi setidaknya satu data untuk aturan grading");
		document.getElementById('bobot1').focus;
		return false; 
	}
	
	document.getElementById('main').submit(); 
}

function simpan(evt) 
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :	((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) 
	{
		document.getElementById('main').submit();
		return false;
	}
	
	return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('cek1').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:13px; font-weight:bold">
    .: Ubah Aturan Perhitungan Nilai Rapor:.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="425">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" valign="top">
    <!-- CONTENT GOES HERE //--->

<form name="main" id="main" action="perhitungan_rapor_simpan.php" method="POST">
<input type="hidden" name="action" id="action" value="Update" />
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
	<td>
    	<input type="text" name="departemen" id="departemen" size="10" maxlength="50" readonly value="<?=$departemen ?>" class="disabled" />
    	<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />    
	</td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
	<td>
    	<input type="text" name="tingkat" id="tingkat" size="10" maxlength="50" readonly value="<?=$tingkat ?>" class="disabled"/>
        <input type="hidden" name="id_tingkat" id="id_tingkat" value="<?=$id_tingkat ?>" /> 
	
	</td>
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
	<td>
    	<input type="text" name="pelajaran" id="pelajaran" size="30" maxlength="50" readonly value="<?=$pelajaran ?>" class="disabled" />
        <input type="hidden" name="id_pelajaran" id="id_pelajaran" value="<?=$id_pelajaran ?>" /> 
	
	</td>
</tr>
<tr>
    <td><strong>Guru</strong></td>
    <td>
        <input type="text" name="guru" id="guru" size="30" readonly value="<?=$guru ?>" class="disabled" /> 
        <input type="hidden" name="nip_guru" id="nip_guru" value="<?=$nip_guru ?>" /> 
        </td>
</tr>
<tr>
	<td><strong>Status</strong></td>
	<td><input type="text" name="aspekket" id="aspekket" size="30" readonly value="<?=$aspekket ?>" class="disabled"/> 
    	<input type="hidden" name="aspek" id="aspek" value="<?=$aspek ?>" /></strong> 
    	   </td>
</tr>
<tr>
	<td colspan = "2" valign="top">
<fieldset><legend><b>Bobot Penilaian</b></legend>
	<br />
	<table border="0" width="100%"  id="table" class="tab">
		<tr>		
			<td width="3%" height="30" align="center" class="header"></td>
			<td width="3%" height="30" align="center" class="header">No</td>
            <td width="8%" height="30" align="center" class="header">Pengujian</td>			
            <td width="15%" height="30" align="center" class="header">Bobot</td>
		</tr>
		<?php
		$sql = "SELECT replid, jenisujian FROM jenisujian WHERE idpelajaran = '$id_pelajaran'"; 
		$result = QueryDb($sql);
		$num = mysqli_num_rows($result);
		
		$i = 1;
		while ($row = @mysqli_fetch_array($result)) 
		{
			$sql1 = "SELECT a.bobot, j.jenisujian, a.replid 
						  FROM aturannhb a, jenisujian j 
						 WHERE a.idpelajaran = '$id_pelajaran' AND a.nipguru = '$nip_guru' AND a.idtingkat = '$id_tingkat' 
						   AND a.dasarpenilaian = '$aspek' AND a.idjenisujian = '".$row['replid']."' AND a.idjenisujian = j.replid"; 
			$result1 = QueryDb($sql1);
			$row1 = @mysqli_fetch_row($result1);	?>		
		<tr>
        	<td height="25" align="center">
           
<?php 			if ($row1[1]) 
			{ ?>
				<input type="checkbox" name="<?='cek'.$i ?>" id="<?='cek'.$i ?>" value="1" checked onchange="checkStatus(<?=$i?>)" onKeyPress="focusNext('bobot<?=$i?>',event)">
    	        <input type="hidden" name="<?='replid'.$i?>" id="<?='replid'.$i?>" value="<?=$row1[2] ?>">
                <input type="hidden" name="<?='isdel'.$i?>" id="<?='isdel'.$i?>" value="0">
<?php  		} 
			else 
			{ ?>
				<input type="checkbox" name="<?='cek'.$i ?>" id="<?='cek'.$i ?>" value="1"  onchange="checkStatus(<?=$i?>)" onKeyPress="focusNext('bobot<?=$i?>',event)"> 
                <input type="hidden" name="<?='replid'.$i?>" id="<?='replid'.$i?>" value="0">
                <input type="hidden" name="<?='isdel'.$i?>" id="<?='isdel'.$i?>" value="0">
<?php 			} ?>
            </td>
			<td height="25" align="center"><?=$i ?>
			<input type="hidden" name="<?='ujian'.$i?>" id = "<?='ujian'.$i?>" value="<?=$row['replid'] ?>">
            </td>
			<td height="25"><?=$row['jenisujian'] ?></td>			
            <td height="25" align="center">
            <input type="text" name="<?='bobot'.$i ?>" id="<?='bobot'.$i ?>" size="4" maxlength="3" value ="<?=$row1[0]?>"  <?php if ($i!=$num) { ?> onKeyPress="focusNext('cek<?=(int)$i+1?>',event)" <?php } else { ?> onkeypress="focusNext('Simpan',event)" <?php } ?> ></td>
		</tr>
		<?php
			$i++;	
		} 
		CloseDb();	?>
		<input type="hidden" name="jum" id="jum" value="<?=$num ?>" />
		</table>
		</fieldset>
        <script language='JavaScript'>
	    Tables('table', 1, 0);
    	</script>
	</td>
</tr>
<tr>
	<td colspan="2" height="25" width="100%" align="left" valign="top" style="border-width:1px; border-style:dashed; border-color:#03F; background-color:#CFF">
    	<strong>Centang jenis pengujian yang sesuai dengan aspek penilaian yang dipilih.<br>Kemudian berikan bobot nilainya.<br/>
				<font color="#FF0000">Contoh yang salah: Praktek-UTS-25, Pemahaman Konsep-UTS-25 </font><br />
				<font color="Blue">Contoh yang benar: Praktek-UTS Praktek-25, Pemahaman Konsep-UTS Pemahaman Konsep-25</font>
									  </strong>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="button" name="Simpan" id="Simpan" value="Simpan" class="but" onClick="return validate();document.getElementById('main').submit();" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
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
<!--
var jum=document.getElementById("jum").value;
var x=1;
while (x<=jum){
	var sprytextfield = new Spry.Widget.ValidationTextField("bobot"+x);
	x++;
}
//-->
</script>