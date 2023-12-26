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
require_once("../include/sessionchecker.php");

if (isset($_REQUEST['nip'])){ //0
	$nip=$_REQUEST['nip'];
	OpenDb();
		$sql = "SELECT p.nama from jbssdm.pegawai p WHERE p.nip='$nip' ";    
		$result = QueryDb($sql);
		$cnt = 0;
		if ($row = @mysqli_fetch_array($result)) {
		$nama=$row[0];
		}
		CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Perhitungan Nilai Rapor[Menu]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function caripegawai() {
	parent.perhitungan_rapor_footer.location.href = "../blank2.php";
	newWindow('../library/caripegawai.php?flag=0', 'CariPegawai','600','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(nip, nama, flag) {
	document.getElementById('nip').value = nip;	
	document.getElementById('nipguru').value = nip;	
	document.getElementById('nama').value = nama;	
	parent.perhitungan_rapor_content.location.href = "../guru/perhitungan_rapor_footer.php?nip="+nip;
	//parent.perhitungan_rapor_content.location.href = "../blank2.php";
}

</script></head>
<body class="headerlink">
<br />
<form name="kiri">
<table width="80%" border="0">
  <tr>
    <td><fieldset><legend><strong>Guru</strong></legend>
            <strong>
        <input type="text" name="nip" id="nip" size="10" style="background-color:#CCCCCC" readonly value="<?=$nip ?>" /> <input type="hidden" name="nipguru" id="nipguru" value="<?=$nipguru ?>" /> 
        <input type="text" name="nama" id="nama" size="20" style="background-color:#CCCCCC" readonly value="<?=$nama ?>" />
      </strong> <a href="JavaScript:caripegawai()"><img src="../images/ico/lihat.png" border="0" /></a></fieldset>      </td>
    </tr>
</table>

<?php
OpenDb();
$sql="SELECT DISTINCT pel.departemen FROM pelajaran pel, guru g WHERE g.nip='$nip' AND pel.replid=g.idpelajaran";
$result = QueryDb($sql);
		$cnt = 0;
		while ($row = @mysqli_fetch_array($result)) {
		$departemen=$row[0];
		
		?>
        <strong>Pelajaran Yang Diajar Guru
        <?=$nama?>
		</strong><br />
        <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="80%" align="left" bordercolor="#000000">
        <tr><td class="header" align="center"><?=$departemen?></td></tr>
        <?php 
		$sql2="SELECT pel.nama,pel.departemen,pel.replid FROM pelajaran pel, guru g WHERE g.nip='$nip' AND pel.replid=g.idpelajaran AND pel.departemen='$departemen' GROUP BY pel.nama";
		$result2 = QueryDb($sql2);
		$cnt2 = 0;
		while ($row2 = @mysqli_fetch_array($result2)) {
		$nama_pelajaran=$row2[0];
		?>
		<tr onclick="parent.perhitungan_rapor_content.location.href='perhitungan_rapor_content.php?nip=<?=$nip?>&id_pelajaran=<?=$row2[2]?>&departemen=<?=$departemen?>';"><td align="right"><input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" /><input type="hidden" name="nip" id="nip" value="<?=$nip?>" /><input type="hidden" name="id_pelajaran" id="id_pelajaran" value="<?=$row2[0]?>" /><!--<a href="perhitungan_rapor_content.php?nip=<?=$nip?>&id_pelajaran=<?=$row2[2]?>&departemen=<?=$departemen?>" target="perhitungan_rapor_content">--><?=$nama_pelajaran?><!--</a>--></td></tr>
		<?php
		$cnt2++;
        }
		?>
		</table></form>
		<script language='JavaScript'>
	    Tables('table', 1, 0);
 </script>
        <br />
        <br />
		<?php
        }
		CloseDb();
		?>
<br/>


</body>
 
</html>
<?php
} //0
?>