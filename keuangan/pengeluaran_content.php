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
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('library/jurnal.php');

$idpengeluaran = 0;
if (isset($_REQUEST['idpengeluaran']))
	$idpengeluaran = (int)$_REQUEST['idpengeluaran'];

$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
	
$errmsg = 0;
if (isset($_REQUEST['errmsg']))
	$errmsg = (int)$_REQUEST['errmsg'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggalmulai = date('d-m-Y');
if (isset($_REQUEST['tanggalmulai']))
	$tanggalmulai = $_REQUEST['tanggalmulai'];

OpenDb();

if (1 == (int)$_REQUEST['issubmit']) 
{
	$idtahunbuku = $_REQUEST['idtahunbuku'];
	$keperluan = CQ($_REQUEST['keperluan']);
	$keterangan = CQ($_REQUEST['keterangan']);
	$jenispemohon = $_REQUEST['spemohon'];
	$idpemohon = $_REQUEST['idpemohon'];
	$penerima = $_REQUEST['penerima'];
	$tanggal = $_REQUEST['tcicilan'];
	$tanggal = MySqlDateFormat($tanggal);
	$jumlah = $_REQUEST['jumlah'];
	$jumlah = UnformatRupiah($jumlah);
	$idpetugas = getIdUser();
	$petugas = getUserName();
	$namapemohon = $_REQUEST['namapemohon'];
	$idjurnal = 0;	

	$rekkredit = $_REQUEST['rekkredit'];
	$rekdebet = $_REQUEST['rekdebet'];
	
	//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
	$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) {
		CloseDb();
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
	$idjurnal = 0;
	$success = SimpanJurnal($idtahunbuku, $tanggal, $keperluan, $nokas, "", $idpetugas, $petugas, "pengeluaran", $idjurnal);
	
	//Simpan ke jurnaldetail
	if ($success) $success = SimpanDetailJurnal($idjurnal, "D", $rekdebet, $jumlah);
	if ($success) $success = SimpanDetailJurnal($idjurnal, "K", $rekkredit, $jumlah);
	
	//increment cacah di tahunbuku
	$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
	if ($success) QueryDbTrans($sql, $success);
	
	//simpan data cicilan di pengeluaran
	if ($jenispemohon == 1)
		$sqlpemohon = "jenispemohon=1, namapemohon='$namapemohon', nip='$idpemohon'";
	else if ($jenispemohon == 2)
		$sqlpemohon = "jenispemohon=2, namapemohon='$namapemohon', nis='$idpemohon'";
	else if ($jenispemohon == 3)
		$sqlpemohon = "jenispemohon=3, namapemohon='$namapemohon', pemohonlain='$idpemohon'";
		
	$sql = "INSERT INTO pengeluaran SET idpengeluaran='$idpengeluaran', idjurnal='$idjurnal', tanggal='$tanggal', jumlah='$jumlah', keperluan='$keperluan', keterangan='$keterangan', petugas='$petugas', tanggalkeluar=now(), penerima='$penerima', $sqlpemohon";
	if ($success) QueryDbTrans($sql, $success);
	
	$idtransaksi = 0;
	if ($success) 
	{
		$sql = "SELECT LAST_INSERT_ID()";
		$result = QueryDbTrans($sql, $success);
		if ($success) 
		{
			$row = mysqli_fetch_row($result);
			$idtransaksi = $row[0];
		}
	}
	
	if ($success) 
	{
		CommitTrans();
		CloseDb();
		header("Location: pengeluaran_cetak.php?idtransaksi=$idtransaksi&idpengeluaran=$idpengeluaran&idtahunbuku=$idtahunbuku&nokas=$nokas");
		exit();
	} 
	else 
	{		
		RollbackTrans();
		CloseDb();
		$errmsg = urlencode("Gagal menyimpan data!");
		header("Location: pengeluaran_content.php?idpengeluaran=$idpengeluaran&idtahunbuku=$idtahunbuku&errmsg=$errmsg");
		exit();	
	}
}

$sql = "SELECT nama FROM datapengeluaran WHERE replid = $idpengeluaran";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapengeluaran = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<script language="javascript">
function ValidateSubmit() 
{
	var isok =  validateEmptyText('nama', 'Nama Pemohon') &&
		   		validateEmptyText('tcicilan', 'Tanggal Pengeluaran') &&
		  	 	validateEmptyText('jumlah', 'Jumlah Pengeluaran') && 
		   		validasiAngka() &&
		   		validateEmptyText('keperluan', 'Keperluan Pengeluaran') && 
		   		validateMaxText('keperluan', 255, 'Keperluan Pengeluaran') &&
		   		confirm('Data sudah benar?');
				
	document.getElementById('issubmit').value = isok ? 1 : 0;				
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function salinangka(){	
	var angka = document.getElementById("jumlah").value;
	document.getElementById("angkabesar").value = angka;
}

function validasiAngka() 
{
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) {
		alert ('Jumlah pengeluaran harus berupa bilangan!');
		document.getElementById('jumlah').value = "";
		document.getElementById('jumlah').focus();
		return false;
	}
	else if (angka <= 0)
	{
		alert ('Jumlah pengeluaran harus positif!');
		document.getElementById('jumlah').focus();
		return false;
	}
	return true;
}

function cari() {
	var spemohon = document.getElementById('spemohon').value;
	var page;
	
	if (spemohon == 1){
		page = 'pegawai.php?flag=0';
		newWindow('pegawai.php?flag=0','CariPegawai','600','590','resizable=1, scrollbars=1, status=0,toolbar=0');
	}else if (spemohon == 2){	
		page = 'siswa.php?flag=0';		
		newWindow('siswa.php?flag=0','CariSiswa','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
	}else{
		page = 'carilain.php?flag=0';	
		newWindow(page,'CariPemohon','550','590','resizable=1,scrollbars=1,status=0,toolbar=0');
	}
	
	//newWindow(page,'CariData','600','590','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptCari(id, nama, flag) {
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function acceptPegawai(id, nama, flag) {	
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function acceptSiswa(id, nama, flag) {
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function clearvalue(v) {
	document.getElementById('spemohon').value = v;
	document.getElementById('nama').value = "";
	document.getElementById('idpemohon').value =  "";
	document.getElementById('penerima').value = "";
	document.getElementById('namapemohon').value = "";
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

function panggil(elem){
	var lain = new Array('rekkredit','rekdebet','penerima','jumlah','keperluan','keterangan');
	for (i=0;i<lain.length;i++) {
		if (lain[i] == elem) {
			document.getElementById(elem).style.background='#FFFF99';
		} else {
			document.getElementById(lain[i]).style.background='#FFFFFF';
		}
	}
}

</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('rekkredit').focus();" >
<table border="0" width="100%" align="center" background="images/pembayaran_trans.png" style="background-repeat:no-repeat;">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top" background="" style="background-repeat:no-repeat">
	<table width="100%" border="0">
  	<tr><td>
    <table border="0"width="100%">
    <!-- TABLE TITLE -->
    <tr>
     
      <td width="50%" align="right" valign="top"><div align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Pembayaran Pengeluaran</font></div></td>
    </tr>
    
    <tr>
      <td align="left" valign="top"><div align="right"><a href="pengeluaran.php" target="_parent">
        <font size="1" color="#000000"><b>Pengeluaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Pembayaran Pengeluaran</b></font> </div></td>
    </tr>
	</table>
    </td></tr>
	</table>
	<br /><br />
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <table width="40%" border="0" height="100%" cellspacing="2" cellpadding="2" align="center">
    <tr>
    	<td valign="top" align="center">
        	<fieldset style="background:url(images/bttablelong.png)">
            <legend></legend>
            <table border="0" cellpadding="2" cellspacing="2" align="center">
			<tr>
                <td width="20%" align="left"><strong>Tahun Buku</strong></td>
                <td colspan="2" align="left">
		<?php  	$sql = "SELECT replid, tahunbuku FROM tahunbuku WHERE aktif = 1 AND departemen = '".$departemen."'";
                $result = QueryDb($sql);
                $row = mysqli_fetch_row($result);
        ?>
             
                <input type="text" name="tahunbuku" id="tahunbuku" size="35" readonly style="background-color:#CCCC99" value="<?=$row[1] ?>">
        		<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$row[0] ?>" />
            	</td>
            </tr>
            <tr>
            	<td align="left"><strong>Pembayaran</strong> </td>
            	<td colspan="2" align="left">
                <input type="text" name="pengeluaran" id="pengeluaran" size="35" readonly style="background-color:#CCCC99" value="<?=$namapengeluaran ?>">
                <!--<strong>&nbsp;<?=$namapengeluaran ?>&nbsp;</strong> -->
            	<input type="hidden" name="idpengeluaran" id="idpengeluaran" value="<?=$idpengeluaran ?>" />
            	</td>
            </tr>
            <?php
            $sql = "SELECT rekdebet, rekkredit FROM datapengeluaran WHERE replid = '".$idpengeluaran."'";
            $result = QueryDb($sql);
            $row = mysqli_fetch_row($result);
            $rekdebet = $row[0];
            $rekkredit = $row[1];
            ?>
            <tr>
                <td align="left"><strong>Rek. Kredit </strong></td>
                <td colspan="2" align="left">
                <select name="rekkredit" id="rekkredit" style="width:225px" onKeyPress="return focusNext('rekdebet', event)" onfocus="panggil('rekkredit')">
            <?php $sql = "SELECT kode, nama FROM rekakun WHERE kategori IN (SELECT kategori FROM rekakun ra, datapengeluaran dp WHERE ra.kode = dp.rekkredit AND dp.replid = '$idpengeluaran') ORDER BY kode";
                $result = QueryDb($sql);
                while ($row = mysqli_fetch_row($result)) { ?>    
                    <option value="<?=$row[0] ?>" <?=StringIsSelected($row[0], $rekkredit)?> > <?=$row[0] . " " . $row[1] ?></option>
            <?php } ?>    
                </select>
                </td>
            </tr>
            <tr>
                <td align="left"><strong>Rek. Debet</strong></td>
                <td colspan="2" align="left">
                <select name="rekdebet" id="rekdebet" style="width:225px" onKeyPress="return focusNext('penerima', event)" onfocus="panggil('rekdebet')"> 
            <?php $sql = "SELECT kode, nama FROM rekakun WHERE kategori IN (SELECT kategori FROM rekakun ra, datapengeluaran dp WHERE ra.kode = dp.rekdebet AND dp.replid = '$idpengeluaran') ORDER BY kode";
                $result = QueryDb($sql);
                while ($row = mysqli_fetch_row($result)) { ?>    
                    <option value="<?=$row[0] ?>" <?=StringIsSelected($row[0], $rekdebet)?> > <?=$row[0] . " " . $row[1] ?></option>
            <?php } ?>    
                </select>
                </td>
            </tr>
            <tr>
            	<td align="left"><strong>Pemohon</strong></td>
                <td colspan="2" align="left">
                <input type="hidden" name="spemohon" id="spemohon" value="1" />
                <input type="radio" name="jpemohon" id="jpemohon" checked="checked" style="background:none" onclick="clearvalue(1)"/>&nbsp;Pegawai&nbsp;&nbsp;
                <input type="radio" name="jpemohon" id="jpemohon" style="background:none" onclick="clearvalue(2)"/>&nbsp;Siswa&nbsp;&nbsp;
                <input type="radio" name="jpemohon" id="jpemohon" style="background:none" onclick="clearvalue(3)"/>&nbsp;Lainnya&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2" align="left">
                <input type="text" name="nama" id="nama" size="35" readonly="readonly" value="<?=$_REQUEST['nama'] ?>" style="background-color:#CCCC99" onClick="cari();"/> 
                <a href="JavaScript:cari()"><img src="images/ico/lihat.png" border="0" /></a>
                <input type="hidden" name="idpemohon" id="idpemohon" value="<?=$_REQUEST['idpemohon'] ?>" />
                <input type="hidden" name="namapemohon" id="namapemohon" value="<?=$_REQUEST['namapemohon'] ?>" />
                </td>
            </tr>
            <tr>
                <td align="left">Penerima</td>
                <td colspan="2" align="left"><input type="text" name="penerima" id="penerima" value="<?=$_REQUEST['penerima'] ?>" size="35" onKeyPress="return focusNext('tcicilan', event)" onfocus="panggil('penerima')"/></td>
            </tr>
            <tr>
                <td align="left"><strong>Tanggal</strong></td>
                <td align="left">
                <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggalmulai?>" onKeyPress="return focusNext('jumlah', event)" onClick="Calendar.setup()" style="background-color:#CCCC99" ></td>
                <td width="60%" align="left">
                <img src="images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
                </td>
            </tr>	
            <tr>
                <td align="left"><strong>Jumlah</strong></td>
                <td colspan="2" align="left"><input type="text" name="jumlah" id="jumlah" size="15" value="<?=FormatRupiah($_REQUEST['jumlah']) ?>" onblur="formatRupiah('jumlah')" onfocus="unformatRupiah('jumlah');panggil('jumlah')" onKeyPress="return focusNext('keperluan', event)" onkeyup="salinangka()"/>
               	<input type="hidden" name="angkabesar" id="angkabesar" value="<?=$_REQUEST['jumlah']?>"/>
                </td>
            </tr>
            <tr>
                <td valign="top" align="left"><strong>Keperluan</strong></td>
                <td colspan="2" align="left"><textarea name="keperluan" id="keperluan" rows="2" cols="40" onKeyPress="return focusNext('keterangan', event)" onfocus="panggil('keperluan')"><?=$_REQUEST['keperluan'] ?></textarea></td>
            </tr>
            <tr>
                <td valign="top" align="left">Keterangan</td>
                <td colspan="2" align="left"><textarea name="keterangan" id="keterangan" rows="2" cols="40" onKeyPress="return focusNext('Simpan', event)" onfocus="panggil('keterangan')"><?=$_REQUEST['keterangan'] ?></textarea></td>
            </tr>
            <tr>
                <td align="center" colspan="3">
                <input type="button" name="Simpan" value="Simpan" id="Simpan" class="but" onclick="this.disabled = true; ValidateSubmit();"/>&nbsp;
                <input type="button" value="Tutup" class="but" onclick="document.location.href = 'pengeluaran_blank.php'"/>
            </td>
            </tr>
            </table>
			</fieldset>
        </td>
    </tr>
    </table>
    </form>
<!-- EOF CONTENT -->
</td></tr>
</table>
</body>
</html>
<script language="javascript">
	Calendar.setup(
	{
	  //inputField  : "tanggalshow","tanggal"
	  inputField  : "tcicilan",         // ID of the input field
	  ifFormat    : "%d-%m-%Y",    // the date format
	  button      : "btntanggal"       // ID of the button
	}
	);
	Calendar.setup(
	{
	  inputField  : "tcicilan",        // ID of the input field
	  ifFormat    : "%d-%m-%Y",    // the date format	  
	  button      : "tcicilan"       // ID of the button
	}
	);
	/*var spryselect1 = new Spry.Widget.ValidationSelect("rekkredit");
	var spryselect1 = new Spry.Widget.ValidationSelect("rekdebet");  
	var sprytextfield1 = new Spry.Widget.ValidationTextField("tcicilan");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("penerima");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("jumlah");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keperluan");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");*/
</script>