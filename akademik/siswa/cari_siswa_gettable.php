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
  ?>
  <script language="javascript" src="../script/tables.js"></script>
  <form name="kiri">

<table width="303" border="0" cellspacing="0" class="tab" id="table">
  
  <tr>
    <td class="header">No</td>
    <td class="header">NIS</td>
    <td class="header">Nama</td>
    <td class="header">Kelas</td>
    <td class="header">&nbsp;</td>
  </tr>
  <?php
  if (isset($_REQUEST['cari'])){
	$cari=$_REQUEST['cari'];
	$jenis=$_REQUEST['jenis'];
	$departemen=$_REQUEST['departemen'];
	$urutan=$_REQUEST['urutan'];
	?>
		<input type="hidden" name="departemen" id="departemen" value="<?=$departemen; ?>">
		<input type="hidden" name="cari" id="cari" value="<?=$cari; ?>">
		<input type="hidden" name="jenis" id="jenis" value="<?=$jenis; ?>">
	<?php
    OpenDb();
		$sql_siswa = "SELECT nis,nama,idkelas from jbsakad.siswa WHERE $jenis LIKE '%$cari%' ORDER BY $urutan"; 
		$result_siswa = QueryDb($sql_siswa);
		$cnt_siswa = 0;
		while ($row_siswa = @mysqli_fetch_array($result_siswa)) {
		$nis=$row_siswa['nis'];
		$nama=$row_siswa['nama'];
		$idkelas=$row_siswa['idkelas'];
				$sql_gabung = "SELECT t.replid,t.departemen,k.replid,k.kelas,k.idtingkat from jbsakad.tingkat t,jbsakad.kelas k WHERE k.replid='$idkelas' AND t.replid=k.idtingkat AND t.departemen = '".$departemen."'"; 
				$result_gabung = QueryDb($sql_gabung);
				if ($row_gabung = @mysqli_fetch_row($result_gabung)) {
				$kelas=$row_gabung[3];
				}
		?>
  <tr> 
  	<td><?=$cnt?></td>
    <td><?=$nis?></td>
    <td><?=$nama?></td>
    <td><?=$kelas?></td>
    <td><a href="siswa_cari_detail.php?nis=<?=$nis?>" target="cari_siswa_content" ><img src="../images/ico/lihat.png" alt="Lihat Detail Siswa" border="0"/></a></td>
  </tr>
  <?php
  }
		CloseDb();
} //0
?>
</table></form>