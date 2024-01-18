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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/sessionchecker.php');

$sender=$_REQUEST['sender'];
if ($sender=="tambah")
{
 OpenDb();
 $judul=CQ($_REQUEST['judul']);
 $tgl=explode("-",(string) $_REQUEST['tanggal']);
 $tanggal=$tgl[2]."-".$tgl[1]."-".$tgl[0];
 $jam=date('H').":".date('i').":00";
 $abstrak=CQ($_REQUEST['abstrak']);
 $isi=$_REQUEST['isi'];
 $isi = str_replace("'", "#sq;", (string) $isi);
 $idguru=SI_USER_ID();
 $sql1="INSERT INTO jbsvcr.beritasiswa
			 SET judul='$judul', tanggal='".$tanggal." ".$jam."',
				 abstrak='$abstrak', isi='$isi', idguru='$idguru'";
 $result1=QueryDb($sql1);
 CloseDb();
?>
<script language="javascript">
	parent.beritasiswa_header.lihat();
</script>
<?php
}
elseif ($sender=="ubah")
{
	OpenDb();
	$page=(int)$_REQUEST['page'];
	$bulan=$_REQUEST['bulan'];
	$tahun=$_REQUEST['tahun'];
	
	$judul=CQ($_REQUEST['judul']);
	$tgl=explode("-",(string) $_REQUEST['tanggal']);
	$tanggal=$tgl[2]."-".$tgl[1]."-".$tgl[0];
	$abstrak=CQ($_REQUEST['abstrak']);
	$isi=$_REQUEST['isi'];
	$isi = str_replace("'", "#sq;", (string) $isi);
	$idguru=SI_USER_ID();
	$replid=$_REQUEST['replid'];
	
	$sql18="UPDATE jbsvcr.beritasiswa
			   SET judul='$judul', tanggal='$tanggal',
				   abstrak='$abstrak', isi='$isi', idguru='$idguru'
			 WHERE replid='$replid'";
	$result18=QueryDb($sql18);
	CloseDb();
?>
<script language="javascript">
document.location.href="beritasiswa_footer.php?page=<?=$page?>&tahun=<?=$tahun?>&bulan=<?=$bulan?>";
</script>
<?php } ?>