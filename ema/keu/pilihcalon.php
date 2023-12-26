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
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
OpenDb();
$departemen=$_REQUEST['departemen'];
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<form name="frmPilih">
<table width="100%" border="0" cellspacing="2" cellpadding="1">
  <tr>
    <td width="4%" class="tab2">Proses&nbsp;Penerimaan</td>
    <td width="96%">
    <div id="prsInfo">
    <select name="proses" class="cmbfrm" id="proses" onchange="chg_prs()">
	<?php
	$sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE departemen='$departemen' AND aktif=1";
	$result = QueryDb($sql);
	while ($row = @mysqli_fetch_row($result)){
		if ($proses=="")
			$proses = $row[0];	
	?>
	<option value="<?=$row[0]?>"><?=$row[1]?></option>
	<?php
	}
	?>
	</select>
    </div>
    </td>
  </tr>
  <tr>
    <td class="tab2">Kelompok</td>
    <td>
    <div id="klpInfo">
	<?php $sql = "SELECT replid,kelompok FROM kelompokcalonsiswa WHERE idproses='$proses'"; 
	//echo $sql;
	?>
    
	<select name="kelompok" class="cmbfrm" id="kelompok" onchange="chg_klp()">
	<?php
	
	$result = QueryDb($sql);
	while ($row = @mysqli_fetch_row($result)){
		if ($kelompok=="")
			$kelompok = $row[0];	
	?>
	<option value="<?=$row[0]?>"><?=$row[1]?></option>
	<?php
	}
	?>
	</select>
    </div>
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <div id="calInfo">
    <?php if ($kelompok!=""){ ?>
	<table width="100%" border="1" class="tab">
      <tr>
        <td height="25" align="center" class="header">No Pendaftaran</td>
        <td height="25" align="center" class="header">Nama</td>
        <td height="25" align="center" class="header">&nbsp;</td>
      </tr>
      <?php
	  $sql = "SELECT * FROM calonsiswa WHERE idkelompok='$kelompok' ORDER BY nama";
	  $result = QueryDb($sql);
	  $num = @mysqli_num_rows($result);
	  if ($num>0){
	  while ($row = @mysqli_fetch_array($result)){
	  ?>
      <tr>
        <td height="20" align="center"><?=$row['nopendaftaran']?></td>
        <td height="20"><?=$row['nama']?></td>
        <td height="20" align="center"><input type="button" value=" > " class="cmbfrm2" onClick="pilihcalon('<?=$row['replid']?>')" /></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td height="20" colspan="3" align="center" class="nodata">Tidak ada data</td>
      </tr>
	  <?php } ?>
    </table>
	<?php } ?>
    </div>
    </td>
  </tr>
</table>
</form>