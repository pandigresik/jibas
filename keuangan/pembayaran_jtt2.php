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

$dis = "style='background-color:#CCCC99' disabled";
if (getLevel() == 1) { 
	$dis ="";	
}

$idkategori = (int)$_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$nis = (string)$_REQUEST['nis'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];

$errmsg = $_REQUEST['errmsg'];

OpenDb();

$op = $_REQUEST['op'];
if ($op == "348328947234923") {
	//Simpan besar pembayaran yang harus dilunasi
	$pengguna = getUserName();
	$idbesarjtt = (int)$_REQUEST['idbesarjtt'];
	$besar = (int)$_REQUEST['besar'];
	$errmsg = "";
	
	if ($idbesarjtt > 0) {
		$sql = "SELECT sum(jumlah) FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
		$result = QueryDb($sql);
		$row = mysqli_fetch_row($result);
		$totalbayaran = $row[0];
		
		if ($totalbayaran > $besar) {
			$errmsg = urlencode("Maaf, besar pembayaran yang harus dilunasi lebih kecil dari jumlah pembayaran cicilan yang telah dilakukan!");
		} else {
			$lunas = 0;
			if ($totalbayaran == $besar)
				$lunas = 1;
				
			$sql = "UPDATE besarjtt SET besar=$besar,keterangan='".$_REQUEST['keterangan']."',lunas=$lunas,pengguna='$pengguna' WHERE replid = '".$idbesarjtt."'";
			QueryDb($sql);
		}
	} else {
		$lunas = 0;
		if ($besar == 0)
			$lunas = 1;
			
		$sql = "INSERT INTO besarjtt SET nis='$nis',idpenerimaan='$idpenerimaan',besar='$besar',keterangan='".CQ($_REQUEST['keterangan'])."',lunas='$lunas',pengguna='$pengguna'";
		QueryDb($sql);		
	}
	
	CloseDb();
	header("Location: pembayaran_jtt.php?idkategori=$idkategori&idpenerimaan=$idpenerimaan&nis=$nis&idtahunbuku=$idtahunbuku&errmsg=$errmsg");

} else if ($op == "d8dm38dn3xdeh9du3e") {	
	//Simpan pembayaran cicilan
	$idpetugas = getIdUser();
	$petugas = getUserName();
	$idbesarjtt = (int)$_REQUEST['idbesarjtt'];
	$tcicilan = $_REQUEST['tcicilan'];
	$tcicilan = MySqlDateFormat($tcicilan);
	$jcicilan = $_REQUEST['jcicilan'];
	$jcicilan = (int)UnformatRupiah($jcicilan);
	$kcicilan = $_REQUEST['kcicilan'];
	
	//Ambil nama penerimaan
	$namapenerimaan = "";
	$rekkas = "";
	$rekpendapatan = "";
	$rekpiutang = "";
	$sql = "SELECT nama, rekkas, rekpendapatan, rekpiutang FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
		trigger_error("Tidak ditemukan data penerimaan", E_USER_ERROR);
	} else {
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		$rekkas = $row[1];
		$rekpendapatan = $row[2];
		$rekpiutang = $row[3];
	}
	
	//Ambil nama siswa
	$namasiswa = "";
	$sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$nis."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
		trigger_error("Tidak ditemukan data siswa", E_USER_ERROR);
	} else {
		$row = mysqli_fetch_row($result);
		$namasiswa = $row[0];
	}
	
	//Cari tahu apakah ini pelunasan?
	$besarjtt = 0;
	$sql = "SELECT besar FROM besarjtt WHERE replid = '".$idbesarjtt."'";
	$result = QueryDb($sql); 
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
		trigger_error("Tidak ditemukan data besarnya pembayaran", E_USER_ERROR);
	} else {
		$row = mysqli_fetch_row($result);
		$besarjtt = $row[0];
	}
	
	//Cari tahu jumlah pembayaran cicilan yang sudah terjadi
	$sql = "SELECT jumlah FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
	$result = QueryDb($sql);
	$jml = 0;
	$cicilan = 0;
	while ($row = mysqli_fetch_row($result)) {
		$jml += $row[0];
		$cicilan++;
	}
		
	//Cek jumlah cicilan dengan besar pembayaran yang mesti dilunasi
	$ketjurnal = "";
	if ($jml + $jcicilan > $besarjtt) {
		CloseDb();
		$errmsg = urlencode("Maaf, pembayaran tidak dapat dilakukan! Jumlah bayaran cicilan lebih besar daripada pembayaran yang harus dilunasi!");
		header("Location: pembayaran_jtt.php?idkategori=$idkategori&idpenerimaan=$idpenerimaan&nis=$nis&idtahunbuku=$idtahunbuku&errmsg=$errmsg");
		exit();
	} else if ($jml + $jcicilan == $besarjtt) {
		$ketjurnal = "Pelunasan $namapenerimaan siswa $namasiswa ($nis)";
		$lunas = 1; //udah lunas
	} else {
		$cicilan++;
		$ketjurnal = "Pembayaran ke-$cicilan $namapenerimaan siswa $namasiswa ($nis)";
		$lunas = 0; //blum lunas
	}
		
	//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
	$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
		trigger_error("Tidak ditemukan data tahunbuku", E_USER_ERROR);
	} else {
		$row = mysqli_fetch_row($result);
		$awalan = $row[0];
		$cacah = $row[1];
	}
	$cacah += 1;
	$nokas = $awalan . rpad($cacah, "0", 6);
	
	//Begin Database Transaction
	BeginTrans();

	//Simpan ke jurnal
	$idjurnal = 0;
	$success = SimpanJurnal($idtahunbuku, $tcicilan, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanjtt", $idjurnal);
	//Simpan ke jurnaldetail
	if ($lunas == 1) {
		if ($cicilan == 1) {
			if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jcicilan);
			if ($success) $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $jcicilan);
		} else {
			if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jcicilan);
			if ($success) $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, $jcicilan);
		}
	} else {
		if ($cicilan == 1) {
			if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jcicilan);
			if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekpiutang, $besarjtt - $jcicilan);
			if ($success) $success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $besarjtt);
		} else {
			if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jcicilan);
			if ($success) $success = SimpanDetailJurnal($idjurnal, "K", $rekpiutang, $jcicilan);
		}
	}
	
	//increment cacah di tahunbuku
	$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
	if ($success) QueryDbTrans($sql, $success);
	
	//simpan data cicilan di penerimaanjtt
	$sql = "INSERT INTO penerimaanjtt SET idbesarjtt='$idbesarjtt',idjurnal='$idjurnal',tanggal='$tcicilan',jumlah='$jcicilan',keterangan='$kcicilan',petugas='$petugas'";
	//RollbackTrans();
	//echo  $sql;
	//exit();
	if ($success) QueryDbTrans($sql, $success);
	
	
	//jika lunas ubah statusnya di besarjtt
	if ($lunas) {
		$sql = "UPDATE besarjtt SET lunas=1 WHERE replid='$idbesarjtt'";
		if ($success) QueryDbTrans($sql, $success);
	}
	
	if ($success)
		CommitTrans();
	else
		RollbackTrans();
	
	CloseDb();
	header("Location: pembayaran_jtt.php?idkategori=$idkategori&idpenerimaan=$idpenerimaan&nis=$nis&idtahunbuku=$idtahunbuku");
	
} else {

	//Muncul pertama kali
	$sql = "SELECT s.replid as replid, nama, telponsiswa as telpon, hpsiswa as hp, kelas as namakelas, alamatsiswa as alamattinggal FROM jbsakad.siswa s, jbsakad.kelas k WHERE s.idkelas = k.replid AND nis = '".$nis."'";
	
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
		exit();
	} else {
		$row = mysqli_fetch_array($result);
		$replid = $row['replid'];
		$nama = $row['nama'];
		$telpon = $row['telpon'];
		$hp = $row['hp'];
		$namakelas = $row['namakelas'];
		$alamattinggal = $row['alamattinggal'];
	}
	
	$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$namapenerimaan = $row[0];
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="script/rupiah.js" type="application/javascript"></script>
<script src="script/validasi.js" type="application/javascript"></script>
<script src="script/tables.js" type="application/javascript"></script>
<script src="script/tooltips.js" type="application/javascript"></script>
<script src="script/tools.js" type="application/javascript"></script>
<script language="javascript" src="script/cal2.js"></script>
<script language="javascript" src="script/cal_conf2.js"></script>
<script src="script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function validasi_besar() {
	return validateEmptyText('besar', 'Besarnya Pembayaran') &&
		   validateMaxText('keterangan', 255, 'Keterangan Besarnya Pembayaran');
}

