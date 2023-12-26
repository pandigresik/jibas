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
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");
require_once('../include/theme.php');

OpenDb();

$nip = $_REQUEST['nip'];

$sql = "SELECT * FROM pegawai WHERE nip='$nip'";

$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

$nama = $row['nama'];
$gelarawal = $row['gelarawal'];
$gelarakhir = $row['gelarakhir'];
$pegawai = $nama;
$nip = $row['nip'];
$nuptk = $row['nuptk'];
$nrp = $row['nrp'];
$tmplahir = $row['tmplahir'];
$tgllahir = GetDatePart($row['tgllahir'], "d");
$blnlahir = GetDatePart($row['tgllahir'], "m");
$thnlahir = GetDatePart($row['tgllahir'], "y");
$agama = $row['agama'];
$suku = $row['suku'];
$alamat = $row['alamat'];
$hp = $row['handphone'];
$foto = $row['foto'];
$telpon = $row['telpon'];
$email = $row['email'];
$facebook = $row['facebook'];
$twitter = $row['twitter'];
$website = $row['website'];
$status = $row['status'];
$bagian = $row['bagian'];
$tglmulai = GetDatePart($row['mulaikerja'], "d");
$blnmulai = GetDatePart($row['mulaikerja'], "m");
$thnmulai = GetDatePart($row['mulaikerja'], "y");
$keterangan = $row['keterangan'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style<?=GetThemeDir2()?>.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS Kepegawaian</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left"> 
<tr><td align="left" valign="top"><?php include("../include/headercetak.php") ?>
<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Data Pribadi</font><br />
    </td>
</tr>
<tr><td>

<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<td align="right" valign="top"><strong>Status :</strong></td>
    <td width="*" colspan="2" align="left" valign="top"><?=$status?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Bagian :</strong></td>
    <td width="*" colspan="2" align="left" valign="top"><?=$bagian?></td>
</tr>
<tr>
	<td width="140" align="right" valign="top"><strong>Nama </strong>:</td>
    <td width="0" align="left" valign="top"><?=$gelarawal . " " . $nama . " " . $gelarakhir?></td>
    <td width="113" rowspan="5" align="center" valign="top" ><img src="../include/gambar.php?nip=<?=$nip?>&table=pegawai&field=foto" height="120" alt="Foto" /></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>NIP </strong>:</td>
    <td width="0" align="left" valign="top"><?=$nip?></td>
</tr>
<tr>
	<td align="right" valign="top">NUPTK :</td>
    <td width="0" align="left" valign="top"><?=$nuptk?></td>
</tr>
<tr>
	<td align="right" valign="top">NRP :</td>
    <td width="0" align="left" valign="top"><?=$nrp?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Tempat, Tgl Lahir </strong>:</td>
    <td width="0" align="left" valign="top">
    <?=$tmplahir?>, <?=$tgllahir?> <?= NamaBulan($blnlahir)?> <?=$thnlahir?>    </td>
</tr>
<tr>
	<td align="right" valign="top">Agama :</td>
    <td width="0" align="left" valign="top"><?=$agama?></td>
</tr>
<tr>
	<td align="right" valign="top">Suku :</td>
    <td width="0" align="left" valign="top"><?=$suku?></td>
</tr>
<tr>
	<td align="right" valign="top">Alamat :</td>
    <td width="0" align="left" valign="top"><?=$alamat?></td>
    </tr>
<tr>
	<td align="right" valign="top">HP :</td>
    <td width="0" align="left" valign="top"><?=$hp?></td>
</tr>
<tr>
	<td align="right" valign="top">Telpon :</td>
    <td width="0" align="left" valign="top"><?=$telpon?></td>
</tr>
<tr>
	<td align="right" valign="top">Email :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$email?></td>
</tr>
<tr>
	<td align="right" valign="top">Facebook :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$facebook?></td>
</tr>
<tr>
	<td align="right" valign="top">Twitter :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$twitter?></td>
</tr>
<tr>
	<td align="right" valign="top">Website :</td>
    <td width="*" colspan="2" align="left" valign="top"><?=$website?></td>
</tr>
<tr>
	<td align="right" valign="top"><strong>Mulai Kerja :</strong></td>
    <td width="*" colspan="2" align="left" valign="top">
	<?=$tglmulai?> <?=NamaBulan($blnmulai)?> <?=$thnmulai?>    </td>
</tr>
<tr>
	<td align="right" valign="top">Keterangan :</td>
    <td width="*" colspan="2" align="left" valign="top">
	<?=$keterangan?>    </td>
</tr>
    <?php
    $sql = "SELECT ds.replid, ds.idtambahan, td.kolom, ds.jenis, ds.teks, ds.filename 
              FROM jbssdm.tambahandatapegawai ds, jbssdm.tambahandata td
             WHERE ds.idtambahan = td.replid
               AND ds.nip = '$nip'
             ORDER BY td.urutan   ";
    $res = QueryDb($sql);
    $ntambahandata = mysqli_num_rows($res);

    if ($ntambahandata > 0)
    {
        $first = true;
        while($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $replid = $row['replid'];
            $kolom = $row['kolom'];
            $jenis = $row['jenis'];

            if ($jenis == 1 || $jenis == 3)
            {
                $data = $row['teks'];
            }
            else
            {
                $filename = $row['filename'];
                $data = $filename;
            }

            $rowspan = "";
            if ($first)
            {
                $rowspan = "<td rowspan='$ntambahandata' bgcolor='#FFFFFF'></td>";
                $first = false;
            }
            ?>
            <tr>
                <td align="right" valign="top"><?=$kolom?> :</td>
                <td width="*" colspan="2" align="left" valign="top">
                    <?=$data?>
                </td>
            </tr>
        <?php  }
    }
    ?>
</table>

<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Pensiun</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab" id="table">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="35%" align="center" class="header">Jadwal Pensiun</td>
	<td width="45%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT replid, DATE_FORMAT(tanggal,'%d %M %Y') AS ftmt, keterangan FROM jadwal WHERE nip='$nip' AND jenis='pensiun'";
$result = QueryDb($sql);
if (mysqli_num_rows($result) > 0) {
	$cnt = 0;
	while ($row = mysqli_fetch_array($result)) { ?>
	<tr height="25">
		<td align="center"><?=++$cnt?></td>
	    <td align="center"><?=$row['ftmt']?></td>
	    <td align="left"><?=$row['keterangan']?></td>
	</tr>
<?php } // while 
} else { ?>
	<tr height="80">
    	<td colspan="3" align="center" valign="middle">
            <font color="#999999"><strong>Belum ada jadwal pensiun pegawai ini.</font>
        </td>
    </tr>
<?php 
} // end if
?>
</table>

</td></tr>
</table>

<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Golongan</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="15%" align="center" class="header">Golongan</td>
    <td width="18%" align="center" class="header">TMT</td>
    <td width="20%" align="center" class="header">SK</td>
    <td width="25%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT replid, golongan, terakhir, DATE_FORMAT(tmt,'%d %M %Y') AS ftmt, sk, keterangan FROM peggol WHERE nip = '$nip' ORDER BY tmt DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="center"><?=$row['golongan']?></td>
    <td align="center"><?=$row['ftmt']?></td>
    <td align="left"><?=$row['sk']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>
<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Jabatan</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="25%" align="center" class="header">Jabatan</td>
    <td width="18%" align="center" class="header">TMT</td>
    <td width="20%" align="center" class="header">SK</td>
    <td width="15%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT p.replid, p.jenis, p.namajab, DATE_FORMAT(p.tmt,'%d %M %Y') AS ftmt, p.sk, p.keterangan, p.terakhir FROM pegjab p WHERE p.nip = '$nip'  ORDER BY tmt DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left"><?=$row['jenis'] . " " . $row['namajab']?></td>
    <td align="center"><?=$row['ftmt']?></td>
    <td align="left"><?=$row['sk']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>
<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Diklat</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="25%" align="center" class="header">Diklat</td>
    <td width="5%" align="center" class="header">Tahun</td>
    <td width="20%" align="center" class="header">SK</td>
    <td width="28%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT p.replid, p.iddiklat, d.diklat, p.tahun, p.sk, p.keterangan, p.terakhir FROM pegdiklat p, diklat d WHERE p.nip = '$nip' AND p.iddiklat = d.replid ORDER BY p.tahun DESC, p.replid DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left"><?=$row['diklat']?></td>
    <td align="center"><?=$row['tahun']?></td>
    <td align="left"><?=$row['sk']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>
<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Sekolah</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="5%" align="center" class="header">Tingkat</td>
    <td width="25%" align="center" class="header">Sekolah</td>
    <td width="5%" align="center" class="header">Lulus</td>
    <td width="20%" align="center" class="header">SK</td>
    <td width="23%" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT ps.replid, ps.tingkat, ps.sekolah, ps.sk, ps.lulus, ps.keterangan, ps.terakhir FROM pegsekolah ps 
        WHERE ps.nip = '$nip' ORDER BY ps.lulus DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="center"><?=$row['tingkat']?></td>
    <td align="left"><?=$row['sekolah']?></td>
    <td align="center"><?=$row['lulus']?></td>
    <td align="left"><?=$row['sk']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>

<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Sertifikasi</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="22%" align="center" class="header">Sertifikasi</td>
    <td width="22%" align="center" class="header">Lembaga</td>
    <td width="7%" align="center" class="header">Tahun</td>
    <td width="*" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT ps.replid, ps.sertifikat, ps.lembaga, ps.tahun, ps.keterangan, ps.terakhir
          FROM pegserti ps 
         WHERE ps.nip = '$nip'
         ORDER BY ps.tahun DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left"><?=$row['sertifikat']?></td>
    <td align="left"><?=$row['lembaga']?></td>
    <td align="center"><?=$row['tahun']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>

<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Riwayat Pekerjaan</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="10%" align="center" class="header">Tahun Awal</td>
    <td width="10%" align="center" class="header">Tahun Akhir</td>
    <td width="20%" align="center" class="header">Tempat</td>
    <td width="20%" align="center" class="header">Jabatan</td>
    <td width="*" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT ps.replid, ps.thnawal, ps.thnakhir, ps.tempat, ps.jabatan, ps.keterangan, ps.terakhir
          FROM pegkerja ps 
         WHERE ps.nip = '$nip'
         ORDER BY ps.thnawal DESC";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="center"><?=$row['thnawal']?></td>
    <td align="center"><?=$row['thnakhir']?></td>
    <td align="left"><?=$row['tempat']?></td>
    <td align="left"><?=$row['jabatan']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>

<br />
<table border="0" cellpadding="5" cellspacing="0" width="100%" id="table56">
<tr>
	<td width="100%" align="left" style="border-bottom:thin dashed #CCCCCC; border-top:none; border-left:none; border-right:none;">
        <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Data Keluarga</font><br />
    </td>
</tr>
<tr><td>

<table border="1" id="table" style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%" class="tab">
<tr height="30">
	<td width="5%" align="center" class="header">No</td>
    <td width="20%" align="center" class="header">Nama</td>
    <td width="12%" align="center" class="header">Hubungan</td>
    <td width="12%" align="center" class="header">Tgl Lahir</td>
    <td width="12%" align="center" class="header">HP</td>
    <td width="15%" align="center" class="header">Email</td>
    <td width="*" align="center" class="header">Keterangan</td>
</tr>
<?php
$sql = "SELECT ps.replid, ps.nama, ps.alm, ps.hubungan, ps.tgllahir, ps.hp, ps.email, ps.keterangan
          FROM pegkeluarga ps 
         WHERE ps.nip = '$nip'
         ORDER BY ps.nama";
$result = QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
?>
<tr height="25">
	<td align="center"><?=++$cnt?></td>
    <td align="left">
	<?=$row['nama']?>
	<?php if ((int)$row['alm'] == 1) echo " (alm)"; ?>
	</td>
    <td align="left"><?=$row['hubungan']?></td>
    <td align="left"><?=$row['tgllahir']?></td>
    <td align="left"><?=$row['hp']?></td>
	<td align="left"><?=$row['email']?></td>
    <td align="left"><?=$row['keterangan']?></td>
</tr>
<?php
}
?>
</table>
</td></tr>
</table>

<?php
CloseDb();
?>    
</td></tr>

</table>

</td></tr></table>
</body>
<script language="javascript">
window.print();
</script>
</html>