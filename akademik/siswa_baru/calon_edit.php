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

$replid = $_REQUEST['replid'];

OpenDb();

$sql = "SELECT c.nopendaftaran, c.nama, c.panggilan, c.tahunmasuk, c.idproses, c.idkelompok, c.suku, c.agama,
			   c.status, c.kondisi as kondisi, c.kelamin, c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan,
			   YEAR(c.tgllahir) AS tahun, c.warga, c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto,
			   c.alamatsiswa, c.kodepossiswa, c.telponsiswa, c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah,
			   c.namaayah, c.namaibu, c.almayah, c.almibu, c.pendidikanayah, c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu,
			   c.wali, c.penghasilanayah, c.penghasilanibu, c.alamatortu, c.telponortu, c.hportu, c.info1, c.info2, c.emailayah, c.emailibu,
			   c.alamatsurat, c.keterangan, p.replid AS proses, p.departemen, p.kodeawalan, k.replid AS kelompok,
			   c.sum1, c.sum2, c.ujian1, c.ujian2, c.ujian3, c.ujian4, c.ujian5, c.ujian6, c.ujian7, c.ujian8, c.ujian9, c.ujian10, c.nisn,
			   c.nik, c.noun, c.statusanak, c.jkandung, c.jtiri, c.noijasah, c.tglijasah,
			   c.statusayah, c.statusibu, c.tmplahirayah, c.tmplahiribu, c.tgllahirayah, c.tgllahiribu, c.hobi, c.jarak
		  FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p
		 WHERE c.replid = '$replid'
		   AND p.replid = c.idproses
		   AND k.replid = c.idkelompok
		   AND p.replid = k.idproses";

$result = QueryDB($sql);
$row_siswa = mysqli_fetch_array($result); 

$departemen = $row_siswa['departemen'];
$proses = $row_siswa['proses'];
$kelompok = $row_siswa['kelompok'];
$kelompok_lama = $row_siswa['kelompok'];
$no = $row_siswa['nopendaftaran'];
$sum1 = $row_siswa['sum1'];
$sum2 = $row_siswa['sum2'];
$ujian1 = $row_siswa['ujian1'];
$ujian2 = $row_siswa['ujian2'];
$ujian3 = $row_siswa['ujian3'];
$ujian4 = $row_siswa['ujian4'];
$ujian5 = $row_siswa['ujian5'];
$ujian6 = $row_siswa['ujian6'];
$ujian7 = $row_siswa['ujian7'];
$ujian8 = $row_siswa['ujian8'];
$ujian9 = $row_siswa['ujian9'];
$ujian10 = $row_siswa['ujian10'];

$tahunmasuk = $row_siswa['tahunmasuk'];
$blnlahir = (int)$row_siswa['bulan'];
$thnlahir = (int)$row_siswa['tahun'];

if ($row_siswa['asalsekolah'] <> NULL) 
{
	$query = "SELECT departemen FROM asalsekolah WHERE sekolah = '".$row_siswa['asalsekolah']."'";
	$hasil = QueryDb($query);	
	$row = mysqli_fetch_array($hasil);
	$dep_asal = $row['departemen'];
	$sekolah = $row_siswa['asalsekolah'];
}
else 
{	
	$dep_asal = "";
	$sekolah = "";
}


if ($blnlahir == 4 || $blnlahir == 6|| $blnlahir == 9 || $blnlahir == 11 ) 
	$n = 30;
else if ($blnlahir == 2 && $thnlahir % 4 <> 0) 
	$n = 28;
else if ($blnlahir == 2 && $thnlahir % 4 == 0) 
	$n = 29;
else 
	$n = 31;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Ubah Calon Siswa]</title>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript" src="calon_edit.js"></script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onLoad="document.getElementById('nisn').focus()">

