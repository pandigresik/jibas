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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
$waktu=explode("-",(string) $_REQUEST['bulan']);

OpenDb();
$sql = "SELECT k.kelas, DAY(p.tanggal), MONTH(p.tanggal), YEAR(p.tanggal), p.jam, pp.catatan, l.nama, g.nama, p.materi, pp.replid FROM presensipelajaran p, ppsiswa pp, jbssdm.pegawai g, kelas k, pelajaran l WHERE pp.idpp = p.replid AND p.idkelas = k.replid AND p.idpelajaran = l.replid AND p.gurupelajaran = g.nip AND pp.nis = '".$_REQUEST['nis_awal']."' AND MONTH(p.tanggal) =  '".$waktu[0]."' AND YEAR(p.tanggal) =  '".$waktu[1]."' AND pp.statushadir='".$_REQUEST['status']."' AND p.idkelas='".$_REQUEST['kelas']."'";
$result = QueryDb($sql);
//echo $_REQUEST['kelas'];
//echo $_REQUEST['kelas']." ".$_REQUEST['departemen']." ".$_REQUEST['tahunajaran'];
?>    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Detail Presensi Pelajaran</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript">
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td><fieldset>
      <legend>Data Presensi bulan <?=NamaBulan($_REQUEST['bulan'])?> <?=$waktu[1]?></legend>
        <table width="100%" border="1" cellspacing="0" class="tab" id="table">
          <tr>
            <td height="30" class="headerlong"><div align="center">Tgl</div></td>
            <td height="30" class="headerlong"><div align="center">Jam</div></td>
            <td height="30" class="headerlong"><div align="center">Kelas</div></td>
            <td height="30" class="headerlong"><div align="center">Catatan</div></td>
            <td height="30" class="headerlong"><div align="center">Pelajaran</div></td>
            <td height="30" class="headerlong"><div align="center">Guru</div></td>
            <td height="30" class="headerlong"><div align="center">Materi</div></td>
          </tr>
          <?php
		  while ($row=@mysqli_fetch_row($result)){
		  ?>
          <tr>
            <td height="25" bgcolor="#FFFFFF"><div align="center">
              <?=$row[1].'-'.$row[2].'-'.substr((string) $row[3],2,2)?>
            </div></td>
            <td height="25" bgcolor="#FFFFFF"><div align="center">
              <?=substr((string) $row[4],0,5)?>
            </div></td>
            <td height="25" bgcolor="#FFFFFF" align="center"><?=$row[0]?></td>
            <td height="25" bgcolor="#FFFFFF"><?=$row[5]?></td>
            <td height="25" bgcolor="#FFFFFF"><?=$row[6]?></td>
            <td height="25" bgcolor="#FFFFFF"><?=$row[7]?></td>
            <td height="25" bgcolor="#FFFFFF"><?=$row[8]?></td>
          </tr>
          <?php
		  }
		  ?>
      </table>
     	<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
    </fieldset></td>
  </tr>
  <tr><td align="center"><br /><input name="kembali" class="but" type="button" value="Tutup" onClick="window.close()" /></td></tr>
</table>
</body>
</html>