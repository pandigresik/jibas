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
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/config.php');
require_once('include/rupiah.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/jurnal.php');
require_once('library/repairdatajttcalon.php');

$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$replid = (int)$_REQUEST['replid'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
$keterangan = CQ((string)$_REQUEST['keterangan']);
$alasan = CQ((string)$_REQUEST['alasan']);
$lunas = 0;
if (isset($_REQUEST['lunas']))
	$lunas = $_REQUEST['lunas'];
$errmsg = $_REQUEST['errmsg'];

OpenDb();

$op = $_REQUEST['op'];
if ($op == "348328947234923") 
{
	// -------------------------------------------
	// Simpan besar pembayaran yang harus dilunasi
	// -------------------------------------------
		
	// Ambil informasi kode rekening berdasarkan jenis penerimaan
	$sql = "SELECT rekkas, rekpiutang, rekpendapatan, nama FROM datapenerimaan WHERE replid='$idpenerimaan'";
	$row = FetchSingleRow($sql);
	$rekkas = $row[0];
	$rekpiutang = $row[1];
	$rekpendapatan = $row[2];
	$namapenerimaan = $row[3];
	
	// linked variable
	$pengguna = getUserName();
	$idbesarjtt = (int)$_REQUEST['idbesarjtt'];
	$besar = $_REQUEST['besar'];
	$cicilan = $_REQUEST['cicilan'];
	$errmsg = "";
	
	if ($idbesarjtt > 0) 
	{
		// -------------------------------------------
		// Besar pembayaran calon siswa telah terdata 
		// -------------------------------------------
		
		// cari tahu total pembayaran yang telah dilakukan
		$sql = "SELECT sum(jumlah), count(replid) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
		$row = FetchSingleRow($sql);
		$totalbayaran = (float)$row[0];
		$nbayaran = (int)$row[1];
		
		$continue = true;
		if ($totalbayaran > $besar) 
		{
			// total pembayaran yang dilakukan lebih besar dari besar pembayaran yang diinput
			$errmsg = urlencode("Maaf, besar pembayaran yang harus dilunasi lebih kecil dari jumlah pembayaran cicilan yang telah dilakukan!");
			$besar = 0;
			$continue = false;
		}
		
		$idjurnal_jtt = 0;
		$idtahunbuku_jtt = 0;
		$besar_jtt = 0;
		$selisih = 0;
		if ($continue)
		{
			// ambil idjurnal dan idtahunbuku pada saat input besar pembayaran
			//   jika diinput menggunakan JIBAS keuangan versi <= 2.0 maka keduanya bernilai nol
			$sql = "SELECT info1, info2, besar FROM besarjttcalon WHERE replid='$idbesarjtt'";
			$row = FetchSingleRow($sql);
			
			$idjurnal_jtt = (int)$row[0];
			$idtahunbuku_jtt = (int)$row[1];
			$besar_jtt = (float)$row[2];
			
			$selisih = $besar - $besar_jtt;
		}
		
		if ($continue && $selisih == 0)
		{
			// hanya berubah keterangannya saja
			$sql = "UPDATE besarjttcalon SET cicilan='$cicilan', keterangan='$keterangan', pengguna='$pengguna', info3='$alasan' WHERE replid='$idbesarjtt'";
			QueryDb($sql);	
			
			$continue = false;
		}
		
		$success = false;
		if ($continue)
		{
			// Begin Transaction
			$success = true;
			BeginTrans();
		}
		
		if ($continue)
		{
			$lunas = 0; // belum lunas
			if ($besar == 0)
				$lunas = 2; // gratis
			else if ($totalbayaran == $besar)
				$lunas = 1;  // lunas
			
			// update besarjtt
			$sql = "UPDATE besarjttcalon SET besar='$besar', cicilan='$cicilan', keterangan='$keterangan', lunas='$lunas', pengguna='$pengguna', info3='$alasan' WHERE replid='$idbesarjtt'";
			QueryDbTrans($sql, $success);
		}
		
		if ($success && $continue)
		{
			// jika data dari JIBAS keuangan <= 2.0 maka idtahunbuku_jtt == 0
			//   pastinya tidak akan sama dengan idtahunbuku 
			//   jadi create_adjustment = true;
			// jika data dari JIBAS keuangan >= 2.1 maka idtahunbuku_jtt != 0
			//   penyesuaian dilakukan jika idtahunbuku != idtahunbuku_jtt
			
			$create_adjustment = false;
			if ($idtahunbuku_jtt == 0)
				$create_adjustment = ($nbayaran > 0);
			else
				$create_adjustment = ($idtahunbuku != $idtahunbuku_jtt);
			
			if ($create_adjustment)
			{
				// Bikin jurnal penyesuaian
			
				// Ambil nama calon siswa	
				$sql = "SELECT nama FROM jbsakad.calonsiswa WHERE replid = '".$replid."'";
				$row = FetchSingleRow($sql);
				$namasiswa = $row[0];
				
				// Ambil awalan dan cacah tahunbuku untuk bikin nokas;
				$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
				$row = FetchSingleRow($sql);
				$awalan = $row[0];
				$cacah = $row[1];
				
				$cacah += 1; // Increment cacah
				$nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas
				
				// tanggal & petugas pendata & keterangan
				$tcicilan = date("Y-m-d");
                $idpetugas = getIdUser();
				$petugas = getUserName();
				$kjurnal = "Jurnal penyesuaian perubahan besar pembayaran $namapenerimaan calon siswa $namasiswa ($nis)";
				
				// simpan ke table jurnal
				$idjurnal = 0;
				$success = SimpanJurnal($idtahunbuku, $tcicilan, $kjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);
	
				// simpan ke table jurnaldetail
				if ($selisih > 0)
				{
					if ($success) 
						$success = SimpanDetailJurnal($idjurnal, "D", $rekpiutang, $selisih);			
					if ($success) 
						$success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $selisih);
				}
				else
				{
					if ($success) 
						$success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, abs($selisih));			
					if ($success) 
						$success = SimpanDetailJurnal($idjurnal, "D", $rekpendapatan, abs($selisih));
				}
				
				//increment cacah di tahunbuku
				if ($success) 
				{
					$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
					QueryDbTrans($sql, $success);
				}
			}
			elseif ($idjurnal_jtt > 0)
			{
				// hanya terjadi untuk JIBAS Keuangan versi >= 2.1
				//   update jurnaldetail pada saat pendataan besar pembayaran
				
				if ($success)
				{
					$sql = "UPDATE jurnaldetail SET debet='$besar' WHERE idjurnal='$idjurnal_jtt' AND koderek='$rekpiutang' AND debet='$besar_jtt'";
					QueryDbTrans($sql, $success);	
				}
					
				if ($success)
				{
					$sql = "UPDATE jurnaldetail SET kredit='$besar' WHERE idjurnal='$idjurnal_jtt' AND koderek='$rekpendapatan' AND kredit='$besar_jtt'";
					QueryDbTrans($sql, $success);	
				}
			}
		} // if ($success && $continue)

		if ($continue)
		{
			if ($success)
				CommitTrans();
			else
				RollbackTrans();	
		}
	} 
	else 
	{
		// ----------------------------------------
		// Pendataan Pembayaran Oleh Calon Siswa   
		// ----------------------------------------
		
		//Ambil nama siswa
		$sql = "SELECT nama, nopendaftaran FROM jbsakad.calonsiswa WHERE replid = '".$replid."'";
		$row = FetchSingleRow($sql);
		$namasiswa = $row[0];
		$nopendaftaran = $row[1];
		
		//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
		$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
		$row = FetchSingleRow($sql);
		$awalan = $row[0];
		$cacah = $row[1];
		
		$cacah += 1; // Increment cacah
		$nokas = $awalan . rpad($cacah, "0", 6); // Form nomor kas
			
		// tanggal & petugas pendata & keterangan
		$tcicilan = date("Y-m-d");
        $idpetugas = getIdUser();
		$petugas = getUserName();
		$keterangan = "Pendataan besar pembayaran $namapenerimaan calon siswa $namasiswa ($nopendaftaran)";
		
		// status lunas
		$lunas = 0; // belum lunas
		if ($besar == 0)
			$lunas = 2; // GRATIS

		$success = true;
		BeginTrans();
		
		// simpan ke table jurnal
		$idjurnal = 0;
		if ($success)
			$success = SimpanJurnal($idtahunbuku, $tcicilan, $keterangan, $nokas, "", $idpetugas, $petugas, "penerimaanjttcalon", $idjurnal);
		
		// simpan ke tabel besarjtt
		if ($success) 
		{
			$sql = "INSERT INTO besarjttcalon SET idcalon=$replid, idpenerimaan='$idpenerimaan', 
			        besar='$besar', cicilan='$cicilan', keterangan='".CQ($_REQUEST['keterangan'])."', lunas='$lunas', pengguna='$pengguna', info1='$idjurnal', info2='$idtahunbuku'";
			QueryDbTrans($sql, $success);
		}
		
		// simpan ke table jurnaldetail
		if ($success) 
			$success = SimpanDetailJurnal($idjurnal, "D", $rekpiutang, $besar);			
		if ($success) 
			$success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $besar);
			
		//increment cacah di tahunbuku
		if ($success) 
		{
			$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid=$idtahunbuku";
			QueryDbTrans($sql, $success);
		}
		
		if ($success) 
			CommitTrans();				
		else 
			RollbackTrans();				
	}
	
	CloseDb();
	
    $r = random_int(10000, 99999);
	header("Location: pembayaran_jttcalon.php?r=$r&idkategori=$idkategori&idpenerimaan=$idpenerimaan&replid=$replid&idtahunbuku=$idtahunbuku&errmsg=$errmsg&besar=$besar&keterangan={$_REQUEST['keterangan']}&lunas=$lunas");
	
	exit();
}

