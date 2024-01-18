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
$Source = $_REQUEST['Source'];
$kls = $_REQUEST['kls'];
$NIS = $_REQUEST['NIS'];
$Nama = $_REQUEST['Nama'];
?>
<link href="style/style.css" rel="stylesheet" type="text/css" />

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
  <tr class="Header">
    <td>No</td>
    <td>NIS/Nama</td>
    <td>No. Ponsel Siswa</td>
    <td>No. Ponsel Ortu</td>
    <td>&nbsp;</td>
  </tr>
  <?php
    if ($Source=='Pilih'){
		$sql = "SELECT * FROM $db_name_akad.siswa WHERE aktif=1 AND idkelas='$kls'";
	} else {
		$sql = "SELECT * FROM $db_name_akad.siswa WHERE replid>0";
		if ($NIS!="")
			$sql .= " AND nis LIKE '%$NIS%'";
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
    <td class="td">(<?=$row['nis']?>) <?=$row['nama']?></td>
    <td class="td"><?=$row['hpsiswa']?></td>
    <td class="td"><?=$row['hportu']?></td>
    <td class="td" align="center">
    <?php if (strlen((string) $row['hpsiswa'])>0 || strlen((string) $row['hportu'])>0){ ?>
    <div align="center" class="BtnSilver90" onclick="InsertNewReceipt2('<?=$row['hpsiswa']?>_<?=$row['hportu']?>','<?=$row['nama']?>_<?=$row['namaayah']?>','<?=$row['nis']?>')"  />Pilih</div>
    <?php } ?>
    </td>
  </tr>
  <?php
        $cnt++;
        }
    } else {
    ?>
  <tr>
    <td colspan="5" class="Ket" align="center">Tidak ada data</td>
  </tr>
  <?php
    }
        ?>
</table>
<?php
CloseDb();
?>