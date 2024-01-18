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
require_once('../include/rupiah.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

$warga = "WNI";
$urutananak = "1";
$jumlahanak = "1";

$berat = "0";
$tinggi = "0";
$penghasilanayah = "0";
$penghasilanibu = "0";

$n = JmlHari($blnlahir, $thnlahir);

$departemen=$_REQUEST['departemen'];
$proses=$_REQUEST['proses'];
$kelompok=$_REQUEST['kelompok'];

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Tambah Calon Siswa]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="calon_add.js"></script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0"
	  style="background-color:#dcdfc4" onLoad="document.getElementById('nisn').focus()">
	
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="left" style="color:#fff08c; font-size:14px; font-weight:bold">
    Calon Siswa Baru
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">

    <form name="main" method="post" action="calon_simpan.php" onSubmit="return validate()" enctype="multipart/form-data"><!---->
    <input type="hidden" name="proses" id="proses" value="<?=$proses?>" />
    <input type="hidden" name="kelompok" id="kelompok" value="<?=$kelompok?>" />
    <input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
    <input type="hidden" name="no" id="no" value="0" />
    <input type="hidden" name="tahunmasuk" id="tahunmasuk" value="<?=date('Y')?>" />
    
    <table width="100%" border="0" cellspacing="0">
    <tr>
    	<td width="45%" valign="top"><!-- Kolom Kiri-->
        <table width="100%" border="0" cellspacing="0" id="table">
        <tr>
        	<td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Calon Siswa</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
			</td>
        </tr>
        <tr>
			<td>N I S N</td>
			<td colspan="2">
				<input type="text" name="nisn" id="nisn" size="30" maxlength="50"
					   value="<?=$nisn?>" onfocus="panggil('nisn');" onKeyPress="return focusNext('nik', event)" onblur="unfokus('nisn')"/>
			</td>
        </tr>
		<tr>
			<td>N I K</td>
			<td colspan="2">
				<input type="text" name="nik" id="nik" size="30" maxlength="50"
					   value="<?=$nik?>" onfocus="panggil('nik');" onKeyPress="return focusNext('noun', event)" onblur="unfokus('nik')"/>
			</td>
        </tr>
		<tr>
			<td>No UN Sebelumnya</td>
			<td colspan="2">
				<input type="text" name="noun" id="noun" size="30" maxlength="50"
					   value="<?=$noun?>" onfocus="panggil('noun');" onKeyPress="return focusNext('nama', event)" onblur="unfokus('noun')"/>
			</td>
        </tr>
        <tr>
          	<td width="22%"><strong>Nama</strong></td>
          	<td colspan="2">
				<input type="text" name="nama" id="nama" size="30" maxlength="100"
					   value="<?=$nama?>" onfocus="showhint('Nama Lengkap Siswa tidak boleh kosong!', this, event, '120px');panggil('nama');"
					   onKeyPress="return focusNext('panggilan', event)" onblur="unfokus('nama')"/>
			</td>
        </tr>
        <tr>
          	<td>Panggilan</td>
          	<td colspan="2">
				<input type="text" name="panggilan" id="panggilan" size="30" maxlength="30"
					   onFocus="showhint('Nama Panggilan tidak boleh lebih dari 30 karakter!', this, event, '120px');panggil('panggilan');" value="<?=$panggilan?>" onKeyPress="return focusNext('kelamin', event)" onblur="unfokus('panggilan')"/>
			</td>
        </tr>
        <tr>
          	<td>Jenis Kelamin</td>
          	<td colspan="2">
				<input type="radio" name="kelamin"  id="kelamin" value="l" 
					   <?php if ($kelamin=="l") echo "checked='checked'"; else echo "checked='checked'"; ?>
					   onKeyPress="return focusNext('tmplahir', event)"/>&nbsp;Laki-laki&nbsp;&nbsp;
                <input type="radio" name="kelamin" value="p"
					   <?php if ($kelamin=="p") echo "checked='checked'"; ?>
					   onKeyPress="return focusNext('tmplahir', event)"/>&nbsp;Perempuan
			</td>
        </tr>
        <tr>
          	<td>Tempat Lahir</td>
          	<td colspan="2">
				<input type="text" name="tmplahir" id="tmplahir" size="30" maxlength="50"
					   onFocus="showhint('Tempat Lahir tidak boleh kosong!', this, event, '120px');panggil('tmplahir');"
					   value="<?=$tmplahir?>" onKeyPress="return focusNext('tgllahir', event)" onblur="unfokus('tmplahir')"/>
			</td>
        </tr>
        <tr>
          	<td>Tanggal Lahir</td>
          	<td colspan="2">
          		<table cellpadding="0" cellspacing="0" border="0">
                <tr>
                	<td>
                    <div id="tgl_info">
                    <select name="tgllahir" id="tgllahir" onKeyPress="return focusNext('blnlahir', event)"
							onFocus="panggil('tgllahir')" onblur="unfokus('tgllahir')">
						<option value="">[Tgl]</option>  
						<?php 	for ($tgl=1;$tgl<=$n;$tgl++) { ?>
							<option value="<?=$tgl?>" <?=IntIsSelected($tgllahir, $tgl)?>><?=$tgl?></option>
						<?php }	?>
                    </select>
                    </div>
					</td>
                    <td>
                    <select name="blnlahir" id="blnlahir" onChange="change_bln()" onKeyPress="return focusNext('thnlahir', event)"
							onFocus="panggil('blnlahir')" onblur="unfokus('blnlahir')">
						<option value="">[Bulan]</option>
	                    <?php 	for ($i=1;$i<=12;$i++) { ?>
							<option value="<?=$i?>" <?=IntIsSelected($blnlahir, $i)?>><?=NamaBulan($i)?></option>	
						<?php } ?>
                    </select>
                    <input type="text" name="thnlahir" id="thnlahir" size="5" maxlength="4" onFocus="showhint('Tahun Lahir tidak boleh kosong!', this, event, '120px');panggil('thnlahir');" value="<?=$thnlahir?>" onKeyPress="return focusNext('agama', event)" onblur="unfokus('thnlahir')"/>
					</td>
                </tr>
            	</table>
			</td>
        </tr>
        <tr>
          	<td>Agama</td>
          	<td colspan="2">
            	<div id="InfoAgama">
            	<select name="agama" id="agama" value="<?=$agama?>" class="ukuran"
						onKeyPress="return focusNext('suku', event)" onfocus="panggil('agama')" onblur="unfokus('agama')">
					<option value="">[Pilih Agama]</option>
					<?php 
					$sql_agama="SELECT replid,agama,urutan FROM jbsumum.agama ORDER BY urutan";
					$result_agama=QueryDB($sql_agama);
					while ($row_agama = mysqli_fetch_array($result_agama)) {
					?>
						<option value="<?=$row_agama['agama']?>"<?=StringIsSelected($row_agama['agama'],$agama)?>>
						<?=$row_agama['agama']?>
		                </option>
					<?php
					} 
					?>
                </select>
              	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            	<img src="../images/ico/tambah.png" onClick="tambah_agama();" onMouseOver="showhint('Tambah Agama!', this, event, '50px')"/>
            	<?php } ?>
          		</div>
			</td>
		</tr>
        <tr>
          	<td>Suku</td>
          	<td colspan="2"><div id="InfoSuku">
            	<select name="suku" id="suku" class="ukuran" onKeyPress="return focusNext('status', event)"
						onfocus="panggil('suku')" onblur="unfokus('suku')">
					<option value="">[Pilih Suku]</option>
					<?php 
					$sql_suku="SELECT suku,urutan,replid FROM jbsumum.suku ORDER BY urutan";
					$result_suku=QueryDB($sql_suku);
					while ($row_suku = mysqli_fetch_array($result_suku)) {
					?>
					<option value="<?=$row_suku['suku']?>"<?=StringIsSelected($row_suku['suku'],$suku)?>>
					<?=$row_suku['suku']?>
					</option>
					<?php
					} 
					?>
              	</select>
              	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            	<img src="../images/ico/tambah.png" onClick="tambah_suku();" onMouseOver="showhint('Tambah Suku!', this, event, '50px')" />
            	<?php } ?></div>
			</td>
   		</tr>
        <tr>
          	<td>Status</td>
          	<td colspan="2"><div id="InfoStatus">
              	<select name="status" id="status" class="ukuran" onKeyPress="return focusNext('kondisi', event)"
						onfocus="panggil('status')" onblur="unfokus('status')">
					<option value="">[Pilih Status]</option>
					<?php 
					$sql_status="SELECT replid,status,urutan FROM jbsakad.statussiswa ORDER BY urutan";
					$result_status=QueryDB($sql_status);
					while ($row_status = mysqli_fetch_array($result_status))	{
					?>
						<option value="<?=$row_status['status']?>"<?=StringIsSelected($row_status['status'],$status)?>>
						<?=$row_status['status']?>
						</option>
					<?php
					} 
					?>
              	</select>
              	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            	<img src="../images/ico/tambah.png" onClick="tambah_status();" onMouseOver="showhint('Tambah Status!', this, event, '50px')"/>
            	<?php } ?>
          		</div>
			</td>
		</tr>
        <tr>
          	<td>Kondisi</td>
          	<td colspan="2"><div id="InfoKondisi">
              	<select name="kondisi" id="kondisi" class="ukuran" onKeyPress="return focusNext('warga', event)"
						onfocus="panggil('kondisi')" onblur="unfokus('kondisi')">
					<option value="">[Pilih Kondisi]</option>
					<?php 
					$sql_kondisi="SELECT kondisi,urutan FROM jbsakad.kondisisiswa ORDER BY urutan";
					$result_kondisi=QueryDB($sql_kondisi);
					while ($row_kondisi = mysqli_fetch_array($result_kondisi)) {
					?>
						<option value="<?=$row_kondisi['kondisi']?>"<?=StringIsSelected($row_kondisi['kondisi'],$kondisi)?>>
						<?=$row_kondisi['kondisi']?>
						</option>
					<?php
					} 
					?>
              	</select>
              	<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
            	<img src="../images/ico/tambah.png" onClick="tambah_kondisi();" onMouseOver="showhint('Tambah Kondisi!', this, event, '50px')"/>
            	<?php } ?>
          		</div>
			</td>
        </tr>
        <tr>
          	<td>Kewarganegaraan</td>
          	<td colspan="2">
            <input type="radio" name="warga" id="warga" value="WNI"  
			<?php if ($warga=="WNI" || $warga=="") 
                echo "checked='checked'"; 
            ?> onKeyPress="return focusNext('urutananak', event)" />&nbsp;WNI&nbsp;&nbsp;
            <input type="radio" name="warga"  id="warga" value="WNA" 
            <?php if ($warga=="WNA") 
            echo "checked='checked'";
            ?> onKeyPress="return focusNext('urutananak', event)"/>&nbsp;WNA</td>
      	</tr>
        <tr>
          	<td>Anak ke</td>
          	<td colspan="2">
          	<input type="text" name="urutananak" id="urutananak" size="3" maxlength="3"
				   onFocus="showhint('Urutan anak tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('urutananak');"
				   value="<?=$urutananak?>" onKeyPress="return focusNext('jumlahanak', event)" onblur="unfokus('urutananak')"/>
            &nbsp;dari&nbsp;
            <input type="text" name="jumlahanak" id="jumlahanak" size="3" maxlength="3"
				   onFocus="showhint('Jumlah saudara tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('jumlahanak');"
				   value="<?=$jumlahanak?>" onKeyPress="return focusNext('statusanak', event)" onblur="unfokus('jumlahanak')"/>
            &nbsp;bersaudara</td>
        </tr>
		<tr>
          	<td>Status anak</td>
          	<td colspan="2">
				<select name="statusanak" id="statusanak" onKeyPress="return focusNext('jkandung', event)"
						onfocus="panggil('statusanak')" onblur="unfokus('statusanak')">
					<option value="Kandung">Anak Kandung</option>
					<option value="Angkat">Anak Angkat</option>
					<option value="Tiri">Anak Tiri</option>
					<option value="Lainnya">Lainnya</option>
				</select>
          	</td>
        </tr>
		<tr>
          	<td>Jml. Saudara Kandung</td>
          	<td colspan="2">
				<input type="text" name="jkandung" id="jkandung" size="3" maxlength="3"
					   onFocus="showhint('Jumlah saudara kandung tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('jkandung');"
					   value="<?=$jkandung?>" onKeyPress="return focusNext('jtiri', event)" onblur="unfokus('bahasa')"/> orang
			</td>
        </tr>
		<tr>
          	<td>Jml. Saudara Tiri</td>
          	<td colspan="2">
				<input type="text" name="jtiri" id="jtiri" size="3" maxlength="3"
					   onFocus="showhint('Jumlah saudara tiri tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('jtiri');"
					   value="<?=$jtiri?>" onKeyPress="return focusNext('alamatsiswa', event)" onblur="unfokus('bahasa')"/> orang
			</td>
        </tr>
        <tr>
          	<td>Bahasa</td>
          	<td colspan="2">
				<input type="text" name="bahasa" id="bahasa" size="30" maxlength="60"
					   onFocus="showhint('Bahasa anak tidak boleh lebih dari 60 karakter!', this, event, '120px');panggil('bahasa')"
					   value="<?=$bahasa?>" onKeyPress="return focusNext('alamatsiswa', event)" onblur="unfokus('bahasa')"/>
			</td>
        </tr>
        <tr>
          	<td>Foto</td>
          	<td colspan="2">
				<input type="file" id="file" name="nama_foto" style="width:215px" size="25" />
			</td>
        </tr>
        <tr>
          	<td valign="top">Alamat</td>
          	<td colspan="2">
				<textarea name="alamatsiswa" id="alamatsiswa" rows="2" cols="30" onKeyUp="change_alamat()" class="Ukuranketerangan"
						  onFocus="showhint('Alamat calon siswa tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('alamatsiswa')"
						  onKeyPress="return focusNext('kodepos', event)" onblur="unfokus('alamatsiswa')"><?=$alamatsiswa?></textarea>
			</td>
        </tr>
        <tr>
          	<td>Kode Pos</td>
          	<td colspan="2">
				<input type="text" name="kodepos" id="kodepos" size="5" maxlength="8"
					   onFocus="showhint('Kodepos tidak boleh lebih dari 8 angka!', this, event, '120px');panggil('kodepos')"
					   value="<?=$kodepos?>" onKeyPress="return focusNext('jarak', event)" onblur="unfokus('kodepos')"/>          	
			</td>
		</tr>
		<tr>
          	<td>Jarak ke Sekolah</td>
          	<td colspan="2">
				<input type="text" name="jarak" id="jarak" size="4" maxlength="4"
					   onFocus="showhint('Jarak tidak boleh lebih dari 4 angka!', this, event, '120px'); panggil('jarak')"
					   value="<?=$jarak?>" onKeyPress="return focusNext('telponsiswa', event)" onblur="unfokus('jarak')"/>&nbsp;km          	
			</td>
		</tr>
        <tr>
          	<td>Telepon</td>
          	<td colspan="2">
				<input type="text" name="telponsiswa" id="telponsiswa" class="ukuran" maxlength="20"
					   onFocus="showhint('Nomor Telepon tidak boleh lebih dari 20 angka!', this, event, '120px');panggil('telponsiswa')"
					   value="<?=$telponsiswa?>" onKeyPress="return focusNext('hpsiswa', event)" onblur="unfokus('telponsiswa')"/>
			</td>
		</tr>
        <tr>
          	<td>Handphone</td>
          	<td colspan="2">
				<input type="text" name="hpsiswa" id="hpsiswa" class="ukuran" maxlength="30"
					   onfocus="showhint('Nomor ponsel tidak boleh lebih dari 20 angka!', this, event, '120px');panggil('hpsiswa')"
					   value="<?=$hpsiswa?>" onkeypress="return focusNext('emailsiswa', event)" onblur="unfokus('hpsiswa')"/>
			</td>
        </tr>
        <tr>
          	<td>Email</td>
          	<td colspan="2">
				<input type="text" name="emailsiswa" id="emailsiswa" size="30" maxlength="100"
					   onFocus="showhint('Alamat email tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailsiswa')"
					   value="<?=$emailsiswa?>" onKeyPress="return focusNext('dep_asal', event)" onblur="unfokus('emailsiswa')"/>
			</td>
        </tr>
      	<tr>
       		<td rowspan="2" valign="top">Asal Sekolah</td>
          	<td>
            <div id="depInfo">                                
            <select name="dep_asal" id="dep_asal" onChange="change_departemen(0)" onKeyPress="return focusNext('sekolah', event)" style="width:150px;" onfocus="panggil('dep_asal')" onblur="unfokus('dep_asal')">
            	<option value="">[Pilih Departemen]</option>	
				<?php 
         		$sql_departemen="SELECT DISTINCT departemen FROM jbsakad.asalsekolah ORDER BY departemen";      
				$result_departemen=QueryDB($sql_departemen);
				while ($row_departemen = mysqli_fetch_array($result_departemen)) {                
				?>
					<option value="<?=$row_departemen['departemen']?>" <?=StringIsSelected($row_departemen['departemen'], $dep_asal)?>>
					<?=$row_departemen['departemen']?>
					</option>
                <?php
				} 
				?>
          	</select>
			</div>
			</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                <div id="sekolahInfo">
                <select name="sekolah" id="sekolah"
						onKeyPress="return focusNext('noijasah', event)" style="width:150px;" onfocus="panggil('sekolah')" onblur="unfokus('sekolah')">
		            <option value="">[Pilih Asal Sekolah]</option>
					<?php 
					$sql_sekolah="SELECT sekolah FROM jbsakad.asalsekolah WHERE departemen='$dep_asal' ORDER BY sekolah ASC";
					$result_sekolah=QueryDB($sql_sekolah);
					while ($row_sekolah = mysqli_fetch_array($result_sekolah)) {       
					?>
				        <option value="<?=$row_sekolah['sekolah']?>" <?=StringIsSelected($row_sekolah['sekolah'],$sekolah)?>>
				        <?=$row_sekolah['sekolah']?>
				        </option><?php
			        } 
			        ?>
                </select>
              	</div></td>
              	<td valign="middle">
					<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
					<img src="../images/ico/tambah.png" onClick="tambah_asal_sekolah();" onMouseOver="showhint('Tambah Asal Sekolah!', this, event, '80px')"/>
					<?php } ?>
				</td>
            </tr>
            </table>
			</td>
        </tr>
		<tr>
          	<td>No Ijasah</td>
          	<td colspan="2">
				<input type="text" name="noijasah" id="noijasah" size="30" maxlength="100"
					   onFocus="showhint('Nomor Ijasah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('noijasah')"
					   value="<?=$noijasah?>"
					   onKeyPress="return focusNext('tglijasah', event)" onblur="unfokus('noijasah')"/>
			</td>
        </tr>
		<tr>
          	<td>Tgl Ijasah</td>
          	<td colspan="2">
				<input type="text" name="tglijasah" id="tglijasah" size="30" maxlength="30"
					   onFocus="showhint('Tanggal Ijasah tidak boleh lebih dari 30 karakter!', this, event, '120px');panggil('noijasah')"
					   value="<?=$tglijasah?>"
					   onKeyPress="return focusNext('ketsekolah', event)" onblur="unfokus('noijasah')"/>
			</td>
        </tr>
        <tr>
          	<td valign="top">Keterangan Asal <br /> Sekolah </td>
          	<td colspan="2">
				<textarea name="ketsekolah" id="ketsekolah" rows="2" cols="30"  class="Ukuranketerangan"
						  onfocus="showhint('Keterangan sekolah asal tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('ketsekolah')"
						  onkeypress="return focusNext('gol', event)" onblur="unfokus('ketsekolah')"><?=$ketsekolah?></textarea>
			</td>
        </tr>
      	</table>
        </td>
      	<!-- Akhir Kolom Kiri-->
      	<td width="1%" align="center" valign="middle"  style="border-left:1px dashed #333333; border-width:thin"></td>
      	<td width="*" valign="top"><!-- Kolom Kanan-->
        <table width="100%" border="0" cellspacing="0" id="table">
    	<tr>
         	<td height="30" colspan="3" valign="top">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Riwayat Kesehatan</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
            </td>
        </tr>
        <tr>
          	<td width="20%" valign="top" >Gol. Darah</td>
          	<td colspan="2">
            <input type="radio" name="gol" id="gol" value="A"
			<?php if ($gol=="A") 
                    echo "checked";
            ?> onKeyPress="return focusNext('berat', event)"/>&nbsp;A&nbsp;&nbsp;
            <input type="radio" name="gol" id="gol" value="AB" 
            <?php if ($gol=="AB") 
                    echo "checked";
            ?> onKeyPress="return focusNext('berat', event)"/>&nbsp;AB&nbsp;&nbsp;
            <input type="radio" name="gol" id="gol" value="B" 
            <?php if ($gol=="B") 
                    echo "checked";
            ?> onKeyPress="return focusNext('berat', event)"/>&nbsp;B&nbsp;&nbsp;
            <input type="radio" name="gol" id="gol" value="O" 
            <?php if ($gol=="O") 
                    echo "checked";
            ?> onKeyPress="return focusNext('berat', event)"/>&nbsp;O&nbsp;&nbsp;
            <input type="radio" name="gol" id="gol" value=""   
          	<?php if ($gol=="") 
                    echo "checked";
            ?> onKeyPress="return focusNext('berat', event)"/>&nbsp;<em>(belum ada data)</em></td>
  		</tr>
        <tr>
          	<td>Berat</td>
          	<td colspan="2">
				<input name="berat" type="text" size="6" maxlength="6" id="berat"
					   onFocus="showhint('Berat badan tidak boleh lebih dari 6 angka!', this, event, '120px');panggil('berat')"
					   value="<?=$berat?>" onKeyPress="return focusNext('tinggi', event)" onblur="unfokus('berat')"/>&nbsp;kg
			</td>
        </tr>
        <tr>
        	<td>Tinggi</td>
          	<td colspan="2">
				<input name="tinggi" type="text" size="6" maxlength="6" id="tinggi"
					   onFocus="showhint('Tinggi badan tidak boleh lebih dari 6 angka!', this, event, '120px');panggil('tinggi')"
					   value="<?=$tinggi?>"  onKeyPress="return focusNext('kesehatan', event)" onblur="unfokus('tinggi')"/>&nbsp;cm
			</td>
        </tr>
        <tr>
            <td valign="top">Riwayat Penyakit</td>
            <td colspan="2">
				<textarea name="kesehatan" id="kesehatan" rows="2" cols="30" class="Ukuranketerangan"
						  onFocus="showhint('Riwayat penyakit tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('kesehatan')"
						  onKeyPress="return focusNext('namaayah', event)" onblur="unfokus('kesehatan')"><?=$kesehatan?></textarea>
			</td>
        </tr>
        <tr>
            <td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Orangtua Siswa</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
          	<td align="center" valign="middle" bgcolor="#DBD8F3"><strong>Ayah</strong></td>
          	<td align="center" valign="middle" bgcolor="#E9AFCF"><strong>Ibu</strong></td>
      	</tr>
        <tr>
          	<td valign="top">Nama</td>
          	<td bgcolor="#DBD8F3">
            	<input name="namaayah" type="text" size="15" maxlength="100" id="namaayah"
					   onFocus="showhint('Nama Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('namaayah')"
					   value="<?=$namaayah?>" class="ukuran"  onKeyPress="return focusNext('namaibu', event)" onblur="unfokus('namaayah')"/>
              	<br />
              	<input type="checkbox" name="almayah" id="almayah" value="1" title="Klik disini jika Ayah Almarhum"  
					   <?php if ($almayah=="1") echo "checked"; ?> />
                &nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
			</td>
         	<td bgcolor="#E9AFCF">
            	<input name="namaibu" type="text" size="15" maxlength="100" id="namaibu"
					   onFocus="showhint('Nama Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('namaibu')"
					   value="<?=$namaibu?>" class="ukuran" onKeyPress="return focusNext('statusayah', event)" onblur="unfokus('namaibu')"/>
                <br />
                <input type="checkbox" name="almibu" id="almibu" value="1" title="Klik disini jika Ibu Almarhumah"
						<?php if ($almibu=="1") echo "checked";?>/>
                &nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
			</td>
     	</tr>
		<tr>
          	<td valign="top">Status Ortu</td>
          	<td bgcolor="#DBD8F3">
            	<select name="statusayah" id="statusayah" onKeyPress="return focusNext('statusibu', event)" onfocus="panggil('statusayah')" onblur="unfokus('statusayah')">
					<option value="Kandung">Ortu Kandung</option>
					<option value="Angkat">Ortu Angkat</option>
					<option value="Tiri">Ortu Tiri</option>
					<option value="Lainnya">Lainnya</option>
				</select>
			</td>
         	<td bgcolor="#E9AFCF">
            	<select name="statusibu" id="statusibu" onKeyPress="return focusNext('tmplahirayah', event)" onfocus="panggil('statusibu')" onblur="unfokus('statusibu')">
					<option value="Kandung">Ortu Kandung</option>
					<option value="Angkat">Ortu Angkat</option>
					<option value="Tiri">Ortu Tiri</option>
					<option value="Lainnya">Lainnya</option>
				</select>
			</td>
     	</tr>
		<tr>
          	<td valign="top">Tmp Lahir</td>
          	<td bgcolor="#DBD8F3">
            	<input name="tmplahirayah" type="text" size="15" maxlength="100" id="tmplahirayah"
					   onFocus="showhint('Tempat Lahir Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tmplahirayah')"
					   value="<?=$tmplahirayah?>" class="ukuran"  onKeyPress="return focusNext('tmplahiribu', event)" onblur="unfokus('tmplahirayah')"/>
			</td>
         	<td bgcolor="#E9AFCF">
            	<input name="tmplahiribu" type="text" size="15" maxlength="100" id="tmplahiribu"
					   onFocus="showhint('Tempat Lahir Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tmplahiribu')"
					   value="<?=$tmplahiribu?>" class="ukuran" onKeyPress="return focusNext('tgllahirayah', event)" onblur="unfokus('tmplahiribu')"/>
			</td>
     	</tr>
		<tr>
          	<td valign="top">Tgl Lahir</td>
          	<td bgcolor="#DBD8F3">
            	<input name="tgllahirayah" type="text" size="15" maxlength="100" id="tgllahirayah"
					   onFocus="showhint('Tanggal Lahir Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tgllahirayah')"
					   value="<?=$tgllahirayah?>" class="ukuran"  onKeyPress="return focusNext('tgllahiribu', event)" onblur="unfokus('tgllahirayah')"/>
			</td>
         	<td bgcolor="#E9AFCF">
            	<input name="tgllahiribu" type="text" size="15" maxlength="100" id="tgllahiribu"
					   onFocus="showhint('Tanggal Lahir Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tgllahiribu')"
					   value="<?=$tgllahiribu?>" class="ukuran" onKeyPress="return focusNext('Infopendidikanayah', event)" onblur="unfokus('tgllahiribu')"/>
			</td>
     	</tr>
        <tr>
        	<td>Pendidikan</td>
            <td bgcolor="#DBD8F3">
            	<div id = "pendidikanayahInfo">
            	<select name="pendidikanayah" id="Infopendidikanayah" class="ukuran"
						onKeyPress="return focusNext('Infopendidikanibu', event)" onfocus="panggil('Infopendidikanayah')" style="width:140px"
						onblur="unfokus('Infopendidikanayah')">
                <option value="">[Pilih Pendidikan]</option>
                <?php 
				$sql_pend_ayah="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
				$result_pend_ayah=QueryDB($sql_pend_ayah);
				while ($row_pend_ayah = mysqli_fetch_array($result_pend_ayah))
				{	?>
                          <option value="<?=$row_pend_ayah['pendidikan']?>" <?=StringIsSelected($row_pend_ayah['pendidikan'],$pendidikanayah)?>>
                          <?=$row_pend_ayah['pendidikan']?>
                          </option>
                          <?php
				} 
				?>
            	</select> 
                </div>
			</td>
        	<td bgcolor="#E9AFCF">
            	<table cellpadding="0" cellspacing="0">
            	<tr>
                	<td>
                    <div id = "pendidikanibuInfo">
                    <select name="pendidikanibu" id="Infopendidikanibu" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanayah', event)" onfocus="panggil('Infopendidikanibu')" style="width:140px" onblur="unfokus('Infopendidikanibu')">
						<option value="">[Pilih Pendidikan]</option>
						<?php 
						$sql_pend_ibu="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
						$result_pend_ibu=QueryDB($sql_pend_ibu);
						while ($row_pend_ibu = mysqli_fetch_array($result_pend_ibu)) {
						?>
							<option value="<?=$row_pend_ibu['pendidikan']?>" <?=StringIsSelected($row_pend_ibu['pendidikan'],$pendidikanibu)?>>
							<?=$row_pend_ibu['pendidikan']?>
							</option>
						<?php
						} 
						?>
                    </select>
                    </div>
					</td>
                    <td>
					<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                        <img src="../images/ico/tambah.png" onClick="tambah_pendidikan();" onMouseOver="showhint('Tambah Tingkat Pendidikan!', this, event, '80px')" />
                    <?php } ?>                    </td>
          		</tr>
                </table>          	</td>
    	</tr>
        <tr> 
        	<td>Pekerjaan</td>
           	<td bgcolor="#DBD8F3">
            	<div id="pekerjaanayahInfo">
            	<select name="pekerjaanayah" id="Infopekerjaanayah" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanibu', event)" onfocus="panggil('Infopekerjaanayah')" style="width:140px" onblur="unfokus('Infopekerjaanayah')">
                	<option value="">[Pilih Pekerjaan]</option>
					<?php 
					$sql_kerja_ayah="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
					$result_kerja_ayah=QueryDB($sql_kerja_ayah);
					while ($row_kerja_ayah = mysqli_fetch_array($result_kerja_ayah)) {
					?>
						<option value="<?=$row_kerja_ayah['pekerjaan']?>"<?=StringIsSelected($row_kerja_ayah['pekerjaan'],$pekerjaanayah)?>>
							<?=$row_kerja_ayah['pekerjaan']?>
							</option>
							<?php
					} 
					?>
                </select>
                </div>
			</td>
           	<td bgcolor="#E9AFCF">
            	<table cellpadding="0" cellspacing="0">
                <tr>
                	<td>
                    <div id = "pekerjaanibuInfo">
                    <select name="pekerjaanibu" id="Infopekerjaanibu"  class="ukuran"  onKeyPress="return focusNext('penghasilanayah1', event)" onfocus="panggil('Infopekerjaanibu')" style="width:140px" onblur="unfokus('Infopekerjaanibu')">
                        <option value="">[Pilih Pekerjaan]</option>
                        <?php 
						$sql_kerja_ibu="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
						$result_kerja_ibu=QueryDB($sql_kerja_ibu);
						while ($row_kerja_ibu = mysqli_fetch_array($result_kerja_ibu)) {
						?>
							<option value="<?=$row_kerja_ibu['pekerjaan']?>"<?=StringIsSelected($row_kerja_ibu['pekerjaan'],$pekerjaanibu)?>>
								  <?=$row_kerja_ibu['pekerjaan']?>
							</option>
						<?php
						} 
						?>
						</select>
                   	</div>
					</td>
                    <td>
                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <img src="../images/ico/tambah.png" onClick="tambah_pekerjaan();" onMouseOver="showhint('Tambah Jenis Pekerjaan!', this, event, '80px')" />
                    <?php }?>
					</td>
             	</tr>
                </table>
			</td> 
    	</tr> 
       
        <tr>
          	<td>Penghasilan</td>
          	<td bgcolor="#DBD8F3">
            	<input type="text" name="penghasilanayah1" id="penghasilanayah1"  maxlength="20" value="<?=FormatRupiah($penghasilanayah) ?>" onblur="formatRupiah('penghasilanayah1');unfokus('penghasilanayah1')" onfocus="unformatRupiah('penghasilanayah1');panggil('penghasilanayah1')" class="ukuran"  onKeyPress="return focusNext('penghasilanibu1', event)" onKeyUp="penghasilan_ayah()" >
				<input type="hidden" name="penghasilanayah" id="penghasilanayah" value="<?=$penghasilanayah?>">         	</td>
          	<td bgcolor="#E9AFCF">
            	<input type="text" name="penghasilanibu1" id="penghasilanibu1" maxlength="20" value="<?=FormatRupiah($penghasilanibu) ?>" onblur="formatRupiah('penghasilanibu1');unfokus('penghasilanibu1')" onfocus="unformatRupiah('penghasilanibu1');panggil('penghasilanibu1')" class="ukuran"  onKeyPress="return focusNext('emailayah', event)" onKeyUp="penghasilan_ibu()" />
				<input type="hidden" name="penghasilanibu" id="penghasilanibu" value="<?=$penghasilanibu?>">          	</td>
  		</tr>
       <tr>
          <td>Email Ortu</td>
          <td bgcolor="#DBD8F3"><input name="emailayah" type="text" size="15" maxlength="100" id="emailayah" onFocus="showhint('Alamat email Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailayah')" value="<?=$emailayah?>" class="ukuran" onKeyPress="return focusNext('emailibu', event)" onblur="unfokus('emailayah')" /></td>
          <td colspan="2" bgcolor="#E9AFCF"><input name="emailibu" type="text" size="15" maxlength="100" id="emailibu" onFocus="showhint('Alamat email Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailibu')" value="<?=$emailibu?>" class="ukuran" onKeyPress="return focusNext('namawali', event)" onblur="unfokus('emailibu')" /></td>
        </tr>
        <tr>
        	<td>Nama Wali</td>
          	<td colspan="2">
            	<input type="text" name="namawali" id="namawali" maxlength="100" value="<?=$namawali?>" size="30"  onKeyPress="return focusNext('alamatortu', event)" onfocus="panggil('namawali')" onblur="unfokus('namawali')"/></td>
        </tr>
        <tr>
          	<td valign="top">Alamat Orang Tua </td>
          	<td colspan="2">
            	<textarea name="alamatortu" id="alamatortu" rows="2" cols="30" class="Ukuranketerangan" onkeypress="return focusNext('telponortu', event)" onfocus="panggil('alamatortu')" onblur="unfokus('alamatortu')"><?=$alamatortu?></textarea></td>
      	</tr>
        <tr>
          	<td>Telepon Ortu</td>
          	<td colspan="2">
          	<input type="text" name="telponortu" id="telponortu" class="ukuran" maxlength="20" value="<?=$telponortu?>" onKeyPress="return focusNext('hportu', event)" onfocus="panggil('telponortu')" onblur="unfokus('telponortu')"/></td>
        </tr>
        <tr>
          	<td>HP Ortu #1</font></td>
          	<td align="left">
				<input type="text" name="hportu" id="hportu" class="ukuran" maxlength="20" value="<?=$hportu?>" onKeyPress="return focusNext('hportu2', event)" onfocus="panggil('hportu')" onblur="unfokus('hportu')"/>
			</td>
			<td rowspan="3" align="left" valign="top">
				<font style="color: blue; font-style: italic">tambahkan # supaya tidak digunakan di JIBAS SMS Gateway. contoh: #08123456789</font>	
			</td>
        </tr>
		<tr>
          	<td>HP Ortu #2</td>
          	<td align="left">
				<input type="text" name="hportu2" id="hportu2" class="ukuran" maxlength="20" value="<?=$hportu2?>"  onKeyPress="return focusNext('hportu3', event)" onfocus="panggil('hportu2')" onblur="unfokus('hportu2')"/>
			</td>
        </tr>
		<tr>
          	<td>HP Ortu #3</td>
          	<td align="left">
				<input type="text" name="hportu3" id="hportu3" class="ukuran" maxlength="20" value="<?=$hportu3?>"  onKeyPress="return focusNext('alamatsurat', event)" onfocus="panggil('hportu3')" onblur="unfokus('hportu3')"/>
			</td>
        </tr>
        <tr>
          	<td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Informasi Tambahan</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
            </td>
        </tr>
		<tr>
          	<td valign="top">Hobi</td>
          	<td colspan="2">
				<textarea name="hobi" id="hobi" rows="1" cols="30" style="height: 21px;" class="Ukuranketerangan"
						  onKeyPress="return focusNext('alamatsurat', event)" onfocus="panggil('hobi')"
						  onblur="unfokus('hobi')"><?=$alamatsurat?></textarea>
			</td>
        </tr>
        <tr>
          	<td valign="top">Alamat Surat</td>
          	<td colspan="2">
				<textarea name="alamatsurat" id="alamatsurat" rows="2" cols="30" class="Ukuranketerangan"
						  onKeyPress="return focusNext('keterangan', event)" onfocus="panggil('alamatsurat')"
						  onblur="unfokus('alamatsurat')"><?=$alamatsurat?></textarea>
			</td>
        </tr>
        <tr>
          	<td valign="top">Keterangan</td>
          	<td colspan="2">
				<textarea name="keterangan" id="keterangan" rows="2" cols="30" class="Ukuranketerangan"
						  onKeyPress="return focusNext('sum1', event)" onfocus="panggil('keterangan')"
						  onblur="unfokus('keterangan')"><?=$keterangan?></textarea>
			</td>
        </tr>
        <tr>
            <td height="30" colspan="3">
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
                <font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Tambahan Data</strong></font>
                <div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
            </td>
        </tr>