OpenDb();

// Informasi Siswa
$sql = "SELECT c.nopendaftaran, c.nama, c.telponsiswa as telpon, c.hpsiswa as hp, k.kelompok, 
					c.alamatsiswa as alamattinggal, p.proses, c.keterangan
			 FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p 
			WHERE c.idkelompok = k.replid AND c.idproses = p.replid AND c.replid = '".$replid."'";

$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) 
{
	CloseDb();
	exit();
} 
else 
{
	$row = mysqli_fetch_array($result);
	$no = $row['nopendaftaran'];
	$nama = $row['nama'];
	$telpon = $row['telpon'];
	$hp = $row['hp'];
	$namakelompok = $row['kelompok'];
	$namaproses = $row['proses'];
	$alamattinggal = $row['alamattinggal'];
    $keterangansiswa = $row['keterangan'];
}

// Nama jenis penerimaan 
$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];

// Besar pembayaran yang harus dilunasi
$input_awal = "onload=\"document.getElementById('besar').focus();\"";
$keterangan = "";
$besar = "";
$lunas = 0;
$idbesarjtt = 0;

// FIXED: 24 Agustus 2010

// periksa apakah berasal dari input JIBAS versi < 2.1
$sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas, b.info1 AS idjurnal, cicilan 
			  FROM besarjttcalon b 
			  WHERE b.idcalon = $replid AND b.idpenerimaan = '$idpenerimaan' AND b.info2 = '".$idtahunbuku."'";	
