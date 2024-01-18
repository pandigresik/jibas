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
require_once('../include/db_functions.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../library/imageresizer.php');

OpenDb();
$sql = "SELECT * FROM jbsfina.barang WHERE replid='".$_REQUEST['idbarang']."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$kode = $row['kode'];
$nama = $row['nama'];
$jumlah = (int)$row['jumlah'];
$kondisi = $row['kondisi'];
$keterangan = $row['keterangan'];
$satuan = $row['satuan'];
$tgl = substr((string) $row['tglperolehan'],8,2)."-".substr((string) $row['tglperolehan'],5,2)."-".substr((string) $row['tglperolehan'],0,4);
$harga = (int)$row['info1'];
$total = $harga * $jumlah;

if (isset($_REQUEST['Simpan'])){
	$sql = "SELECT kode FROM jbsfina.barang WHERE kode='".$_REQUEST['kode']."' AND replid<>'".$_REQUEST['idbarang']."'";
	$result = QueryDb($sql);
	$num = @mysqli_num_rows($result);
	if ($num>0){
		?>
		<script language="javascript">
			alert('Kode Barang \'<?=$_REQUEST['kode']?>\' sudah digunakan!');
        </script>
		<?php
	} else {
		$foto=$_FILES["foto"];
		$uploadedfile = $foto['tmp_name'];
		$uploadedtypefile = $foto['type'];
		$uploadedsizefile = $foto['size'];
		if (strlen((string) $uploadedfile)!=0)
		{
			$tmp_path = realpath(".") . "/../../temp";
			$tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
			if (!$tmp_exists)
				mkdir($tmp_path, 0755);
		
			$filename = "$tmp_path/ed-inv-tmp.jpg";
			ResizeImage($foto, 120, 90, 70, $filename);

			$fh = fopen($filename, "r");
			$foto_data = addslashes(fread($fh, filesize($filename)));
			fclose($fh);
			
			$isifoto = ", foto='$foto_data'";
		} else {
			$isifoto = "";
		}
		
		$tgl = MySqlDateFormat($_REQUEST['tgl']);
		
		$sql = "UPDATE jbsfina.barang
				   SET kode='".trim((string) $_REQUEST['kode'])."', nama='".trim((string) $_REQUEST['nama'])."',
					   jumlah='".trim((string) $_REQUEST['jumlah'])."',kondisi='".addslashes(trim((string) $_REQUEST['kondisi']))."',tglperolehan='$tgl',
					   keterangan='".addslashes(trim((string) $_REQUEST['keterangan']))."',idkelompok='".$_REQUEST['idkelompok']."',
					   satuan='".$_REQUEST['satuan']."', info1='".$_REQUEST['angkaharga']."' $isifoto
				 WHERE replid='".$_REQUEST['idbarang']."'";
		$result = QueryDb($sql);
		if ($result){
			?>
            <script language="javascript">
				parent.opener.GetFresh();
				window.close();
            </script>
            <?php
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ubah Barang</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/string.js"></script>
<script language="javascript" src="../script/rupiah.js"></script>
<script language="javascript">
function TakeDate(elementid){
	var addr = "../library/cals.php?elementid="+elementid;
	newWindow(addr, 'CariTanggal','338','216','resizable=0,scrollbars=0,status=0,toolbar=0')
}
function AcceptDate(date,elementid){
	document.getElementById(elementid).value=date;
}
function validate(){
	var kode = document.getElementById('kode').value;
	var nama = document.getElementById('nama').value;
	var jumlah = document.getElementById('jumlah').value;
	var tgl = document.getElementById('tgl').value;
	var foto = document.getElementById('foto').value;
	if (kode.length==0){
		alert ('Anda harus mengisikan data untuk Kode Barang!');
		document.getElementById('kode').focus();
		return false;
	} else {
		if (kode.length>20){
			alert ('Kode Barang maksimal 20 karakter!');
			document.getElementById('kode').focus();
			return false;
		}
	}
	
	if (nama.length==0){
		alert ('Anda harus mengisikan data untuk Nama Barang!');
		document.getElementById('nama').focus();
		return false;
	} else {
		if (nama.length>50){
			alert ('Nama Barang maksimal 50 karakter!');
			document.getElementById('nama').focus();
			return false;
		}
	}
	
	if (jumlah.length==0){
		alert ('Anda harus mengisikan nilai untuk Jumlah Barang!');
		document.getElementById('jumlah').focus();
		return false;
	} else {
		if (jumlah.length>10){
			alert ('Jumlah Barang maksimal 10 digit!');
			document.getElementById('jumlah').focus();
			return false;
		}
		if (isNaN(jumlah)){
			alert ('Jumlah Barang harus berupa bilangan!');
			document.getElementById('jumlah').value="";;
			document.getElementById('jumlah').focus();
			return false;
		}
	}
	
	if (tgl.length==0){
		alert ('Anda harus mengisikan data untuk Tanggal Perolehan!');
		document.getElementById('tgl').focus();
		return false;
	} else {
		if (tgl.length>10){
			alert ('Tanggal Perolehan maksimal 10 karakter!');
			document.getElementById('tgl').focus();
			return false;
		}
	}

	if (foto.length>0){
		var ext = "";
		var i = 0;
		var string4split='.';

		z = foto.explode(string4split);
		ext = z[z.length-1];
		
		if (ext!='JPG' && ext!='jpg' && ext!='Jpg' && ext!='JPg' && ext!='JPEG' && ext!='jpeg'){
			alert ('Format Gambar harus ber-extensi jpg atau JPG !');
			//document.getElementById('cover').value='';
	
			return false;
		} 
	}
}

IsNumber = function(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function CalculatePrice()
{
	var jumlah = trim(document.getElementById("jumlah").value);
	var harga = trim(document.getElementById("harga").value);
	harga = rupiahToNumber(harga);
	
	jumlah = IsNumber(jumlah) ? jumlah : 0;
	harga = IsNumber(harga) ? harga : 0;
	
	var total = jumlah * harga;
	document.getElementById("total").value = numberToRupiah(total);
}

function salinharga()
{	
	var harga = document.getElementById("harga").value;
	document.getElementById("angkaharga").value = harga;
}
</script>
</head>
<body onLoad="document.getElementById('kode').focus()">
<fieldset style="border:#336699 1px solid; background-color:#eaf4ff" >
<legend style="background-color:#336699; color:#FFFFFF; font-size:10px; font-weight:bold; padding:5px">&nbsp;Ubah&nbsp;Barang&nbsp;</legend>
<form action="EditBarang.php" method="post" enctype="multipart/form-data" onSubmit="return validate()">
<input type="hidden" name="idkelompok" id="idkelompok" value="<?=$_REQUEST['idkelompok']?>" />
<input type="hidden" name="idbarang" id="idbarang" value="<?=$_REQUEST['idbarang']?>" />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="14%" align="right"><strong>Kode&nbsp;Barang</strong></td>
    <td width="86%"><input type="text" id="kode" name="kode" maxlength="20" value="<?=stripslashes((string) $kode)?>" /></td>
    <td width="86%" rowspan="5" align="center" valign="middle"><img src="gambar.php?table=jbsfina.barang&replid=<?=$_REQUEST['idbarang']?>" style="padding:2px" /></td>
  </tr>
  <tr>
    <td align="right"><strong>Nama&nbsp;Barang</strong></td>
    <td><input type="text" id="nama" name="nama" style="width:95%" maxlength="50" value="<?=stripslashes((string) $nama)?>" /></td>
    </tr>
  <tr>
    <td align="right"><strong>Jumlah</strong></td>
    <td><input type="text" id="jumlah" name="jumlah" size="5" maxlength="10" value="<?=stripslashes($jumlah)?>" />
    &nbsp;Satuan&nbsp;<input type="text" id="satuan" name="satuan" size="10" maxlength="20" value="<?=stripslashes((string) $satuan)?>" /></td>
  </tr>
  <tr>
    <td align="right">Harga Satuan</td>
    <td>
		<input type="text" id="harga" name="harga" size="15" maxlength="14" value="<?=FormatRupiah($harga) ?>" onblur="CalculatePrice(); formatRupiah('harga');" onfocus="unformatRupiah('harga')" onkeyup="salinharga();" />
		<input type="hidden" id="angkaharga" name="angkaharga" size="15" maxlength="14" value="<?=$harga?>" />
	</td>
  </tr>
  <tr>
    <td align="right">Total Harga</td>
    <td><input type="text" id="total" name="total" readonly value="<?=FormatRupiah($total) ?>" style="background-color: #DDD" size="15" maxlength="14" /></td>
  </tr>
  <tr>
    <td align="right"><strong>Tanggal Perolehan</strong></td>
    <td><input type="text" id="tgl" name="tgl" maxlength="10" size="11" value="<?=$tgl?>" />&nbsp;<a href="javascript:TakeDate('tgl')"><img src="../images/ico/calendar.png" border="0" /></a>&nbsp;<em>mm-dd-yyyy</em></td>
    </tr>
  <tr>
    <td align="right">* Foto</td>
    <td><input type="file" id="foto" name="foto" /><br /><em>* Diisi untuk ganti gambar</em></td>
    </tr>
  <tr>
    <td align="right">Kondisi</td>
    <td colspan="2"><textarea name="kondisi" id="kondisi"  style="width:95%"><?=stripslashes((string) $kondisi)?></textarea></td>
    </tr>
  <tr>
    <td align="right">Keterangan</td>
    <td colspan="2"><textarea name="keterangan" rows="4" id="keterangan"  style="width:95%"><?=stripslashes((string) $keterangan)?></textarea></td>
    </tr>
  <tr>
    <td colspan="3" align="center"><input name="Simpan" type="submit" class="but" value="Simpan" />
    &nbsp;&nbsp;<input name="Batal" type="button" class="but" onClick="window.close()" value="Batal" /></td>
  </tr>
</table>
</form>
</fieldset>
</body>
<?php
CloseDb();
?>
</html>