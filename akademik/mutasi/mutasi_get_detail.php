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
$departemen=$_REQUEST['departemen'];
$jenismutasi=$_REQUEST['jenismutasi'];
$show=$_REQUEST['show'];
if ($show==1){
$tahunakhir=$_REQUEST['tahunakhir'];
$tahunawal=$_REQUEST['tahunawal'];
$sql="SELECT m.nis as nis,s.nama as nama,m.tglmutasi as tglmutasi,m.keterangan as keterangan,s.replid as replid FROM mutasisiswa m, siswa s, kelas k, tingkat ti, tahunajaran ta WHERE s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND ta.departemen='$departemen' AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND YEAR(m.tglmutasi)>='$tahunawal' AND YEAR(m.tglmutasi)<='$tahunakhir' AND m.jenismutasi='$jenismutasi' AND m.nis=s.nis";
}
if ($show==2){
$tahun=$_REQUEST['tahun'];
$sql="SELECT m.nis as nis,s.nama as nama,m.tglmutasi as tglmutasi,m.keterangan as keterangan,s.replid as replid FROM mutasisiswa m, siswa s, kelas k, tingkat ti, tahunajaran ta WHERE s.idkelas=k.replid AND k.idtahunajaran=ta.replid AND ta.departemen='$departemen' AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND YEAR(m.tglmutasi)='$tahun' AND m.jenismutasi='$jenismutasi' AND m.nis=s.nis";
}
openDb();
?>
<!--script language="javascript">
function tampil(replid) {
	newWindow('../siswa/siswa_tampil.php?replid='+replid, 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
</script-->
<table width="95%" border="1" class="tab" id="table" align="center">
  <tr>
    <td class="header" height="30">No.</td>
    <td class="header" height="30">NIS</td>
    <td class="header" height="30">Nama</td>
    <td class="header" height="30">Tgl Mutasi</td>
    <td class="header" height="30">Keterangan</td>
    <td class="header" height="30">&nbsp;</td>
  </tr>
  <?php
  $result=QueryDb($sql);
  $cnt=1;
  while ($row=@mysqli_fetch_array($result)){
  ?>
  <tr>
    <td height="25"><?=$cnt?></td>
    <td height="25"><?=$row['nis']?></td>
    <td height="25"><?=$row['nama']?></td>
    <td height="25"><?=LongDateFormat($row['tglmutasi'])?></td>
    <td height="25"><?=$row['keterangan']?></td>
    <td height="25"><img onClick="tampil('<?=$row['replid']?>')" src="../images/ico/lihat.png"></td>
  </tr>
  <?php
  $cnt++;
  }
  ?>
</table>
<script language="javascript">
 	 Tables('table', 1, 0);
function tampil(replid) {
	newWindow('../siswa/siswa_tampil.php?replid='+replid, 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')
</script>