$result = QueryDb($sql);

$bayar = mysqli_num_rows($result);
$tgl_jurnal = date('d-m-Y');
if (mysqli_num_rows($result) > 0) 
{
	$row = mysqli_fetch_array($result);
	$idbesarjtt = $row['id'];
	$lunas = $row['lunas'];
	
	$besar = $row['besar'];
	if (isset($_REQUEST['besar']))
		$besar = $_REQUEST['besar']; 
		
	$cicilan = $row['cicilan'];
	if (isset($_REQUEST['cicilan']))
		$cicilan = $_REQUEST['cicilan']; 
		
	$keterangan = $row['keterangan'];
	if (isset($_REQUEST['keterangan']))
		$keterangan = CQ($_REQUEST['keterangan']); 
	
	$idjurnal = $row['idjurnal'];
	if ($idjurnal != 0)
	{
		$sql = "SELECT DATE_FORMAT(tanggal, '%d-%m-%Y') FROM jurnal WHERE replid='$idjurnal'";
		$tgl_jurnal = FetchSingle($sql);
	}
	
	$input_awal = "";
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pembayaran</title>
<script src="script/rupiah2.js" language="javascript"></script>
<script src="script/validasi.js" language="javascript"></script>
<script src="script/tables.js" language="javascript"></script>
<script src="script/tooltips.js" language="javascript"></script>
<script src="script/tools.js" language="javascript"></script>
<script language="javascript">
function validasi_besar() 
{
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	var isok = validateEmptyText('besar', 'Besarnya Pembayaran') &&
		       validasiAngka() &&
		       validateMaxText('keterangan', 255, 'Keterangan Besarnya Pembayaran');
				  
	if (isok && idbesarjtt > 0)				  
		isok = isok && validateEmptyText('alasan','Alasan Perubahan Data');
		
	return isok;				  
}

function salinangka()
{	
	var angka = document.getElementById("besar").value;
	document.getElementById("angkabesar").value = angka;
}

function salincicilan()
{	
	var angka = document.getElementById("cicilan").value;
	document.getElementById("angkacicilan").value = angka;
}

function validasiAngka() 
{
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya pembayaran harus berupa bilangan!');
		document.getElementById('besar').value = "";
		document.getElementById('besar').focus();
		return false;
	}
	else if (angka < 0)
	{
		alert ('Besarnya pembayaran harus positif!');
		document.getElementById('besar').focus();
		return false;
	}
	
	angka = document.getElementById("angkacicilan").value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya cicilan harus berupa bilangan!');
		document.getElementById('cicilan').value = "";
		document.getElementById('cicilan').focus();
		return false;
	}
	else if (angka < 0)
	{
		alert ('Besarnya cicilan harus positif!');
		document.getElementById('cicilan').focus();
		return false;
	}
	
	return true;
}