function simpan_besar() {
	if (validasi_besar()) {
		var idkategori = document.getElementById('idkategori').value;
		var idpenerimaan = document.getElementById('idpenerimaan').value;
		var nis = document.getElementById('nis').value;
		var idtahunbuku = document.getElementById('idtahunbuku').value;
		var besar = document.getElementById('besar').value;
		var keterangan = document.getElementById('keterangan').value;
		var idbesarjtt = document.getElementById('idbesarjtt').value;
		
		besar = rupiahToNumber(besar);
		keterangan = escape(keterangan);
		
		var addr = "pembayaran_jtt.php?op=348328947234923&idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&nis="+nis+"&idtahunbuku="+idtahunbuku+"&besar="+besar+"&keterangan="+keterangan+"&idbesarjtt="+idbesarjtt;
		
		document.location.href = addr;
	}
}

function validasi_cicilan() {
	return validateEmptyText('jcicilan', 'Besarnya Cicilan') &&
		   validateEmptyText('tcicilan', 'Tanggal Cicilan') &&
		   validateMaxText('kcicilan', 255, 'Keterangan Cicilan');
}

function simpan_cicilan() {
	if (validasi_cicilan()) {
	
		var idkategori = document.getElementById('idkategori').value;
		var idpenerimaan = document.getElementById('idpenerimaan').value;
		var nis = document.getElementById('nis').value;
		var idtahunbuku = document.getElementById('idtahunbuku').value;
		var idbesarjtt = document.getElementById('idbesarjtt').value;
		
		var jcicilan = document.getElementById('jcicilan').value;
		var kcicilan = document.getElementById('kcicilan').value;
		var tcicilan = document.getElementById('tcicilan').value;
		
		jcicilan = rupiahToNumber(jcicilan);
		kcicilan = escape(kcicilan);
		tcicilan = escape(tcicilan);
		
		var addr = "pembayaran_jtt.php?op=d8dm38dn3xdeh9du3e&idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&nis="+nis+"&idtahunbuku="+idtahunbuku+"&jcicilan="+jcicilan+"&kcicilan="+kcicilan+"&tcicilan="+tcicilan+"&idbesarjtt="+idbesarjtt;
		
		document.location.href = addr;
	}
}

