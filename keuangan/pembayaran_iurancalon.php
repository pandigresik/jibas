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
require_once('library/smsmanager.func.php');

$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$replid = (int)$_REQUEST['replid'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
$errmsg = $_REQUEST['errmsg'];

OpenDb();

// -- ambil nama penerimaan -------------------------------
$sql = "SELECT nama, rekkas, info2 FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$row = FetchSingleRow($sql);
$namapenerimaan = $row[0];
$defrekkas = $row[1];
$smsinfo = (int)$row[2];

if (1 == (int)$_REQUEST['issubmit']) 
{	
	$jbayar = UnformatRupiah($_REQUEST['besar']);	
	$tbayar = $_REQUEST['tbayar'];
	$tbayar = MySqlDateFormat($tbayar);
	$kbayar = CQ($_REQUEST['keterangan']);
	$kbayar = CQ($kbayar);
	$idpetugas = getIdUser();
	$petugas = getUserName();
	$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;
	
	//Ambil nama penerimaan
	$namapenerimaan = "";
	$rekkas = "";
	$rekpendapatan = "";
	$rekpiutang = "";
	$sql = "SELECT nama, rekkas, rekpendapatan, rekpiutang FROM datapenerimaan WHERE replid='$idpenerimaan'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0)
	{
		trigger_error("Tidak ditemukan data penerimaan!", E_USER_ERROR);
	}
	else
	{
		$row = mysqli_fetch_row($result);
		$namapenerimaan = $row[0];
		$rekkas = $row[1];
		$rekpendapatan = $row[2];
		$rekpiutang = $row[3];
	}
	
	// rek kas from selected value
	if (isset($_REQUEST['rekkas']) && strlen(trim((string) $_REQUEST['rekkas'])) > 0)
		$rekkas = trim((string) $_REQUEST['rekkas']);
	
	//Ambil nama siswa
	$namasiswa = "";
	$sql = "SELECT nama, nopendaftaran FROM jbsakad.calonsiswa WHERE replid='$replid'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		//CloseDb();
		trigger_error("Tidak ditemukan data calon siswa!", E_USER_ERROR);
	} else {
		$row = mysqli_fetch_row($result);
		$namasiswa = $row[0];
		$no = $row[1];
	}
	
	//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
	$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		//CloseDb();
		trigger_error("Tidak ditemukan data tahun buku!", E_USER_ERROR);
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
	$ketsms = "pembayaran $namapenerimaan";
	$ketjurnal = "Pembayaran $namapenerimaan tanggal {$_REQUEST['tbayar']} calon siswa $namasiswa ($no)";
	$idjurnal = 0;
	$success = SimpanJurnal($idtahunbuku, $tbayar, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaaniurancalon", $idjurnal);
	
	//Simpan ke jurnaldetail
	if ($success)
		$success = SimpanDetailJurnal($idjurnal, "D", $rekkas, $jbayar);
	if ($success)
		$success = SimpanDetailJurnal($idjurnal, "K", $rekpendapatan, $jbayar);
	
	//increment cacah di tahunbuku
	if ($success)
	{
		$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success)
	{
		$sql = "INSERT INTO penerimaaniurancalon SET idpenerimaan='$idpenerimaan', idcalon='$replid', idjurnal='$idjurnal', jumlah='$jbayar', tanggal='$tbayar', keterangan='$kbayar', petugas='$petugas'";
		QueryDbTrans($sql, $success);
	}
	
	// -- Kirim SMS Informasi Pembayaran Calon Siswa
	if ($success && $smsinfo == 1)
	{
		$sql = "SELECT departemen
				  FROM jbsfina.tahunbuku
				 WHERE replid = '".$idtahunbuku."'";
		$departemen = FetchSingle($sql);
		
		CreateSMSPaymentInfo('CSISPAY',
							 $departemen, $no, $namasiswa,
							 RegularDateFormat($tbayar),
							 FormatRupiah($jbayar),
							 $ketsms,
							 $success);
	}
	
	if ($success) 	
		CommitTrans();
	else 		
		RollbackTrans();

	CloseDb();
	
	$r = random_int(10000, 99999);
	header("Location: pembayaran_iurancalon.php?r=$r&idkategori=$idkategori&idpenerimaan=$idpenerimaan&replid=$replid&idtahunbuku=$idtahunbuku");
	
	exit();
}

//Muncul pertama kali

$sql = "SELECT c.nopendaftaran, c.nama, c.telponsiswa as telpon, c.hpsiswa as hp, k.kelompok,
		       c.alamatsiswa as alamattinggal, p.proses, c.keterangan
		  FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p
		 WHERE c.idkelompok = k.replid AND c.idproses = p.replid AND c.replid = '".$replid."'";
//echo  $sql;
$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) {
	CloseDb();
	//echo  "Masuk kesini";
	exit();
} else {
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
	
$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];