function ValidateSubmit() 
{
	if (validasi_besar()) 
	{
		var idkategori = document.getElementById('idkategori').value;
		var idpenerimaan = document.getElementById('idpenerimaan').value;
		var replid = document.getElementById('replid').value;		
		var idtahunbuku = document.getElementById('idtahunbuku').value;		
		var besar = document.getElementById('besar').value;	
		var cicilan = document.getElementById('cicilan').value;	
		var keterangan = document.getElementById('keterangan').value;		
		var idbesarjtt = document.getElementById('idbesarjtt').value;
		
		besar = rupiahToNumber(besar);
		cicilan = rupiahToNumber(cicilan);
		keterangan = escape(keterangan);
		
		var addr = "pembayaran_jttcalon.php?op=348328947234923&idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&replid="+replid+"&idtahunbuku="+idtahunbuku+"&besar="+besar+"&keterangan="+keterangan+"&idbesarjtt="+idbesarjtt+"&cicilan="+cicilan;
		
		document.location.href = addr;
	}
	else
	{
		document.getElementById('simpan').disabled = false;
	}
}

function cetakkuitansi(id) 
{
	newWindow('kuitansijtt.php?id='+id+'&status=calon', 'CetakKuitansi','360','650','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function editpembayaran(id) 
{
	newWindow('pembayaranjttcalon_edit.php?idpembayaran='+id, 'EditPembayaran','425','450','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit() 
{
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var replid = document.getElementById('replid').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	
	var addr = "pembayaran_jttcalon.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&replid="+replid+"&idtahunbuku="+idtahunbuku+"&idbesarjtt="+idbesarjtt+"&edit=1";	
	
	document.location.href = addr;
}

function refresh() 
{	
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var replid = document.getElementById('replid').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	
	var addr = "pembayaran_jttcalon.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&replid="+replid+"&idtahunbuku="+idtahunbuku+"&idbesarjtt="+idbesarjtt;	
	
	document.location.href = addr;
}

function cetak() 
{		
	var addr = "pembayaranjttcalon_cetak.php?idkategori=<?=$idkategori ?>&idpenerimaan=<?=$idpenerimaan ?>&replid=<?=$replid ?>&idtahunbuku=<?=$idtahunbuku ?>"
	newWindow(addr, 'CetakPembayaranJttCalonSiswa','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() 
{	
	newWindow('pembayaranjttcalon_add.php?idpenerimaan=<?=$idpenerimaan?>&idkategori=<?=$idkategori?>&replid=<?=$replid?>&idtahunbuku=<?=$idtahunbuku?>', 'TambahPembayaran','445','415','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function focusNext(elemName, evt) 
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) 
	 {
		document.getElementById(elemName).focus();
    	return false;
    }
    return true;
}

function panggil(elem)
{
	var lain = new Array('besar','keterangan');
	for (i=0;i<lain.length;i++) 
	{
		if (lain[i] == elem) 
		{
			document.getElementById(elem).style.background='#FFFF99';
		} 
		else 
		{
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}
</script>
</head>
<body topmargin="0" leftmargin="0" <?=$input_awal?>>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%" cellspacing="2" cellpadding="2">
   	<tr>
    	<td colspan="2">
        <font size="5" color="#990000"><strong><?=$namapenerimaan ?></strong></font><p></td>
   	</tr>
    <tr>
    	<td valign="top" width="325">
        	<fieldset style="background:url(images/bttable400.png);height:310px">
            <legend></legend>
            <form name="main">
            <input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
            <input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
            <input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
            <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
            <input type="hidden" name="idbesarjtt" id="idbesarjtt" value="<?=$idbesarjtt ?>" />
            <table border="0" cellpadding="2" cellspacing="2" align="center">
              
            <tr height="25">
                <td colspan="2" class="header" align="center">Pembayaran yang Harus Dilunasi</td>
            </tr>
            <tr>
                <td width="40%"><strong>Pembayaran</strong></td>
                <td><input type="text"  readonly="readonly" size="20" value="<?=$namapenerimaan?>" style="background-color:#CCCC99" /></td>
            </tr>
            <tr>
                <td><strong>Jumlah Pembayaran</strong></td>
            	<td><input type="text" name="besar" id="besar" size="20" value="<?=FormatRupiah($besar) ?>" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar');panggil('besar')" onKeyPress="return focusNext('cicilan', event)" <?php //$dis?> onkeyup="salinangka()"/>
                	<input type="hidden" name="angkabesar" id="angkabesar" value="<?=$besar ?>" />
                </td>
            </tr>
            <tr>
                <td><strong>Besar Cicilan</strong></td>
            	<td><input type="text" name="cicilan" id="cicilan" size="20" value="<?=FormatRupiah($cicilan) ?>" onblur="formatRupiah('cicilan')" onfocus="unformatRupiah('cicilan');panggil('cicilan')" onKeyPress="return focusNext('keterangan', event)" <?php //$dis?> onkeyup="salincicilan()"/>
                	<input type="hidden" name="angkacicilan" id="angkacicilan" value="<?=$cicilan ?>" />
                </td>
            </tr>
            <tr>
                <td>Tgl.Jurnal</td>                
                <td><input type="text" readonly="readonly" size="20" value="<?=$tgl_jurnal?>" style="background-color:#CCCC99"  /></td>
            </tr>
            <tr>
			    <td>Status</td>
                <td>
				 <?php if ($lunas == 1)
                    $info = "<font color=blue><strong>Lunas</strong></font>";
					 elseif ($lunas == 2)
						  $info = "<font color=brown><strong>Gratis</strong></font>";
					 else 
						  $info = "<font color=red><strong>Belum Lunas</strong></font>";
                echo  $info; ?>
                </td>
            </tr>
            <tr>
                <td valign="top" align="left" colspan="2">Keterangan</td>
            </tr>
            <tr>
            	<td colspan="2"><textarea id="keterangan" name="keterangan" rows="3" cols="35" onKeyPress="return focusNext('simpan', event)" <?php //$dis?> onfocus="panggil('keterangan')" style="width:275px; height:30px"><?=$keterangan ?></textarea>
            	</td>
            </tr>
<?php 		   if ($idbesarjtt > 0) { ?>
				<tr>
                <td valign="top" colspan="2" align="left"><strong>Alasan perubahan data</strong></td>
            </tr>
            <tr>
            	<td colspan="2"><textarea id="alasan" name="alasan" rows="2" cols="35" onKeyPress="return focusNext('simpan', event)" <?php //$dis?> onfocus="panggil('simpan')" style="width:275px; height:30px"><?=$alasan ?></textarea>
            	</td>
            </tr>
<?php 		   } ?>               
           	<tr>
            	<td colspan="2" align="center">
            	<input type="button" name="simpan" id="simpan" class="but" value="Simpan" onclick="this.disabled = true; ValidateSubmit();" style="width:100px" onfocus="panggil('simpan')"/>
            	</td>
        	</tr>
            </table>
            </form>
            </fieldset>
        </td>
        <td valign="top">
            <fieldset style="background:url(images/bttable400.png);height:310px">
            <legend></legend>
            <table border="0" width="100%" cellpadding="2" cellspacing="2">
            <tr height="25">
                <td colspan="4" class="header" align="center">Data Calon Siswa</td>
            </tr>
            <tr valign="top">                    
                <td width="5%"><strong>Pendaftaran</strong></td>
                <td><strong>:</strong></td>
               	<td><strong><?=$no ?></strong> </td><td rowspan="5" width="25%">
                <img src='<?="library/gambar.php?replid=".$replid."&table=jbsakad.calonsiswa";?>' width='100' height='100'></td>
            </tr>
            <tr>
                <td valign="top"><strong>Nama</strong></td>
                <td valign="top"><strong>:</strong></td> 
				<td><strong><?=$nama ?></strong></td>
            </tr>
            <tr>
                <td valign="top"><strong>Proses</strong></td>
                <td valign="top"><strong>:</strong></td>
                <td><strong><?=$namaproses ?></strong></td>
            </tr>
            <tr>
                <td valign="top"><strong>Kelompok</strong></td>
                <td valign="top"><strong>:</strong></td>
                <td><strong><?=$namakelompok ?></strong></td>
            </tr>
            <tr>
                <td><strong>HP</strong></td>
                 <td><strong>:</strong></td>
                <td><strong><?=$hp ?></strong></td>
            </tr>
            <tr>
                <td><strong>Telepon</strong></td>
                 <td><strong>:</strong></td>
                <td><strong><?=$telpon ?></strong></td>
            </tr>
            
            <tr>
                <td valign="top"><strong>Alamat</strong></td>
                <td valign="top"><strong>:</strong></td>
               	<td colspan="2" valign="top"><strong>
                  <?=$alamattinggal ?>
                </strong></td>
            </tr>
            <tr>
                <td valign="top"><strong>Keterangan</strong></td>
                <td valign="top"><strong>:</strong></td>
               	<td colspan="2" valign="top">
                  <?=$keterangansiswa?>
                </td>
            </tr>
            
            </table>            
            </fieldset>
  		</td>
  	</tr>
    <tr>
        <td align="center" colspan="2"> 
<?php if ($bayar > 0 && $lunas <> 2) 
	{ 
   	$sql = "SELECT count(*) FROM penerimaanjttcalon WHERE idbesarjttcalon = '".$idbesarjtt."'";
      $result = QueryDb($sql);
      $row = mysqli_fetch_row($result);
      $nbayar = $row[0];
        
      $info = "Pembayaran Pertama";
      if ($nbayar > 0) 
		{
			$sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan,
                           p.jumlah, p.petugas, p.info1 AS diskon, jd.koderek AS rekkas, ra.nama AS namakas 
					  FROM penerimaanjttcalon p, besarjttcalon b, jurnal j, jurnaldetail jd, rekakun ra 
					 WHERE b.idpenerimaan = '$idpenerimaan'
                       AND p.idbesarjttcalon = b.replid
                       AND j.replid = p.idjurnal
                       AND j.replid = jd.idjurnal
                       AND jd.koderek = ra.kode
                       AND ra.kategori = 'HARTA'
					   AND b.replid = '$idbesarjtt'
                     ORDER BY p.tanggal, p.replid ASC";
			$result = QueryDb($sql);
			if (mysqli_num_rows($result) > 1) 
				$info = "Pembayaran Cicilan";
    ?> 
        <fieldset>
        <legend><font size="2" color="#003300"><strong><?=$info?></strong></font></legend>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tr>
            <td align="right">
            <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;        
        	<?php if ($lunas == 0) { ?>		
				<a href="#" onClick="JavaScript:tambah()">
	            <img src="images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Tambah Cicilan</a>&nbsp;
            <?php } ?>
            </td>
        </tr>
        </table>        
        <br />
        <table class="tab" id="table" border="0" style="border-collapse:collapse" width="100%" align="center">
        <tr height="30" align="center">
            <td class="header" width="5%">No</td>
            <td class="header" width="15%">No. Jurnal/Tgl</td>
            <td class="header" width="15%">Rek. Kas</td>
            <td class="header" width="15%">Besar</td>
			<td class="header" width="15%">Diskon</td>
            <td class="header" width="*">Keterangan</td>
            <td class="header" width="12%">Petugas</td>
            <td class="header">&nbsp;</td>
        </tr>
        <?php 
			$cnt = 0;
			$total = 0;
			while ($row = mysqli_fetch_array($result))
			{
				$total += $row['jumlah'] + $row['diskon'];
				$total_diskon += $row['diskon'];  ?>
        <tr height="25">
            <td align="center"><?=++$cnt?></td>
            <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
            <td align="left"><?= $row['rekkas'] . " " . $row['namakas']  ?> </td>
            <td align="right"><?=FormatRupiah($row['jumlah'] + $row['diskon'])?></td>
			<td align="right"><?=FormatRupiah($row['diskon'])?></td>
            <td align="left"><?=$row['keterangan'] ?></td>
            <td align="center"><?=$row['petugas'] ?></td>
            <td align="center">
            	<a href="#" onclick="cetakkuitansi(<?=$row['id'] ?>)"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Kuitansi Pembayaran!', this, event, '100px')"/></a>&nbsp;
        	<?php  if (getLevel() != 2) { ?>
                <a href="#" onclick="editpembayaran(<?=$row['id'] ?>)"><img src="images/ico/ubah.png" border="0"onMouseOver="showhint('Ubah Pembayaran Cicilan!', this, event, '120px')" /></a>
        	<?php } ?>
            </td>
        </tr>
        <?php
        	}
        	$sisa = $besar - $total;?>
        <tr height="35">
            <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
			<td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total_diskon) ?></strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF">Sisa <strong><?=FormatRupiah($sisa) ?></strong></font></td>
            <td bgcolor="#996600" colspan="3">&nbsp;</td>
        </tr>
        </table>
        <script language='JavaScript'>
        Tables('table', 1, 0);
        </script>
        </fieldset>
   <?php } else { ?>
   		<fieldset>
        <legend><font size="2" color="#003300"><strong>Pembayaran Pertama</strong></font></legend>
        <table width="100%" border="0" align="center">          
        <tr>
            <td align="center" valign="middle" height="100">    
                <font size = "2" color ="red"><b>Tidak ditemukan adanya data.                 
                <br />Klik &nbsp;<a href="JavaScript:tambah()" ><font size = "2" color ="green">di sini</font></a>&nbsp;untuk melakukan pembayaran pertama.                
                </b></font>
            </td>
        </tr>
        </table>  
		</fieldset>   		
   <?php 	} ?>     
<?php } ?>       
		<!-- EOF CONTENT -->
		</td>
	</tr>
	</table>
    </td>
</tr>
</table>

<?php if (strlen((string) $errmsg) > 0) { ?>
<script language="javascript">
alert('<?=$errmsg ?>');
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
	var sprytextfield2 = new Spry.Widget.ValidationTextField("besar");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>