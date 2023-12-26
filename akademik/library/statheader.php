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
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('departemen.php');
OpenDb();
$kode="";
if (isset($_REQUEST['kode']))
	$kode=$_REQUEST['kode'];

$departemen="-1";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];

$key="-1";
if (isset($_REQUEST['key']))
	$key=$_REQUEST['key'];

$keyword="";
if (isset($_REQUEST['keyword']))
	$keyword=$_REQUEST['keyword'];


$dep="";
if ($departemen!="-1"){
	$dep="WHERE departemen='$departemen'";
	$dis="";
	} else {
	$dep="";
	$dis="disabled='disabled'";
	}


?>
<!-- punya elif pegawai-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../style/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISFO KUA [Statistik Kepegawaian]</title>
<script language="javascript">
function Show() {
	var dep=document.getElementById("departemen").value;
	var key=document.getElementById("key").value;
	var keyword=document.getElementById("keyword").value;
	parent.statcontent.location.href = "statcontent.php?departemen="+dep+"&kode=<?=$kode?>&key="+key+"&keyword="+keyword;
}

function ChangeBlank() {
	parent.statcontent.location.href = "blank.php";
}
function change_dep() {
	var dep=document.getElementById("departemen").value;
	document.location.href="statheader.php?departemen="+dep+"&kode=<?=$kode?>";
	parent.statcontent.location.href = "blank.php";
}
function change_key() {
	var dep=document.getElementById("departemen").value;
	var key=document.getElementById("key").value;
	document.location.href="statheader.php?departemen="+dep+"&kode=<?=$kode?>&key="+key;
	parent.statcontent.location.href = "blank.php";
}
function change_keyword() {
	var dep=document.getElementById("departemen").value;
	var key=document.getElementById("key").value;
	var keyword=document.getElementById("keyword").value;
	document.location.href="statheader.php?departemen="+dep+"&kode=<?=$kode?>&key="+key+"&keyword="+keyword;
	parent.statcontent.location.href = "blank.php";
}
</script>
</head>

<body>

<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
    <td align="left" valign="top" width="70%">
    
<table border="0" cellpadding="2" cellspacing="0">
<tr>
	<td align="left">Departemen</td>
    <td align="left">
    <select name="departemen" id="departemen" onChange="change_dep()">
    	 <option value="-1" <?=StringIsSelected("-1", $departemen) ?> > 
                Semua Departemen                </option>
    	 <?php $dep = getDepartemen(SI_USER_ACCESS());    
			foreach($dep as $value) {
				if ($departemen == "")
					$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                <?=$value ?> 
                </option>
         <?php } ?>
    </select>    </td>
    <td rowspan="3" align="center" valign="middle">
    	<a href="JavaScript:Show()"><img src="../images/ico/view_x.png" border="0" /></a>    </td>
</tr>
<tr>
  <td align="left"><?php if ($kode==0) { echo "Angkatan"; } else if ($kode==1) { echo "Proses Penerimaan"; } ?></td>
  <td align="left">
  <select name="key" id="key" onchange="change_key()" <?=$dis?>>
  <?php  	if ($kode==0){
		?>
			<option value="-1" <?=StringIsSelected("-1", $key) ?> >Semua Angkatan</option>
		<?php
  		$sql="SELECT replid,angkatan FROM jbsakad.angkatan $dep ORDER BY aktif,replid ";
  		} else if ($kode==1) {
		?>
			<option value="-1" <?=StringIsSelected("-1", $key) ?> >Semua Penerimaan</option>
		<?php
  		$sql="SELECT replid,proses FROM jbsakad.prosespenerimaansiswa $dep ORDER BY aktif,replid ";
		}
		$result=QueryDb($sql);
		while ($row=@mysqli_fetch_array($result)){
		?>
        <option value="<?=$i?>" <?=StringIsSelected($i, $key) ?>><?=$row[1]?></option>
        <?php
		}
  ?>
  </select>
  </td>
  </tr>
<tr>
  <td align="left">Berdasarkan</td>
  <td align="left">
  <select name="keyword" id="keyword" onchange="change_keyword()">
        <option value="1" <?=StringIsSelected("1", $keyword) ?>>Agama</option>
        <option value="2" <?=StringIsSelected("2", $keyword) ?>>Asal Sekolah</option>
        <option value="3" <?=StringIsSelected("3", $keyword) ?>>Golongan Darah</option>
        <option value="4" <?=StringIsSelected("4", $keyword) ?>>Jenis Kelamin</option>
        <option value="5" <?=StringIsSelected("5", $keyword) ?>>Kewarganegaraan</option>
        <option value="6" <?=StringIsSelected("6", $keyword) ?>>Kodepos Siswa</option>
        <option value="7" <?=StringIsSelected("7", $keyword) ?>>Kondisi</option>
        <option value="8" <?=StringIsSelected("8", $keyword) ?>>Pekerjaan Ayah</option>
        <option value="9" <?=StringIsSelected("9", $keyword) ?>>Pekerjaan Ibu</option>
        <option value="10" <?=StringIsSelected("10", $keyword) ?>>Pendidikan Ayah</option>
        <option value="11" <?=StringIsSelected("11", $keyword) ?>>Pendidikan Ibu</option>
        <option value="12" <?=StringIsSelected("12", $keyword) ?>>Penghasilan Orang Tua</option>
        <option value="13" <?=StringIsSelected("13", $keyword) ?>>Status Aktif</option>
        <option value="14" <?=StringIsSelected("14", $keyword) ?>>Status Siswa</option>
        <option value="15" <?=StringIsSelected("15", $keyword) ?>>Suku</option>
        <option value="16" <?=StringIsSelected("16", $keyword) ?>>Tahun Kelahiran</option>
        <option value="17" <?=StringIsSelected("17", $keyword) ?>>Usia</option>
  </select>
  </td>
  </tr>
</table>

</td>
    <td align="right"  valign="top" width="30%">
	<?php if ($kode==0){ ?>
	<font size="4" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" color="Gray">Statistik
	Kesiswaan</font><br />
	<a href="../siswa.php" target="_parent" style="color:#0000FF">Kesiswaan</a> > <strong>Statistik
	Kesiswaan</strong>    
    <?php } else if ($kode==1){ ?>
    <font size="4" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" color="Gray">Statistik
	Calon Siswa</font><br />
	<a href="../siswa_baru.php" target="_parent" style="color:#0000FF">P S B</a> > <strong>Statistik
	Calon Siswa</strong>    
	<?php } ?>
    </td>
</tr>
</table>

</body>
</html>