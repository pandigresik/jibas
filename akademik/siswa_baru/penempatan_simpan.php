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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

OpenDb();
	
$replid = $_REQUEST['replid'];
$angkatan = $_REQUEST['angkatan'];	
$tahunajaran = $_REQUEST['tahunajaran'];	
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$departemen = $_REQUEST['departemen'];
$proses =$_REQUEST['proses'];
$kelompok = $_REQUEST['kelompok'];
$nis = CQ($_REQUEST['nis']);
$nisn = CQ($_REQUEST['nisn']);
$keterangan = CQ($_REQUEST['keterangan']);
$cari = $_REQUEST['cari'];
$no = $_REQUEST['no'];
$nama = $_REQUEST['nama'];

$ERROR_MSG = "";
if (isset($_REQUEST['Simpan'])) 
{
	$sql = "SELECT replid FROM siswa WHERE nis = '".$nis."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) 
	{
		$ERROR_MSG = "NIS ".$nis." sudah digunakan!";
	} 
	else 
	{
		$sql = "SELECT * FROM calonsiswa WHERE replid = '".$replid."'";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);

		$nopendaftaran = $row['nopendaftaran'];
		$agama = $row['agama'] == NULL ? "NULL" : "'" . $row['agama'] . "'";
		$suku = $row['suku'] == NULL ? "NULL" : "'" . $row['suku'] . "'";
		$kondisi = $row['kondisi'] == NULL ? "NULL" : "'" . $row['kondisi'] . "'";
		$status = $row['status'] == NULL ? "NULL" : "'" . $row['status'] . "'";
		
		$kodepos = $row['kodepossiswa'];
		$kodepos_sql = "kodepossiswa = '".$kodepos."'";
		if ($kodepos == NULL)
			$kodepos_sql = "kodepossiswa = NULL";		
			
		$sekolah = $row['asalsekolah'];
		$sekolah_sql = "asalsekolah = '".$sekolah."'";
		if ($sekolah == NULL)
			$sekolah_sql = "asalsekolah = NULL";		
			
		$pendidikanayah = $row['pendidikanayah'];
		$pendidikanayah_sql = "pendidikanayah = '".$pendidikanayah."'";
		if ($pendidikanayah == "")
			$pendidikanayah_sql = "pendidikanayah = NULL";
			
		$pendidikanibu = $row['pendidikanibu'];
		$pendidikanibu_sql = "pendidikanibu = '".$pendidikanibu."'";
		if ($pendidikanibu == "")
			$pendidikanibu_sql = "pendidikanibu = NULL";
			
		$pekerjaanayah = $row['pekerjaanayah'];
		$pekerjaanayah_sql = "pekerjaanayah = '".$pekerjaanayah."'";
		if ($pekerjaanayah == "")
			$pekerjaanayah_sql = "pekerjaanayah = NULL";
			
		$pekerjaanibu = $row['pekerjaanibu'];
		$pekerjaanibu_sql = "pekerjaanibu = '".$pekerjaanibu."'";
		if ($pekerjaanibu == "")
			$pekerjaanibu_sql = "pekerjaanibu = NULL";
		
		$nama = $row['nama'];
		$nama = str_replace("'", "`", (string) $nama);
			
		$date = date('j');
		$month = date('m');
		$year = date('Y');
		$kumplit = $year."-".$month."-".$date;
		
		$pinsiswa = random(5);
		$pinortu = random(5);
		$pinortuibu = random(5);

		BeginTrans();
		$success = true;		
		$sql = "INSERT INTO jbsakad.siswa SET nis='".$nis."',nama='$nama', panggilan='".$row['panggilan']."', tahunmasuk=$year, 
					idangkatan=$angkatan, idkelas=$kelas, suku=$suku, agama=$agama, status=$status, 
					kondisi=$kondisi, kelamin='".$row['kelamin']."', tmplahir='".$row['tmplahir']."', tgllahir='".$row['tgllahir']."', 
					warga='".$row['warga']."', anakke='".$row['anakke']."', jsaudara='".$row['jsaudara']."', bahasa='".$row['bahasa']."', berat='".$row['berat']."', 
					tinggi='".$row['tinggi']."', darah='".$row['darah']."', alamatsiswa='".$row['alamatsiswa']."', $kodepos_sql, telponsiswa='".$row['telponsiswa']."', 
					hpsiswa='".$row['hpsiswa']."', emailsiswa='".$row['emailsiswa']."', kesehatan='".$row['kesehatan']."', $sekolah_sql, ketsekolah='".$row['ketsekolah']."', 
					namaayah='".$row['namaayah']."', namaibu='".$row['namaibu']."', almayah='".$row['almayah']."', almibu='".$row['almibu']."', $pendidikanayah_sql, 
					$pendidikanibu_sql, $pekerjaanayah_sql, $pekerjaanibu_sql, wali='".$row['namawali']."', penghasilanayah='".$row['penghasilanayah']."', 
					penghasilanibu='".$row['penghasilanibu']."', alamatortu='".$row['alamatortu']."', telponortu='".$row['telponortu']."', hportu='".$row['hportu']."',
					info1='".$row['info1']."', info2='".$row['info2']."', 
					emailayah='".$row['emailayah']."', emailibu='".$row['emailibu']."', alamatsurat='".$row['alamatsurat']."', keterangan='".$row['keterangan']."', 
					frompsb=1, ketpsb='$keterangan', pinsiswa='$pinsiswa', pinortu='$pinortu', pinortuibu = '$pinortuibu',nisn='$nisn',
					nik='".$row['nik']."',noun='".$row['noun']."',statusanak='".$row['statusanak']."',jkandung='".$row['jkandung']."',jtiri='".$row['jtiri']."',jarak='".$row['jarak']."',
					noijasah='".$row['noijasah']."', tglijasah='".$row['tglijasah']."', statusayah='".$row['statusayah']."', statusibu='".$row['statusibu']."',
					tmplahirayah='".$row['tmplahirayah']."', tmplahiribu='".$row['tmplahiribu']."', tgllahirayah='".$row['tgllahirayah']."', tgllahiribu='".$row['tgllahiribu']."',
					hobi='".$row['hobi']."'";
		QueryDbTrans($sql,$success);
		
		if ($success) 
		{
			$sql1 = "SELECT LAST_INSERT_ID()";	
			$result1 = QueryDb($sql1);
			$row1 = mysqli_fetch_row($result1);
			$id = $row1[0];
			
			$sql2 = "UPDATE calonsiswa SET replidsiswa = '$id' WHERE replid = '".$replid."'";				
			QueryDbTrans($sql2,$success);
			
			if ($row['foto'] <> "") 
			{
				$sql3 = "UPDATE siswa SET foto = (SELECT foto FROM calonsiswa where replid = '$replid') WHERE replid = '".$id."'";
				QueryDbTrans($sql3, $success);  
			}
		}
		
		$sql_dept = "INSERT INTO jbsakad.riwayatdeptsiswa SET nis='$nis',departemen='$departemen',mulai='$kumplit'";
		if ($success)		
			QueryDbTrans($sql_dept,$success);
		
		$sql_kls = "INSERT INTO jbsakad.riwayatkelassiswa SET nis='$nis',idkelas='$kelas',mulai='$kumplit'";
		if ($success)
			QueryDbTrans($sql_kls,$success);

		if ($success)
        {
            $sql = "INSERT INTO jbsakad.tambahandatasiswa (nis, idtambahan, jenis, teks, filedata, filename, filemime, filesize)
                    SELECT '$nis', idtambahan, jenis, teks, filedata, filename, filemime, filesize
                      FROM jbsakad.tambahandatacalon
                     WHERE nopendaftaran = '".$nopendaftaran."'";
            QueryDbTrans($sql, $success);
        }
		
		if ($success)
		{			
			CommitTrans(); 
			CloseDb(); ?>
			<script language="javascript">
                parent.opener.refresh_daftar();
                window.close();
            </script>
<?php 		exit();
		} 
		else 
		{
			RollbackTrans();
			CloseDb();
		}
	}
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Penempatan Calon Siswa]</title>
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function validate() {
	return validateEmptyText('nis', 'NIS') &&
		   validateMaxText('keterangan', 255, 'Keterangan');
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nis').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
    <div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Penempatan Calon Siswa :.
    </div>
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->

<?php $sql = "SELECT k.kelas, t.tahunajaran, a.angkatan, t.departemen 
			FROM kelas k, tahunajaran t, angkatan a 
			WHERE k.replid = '$kelas' AND t.replid = '$tahunajaran' AND k.idtahunajaran = t.replid 
			AND a.replid = '$angkatan' AND a.departemen = '".$departemen."'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_row($result);
	$kls = $row[0];
	$tahun = $row[1];
	$angkt = $row[2];
	$dept = $row[3]; ?> 

<form name="main" onSubmit="return validate()" enctype="multipart/form-data" method="post" >
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>">
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas?>">
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">
<input type="hidden" name="angkatan" id="angkatan" value="<?=$angkatan?>">
<input type="hidden" name="proses" id="proses" value="<?=$proses?>">
<input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok?>">
<input type="hidden" name="replid" id="replid" value="<?=$replid?>">
<input type="hidden" name="no" id="no" value="<?=$no?>">
<input type="hidden" name="nama" id="nama" value="<?=$nama?>">
<input type="hidden" name="cari" id="cari" value="<?=$cari?>">

<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
	<td width="120"><strong>Departemen</strong></td>
    <td><input type="text" name="dept" size="10" maxlength="50" class="disabled" readonly value="<?=$departemen?>"/></td>
</tr>
<tr>
	<td width="120"><strong>Angkatan</strong></td>
    <td><input type="text" name="angkt" size="20" value="<?=$angkt ?>" readonly class="disabled"/></td>
</tr>
<tr>
	<td width="120"><strong>Tahun Ajaran</strong></td>
    <td><input type="text" name="tahun" size="10" value="<?=$tahun ?>" readonly class="disabled"/></td>
</tr>
<tr>
	<td width="120"><strong>Kelas</strong></td>
    <td><input type="text" name="kls" size="10" value="<?=$kls ?>" readonly class="disabled"/></td>
</tr>
<tr>
	<td><strong>NIS</strong></td>
	<td>
    	<input type="text" name="nis" id="nis" size="15" maxlength="15" value="<?=$nis ?>" onFocus="showhint('NIS tidak boleh lebih dari 15 karakter!', this, event, '120px')" onKeyPress="return focusNext('nisn', event)"/>
    </td>
</tr>
<tr>
	<td>N I S N</td>
	<td>
    	<input type="text" name="nisn" id="nisn" size="15" maxlength="15" value="<?=$nisn ?>" onFocus="showhint('NISN tidak boleh lebih dari 50 karakter!', this, event, '120px')" onKeyPress="return focusNext('keterangan', event)"/>
    </td>
</tr>
<tr>
	<td valign="top">Keterangan</td>
	<td>
    	<textarea name="keterangan" id="keterangan" rows="3" cols="40" onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Simpan" class="but" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />
    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>



<?php // }?>


<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');		
</script>
<?php } ?>

<!-- Pilih inputan pertama -->
 <!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("nis");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>
<?php
CloseDb();
?>