$tanggal = date('d-m-Y');
if (isset($_REQUEST['tbayar']))
	$tanggal = $_REQUEST['tbayar'];
$keterangan = "";
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pembayaran Iuran Calon Siswa</title>
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tooltips.js" ></script>
<script language="javascript" src="script/tools.js" ></script>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function val2()
{
	if (confirm('Data sudah benar?'))
		return true;
	else 
		return false;
}

function ValidateSubmit() 
{
	var isok = 	validateEmptyText('besar','Jumlah Pembayaran') &&
		   		validasiAngka() &&
		   		validateEmptyText('tbayar','Tanggal Pembayaran') && 
		   		validateMaxText('keterangan', 255, 'Keterangan Pembayaran') &&
				confirm('Data sudah benar?');
				
	document.getElementById('issubmit').value = isok ? 1 : 0;
	if (isok)
		document.main.submit();
	else
		document.getElementById('simpan').disabled = false;
}

function salinangka(){	
	var angka = document.getElementById("besar").value;
	document.getElementById("angkabesar").value = angka;
}


function validasiAngka() {
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) {
		alert ('Jumlah pembayaran harus berupa bilangan!');
		document.getElementById('besar').value = "";
		document.getElementById('besar').focus();
		return false;
	}
	else if (parseInt(angka) <= 0)
	{
		alert ('Jumlah pembayaran harus positif!');
		document.getElementById('besar').focus();
		return false;
	}
	return true;
}