function cetakkuitansi(id) {
	newWindow('kuitansijtt.php?id='+id, 'CetakKuitansi','750','850','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function editpembayaran(id) {
	newWindow('pembayaranjtt_edit.php?idpembayaran='+id, 'EditPembayaran','520','270','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var nis = document.getElementById('nis').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	
	var addr = "pembayaran_jtt.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&nis="+nis+"&idtahunbuku="+idtahunbuku+"&idbesarjtt="+idbesarjtt;
	
	document.location.href = addr;
}


function cetak() {
	var addr = "pembayaranjtt_cetak.php?idkategori=<?=$idkategori ?>&idpenerimaan=<?=$idpenerimaan ?>&nis=<?=$nis ?>&idtahunbuku=<?=$idtahunbuku ?>"
	newWindow(addr, 'CetakPembayaranJtt','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

</script>
</head>
<body topmargin="0" leftmargin="0">
<input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
<input type="hidden" name="nis" id="nis" value="<?=$nis ?>" />
<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%" cellspacing="0" cellpadding="0">
   	<tr>
    	<td colspan="2">
        <table width="100%" border="0">
        <tr>
        	<td>
            	<font size="5" color="#990000"><strong><?=$namapenerimaan ?></strong></font><br /><br />
				<fieldset style="background:url(images/bttable400.png)">
				<legend><font size="2" color="#003300"><strong>Data Siswa</strong></font></legend>
				<table border="0" width="100%" cellpadding="5" cellspacing="0">
                <tr valign="top">
                    <td rowspan="10" width="25%">
                    <img src='<?="../jibassimaka2/library/gambar.php?replid=".$replid."&table=jbsakad.siswa";?>' width='150' height='150'></td>
                    <td width="10%"><strong>NIS</strong></td>
                    <td><strong>: <?=$nis ?></strong> </td>
                </tr>
                <tr>
                    <td><strong>Nama</strong></td>
                    <td><strong>: <?=$nama ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Kelas</strong></td>
                    <td><strong>: <?=$namakelas ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Handphone</strong></td>
                    <td><strong>: <?=$hp ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Telepon</strong></td>
                    <td><strong>: <?=$telpon ?></strong></td>
                </tr>
                
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td rowspan="2" valign="top"><strong>: <?=$alamattinggal ?></strong></td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                </tr>
                                
                </table>
                </fieldset>
         	</td>
  		</tr>
        </table>
  		</td>
  	</tr>
    <tr>
        <td valign="top"> 
        <fieldset style="background:url(images/bttable400.png)">
        <legend><font size="2" color="#003300">
        <strong>Pembayaran Yang Harus Dilunasi</strong></font></legend>
    <?php
        $sql = "SELECT replid AS id, besar, keterangan, lunas FROM besarjtt WHERE nis = '$nis' AND idpenerimaan = '".$idpenerimaan."'";
        $result = QueryDb($sql);
        $besar = "";
        $keterangan = "";
        $lunas = "";
        $idbesarjtt = 0;
    
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            
            $besar = $row['besar'];
            $keterangan = $row['keterangan'];
            $lunas = (int)$row['lunas'];
            $idbesarjtt = $row['id'];
        }
    ?>
        <table border="0" cellpadding="2" cellspacing="2" align="center">
        <input type="hidden" id="idbesarjtt" name="idbesarjtt" value="<?=$idbesarjtt ?>" />
        <tr>
            <td width="25%"><strong>Pembayaran</strong></td>
            <td><input type="text" disabled size="23" value="<?=$namapenerimaan?>" style="background-color:#CCCC99"/></td>
        </tr>
        <tr>
            <td><strong>Besar</strong></td>
            <td><input type="text" name="besar" id="besar" size="23" value="<?=FormatRupiah($besar) ?>" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar')" <?=$dis?> /></td>
        </tr>
        <tr>
            <td valign="top"><strong>Keterangan</strong></td>
            <td><textarea id="keterangan" name="keterangan" rows="3" cols="20" <?=$dis?>>
                <?=$keterangan ?></textarea>
            </td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>
        <?php 
            $info = "<font color=red><strong>Belum Lunas</strong></font>";
            if ($lunas == 1)
                $info = "<font color=blue><strong>Lunas</strong></font>";
            echo  $info;
        ?>
            </td>
        </tr>
        <?php  if (getLevel() == 1) { ?> 
        <tr>
            <td colspan="2" align="center">
            <input type="button" name="simpan" id="simpan" class="but" value="Simpan" onclick="simpan_besar()" />
            </td>
        </tr>
        <?php } ?>
        </table>
        </fieldset>
        </td>
    	<td>
	<?php  
    if ($idbesarjtt > 0) { 
        $sql = "SELECT count(*) FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $nbayar = $row[0];
        
        $info = "Pembayaran Cicilan";
        if ($nbayar == 0)
            $info = "Pembayaran Pertama";
            
    ?> 
        <fieldset style="background:url(images/bttablelong.png)">
        <legend><font size="2" color="#003300"><strong><?=$info ?>:</strong></font></legend>
        <form name="main">
         <table border="0" width="100%">
        <tr>
            <td align="right">
            <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" />&nbsp;Refresh</a>&nbsp;
            <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" />&nbsp;Cetak</a>&nbsp;
            </td>
        </tr>
        </table>        
        <table class="tab" id="table" border="0" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="100%" align="center">
        <tr height="30">
            <td class="header" width="5%">No</td>
            <td class="header" width="15%">Tanggal</td>
            <td class="header" width="20%">Jumlah</td>
            <td class="header" width="25%">Keterangan</td>
            <td class="header" width="15%">Petugas</td>
            <td class="header">&nbsp;</td>
        </tr>
        <?php 
        $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas FROM penerimaanjtt p, besarjtt b, jurnal j WHERE p.idbesarjtt = b.replid AND j.replid = p.idjurnal AND b.replid = $idbesarjtt ORDER BY p.tanggal ASC";
        $result = QueryDb($sql);
        $cnt = 0;
        $total = 0;
        while ($row = mysqli_fetch_array($result)) {
            $total += $row['jumlah'];
        ?>
        <tr>
            <td align="center"><?=++$cnt?></td>
            <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br>" . $row['tanggal']?></td>
            <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
            <td><?=$row['keterangan'] ?></td>
            <td><?=$row['petugas'] ?></td>
            <td align="center">
            <a href="#" onclick="cetakkuitansi(<?=$row['id'] ?>)" title="Cetak Kuitansi Pembayaran" ><img src="images/ico/print.png" border="0" /></a>&nbsp;
        <?php  if (getLevel() == 1) { ?>
                <a href="#" onclick="editpembayaran(<?=$row['id'] ?>)" title="Edit"><img src="images/ico/ubah.png" border="0" /></a>
        <?php } ?>     
            </td>
        </tr>
        <?php
        }
        ?>
        <?php $sisa = $besar - $total;?>
        <tr height="35">
            <td bgcolor="#996600" colspan="2" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF">Sisa <strong><?=FormatRupiah($sisa) ?></strong></font></td>
            <td bgcolor="#996600" colspan="3">&nbsp;</td>
        </tr>
        </table>
        <script language='JavaScript'>
        Tables('table', 1, 0);
        </script>
        <table border="0" cellpadding="2" width="100%">
        <tr>
            <td width="25%"><strong>Pembayaran</strong></td>
            <td><input type="text" disabled size="30" value="<?=$namapenerimaan?>" <?=$dis?>/></td>
        </tr>
        <tr>
            <td><strong>Jumlah Bayaran</strong></td>
            <td><input type="text" name="jcicilan" id="jcicilan" onblur="formatRupiah('jcicilan')" onfocus="unformatRupiah('jcicilan')" <?=$dis?>/></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td><input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=date('d-m-Y') ?>" <?=$dis?>>
            <?php  if (getLevel() == 1) { ?>
            <a href="javascript:showCal('Calendar1');blank();"><img src="images/calendar.jpg" border="0"></a>
            <?php } ?>
            </td>
        </tr>
        <tr>
            <td><strong>Keterangan</strong></td>
            <td><textarea id="kcicilan" name="kcicilan" rows="3" cols="30" <?=$dis?>></textarea>
            </td>
        </tr>
        <?php  if (getLevel() == 1) { ?>
        <tr>
            <td colspan="2" align="center">
            <input type="button" name="scicilan" id="scicilan" class="but" value="Simpan" onclick="simpan_cicilan()" />
            </td>
        </tr>
       	<?php } ?>
        </table>
    
        <br />
       
        </form>
        </fieldset>
    <?php  } ?>
        	
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