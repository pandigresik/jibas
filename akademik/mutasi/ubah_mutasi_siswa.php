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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
$nis=$_REQUEST['nis'];
$departemen=$_REQUEST['departemen'];
//$nis=$_REQUEST['nis'];
$asal=$_REQUEST['asal'];
$pop=$_REQUEST['pop'];
$tampil=$_REQUEST['tampil'];
$Simpan=$_REQUEST['Simpan'];
if ($tampil<>"" && $nis<>""){
	OpenDb();
	$sql_tampil="SELECT s.nis,s.nama,k.kelas,a.angkatan,m.jenismutasi,m.keterangan,m.tglmutasi FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.angkatan a, jbsakad.mutasisiswa m WHERE s.nis='$nis' AND k.replid=s.idkelas AND s.idangkatan=a.replid AND m.nis='$nis' ";
	$result_tampil=QueryDb($sql_tampil);
	$row_tampil=mysqli_fetch_row($result_tampil);
	CloseDb();
}

if ($Simpan=="Simpan"){
	$tanggal=$_REQUEST["tanggal"];
	$tglmutasi=TglDb($tanggal);
	
	OpenDb();
	BeginTrans();
	$success=0;
	$sql_siswa_update="UPDATE jbsakad.siswa SET aktif=0,statusmutasi='".$_REQUEST['mutasi']."' WHERE nis='".$_REQUEST['nis']."'";
	QueryDbTrans($sql_siswa_update, $success);
	if ($success){
		$sql_mutasi_simpan="UPDATE jbsakad.mutasisiswa SET nis='".$_REQUEST['nis']."',jenismutasi='".$_REQUEST['mutasi']."',tglmutasi='$tglmutasi',keterangan='".CQ($_REQUEST['keterangan'])."' WHERE nis='".$_REQUEST['nis']."'";
		QueryDbTrans($sql_mutasi_simpan, $success);
	}

	if ($success){
		CommitTrans();
		if ($pop==1){
		?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Mutasi siswa berhasil diubah');
			document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>";
			//window.close();
		</script>
		<?php
		} else {
		?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Mutasi siswa berhasil diubah');
			document.location.href="daftar_mutasi_siswa_footer.php?departemen=<?=$departemen?>&from_left=1";
			//window.close();
		</script>
		<?php
		}
	} else {
		RollBackTrans();
	?>
		<SCRIPT type="text/javascript" language="javascript">
			alert ('Gagal merubah mutasi siswa !');
			document.location.href="ubah_mutasi.php?tampil=tampil&nis=<?=$_REQUEST['nis']?>";	
		</script>
	<?php
	}
	CloseDb();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<SCRIPT type="text/javascript" language="text/javascript" src="../script/tables.js"></SCRIPT>
<SCRIPT type="text/javascript" language="javascript" src="../script/common.js"></script>
<SCRIPT type="text/javascript" language="javascript" src="../script/tools.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css">
<title>Tambah Jenis Mutasi</title>
<SCRIPT type="text/javascript" language="javascript">
function verifikasi(){
	if (confirm('Anda yakin akan merubah status mutasi siswa ini?'))
 	 	return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
<form name="simpan_mutasi" id="simpan_mutasi" action="ubah_mutasi_siswa.php" method="post" onSubmit="return verifikasi();">
<?php if ($pop==1){ ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Mutasi Siswa :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->               
<?php } ?>                 
<table width="100%" border="0">
  <tr>
    <td><fieldset>
    <legend>Siswa yang status mutasinya akan diubah</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                 <tr>
                   <td>NIS</td>
                   <td> <input name="asal" type="hidden" class="disabled" id="asal" value="<?=$asal?>" readonly><input name="nis" type="text" id="nis" value="<?=$row_tampil[0]?>" class="disabled" readonly></td>
                 </tr>
                 <tr>
                   <td>Nama</td>
                   <td><input name="nama" type="text" id="nama" class="disabled" value="<?=$row_tampil[1]?>" readonly><input name="departemen" type="hidden" id="departemen" value="<?=$departemen?>" readonly></td>
                 </tr>
                 <tr>
                   <td>Kelas</td>
                   <td><input name="kelas" class="disabled" type="text" id="kelas" value="<?=$row_tampil[2]?>" readonly></td>
                 </tr>
                 <tr>
                   <td>Angkatan</td>
                   <td><input name="angkatan" class="disabled" type="text" id="angkatan" value="<?=$row_tampil[3]?>" readonly></td>
                 </tr>
        </table>
    </fieldset></td>
  </tr>
  <tr>
    <td> <table width="100%" border="0">
                     <tr>
                       <td>&nbsp;&nbsp;Tanggal Mutasi</td>
                       <td><input name="tanggal" class="disabled" id="tanggal" type="text" readonly="readonly" value="<?=TglText($row_tampil[6])?>">&nbsp;<img src="../images/ico/calendar_1.png" name="tabel" width="22" height="22" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '120px')"/></td>
                     </tr>
                     <tr>
                         <td>&nbsp;&nbsp;Jenis Mutasi                         </td>
                         <td><select name="mutasi" id="mutasi">
          <?php
				$sql = "SELECT * FROM jbsakad.jenismutasi ORDER BY replid";
				OpenDb();
				$result = QueryDb($sql);
				CloseDb();
			
				$replid = "";	
				while($row = mysqli_fetch_array($result)) {
					if ($replid == "")
						$replid = $row['replid'];
			?>
          <option value="<?=urlencode((string) $row['replid'])?>" <?=StringIsSelected($row['replid'], $row_tampil[4]) ?>>
          <?=$row['jenismutasi']?>
          </option>
          <?php
				} //while
				CloseDb();
			?>
        </select>                         </td>
                     </tr>
                       <tr>
                       <td>&nbsp;&nbsp;Keterangan<br />&nbsp;&nbsp;Mutasi                         </td>
                        <td><textarea name="keterangan" cols="30" rows="5" id="keterangan"><?=$row_tampil[5]?></textarea></td>
                       </tr>
                     </table>
                     </td>
                 </tr>
                 <tr>
                   <td colspan="2"><div align="center">
                     <input name="Simpan" type="Submit" class="but" value="Simpan">
                     &nbsp;
	<?php
  if ($pop==1){ ?>
  <input name="Tutup" type="button" class="but" value="Tutup" onClick="window.close()">

	<?php } else { ?>
  <input name="Tutup" type="button" class="but" value="Batal" onClick="window.self.history.back()">
	<?php } ?>
                     </div></td>
                 </tr>
               </table>
              <!-- </td>
  </tr>
</table>-->
 <?php if ($pop==1){ ?>
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
<?php } ?>
      </form>
            
			</body>
            <script type="text/javascript">
  Calendar.setup(
    {
      inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntanggal"       // ID of the button
    }
  );
  Calendar.setup(
    {
      inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "tanggal"       // ID of the button
    }
  );
</script>

</html>