<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="left" style="color:#fff08c; font-size:14px; font-weight:bold">
    Edit Calon Siswa
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
	<form name="main" method="post" action="calon_simpan.php" onsubmit="return validate()" enctype="multipart/form-data">
	<input type="hidden" name="replid" id="replid" value="<?=$replid?>">
	<input type="hidden" name="action" id="action" value="ubah">
    <input type="hidden" name="kel_lama" id="kel_lama" value="<?=$kelompok_lama?>">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr>
	<td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr>
    	<td>
       	<table width="96%" border="0" >
 		<tr>
    		<td width="27%"><strong>Departemen</strong></td>
    		<td width="*"><div id="InfoDepartemen">
            <select name="departemen" id="departemen" onchange="change_dep()" style="width:280px" onKeyPress="return focusNext('kelompok',event)" onFocus="panggil('departemen')">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
				foreach($dep as $value) {
					if ($departemen == "")
						$departemen = $value; ?>
              <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
              <?=$value ?>
              </option>
              <?php } ?>
            </select>
            </div>
            </td>          
  		</tr>
  		
        <tr>
        	<td><strong>Proses Penerimaan</strong></td>
    		<td><div id="InfoProses">
            <?php $sql = "SELECT replid,proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='$departemen'";				
				$result = QueryDb($sql);				
				$row = mysqli_fetch_array($result);
				$proses = $row['replid'];
			?>
            <input type="text" name="nama_proses" id="nama_proses" style="width:270px;" class="disabled" value="<?=$row['proses']?>" readonly />
            <input type="hidden" name="proses" id="proses"  value="<?=$proses?>" />
            	</div>            </td>
  		</tr>
  		<tr>
    		<td><strong>Kelompok Calon Siswa</strong></td>
    		<td>
            	<div id = "InfoKelompok">
            	<select name="kelompok" id="kelompok" onchange="change_kel()" style="width:280px" onKeyPress="return focusNext('nama',event)" onFocus="panggil('kelompok')">
   		 	<?php 
				$sql = "SELECT replid,kelompok,kapasitas FROM kelompokcalonsiswa WHERE idproses = $proses ORDER BY kelompok";
				$result = QueryDb($sql);
				
				while ($row = @mysqli_fetch_array($result)) {
					
										
					$sql1 = "SELECT COUNT(replid) FROM calonsiswa WHERE idkelompok = ".$row['replid']." AND aktif = 1";
					$result1 = QueryDb($sql1);				
					$row1 = mysqli_fetch_row($result1);
					
					if ($kelompok == "")
						$kelompok = $row['replid'];
					
			?>
    			<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelompok)?> ><?=$row['kelompok'].', kapasitas: '.$row['kapasitas'] .', terisi: '.$row1[0]?>
                </option>
    		<?php
				//$cnt++; 
				}	
    		?>
    			</select>&nbsp;<img src="../images/ico/tambah.png" onclick="tampil_kelompok();" onMouseOver="showhint('Tambah Kelompok!', this, event, '60px')" />            	
                	
                </div>  
           	</td>
  		</tr>
  		<tr>
    		<td valign="top"><strong>Keterangan</strong></td>
    		<td>
            	<div id = "InfoKeterangan">
			<?php 
				$sql = "SELECT keterangan FROM kelompokcalonsiswa WHERE replid = $kelompok";
				$result = QueryDb($sql);
				
				$row = @mysqli_fetch_array($result);			
			?>
              <textarea name="keterangan1" id="keterangan1" rows="2" cols="60" readonly style="background-color:#E5F7FF" ><?=$row['keterangan'] ?>
              </textarea>
              <?php
			  $sql_cek_kap="SELECT kapasitas FROM jbsakad.kelompokcalonsiswa WHERE replid=$kelompok";
			  $res_cek_kap=QueryDb($sql_cek_kap);
			  $row_cek_kap=@mysqli_fetch_array($res_cek_kap);
			  
			  $sql_cek_jum = "SELECT COUNT(replid) FROM calonsiswa WHERE idkelompok = $kelompok AND aktif = 1";
			  $res_cek_jum = QueryDb($sql_cek_jum);				
			  $row_cek_jum = mysqli_fetch_row($res_cek_jum);
			  ?>
			  <input type="hidden" name="kapasitas" id="kapasitas" value="<?=$row_cek_kap['kapasitas']?>" />
			  <input type="hidden" name="isi" id="isi" value="<?=$row_cek_jum[0]?>" />
              
            	</div>        	</td>
  		</tr>
		</table>  
		
      	</td>
        <td align="right"><img src="../library/gambar.php?replid=<?=$replid?>&table=calonsiswa"  border="0"/>
        </td>
  	</tr>
	</table>
	<br />
  	<table width="100%" border="0" cellspacing="0">
 	<tr>
    	<td width="45%" valign="top"><!--Kolom Kiri-->      
		<table width="100%" border="0" cellspacing="0" id="table">
  		<tr>
    		<td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Calon Siswa</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
			</td>
		</tr>
  		<tr>
    		<td width="22%"><strong>Pendaftaran</strong></td>
    		<td colspan="2">
            	<input type="text" name="kode" id="kode" size="20" class="disabled" value="<?=$no ?>" readonly />
                <input type="hidden" name="no" id="no" value="<?=$no?>" />
			</td>
  		</tr>
  		<tr>
    		<td><strong>Tahun Masuk</strong></td>
    		<td colspan="2">
            	<input type="text" name="masuk" id="masuk" size="5" maxlength="4" value="<?=$tahunmasuk?>" readonly="readonly" class="disabled"/> 
            	<input type="hidden" name="tahunmasuk" id="tahunmasuk" value="<?=$tahunmasuk?>" />
			</td>
  		</tr>
  		<tr>
			<td>N I S N</td>
			<td colspan="2">
				<input type="text" name="nisn" id="nisn" size="30" maxlength="100"  value="<?=$row_siswa['nisn']?>"
					   onfocus="showhint('NISN tidak boleh lebih dari 50 karakter!', this, event, '120px');panggil('nisn');"
					   onKeyPress="return focusNext('nik', event);" onblur="unfokus('nisn')"/>
			</td>
		</tr>
		<tr>
			<td>N I K</td>
			<td colspan="2">
				<input type="text" name="nik" id="nik" size="30" maxlength="100"  value="<?=$row_siswa['nik']?>"
					   onfocus="showhint('NIK tidak boleh lebih dari 50 karakter!', this, event, '120px'); panggil('nik');"
					   onKeyPress="return focusNext('noun', event);" onblur="unfokus('nik')"/>
			</td>
		</tr>
		<tr>
			<td>No. UN Sebelumnya</td>
			<td colspan="2">
				<input type="text" name="noun" id="noun" size="30" maxlength="100"  value="<?=$row_siswa['noun']?>"
					   onfocus="showhint('NIK tidak boleh lebih dari 50 karakter!', this, event, '120px'); panggil('noun');"
					   onKeyPress="return focusNext('nama', event);" onblur="unfokus('noun')"/>
			</td>
		</tr>
  		<tr>
    		<td><strong>Nama</strong></td>
    		<td colspan="2">
				<input type="text" name="nama" id="nama" size="30" maxlength="100"  value="<?=$row_siswa['nama']?>"
					   onfocus="showhint('Nama Lengkap Siswa tidak boleh kosong!', this, event, '120px');panggil('nama');"
					   onKeyPress="return focusNext('panggilan', event);" onblur="unfokus('nama')"/>
			</td>
  		</tr>
  		<tr>
    		<td>Panggilan</td>
    		<td colspan="2">
				<input type="text" name="panggilan" id="panggilan" size="30" maxlength="30"
					   onFocus="showhint('Nama Panggilan tidak boleh lebih dari 30 karakter!', this, event, '120px');panggil('panggilan');" value="<?=$row_siswa['panggilan']?>"
					   onKeyPress="return focusNext('kelamin', event)" onblur="unfokus('panggilan')"/>
			</td>
  		</tr>
  		<tr>
    		<td>Jenis Kelamin</td>
    		<td colspan="3">
				<input type="radio" id="kelamin" name="kelamin" value="l"
					<?php if ($row_siswa['kelamin']=="l") echo "checked='checked'"; ?>
					onKeyPress="return focusNext('tmplahir', event)" />&nbsp;Laki-laki&nbsp;&nbsp;
				<input type="radio" id="kelamin" name="kelamin" value="p" 
					<?php if ($row_siswa['kelamin']=="p") echo "checked ='checked'"; ?>
					onKeyPress="return focusNext('tmplahir', event)" />&nbsp;Perempuan
			</td>
		</tr>
  		<tr>
			<td>Tempat Lahir</td>
			<td colspan="2">
				<input type="text" name="tmplahir" id="tmplahir" size="30" maxlength="50"
					   onFocus="showhint('Tempat Lahir tidak boleh kosong!', this, event, '120px');panggil('tmplahir')"
					   value="<?=$row_siswa['tmplahir']?>"  onKeyPress="return focusNext('tgllahir', event)" onblur="unfokus('tmplahir')" />
			</td>
  		</tr>
  		<tr>
    		<td>Tanggal Lahir</td>
    		<td colspan="2">
            	<table cellpadding="0" cellspacing="0" border="0">
                <tr>
                	<td>
						<div id="tgl_info">
						<select name="tgllahir" id="tgllahir" onKeyPress="return focusNext('blnlahir', event)" onfocus="panggil('tgllahir')" onblur="unfokus('tgllahir')" >                              
						<option value="">[Tgl]</option>
						<?php 	for ($tgl=1;$tgl<=$n;$tgl++){ ?>
							<option value="<?=$tgl?>" <?=IntIsSelected($tgl, $row_siswa['tanggal'])?>><?=$tgl?></option>
						<?php }	?>
						</select>
						</div>
					</td>
                    <td>
						<select name="blnlahir" id="blnlahir" onKeyPress="return focusNext('thnlahir', event)" onChange="change_bln()" onfocus="panggil('blnlahir')" onblur="unfokus('blnlahir')" />
						<option value="">[Bln]</option>
						<?php 	for ($i=1;$i<=12;$i++) { ?>
							<option value="<?=$i?>" <?=IntIsSelected($i, $row_siswa['bulan'] )?>><?=NamaBulan($i)?></option>	
						<?php } ?>
						</select>
						<input type="text" name="thnlahir" id="thnlahir" size="5" maxlength="4" onFocus="showhint('Tahun Lahir tidak boleh kosong!', this, event, '120px');panggil('thnlahir')" value="<?=$row_siswa['tahun']?>" onKeyPress="return focusNext('agama', event)" onblur="unfokus('thnlahir')"/>
					</td>
             	</tr>
                </table>
			</td>
  		</tr>
        <tr>
    		<td>Agama</td>
    		<td colspan="2">
    		<div id="InfoAgama">
				<select name="agama" id="agama" class="ukuran"  onKeyPress="return focusNext('suku', event)" onfocus="panggil('agama')" onblur="unfokus('agama')">
				<option value="">[Pilih Agama]</option>
				<?php 
				$sql_agama="SELECT replid,agama,urutan FROM jbsumum.agama ORDER BY urutan";
				$result_agama=QueryDB($sql_agama);
				while ($row_agama = mysqli_fetch_array($result_agama)) {
				?>
				<option value="<?=$row_agama['agama']?>" <?=StringIsSelected($row_agama['agama'], $row_siswa['agama'])?>><?=$row_agama['agama']?></option>
				<?php
				} 
				?>
				</select>
				<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
				<img src="../images/ico/tambah.png" onclick="tambah_agama();"  onMouseOver="showhint('Tambah Agama!', this, event, '50px')"/>
				<?php } ?>
            </div>
			</td>
  		</tr>
  		<tr>
    		<td>Suku</td>
    		<td colspan="2">
    		<div id="InfoSuku">
				<select name="suku" id="suku" class="ukuran" onKeyPress="return focusNext('status', event)" onfocus="panggil('suku')" onblur="unfokus('suku')">            
				<option value="">[Pilih Suku]</option>
				<?php 
				$sql_suku="SELECT suku,urutan,replid FROM jbsumum.suku ORDER BY urutan";
				$result_suku=QueryDB($sql_suku);
				while ($row_suku = mysqli_fetch_array($result_suku)) {				
				?>
				<option value="<?=$row_suku['suku']?>" <?=StringIsSelected($row_suku['suku'], $row_siswa['suku'])?>><?=$row_suku['suku']?></option>
				<?php
				} 
				?>
				</select>
				<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
				<img src="../images/ico/tambah.png" onclick="tambah_suku();"  onMouseOver="showhint('Tambah Suku!', this, event, '50px')"/>
				<?php } ?></div>
			</td>
  		</tr>
  		<tr>
    		<td>Status</td>
    		<td colspan="2">
    		<div id="InfoStatus">
				<select name="status" id="status" class="ukuran"  onKeyPress="return focusNext('kondisi', event)" onfocus="panggil('status')" onblur="unfokus('status')">
				<option value="">[Pilih Status]</option>
				<?php 
				$sql_status="SELECT replid,status,urutan FROM jbsakad.statussiswa ORDER BY urutan";
				$result_status=QueryDB($sql_status);
				while ($row_status = mysqli_fetch_array($result_status)) {
				?>
				<option value="<?=$row_status['status']?>" <?=StringIsSelected($row_status['status'], $row_siswa['status'])?>><?=$row_status['status']?></option>
				<?php
				} 
				?>
				</select>
				<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
				<img src="../images/ico/tambah.png" onclick="tambah_status();"  onMouseOver="showhint('Tambah Status!', this, event, '50px')"/>
				<?php } ?>
				</div>
			</td>
 		</tr>
  		<tr>
    		<td>Kondisi</td>
    		<td colspan="2">
    		<div id="InfoKondisi">
				<select name="kondisi" id="kondisi" class="ukuran"  onKeyPress="return focusNext('warga', event)" onfocus="panggil('kondisi')" onblur="unfokus('kondisi')">
				<option value="">[Pilih Kondisi]</option>
				<?php 
				$sql_kondisi="SELECT kondisi,urutan FROM jbsakad.kondisisiswa ORDER BY urutan";
				$result_kondisi=QueryDB($sql_kondisi);
				while ($row_kondisi = mysqli_fetch_array($result_kondisi)) {
				?>
					<option value="<?=$row_kondisi['kondisi']?>" <?=StringIsSelected($row_kondisi['kondisi'], $row_siswa['kondisi'])?>>
					  <?=$row_kondisi['kondisi']?>
					</option>
				<?php
				} 
				?>
				</select>
				<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
				<img src="../images/ico/tambah.png" onclick="tambah_kondisi();" onMouseOver="showhint('Tambah Kondisi!', this, event, '50px')"/>
				<?php } ?>
            </div>
			</td>
  		</tr>
  		<tr>
    		<td>Kewarganegaraan</td>
    		<td colspan="2">
    		<input type="radio" name="warga" id="warga" value="WNI"
				<?php if ($row_siswa['warga']=="WNI") echo "checked='checked'";?>
				onKeyPress="return focusNext('urutananak', event)" />&nbsp;WNI&nbsp;&nbsp;
    		<input type="radio" name="warga" id="warga" value="WNA" 
				<?php if ($row_siswa['warga']=="WNA") echo "checked='checked'";?>
				onKeyPress="return focusNext('urutananak', event)" />&nbsp;WNA</td>
  		</tr>
  		<tr>
    		<td>Anak ke</td>
    		<td colspan="2">
				<input type="text" name="urutananak" id="urutananak" size="3" maxlength="3"
					   onFocus="showhint('Urutan anak tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('urutananak')"
					   value="<?=$row_siswa['anakke']?>" onKeyPress="return focusNext('jumlahanak', event)" onblur="unfokus('urutananak')" />
				&nbsp;dari&nbsp;
				<input type="text" name="jumlahanak" id="jumlahanak" size="3" maxlength="3"
					   onFocus="showhint('Jumlah saudara tidak boleh lebih dari 3 angka!', this, event, '120px');panggil('jumlahanak')"
					   value="<?=$row_siswa['jsaudara'];?>" onKeyPress="return focusNext('statusanak', event)" onblur="unfokus('jumlahanak')" />&nbsp;bersaudara
			</td>
  		</tr>
		<tr>
			<td>Status Anak</td>
    		<td colspan="2">
				<select name="statusanak" id="statusanak" onKeyPress="return focusNext('jkandung', event)"
						onfocus="panggil('statusanak')" onblur="unfokus('statusanak')">
					<option value="Kandung" <?= $row_siswa['statusanak'] == "Kandung" ? "selected" : "" ?>>Anak Kandung</option>
					<option value="Angkat" <?= $row_siswa['statusanak'] == "Angkat" ? "selected" : "" ?>>Anak Angkat</option>
					<option value="Tiri" <?= $row_siswa['statusanak'] == "Tiri" ? "selected" : "" ?>>Anak Tiri</option>
					<option value="Lainnya" <?= $row_siswa['statusanak'] == "Lainnya" ? "selected" : "" ?>>Lainnya</option>
				</select>
			</td>
		</tr>
		<tr>
    		<td>Jml. Saudara Kandung</td>
    		<td colspan="2">
				<input type="text" name="jkandung" id="jkandung" size="3" maxlength="3"
					   onFocus="showhint('Urutan jumlah saudara kandung tidak boleh lebih dari 3 angka!', this, event, '120px'); panggil('jkandung')"
					   value="<?=$row_siswa['jkandung']?>" onKeyPress="return focusNext('jtiri', event)" onblur="unfokus('jkandung')" />&nbsp;orang
			</td>
  		</tr>
		<tr>
    		<td>Jml. Saudara Tiri</td>
    		<td colspan="2">
				<input type="text" name="jtiri" id="jtiri" size="3" maxlength="3"
					   onFocus="showhint('Urutan jumlah saudara tiri tidak boleh lebih dari 3 angka!', this, event, '120px'); panggil('jtiri')"
					   value="<?=$row_siswa['jtiri']?>" onKeyPress="return focusNext('bahasa', event)" onblur="unfokus('jtiri')" />&nbsp;orang
			</td>
  		</tr>
  		<tr>
    		<td>Bahasa</td>
    		<td colspan="2">
				<input type="text" name="bahasa" id="bahasa" size="30" maxlength="60"
					   onFocus="showhint('Bahasa anak tidak boleh lebih dari 60 karakter!', this, event, '120px');panggil('bahasa')"
					   value="<?=$row_siswa['bahasa']?>" class="ukuran"  onKeyPress="return focusNext('alamatsiswa', event)" onblur="unfokus('bahasa')" />
			</td>
  		</tr>
  		<tr>
    		<td>Foto</td>
    		<td colspan="2">
				<input type="file" name="nama_foto" id="file" size="25" />
			</td>
  		</tr>
  		<tr>
    		<td>Alamat</td>
    		<td colspan="2">
				<textarea name="alamatsiswa" id="alamatsiswa" rows="2" cols="30"
						  onFocus="showhint('Alamat siswa tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('alamatsiswa')"
						  class="Ukuranketerangan" onKeyUp="change_alamat()"  onKeyPress="return focusNext('kodepos', event)"
						  onblur="unfokus('alamatsiswa')" ><?=$row_siswa['alamatsiswa']?></textarea>
			</td>
  		</tr>
  		<tr>
    		<td>Kode Pos</td>
    		<td colspan="2">
				<input type="text" name="kodepos" id="kodepos" size="5" maxlength="8"
					   onFocus="showhint('Kodepos tidak boleh lebih dari 8 angka!', this, event, '120px');panggil('kodepos')"
					   value="<?=$row_siswa['kodepossiswa'];?>"  onKeyPress="return focusNext('jarak', event)"  onblur="unfokus('kodepos')"/>
			</td>
  		</tr>
		<tr>
    		<td>Jarak ke Sekolah</td>
    		<td colspan="2">
				<input type="text" name="jarak" id="jarak" size="4" maxlength="4"
					   onFocus="showhint('Jarak ke sekolah tidak boleh lebih dari 4 angka!', this, event, '120px');panggil('jarak')"
					   value="<?=$row_siswa['jarak'];?>"  onKeyPress="return focusNext('telponsiswa', event)"  onblur="unfokus('jarak')"/>&nbsp;km
			</td>
  		</tr>
  		<tr>
    		<td>Telepon</td>
    		<td colspan="2">
				<input type="text" name="telponsiswa" id="telponsiswa" size="20" maxlength="25"
					   onFocus="showhint('Nomor Telepon tidak boleh lebih dari 20 angka!', this, event, '120px');panggil('telponsiswa')"
					   value="<?=$row_siswa['telponsiswa'];?>" class="ukuran"  onKeyPress="return focusNext('hpsiswa', event)" onblur="unfokus('telponsiswa')" />
			</td>
  		</tr>
  		<tr>
    		<td>Handphone</td>
    		<td colspan="2">
				<input type="text" name="hpsiswa" id="hpsiswa" size="20" maxlength="25"
					   onFocus="showhint('Nomor ponsel tidak boleh lebih dari 20 angka!', this, event, '120px');panggil('hpsiswa');"
					   value="<?=$row_siswa['hpsiswa'];?>" class="ukuran"  onKeyPress="return focusNext('emailsiswa', event)" onblur="unfokus('hpsiswa')" />
			</td>
  		</tr>
  		<tr>
    		<td>Email</td>
    		<td colspan="2">
				<input type="text" name="emailsiswa" id="emailsiswa" size="30" maxlength="100"
					   onFocus="showhint('Alamat email tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailsiswa')"
					   value="<?=$row_siswa['emailsiswa']?>"  onKeyPress="return focusNext('dep_asal', event)" onblur="unfokus('emailsiswa')" />
			</td>
  		</tr>
  		<tr>
    		<td rowspan="2" valign="top">Asal Sekolah</td>
    		<td>
			<div id="depInfo">
				<select name="dep_asal" id="dep_asal" onchange="change_departemen(0)" onKeyPress="return focusNext('sekolah', event)"  style="width:150px;" onfocus="panggil('dep_asal')" onblur="unfokus('dep_asal')">
				<option value="">[Pilih Departemen]</option>	
				<?php 
				$sql_departemen="SELECT DISTINCT departemen FROM jbsakad.asalsekolah ORDER BY departemen";   			
				$result_departemen=QueryDB($sql_departemen);			
				while ($row_departemen = mysqli_fetch_array($result_departemen)) {			
				?>
				<option value="<?=$row_departemen['departemen']?>" <?=StringIsSelected($row_departemen['departemen'], $dep_asal)?>><?=$row_departemen['departemen']?></option>
				<?php 	} 
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
					<select name="sekolah" id="sekolah"  onKeyPress="return focusNext('noijasah', event)"
							style="width:150px;" onfocus="panggil('sekolah')" onblur="unfokus('sekolah')">
					<option value="">[Pilih Asal Sekolah]</option>	
					<?php 
					$sql_sekolah="SELECT sekolah FROM jbsakad.asalsekolah WHERE departemen='$dep_asal' ORDER BY sekolah";
					$result_sekolah=QueryDB($sql_sekolah);
					while ($row_sekolah = mysqli_fetch_array($result_sekolah)) {
					?>
					<option value="<?=$row_sekolah['sekolah']?>" <?=StringIsSelected($row_sekolah['sekolah'], $sekolah)?>>
					<?=$row_sekolah['sekolah']?>
					</option>
					<?php } ?>
					</select>
				</div>
				</td>
                <td valign="middle">
					<img src="../images/ico/tambah.png" onclick="tambah_asal_sekolah();" onMouseOver="showhint('Tambah Asal Sekolah!', this, event, '80px')"/>
				</td>
            </tr>
            </table>
			</td>
		</tr>
		<tr>
    		<td>No. Ijasah</td>
    		<td colspan="2">
				<input type="text" name="noijasah" id="noijasah" size="45" maxlength="45"
					   onFocus="showhint('No Ijasah tidak boleh lebih dari 45 digit!', this, event, '120px'); panggil('noijasah')"
					   value="<?=$row_siswa['noijasah'];?>" class="ukuran"  onKeyPress="return focusNext('tglijasah', event)" onblur="unfokus('noijasah')" />
			</td>
  		</tr>
		<tr>
    		<td>Tgl. Ijasah</td>
    		<td colspan="2">
				<input type="text" name="tglijasah" id="tglijasah" size="25" maxlength="25"
					   onFocus="showhint('No Ijasah tidak boleh lebih dari 25 digit!', this, event, '120px'); panggil('tglijasah')"
					   value="<?=$row_siswa['tglijasah'];?>" class="ukuran"  onKeyPress="return focusNext('ketsekolah', event)" onblur="unfokus('tglijasah')" />
			</td>
  		</tr>
 		<tr>
    		<td valign="top">Keterangan Asal <br />Sekolah</td>
    		<td colspan="2">
				<textarea name="ketsekolah" id="ketsekolah"
						  onFocus="showhint('Keterangan sekolah asal tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('ketsekolah')"
						  class="Ukuranketerangan"  onKeyPress="return focusNext('gol', event)" onblur="unfokus('ketsekolah')" ><?=$row_siswa['ketsekolah']?></textarea>
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
    		<td width="20%" valign="top">Gol. Darah</td>
    		<td colspan="2">
                <input type="radio" name="gol" id="gol" value="A" <?php 
                if ($row_siswa['darah']=="A")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;A&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="AB" <?php 
                if ($row_siswa['darah']=="AB")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;AB&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="B" <?php 
                if ($row_siswa['darah']=="B")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;B&nbsp;&nbsp;
                <input type="radio" name="gol" id="gol" value="O" <?php 
                if ($row_siswa['darah']=="O")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;O&nbsp;&nbsp;	
                <input type="radio" name="gol" id="gol" value="" <?php 
                if ($row_siswa['darah']=="")
                echo "checked='checked'";
                ?> onKeyPress="return focusNext('berat', event)" />&nbsp;<em>(belum ada data)</em>&nbsp;         	
           	</td>
 		</tr>
  		<tr>
    		<td>Berat</td>
    		<td colspan="2">
				<input name="berat" id="berat" type="text" size="6" maxlength="6"
					   onFocus="showhint('Berat badan tidak boleh lebih dari 6 angka!', this, event, '120px');panggil('berat')"
					   value="<?=$row_siswa['berat']?>" onKeyPress="return focusNext('tinggi', event)" onblur="unfokus('berat')" />&nbsp;kg
			</td>
  		</tr>
        <tr>
        	<td>Tinggi</td>
          	<td colspan="2">
				<input name="tinggi" id="tinggi" type="text" size="6" maxlength="6"
					   onFocus="showhint('Tinggi badan tidak boleh lebih dari 6 angka!', this, event, '120px');panggil('tinggi')"
					   value="<?=$row_siswa['tinggi']?>"  onKeyPress="return focusNext('kesehatan', event)" onblur="unfokus('tinggi')"/>&nbsp;cm
			</td>
        </tr>
        <tr>
            <td valign="top">Riwayat Penyakit</td>
            <td colspan="2">
				<textarea name="kesehatan" id="kesehatan" rows="2" cols="30" class="Ukuranketerangan"
						  onFocus="showhint('Riwayat penyakit tidak boleh lebih dari 255 karakter!', this, event, '120px');panggil('kesehatan')"
						  onKeyPress="return focusNext('namaayah', event)" onblur="unfokus('kesehatan')"><?=$row_siswa['kesehatan']?></textarea>
			</td>
        </tr>
  		<tr>
    		<td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Orangtua</strong></font>
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
					   value="<?=$row_siswa['namaayah']?>" class="ukuran"  onKeyPress="return focusNext('namaibu', event)" onblur="unfokus('namaayah')" />
            	<br />
                <input type="checkbox" name="almayah" id="almayah" value="1" title="Klik disini jika Ayah Almarhum"  
					<?php if ($row_siswa['almayah']=="1") echo "checked";?> />
					&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
			</td>
          	<td colspan="2" bgcolor="#E9AFCF">
				<input name="namaibu" type="text" size="15" maxlength="100" id="namaibu"
					   onFocus="showhint('Nama Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('namaibu')"
					   value="<?=$row_siswa['namaibu']?>" class="ukuran"  onKeyPress="return focusNext('statusayah', event)" onblur="unfokus('namaibu')" />
            	<br />
                <input type="checkbox" name="almibu" id="almibu" value="1" title="Klik disini jika Ibu Almarhumah"
					<?php if ($row_siswa['almibu']=="1") echo "checked";?>/>
					&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
			</td>
        </tr>
		<tr>
          	<td valign="top">Status Ortu</td>
          	<td bgcolor="#DBD8F3">
            	<select name="statusayah" id="statusayah" onKeyPress="return focusNext('statusibu', event)"
						onfocus="panggil('statusayah')" onblur="unfokus('statusayah')">
					<option value="Kandung" <?= $row_siswa['statusayah'] == "Kandung" ? "selected" : ""?>>Ortu Kandung</option>
					<option value="Angkat" <?= $row_siswa['statusayah'] == "Angkat" ? "selected" : ""?>>Ortu Angkat</option>
					<option value="Tiri" <?= $row_siswa['statusayah'] == "Tiri" ? "selected" : ""?>>Ortu Tiri</option>
					<option value="Lainnya" <?= $row_siswa['statusayah'] == "Lainnya" ? "selected" : ""?>>Lainnya</option>
				</select>
			</td>
         	<td bgcolor="#E9AFCF">
            	<select name="statusibu" id="statusibu" onKeyPress="return focusNext('tmplahirayah', event)"
						onfocus="panggil('statusibu')" onblur="unfokus('statusibu')">
					<option value="Kandung" <?= $row_siswa['statusibu'] == "Kandung" ? "selected" : ""?>>Ortu Kandung</option>
					<option value="Angkat" <?= $row_siswa['statusibu'] == "Angkat" ? "selected" : ""?>>Ortu Angkat</option>
					<option value="Tiri" <?= $row_siswa['statusibu'] == "Tiri" ? "selected" : ""?>>Ortu Tiri</option>
					<option value="Lainnya" <?= $row_siswa['statusibu'] == "Lainnya" ? "selected" : ""?>>Lainnya</option>
				</select>
			</td>
     	</tr>
		<tr>
          	<td valign="top">Tmp Lahir</td>
          	<td bgcolor="#DBD8F3">
            	<input name="tmplahirayah" type="text" size="15" maxlength="100" id="tmplahirayah"
					   onFocus="showhint('Tempat Lahir Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tmplahirayah')"
					   value="<?=$row_siswa['tmplahirayah']?>" class="ukuran"  onKeyPress="return focusNext('tmplahiribu', event)" onblur="unfokus('tmplahirayah')"/>
			</td>
         	<td bgcolor="#E9AFCF">
            	<input name="tmplahiribu" type="text" size="15" maxlength="100" id="tmplahiribu"
					   onFocus="showhint('Tempat Lahir Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tmplahiribu')"
					   value="<?=$row_siswa['tmplahiribu']?>" class="ukuran" onKeyPress="return focusNext('tgllahirayah', event)" onblur="unfokus('tmplahiribu')"/>
			</td>
     	</tr>
		<tr>
          	<td valign="top">Tgl Lahir</td>
          	<td bgcolor="#DBD8F3">
            	<input name="tgllahirayah" type="text" size="15" maxlength="100" id="tgllahirayah"
					   onFocus="showhint('Tanggal Lahir Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tgllahirayah')"
					   value="<?=$row_siswa['tgllahirayah']?>" class="ukuran"  onKeyPress="return focusNext('tgllahiribu', event)" onblur="unfokus('tgllahirayah')"/>
			</td>
         	<td bgcolor="#E9AFCF">
            	<input name="tgllahiribu" type="text" size="15" maxlength="100" id="tgllahiribu"
					   onFocus="showhint('Tanggal Lahir Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('tgllahiribu')"
					   value="<?=$row_siswa['tgllahiribu']?>" class="ukuran" onKeyPress="return focusNext('Infopendidikanayah', event)" onblur="unfokus('tgllahiribu')"/>
			</td>
     	</tr>
  		<tr>
    		<td>Pendidikan</td>
    		<td bgcolor="#DBD8F3">
            <div id = "pendidikanayahInfo">			
				<select name="pendidikanayah" id="Infopendidikanayah" class="ukuran"  onKeyPress="return focusNext('Infopendidikanibu', event)" onfocus="panggil('Infopendidikanayah')" style="width:140px" onblur="unfokus('Infopendidikanayah')">
					<option value="">[Pilih Pendidikan]</option>
					<?php 
					$sql_pend_ayah="SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY pendidikan";
					$result_pend_ayah=QueryDB($sql_pend_ayah);
					while ($row_pend_ayah = mysqli_fetch_array($result_pend_ayah)) {
					?>
					<option value="<?=$row_pend_ayah['pendidikan']?>" <?=StringIsSelected($row_pend_ayah['pendidikan'], $row_siswa['pendidikanayah'])?>><?=$row_pend_ayah['pendidikan']?></option>
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
					<option value="<?=$row_pend_ibu['pendidikan']?>" <?=StringIsSelected($row_pend_ibu['pendidikan'], $row_siswa['pendidikanibu'])?>><?=$row_pend_ibu['pendidikan']?></option>
					<?php
					} 
					?>
					</select>
				</div>
				</td>
				<td>
					<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
					<img src="../images/ico/tambah.png" onclick="tambah_pendidikan();" onMouseOver="showhint('Tambah Tingkat Pendidikan!', this, event, '80px')" />
					<?php } ?>
				</td>
			</tr>
            </table>
            </td>
  		</tr>
  		<tr>
    		<td>Pekerjaan</td>
    		<td bgcolor="#DBD8F3">
				<div id = "pekerjaanayahInfo">
				<select name="pekerjaanayah" id="Infopekerjaanayah" class="ukuran"  onKeyPress="return focusNext('Infopekerjaanibu', event)" onfocus="panggil('Infopekerjaanayah')" style="width:140px" onblur="unfokus('Infopekerjaanayah')">
		      		<option value="">[Pilih Pekerjaan]</option>
					<?php 
					$sql_kerja_ayah="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
					$result_kerja_ayah=QueryDB($sql_kerja_ayah);
					while ($row_kerja_ayah = mysqli_fetch_array($result_kerja_ayah)) {
					?>
					<option value="<?=$row_kerja_ayah['pekerjaan']?>" <?=StringIsSelected($row_kerja_ayah['pekerjaan'], $row_siswa['pekerjaanayah'])?>><?=$row_kerja_ayah['pekerjaan']?></option>
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
				<select name="pekerjaanibu" id="Infopekerjaanibu" class="ukuran"  onKeyPress="return focusNext('penghasilanayah1', event)" onfocus="panggil('Infopekerjaanibu')" style="width:140px" onblur="unfokus('Infopekerjaanibu')">
					<option value="">[Pilih Pekerjaan]</option>
					<?php
					$sql_kerja_ibu="SELECT pekerjaan FROM jbsumum.jenispekerjaan ORDER BY pekerjaan";
					$result_kerja_ibu=QueryDB($sql_kerja_ibu);
					while ($row_kerja_ibu = mysqli_fetch_array($result_kerja_ibu)) {
					?>
					<option value="<?=$row_kerja_ibu['pekerjaan']?>" <?=StringIsSelected($row_kerja_ibu['pekerjaan'], $row_siswa['pekerjaanibu'])?>><?=$row_kerja_ibu['pekerjaan']?></option>
					<?php
					} 
					?>
				</select>
				</div>
				</td>
				<td>
					<?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
					<img src="../images/ico/tambah.png" onclick="tambah_pekerjaan();" onMouseOver="showhint('Tambah Jenis Pekerjaan!', this, event, '80px')" />
					<?php } ?>
				</td>
			</tr>
			</table>
            </td>
  		</tr>
  		<tr>
    		<td>Penghasilan</td>
    		<td bgcolor="#DBD8F3">
				<input type="text" name="penghasilanayah1" id="penghasilanayah1" size="15" maxlength="20"
					   value="<?=FormatRupiah($row_siswa['penghasilanayah'])?>"
					   onblur="formatRupiah('penghasilanayah1'); unfokus('penghasilanayah1')"
					   onfocus="unformatRupiah('penghasilanayah1');panggil('penghasilanayah1')" class="ukuran"
					   onKeyPress="return focusNext('penghasilanibu1', event)" onKeyUp="penghasilan_ayah()">
				<input type="hidden" name="penghasilanayah" id="penghasilanayah" value = "<?=$row_siswa['penghasilanayah'] ?>">
			</td>
    		<td bgcolor="#E9AFCF">
				<input type="text" name="penghasilanibu1" id="penghasilanibu1" size="15" maxlength="20"
					   value="<?=FormatRupiah($row_siswa['penghasilanibu']) ?>"
					   onblur="formatRupiah('penghasilanibu1');unfokus('penghasilanibu1')"
					   onfocus="unformatRupiah('penghasilanibu1');panggil('penghasilanibu1')" class="ukuran"
					   onKeyPress="return focusNext('emailayah', event)" onKeyUp="penghasilan_ibu()" />
				<input type="hidden" name="penghasilanibu" id="penghasilanibu" value="<?=$row_siswa['penghasilanibu'] ?>" >
			</td>
  		</tr>
        <tr>
			<td>Email Ortu</td>
			<td bgcolor="#DBD8F3">
				<input name="emailayah" type="text" size="15" maxlength="100" id="emailayah"
					   onFocus="showhint('Alamat email Ayah tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailayah')"
					   value="<?=$row_siswa['emailayah']?>" class="ukuran" onKeyPress="return focusNext('emailibu', event)" onblur="unfokus('emailayah')" />
			</td>
	        <td colspan="2" bgcolor="#E9AFCF">
				<input name="emailibu" type="text" size="15" maxlength="100" id="emailibu"
					   onFocus="showhint('Alamat email Ibu tidak boleh lebih dari 100 karakter!', this, event, '120px');panggil('emailibu')"
					   value="<?=$row_siswa['emailibu']?>" class="ukuran" onKeyPress="return focusNext('namawali', event)" onblur="unfokus('emailibu')" />
			</td>
        </tr>
  		<tr>
    		<td>Nama Wali</td>
    		<td colspan="2">
				<input type="text" name="namawali" id="namawali" size="30" maxlength="100"
					   value="<?=$row_siswa['wali']?>"  onKeyPress="return focusNext('alamatortu', event)" onfocus="panggil('namawali')"
					   onblur="unfokus('namawali')" >
			</td>
  		</tr>
  		<tr>
    		<td valign="top">Alamat Ortu</td>
    		<td colspan="2">
				<textarea name="alamatortu" id="alamatortu" size="25" maxlength="100" class="Ukuranketerangan"
						  onKeyPress="return focusNext('telponortu', event)" onfocus="panggil('alamatortu')" onblur="unfokus('alamatortu')" ><?=$row_siswa['alamatortu']?></textarea>
			</td>
  		</tr>
  		<tr>
    		<td>Telepon Ortu</td>
    		<td colspan="2">
				<input type="text" name="telponortu" id="telponortu" class="ukuran" maxlength="20" value="<?=$row_siswa['telponortu']?>"
					   onKeyPress="return focusNext('hportu', event)" onfocus="panggil('telponortu')" onblur="unfokus('telponortu')"/>
            </td>
  		</tr>
  		<tr>
    		<td>HP Ortu #1</td>
    		<td align="left">
				<input type="text" name="hportu" id="hportu" class="ukuran" maxlength="20" value="<?=$row_siswa['hportu']?>"
					   onKeyPress="return focusNext('hportu2', event)" onfocus="panggil('hportu')" onblur="unfokus('hportu')" />
            </td>
			<td rowspan="3" align="left" valign="top">
				<font style="color: blue; font-style: italic">tambahkan # supaya tidak digunakan di JIBAS SMS Gateway. contoh: #08123456789</font>	
			</td>
  		</tr>
		<tr>
    		<td>HP Ortu #2</td>
    		<td align="left">
				<input type="text" name="hportu2" id="hportu2" class="ukuran" maxlength="20" value="<?=$row_siswa['info1']?>"  onKeyPress="return focusNext('hportu3', event)" onfocus="panggil('hportu2')" onblur="unfokus('hportu2')" />
            </td>
  		</tr>
		<tr>
    		<td>HP Ortu #3</td>
    		<td align="left">
				<input type="text" name="hportu3" id="hportu3" class="ukuran" maxlength="20" value="<?=$row_siswa['info2']?>"  onKeyPress="return focusNext('hobi', event)" onfocus="panggil('hportu3')" onblur="unfokus('hportu3')" />
            </td>
  		</tr>
  		<tr>
    		<td height="30" colspan="3">
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Lainnya</strong></font>
				<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
            </td>
    	</tr>
		<tr>
          	<td valign="top">Hobi</td>
          	<td colspan="2">
				<textarea name="hobi" id="hobi" rows="2" cols="30" class="Ukuranketerangan"
						  onKeyPress="return focusNext('alamatsurat', event)" onfocus="panggil('hobi')"
						  onblur="unfokus('hobi')"><?=$row_siswa['hobi']?></textarea>
			</td>
        </tr>
  		<tr>
    		<td valign="top">Alamat Surat</td>
    		<td colspan="2">
				<textarea name="alamatsurat" id="alamatsurat" size="35" maxlength="100" class="Ukuranketerangan"
						  onKeyPress="return focusNext('keterangan', event)" onfocus="panggil('alamatsurat')"
						  onblur="unfokus('alamatsurat')"><?=$row_siswa['alamatsurat']?></textarea>
			</td>
  		<tr>
    		<td valign="top">Keterangan</td>
    		<td colspan="2">
				<textarea name="keterangan" id="keterangan" rows="2" cols="30" class="Ukuranketerangan"
						  onKeyPress="return focusNext('sum1', event)" onfocus="panggil('keterangan')"
						  onblur="unfokus('keterangan')"><?=$row_siswa['keterangan']?></textarea>
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
                 ORDER BY urutan  ";
        $res = QueryDb($sql);
        $idtambahan = "";
        while($row = mysqli_fetch_row($res))
        {
            $replid = $row[0];
            $kolom = $row[1];
            $jenis = $row[2];

            if ($idtambahan != "") $idtambahan .= ",";
            $idtambahan .= $replid;

            $replid_data = 0;
            $data = "";
            if ($jenis == 1)
            {
                $sql = "SELECT replid, teks FROM tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '".$replid."'";
                $res2 = QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                {
                    $replid_data = $row2[0];
                    $data = $row2[1];
                }
            }
            else if ($jenis == 2)
            {
                $sql = "SELECT replid, filename FROM tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '".$replid."'";
                $res2 = QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                {
                    $replid_data = $row2[0];
                    $filename = $row2[1];
                    $data = "<a href='../library/detail_calon_file.php?replid=$replid_data'>$filename</a>";
                }
            }
            else if ($jenis == 3)
            {
                $sql = "SELECT replid, teks FROM tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '".$replid."'";
                $res2 = QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                {
                    $replid_data = $row2[0];
                    $data = $row2[1];
                }

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
                    $sel = $pilihan == $data ? "selected" : "";
                    $opt .= "<option value='$pilihan' $sel>$pilihan</option>";
                }
            }

            ?>
            <tr>
                <td valign="top"><?=$kolom?></td>
                <td colspan="2">
                    <?php if ($jenis == 1) { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="1">
                        <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
                        <input type="text" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="40" maxlength="1000" value="<?=$data?>">
                    <?php } else if ($jenis == 2) { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="2">
                        <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
                        <input type="file" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="40" style="width: 255px""/>
                        <i><?=$data?></i>
                    <?php } else { ?>
                        <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="3">
                        <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
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
		</td><!-- Akhir Kolom Kanan-->	
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
        <td width="40" align="left">
        <input type="text" name="sum1" id="sum1" size="10" maxlength="15"
	        value="<?= FormatRupiah($sum1) ?>" onblur="formatRupiah('sum1')" onKeyPress="return focusNext('sum2', event)" onfocus="unformatRupiah('sum1')" /> </td>
        <td width="170" align="left">Sumbangan #2 (<?=$kdsum2?>):</td>
        <td width="40" align="left"><input type="text" name="sum2" id="sum2" onKeyPress="return focusNext('ujian1', event)" size="10" maxlength="15"
	        value="<?= FormatRupiah($sum2) ?>" onblur="formatRupiah('sum2')" onfocus="unformatRupiah('sum2')" /> </td>
    </tr>
    </table>
    <br />
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Nilai Ujian</strong></font>
	<div style="border-bottom:1px dashed #666666; border-width:thinl; margin-bottom:5px; margin-top:3px;"></div>
    <table border="0" cellpadding="2" cellspacing="0" style="background-color:#FFFFF2">
    <tr>
        <td width="120" align="left">Ujian #1 (<?=$kdujian1?>):</td>
        <td width="30" align="left"><input type="text" name="ujian1" id="ujian1" onKeyPress="return focusNext('ujian2', event)" value="<?=$ujian1?>" size="5" maxlength="5" /> </td>
        <td width="120" align="left">Ujian #2 (<?=$kdujian2?>):</td>
        <td width="30" align="left"><input type="text" name="ujian2" id="ujian2" onKeyPress="return focusNext('ujian3', event)" value="<?=$ujian2?>" size="5" maxlength="5" /> </td>   
        <td width="120" align="left">Ujian #3 (<?=$kdujian3?>):</td>
        <td width="30" align="left"><input type="text" name="ujian3" id="ujian3" onKeyPress="return focusNext('ujian4', event)" value="<?=$ujian3?>" size="5" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #4 (<?=$kdujian4?>):</td>
        <td width="30" align="left"><input type="text" name="ujian4" id="ujian4" onKeyPress="return focusNext('ujian5', event)" value="<?=$ujian4?>" size="5" maxlength="5" /> </td>
    </tr>
    <tr>    
        <td width="120" align="left">Ujian #5 (<?=$kdujian5?>):</td>
        <td width="30" align="left"><input type="text" name="ujian5" id="ujian5" onKeyPress="return focusNext('ujian6', event)" value="<?=$ujian5?>" size="5" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #6 (<?=$kdujian6?>):</td>
        <td width="30" align="left"><input type="text" name="ujian6" id="ujian6" onKeyPress="return focusNext('ujian7', event)" value="<?=$ujian6?>" size="5" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #7 (<?=$kdujian7?>):</td>
        <td width="30" align="left"><input type="text" name="ujian7" id="ujian7" onKeyPress="return focusNext('ujian8', event)" value="<?=$ujian7?>" size="5" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #8 (<?=$kdujian8?>):</td>
        <td width="30" align="left"><input type="text" name="ujian8" id="ujian8" onKeyPress="return focusNext('ujian9', event)" value="<?=$ujian8?>" size="5" maxlength="5" /> </td>
	</tr>
	<tr>
		<td width="120" align="left">Ujian #9 (<?=$kdujian9?>):</td>
        <td width="30" align="left"><input type="text" name="ujian9" id="ujian9" onKeyPress="return focusNext('ujian10', event)" value="<?=$ujian9?>" size="5" maxlength="5" /> </td>
		<td width="120" align="left">Ujian #10 (<?=$kdujian10?>):</td>
        <td width="30" align="left"><input type="text" name="ujian10" id="ujian10" onKeyPress="return focusNext('Simpan', event)" value="<?=$ujian10?>" size="5" maxlength="5" /> </td>
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
        <input type="Submit" value="Simpan" name="Simpan" class="but" onfocus="panggil('Simpan')" onblur="unfokus('Simpan')" />
       <!-- <input type="button" value="Simpan" name="Simpan" class="but" onfocus="panggil('Simpan')" />-->
        </td>
  		<td align="center" valign="middle"></td>
  		<td valign="middle"><input class="but" type="button" value="Tutup" name="Tutup"  onClick="tutup()" />
  		</td>           
	</tr>
	</table>
	
   	</td>
</tr>
</table>
</form>
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