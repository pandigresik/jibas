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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');

OpenDb();
$bag = $_REQUEST['Bagian'];
if ($bag=="")
	$bag='-1';
$NIP 	= $_REQUEST['NIP'];
$Nama 	= $_REQUEST['Nama'];
$Source = $_REQUEST['Source'];
?>
<link href="style/style.css" rel="stylesheet" type="text/css" />
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
  <tr class="Header">
    <td>No</td>
    <td>NIP/Nama</td>
    <td>No. Ponsel</td>
    <td>&nbsp;</td>
  </tr>
  <?php
    if ($Source=='Pilih'){
		if ($bag=='-1')
			$sql = "SELECT * FROM $db_name_sdm.pegawai";
		else
			$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE bagian='$bag'";
	} else {
		$sql = "SELECT * FROM $db_name_sdm.pegawai WHERE replid>0";
		if ($NIP!="")
			$sql .= " AND nip LIKE '%$NIP%'";
		if ($Nama!="")
			$sql .= " AND nama LIKE '%$Nama%'";	
	}
	$res = QueryDb($sql);
    $num = @mysqli_num_rows($res);
    if ($num>0){
        $cnt=1;
        while ($row = @mysqli_fetch_array($res)){
  ?>
  <tr>
    <td align="center" class="td"><?=$cnt?></td>
    <td class="td">(<?=$row['nip']?>) <?=$row['nama']?></td>
    <td class="td"><?=$row['handphone']?></td>
    <td class="td" align="center">
    <?php if (strlen((string) $row['handphone'])>0){ ?>
    <span style="cursor:pointer" class="Link" onclick="InsertNewReceipt('<?=$row['handphone']?>','<?=$row['nama']?>','<?=$row['nip']?>')" align="center"  />Pilih</span>
    <?php } ?>
    </td>
  </tr>
  <?php
        $cnt++;
        }
    } else {
    ?>
  <tr>
    <td colspan="4" class="Ket" align="center">Tidak ada data</td>
  </tr>
  <?php
    }
        ?>
</table>
<?php
CloseDb();
?>