<?php
        $sql = "SELECT replid, kolom, jenis
                  FROM tambahandata 
                 WHERE aktif = 1
                   AND departemen = '$departemen'
                 ORDER BY urutan";
        $res = QueryDb($sql);
        $idtambahan = "";
        while($row = mysqli_fetch_row($res))
        {
            $replid = $row[0];
            $kolom = $row[1];
            $jenis = $row[2];

            if ($idtambahan != "") $idtambahan .= ",";
            $idtambahan .= $replid;

            if ($jenis == 3)
            {
                $sql = "SELECT pilihan 
                          FROM jbsakad.pilihandata 
                         WHERE idtambahan = '$replid'
                           AND aktif = 1
                         ORDER BY urutan";
                $res2 = QueryDb($sql);

                $arrList = [];
                if (mysqli_num_rows($res2) == 0)
                    $arrList[] = "-";

                while($row2 = mysqli_fetch_row($res2))
                {
                    $arrList[] = $row2[0];
                }

                $opt = "";
                for($i = 0; $i < count($arrList); $i++)
                {
                    $pilihan = CQ($arrList[$i]);
                    $opt .= "<option value='$pilihan'>$pilihan</option>";
                }
            }

            ?>
            <tr>
                <td valign="top"><?=$kolom?></td>
                <td colspan="2">
                    <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="0">
                    <?php if ($jenis == 1) { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="1">
                        <input type="text" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="40" maxlength="1000"/>
                    <?php } else if ($jenis == 2) { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="2">
                        <input type="file" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="25" style="width:215px"/>
                    <?php } else { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="3">
                        <select name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" style="width:215px">
                            <?= $opt ?>
                        </select>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
?>
        <input type="hidden" id="idtambahan" name="idtambahan" value="<?=$idtambahan?>">
      	</table>
        <!-- Akhir Kolom Kanan-->
 		</td>
</tr>
<tr>
	<td colspan="3" align="left">
<?php
	$sqlset = "SELECT COUNT(replid) FROM settingpsb WHERE idproses = $proses";
	$resset = QueryDb($sqlset);
	$rowset = mysqli_fetch_row($resset);
	$ndata = $rowset[0];
	
	if ($ndata > 0)
	{
		$sqlset = "SELECT * FROM settingpsb WHERE idproses = $proses";
		$resset = QueryDb($sqlset);
		$rowset = mysqli_fetch_array($resset);
		
		$kdsum1 = $rowset['kdsum1']; //$nmsum1 = $rowset['nmsum1'];
		$kdsum2 = $rowset['kdsum2']; //$nmsum2 = $rowset['nmsum2'];
		$kdujian1 = $rowset['kdujian1']; //$nmujian1 = $rowset['nmujian1'];
		$kdujian2 = $rowset['kdujian2']; //$nmujian2 = $rowset['nmujian2'];
		$kdujian3 = $rowset['kdujian3']; //$nmujian3 = $rowset['nmujian3'];
		$kdujian4 = $rowset['kdujian4']; //$nmujian4 = $rowset['nmujian4'];
		$kdujian5 = $rowset['kdujian5']; //$nmujian5 = $rowset['nmujian5'];
		$kdujian6 = $rowset['kdujian6'];
		$kdujian7 = $rowset['kdujian7'];
		$kdujian8 = $rowset['kdujian8'];
		$kdujian9 = $rowset['kdujian9'];
		$kdujian10 = $rowset['kdujian10'];
	}
?>
    
    <br />
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Sumbangan</strong></font>
	<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
    <table border="0" cellpadding="2" cellspacing="0" style="background-color:#FFFFF2">
    <tr>
    	<td width="170" align="left">Sumbangan #1 (<?=$kdsum1?>):</td>
        <td width="40" align="left"><input type="text" name="sum1" id="sum1" size="10" maxlength="15" onKeyPress="return focusNext('sum2', event)" onblur="formatRupiah('sum1')" onfocus="unformatRupiah('sum1')"  /> </td>
        <td width="170" align="left">&nbsp;&nbsp;Sumbangan #2 (<?=$kdsum2?>):</td>
        <td width="40" align="left"><input type="text" name="sum2" id="sum2" size="10" maxlength="15" onKeyPress="return focusNext('ujian1', event)" onblur="formatRupiah('sum2')" onfocus="unformatRupiah('sum2')" /> </td>
    </tr>
    </table>
    <br />
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Nilai Ujian</strong></font>
	<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
    <table border="0" cellpadding="2" cellspacing="0" style="background-color:#FFFFF2">
    <tr>
        <td width="120" align="left">Ujian #1 (<?=$kdujian1?>):</td>
        <td width="30" align="left"><input type="text" name="ujian1" id="ujian1" size="5" onKeyPress="return focusNext('ujian2', event)" maxlength="5" /> </td>
        <td width="120" align="left">Ujian #2 (<?=$kdujian2?>):</td>
        <td width="30" align="left"><input type="text" name="ujian2" id="ujian2" size="5" onKeyPress="return focusNext('ujian3', event)" maxlength="5" /> </td>   
        <td width="120" align="left">Ujian #3 (<?=$kdujian3?>):</td>
        <td width="30" align="left"><input type="text" name="ujian3" id="ujian3" size="5" onKeyPress="return focusNext('ujian4', event)" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #4 (<?=$kdujian4?>):</td>
        <td width="30" align="left"><input type="text" name="ujian4" id="ujian4" size="5" onKeyPress="return focusNext('ujian5', event)" maxlength="5" /> </td>
    </tr>
    <tr>    
        <td width="120" align="left">Ujian #5 (<?=$kdujian5?>):</td>
        <td width="30" align="left"><input type="text" name="ujian5" id="ujian5" size="5" onKeyPress="return focusNext('ujian6', event)" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #6 (<?=$kdujian6?>):</td>
        <td width="30" align="left"><input type="text" name="ujian6" id="ujian6" size="5" onKeyPress="return focusNext('ujian7', event)" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #7 (<?=$kdujian7?>):</td>
        <td width="30" align="left"><input type="text" name="ujian7" id="ujian7" size="5" onKeyPress="return focusNext('ujian8', event)" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #8 (<?=$kdujian8?>):</td>
        <td width="30" align="left"><input type="text" name="ujian8" id="ujian8" size="5" onKeyPress="return focusNext('ujian9', event)" maxlength="5" /> </td>
    </tr>
	<tr>
		<td width="120" align="left">Ujian #9 (<?=$kdujian9?>):</td>
        <td width="30" align="left"><input type="text" name="ujian9" id="ujian9" size="5" onKeyPress="return focusNext('ujian10', event)" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #10 (<?=$kdujian10?>):</td>
        <td width="30" align="left"><input type="text" name="ujian10" id="ujian10" size="5" onKeyPress="return focusNext('Simpan', event)" maxlength="5" /> </td>
		<td width="120" align="left">&nbsp;</td>
        <td width="30" align="left">&nbsp;</td>
		<td width="120" align="left">&nbsp;</td>
        <td width="30" align="left">&nbsp;</td>
	</tr>
    </table>
    
    </td>
</tr>
<tr height="50">
  	<td valign="middle" align="right">
  	<input type="Submit" value="Simpan" name="Simpan" class="but" id="Simpan" onfocus="panggil('Simpan')" onblur="unfokus('Simpan')"/></td>
  	<td align="center" valign="middle"></td>
  	<td valign="middle"><input class="but" type="button" value="Tutup" name="Tutup"  onClick="tutup()" />
  	</td>
</tr>
</table>
</form>
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
<?php
CloseDb();
?>