function cetakkuitansi(id) {
	newWindow('kuitansiiuran.php?id='+id+'&status=calon','','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function editpembayaran(id) {
	newWindow('pembayaraniurancalon_edit.php?idpembayaran='+id, 'EditPembayaran','425','392','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var replid = document.getElementById('replid').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	
	var addr = "pembayaran_iurancalon.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&replid="+replid+"&idtahunbuku="+idtahunbuku;
	document.location.href = addr;
}

function cetak() {
	var addr = "pembayaraniurancalon_cetak.php?idkategori=<?=$idkategori ?>&idpenerimaan=<?=$idpenerimaan ?>&replid=<?=$replid ?>&idtahunbuku=<?=$idtahunbuku ?>"
	newWindow(addr, 'CetakPembayaranIuranCalonSiswa','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
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

<body topmargin="0" leftmargin="0" onload="document.getElementById('besar').focus();">
<form name="main" id="main" method="post">
<input type="hidden" name="issubmit" id="issubmit" value="0" />
<input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
<input type="hidden" name="replid" id="replid" value="<?=$replid ?>" />
<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%" cellspacing="2" cellpadding="2">
   	<tr>
    	<td colspan="2">
        <font size="5" color="#990000"><strong><?=$namapenerimaan ?></strong></font><p></td>
   	</tr>
    <tr>
    	<td width="325" valign="top">
			<fieldset style="background:url(images/bttable400.png);height:280px">
            <legend></legend>
            <table border="0" cellpadding="2" cellspacing="2" align="center" width="100%">
                    
            <tr height="25">
                <td colspan="3" class="header" align="center">Iuran <?=$namapenerimaan?></td>
            </tr>
            <tr>
                <td width="40%"><strong>Pembayaran</strong></td>
                <td colspan="2"><input type="text" readonly="readonly" size="20" value="<?=$namapenerimaan?>" style="background-color:#CCCC99" /></td>
            </tr>
            <tr>
                <td><strong>Jumlah</strong></td>
                <td colspan="2"><input type="text" name="besar" id="besar" size="20" value="<?=FormatRupiah($besar) ?>" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar')" onKeyPress="return focusNext('keterangan', event)" <?=$dis?> onkeyup="salinangka()" />
                <input type="hidden" name="angkabesar" id="angkabesar" value="<?=$besar ?>" />
                </td>
            </tr>
			<tr>
                <td><strong>Rek Kas</strong></td>
                <td colspan="2">
					<select name="rekkas" id="rekkas" style="width: 140px">
<?php              		$sql = "SELECT kode, nama
								  FROM jbsfina.rekakun
								 WHERE kategori = 'HARTA'
								 ORDER BY nama";        
						$res = QueryDb($sql);
						while($row = mysqli_fetch_row($res))
						{
							$sel = $row[0] == $defrekkas ? "selected" : "";
							echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
						}  ?>                
					</select>
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>
                <input type="text" name="tbayar" id="tbayar" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('keterangan', event)" style="background-color:#CCCC99"> </td>
                <td width="60%">
                &nbsp;
                </td>        
            </tr>
            <tr>
                <td valign="top">Keterangan</td>
            </tr>
            <tr>
                <td colspan="3"><textarea id="keterangan" name="keterangan" rows="2" cols="35" onKeyPress="return focusNext('simpan', event)" <?=$dis?> style="width:275px; height:50px"><?=$keterangan ?></textarea><br>
				<br>
                <input type='checkbox' id='smsinfo' name='smsinfo' <?php if ($smsinfo == 1) echo "checked"?> >&nbsp;Notifikasi SMS | Telegram
                </td>
            </tr> 
            <tr>
                <td colspan="3" align="center" height="30">
                <input type="button" name="simpan" id="simpan" class="but" value="Simpan" value="1" onclick="this.disabled = true; ValidateSubmit();" style="width:100px"/>
                </td>
            </tr>
            </table>
            </fieldset>            
        </td>
        <td valign="top">
			
            <fieldset style="background:url(images/bttable400.png);height:280px">
            <legend></legend>
            <table border="0" width="100%" cellpadding="2" cellspacing="2">
            <tr height="25">
                <td colspan="4" class="header" align="center">Data Calon Siswa</td>
            </tr>
            <tr valign="top">                    
                <td width="5%"><strong>Pendaftaran</strong></td>
                <td><strong>:</strong></td>
               	<td><strong><?=$no ?></strong> </td>
                <td rowspan="5" width="25%">
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
                  <?=$keterangansiswa ?>
                </td>
            </tr>
            
            </table>            
            </fieldset>
            
		</td>
  	</tr>
<?php  
	$sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas,
				   jd.koderek AS rekkas, ra.nama AS namakas	
	          FROM penerimaaniurancalon p, jurnal j, jurnaldetail jd, rekakun ra  
			 WHERE j.replid = p.idjurnal
			   AND j.replid = jd.idjurnal
			   AND jd.koderek = ra.kode
			   AND j.idtahunbuku = '$idtahunbuku'
			   AND p.idpenerimaan = '$idpenerimaan'
			   AND p.idcalon = '$replid'
			   AND ra.kategori = 'HARTA'
			 ORDER BY p.tanggal, p.replid";
	$result = QueryDb($sql);    
	if (mysqli_num_rows($result) > 0) {
?>
    <tr>
        <td align="center" colspan="2">
            <fieldset>
            <legend></legend>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        	<tr>
                <td align="right">
                <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
                <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
                </td>
            </tr>
            </table>
            <br />
            <table class="tab" id="table" border="0" style="border-collapse:collapse" width="100%" align="center">
            <tr height="30" align="center">
                <td class="header" width="5%">No</td>
                <td class="header" width="15%">No. Jurnal/Tgl</td>
				<td class="header" width="15%">Rek. Kas</td>
                <td class="header" width="15%">Jumlah</td>
                <td class="header" width="*">Keterangan</td>
                <td class="header" width="12%">Petugas</td>
                <td class="header">&nbsp;</td>
            </tr>
            <?php 
          
            $cnt = 0;
            $total = 0;
            while ($row = mysqli_fetch_array($result)) {
                $total += $row['jumlah'];
            ?>
            <tr height="25">
                <td align="center"><?=++$cnt?></td>
                <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
				<td align="left"><?= $row['rekkas'] . " " . $row['namakas']  ?> </td>
                <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
                <td align="left"><?=$row['keterangan'] ?></td>
                <td align="center"><?=$row['petugas'] ?></td>
                <td align="center">
                <a href="javascript:cetakkuitansi(<?=$row['id'] ?>)" ><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Kuitansi Pembayaran!', this, event, '100px')"/></a>&nbsp;
            <?php  if (getLevel() != 2) { ?>    
                <a href="javascript:editpembayaran(<?=$row['id'] ?>)"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pembayaran Cicilan!', this, event, '120px')" /></a>
           	<?php } ?>	                 
                </td>
            </tr>
            <?php
            }
            ?>
            <tr height="35">
                <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
                <td bgcolor="#996600" align="right"><font color="#FFFFFF">
                <strong><?=FormatRupiah($total); ?></strong></font></td>
                <td bgcolor="#996600" colspan="3">&nbsp;</td>
            </tr>
            </table>
            <script language='JavaScript'>
            Tables('table', 1, 0);
            </script>
           	</fieldset>
            
		</td>
    </tr>
<?php } ?>
	</table>
<!-- EOF CONTENT -->
</td></tr>
</table>
</form>
</body>
</html>
<script language="javascript">

	var sprytextfield2 = new Spry.Widget.ValidationTextField("besar");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>