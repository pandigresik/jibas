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
require_once('library/jurnal.php');
require_once('include/theme.php');

$idtransaksi = 0;
if (isset($_REQUEST['idtransaksi']))
	$idtransaksi = $_REQUEST['idtransaksi'];

if ($idtransaksi == 0)
	exit();

OpenDb();

if (1 == (int)$_REQUEST['issubmit']) 
{
	$idtahunbuku = $_REQUEST['idtahunbuku'];
	$idpengeluaran = $_REQUEST['idpengeluaran'];
	$idjurnal = $_REQUEST['idjurnal'];
	$keperluan = CQ($_REQUEST['keperluan']);
	$keterangan = CQ($_REQUEST['keterangan']);
	$jenispemohon = $_REQUEST['spemohon'];
	$idpemohon = $_REQUEST['idpemohon'];
	$namapemohon = $_REQUEST['namapemohon'];
	$penerima = $_REQUEST['penerima'];
	$tanggal = $_REQUEST['tcicilan'];
	$tanggal = MySqlDateFormat($tanggal);
	$jumlahawal = $_REQUEST['jumlahawal'];
	$alasan = CQ($_REQUEST['alasan']);
	$jumlah = $_REQUEST['jumlah'];
	$jumlah = UnformatRupiah($jumlah);
	$petugas = getUserName();
	
	$rekkredit = $_REQUEST['rekkredit'];
	$rekdebet = $_REQUEST['rekdebet'];
	
	//Begin Database Transaction
	BeginTrans();
	$success = 0;	

	//simpan data cicilan di pengeluaran
	if ($jenispemohon == 1)
		$sqlpemohon = "jenispemohon=1, namapemohon='$namapemohon', nip='$idpemohon', nis=null, pemohonlain=null";
	else if ($jenispemohon == 2)
		$sqlpemohon = "jenispemohon=2, namapemohon='$namapemohon', nis='$idpemohon', nip=null, pemohonlain=null";
	else if ($jenispemohon == 3)
		$sqlpemohon = "jenispemohon=3, namapemohon='$namapemohon', pemohonlain='$idpemohon', nip=null, nis=null";
	
	$sql = "UPDATE pengeluaran 
			   SET idpengeluaran='$idpengeluaran', tanggal='$tanggal', jumlah='$jumlah', 
				   keperluan='$keperluan', keterangan='$keterangan', petugas='$petugas', penerima='$penerima', 
				   $sqlpemohon, alasan='$alasan' 
		     WHERE replid = '".$idtransaksi."'";
	QueryDbTrans($sql, $success);
		
	$sql = "UPDATE jurnal SET idtahunbuku = '$idtahunbuku', transaksi='$keperluan' WHERE replid = '".$idjurnal."'";
	if ($success) 
		QueryDbTrans($sql, $success);
	
	$sql = "DELETE FROM jurnaldetail WHERE idjurnal = '".$idjurnal."'";
	if ($success) 
		QueryDbTrans($sql, $success);
	
	//Simpan ke jurnaldetail
	if ($success) 
		$success = SimpanDetailJurnal($idjurnal, "D", $rekdebet, $jumlah);
	if ($success) 
		$success = SimpanDetailJurnal($idjurnal, "K", $rekkredit, $jumlah);
	
	if ($success) 
	{
		CommitTrans();
		CloseDb(); ?>
        <script language="javascript">
			opener.refresh();
			window.close();
        </script>
<?php 	exit();
	} 
	else 
	{
		RollbackTrans();
		CloseDb();
		$errmsg = urlencode("Gagal menyimpan data!");
		header("Location: pengeluaran_edit.php?idtransaksi=$idtransaksi&errmsg=$errmsg");
		exit();	
	}
}

$sql = "SELECT idpengeluaran, keperluan, keterangan, jenispemohon, nip, nis, 
			   pemohonlain, penerima, date_format(tanggal, '%d-%m%-%Y') AS tanggal, jumlah, idjurnal 
          FROM pengeluaran WHERE replid = '".$idtransaksi."'";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);

$idpengeluaran = $row['idpengeluaran'];
$keperluan = $row['keperluan'];
$keterangan = $row['keterangan'];
$jenispemohon = $row['jenispemohon'];
$nip = $row['nip'];
$nis = $row['nis'];
$pemohonlain = $row['pemohonlain'];
$penerima = $row['penerima'];
$tanggal = $row['tanggal'];
$jumlah = $row['jumlah'];
$idjurnal = $row['idjurnal'];

if ($row['jenispemohon'] == 1) 
{
	$idpemohon = $row['nip'];
	$sql = "SELECT nama FROM jbssdm.pegawai WHERE nip = '".$idpemohon."'";
	$jenisinfo = "pegawai";
} 
else if ($row['jenispemohon'] == 2) 
{
	$idpemohon = $row['nis'];
	$sql = "SELECT nama FROM jbsakad.siswa WHERE nis = '".$idpemohon."'";
	$jenisinfo = "siswa";
} 
else 
{
	$idpemohon = $row['pemohonlain'];
	$sql = "SELECT nama FROM pemohonlain WHERE replid = '".$row['pemohonlain']."'";
	$jenisinfo = "pemohon lain";
}
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapemohon = $row[0];

if (isset($_REQUEST['idpengeluaran']))
	$idpengeluaran = $_REQUEST['idpengeluaran'];
if (isset($_REQUEST['spemohon']))
	$jenispemohon = $_REQUEST['spemohon'];
if (isset($_REQUEST['penerima']))
	$penerima = $_REQUEST['penerima'];
if (isset($_REQUEST['tcicilan']))
	$tcicilan = $_REQUEST['tcicilan'];
if (isset($_REQUEST['jumlah']))
	$jumlah = $_REQUEST['jumlah'];
if (isset($_REQUEST['keperluan']))
	$keperluan = $_REQUEST['keperluan'];
if (isset($_REQUEST['keterangan']))
	$keterangan = $_REQUEST['keterangan'];
	
//Ambil rek akun debet dan kredit dari jurnal detail
$sql = "SELECT koderek FROM jurnaldetail WHERE idjurnal='$idjurnal' AND kredit=0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$rekdebet = $row[0];

//Ambil rek akun debet dan kredit dari jurnal detail
$sql = "SELECT koderek FROM jurnaldetail WHERE idjurnal='$idjurnal' AND debet=0";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$rekkredit = $row[0];

$sql = "SELECT nama, departemen FROM datapengeluaran WHERE replid = '".$idpengeluaran."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapengeluaran = $row[0];
$departemen = $row[1];

$sql = "SELECT idtahunbuku FROM jurnal WHERE replid = '".$idjurnal."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$idtahunbuku = $row[0];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Pengeluaran</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<link rel="stylesheet" type="text/css" href="style/calendar-win2k-1.css">
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript">

function ValidateSubmit() 
{
	var isok = 	validateEmptyText('idpemohon', 'Nama Pemohon') &&
		   		validateEmptyText('tcicilan', 'Tanggal Pengeluaran') &&
		   		validateEmptyText('jumlah', 'Jumlah Pengeluaran') && 
		   		validasiAngka() &&
		   		validateEmptyText('keperluan', 'Keperluan Pengeluaran') && 
		   		validateEmptyText('alasan', 'Alasan Perubahan') && 
		   		validateMaxText('keperluan', 255, 'Keperluan Pengeluaran') &&
				confirm('Data sudah benar?');
	
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function salinangka()
{	
	var angka = document.getElementById("jumlah").value;
	document.getElementById("angkabesar").value = angka;
}

function validasiAngka() 
{
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) 
	{
		alert ('Jumlah pengeluaran harus berupa bilangan!');
		document.getElementById('jumlah').value = "";
		document.getElementById('jumlah').focus();
		return false;
	}
	else if(angka < 0) 
	{
		alert ('Jumlah pengeluaran tidak boleh negatif!');
		document.getElementById('jumlah').focus();
		return false;
	}
	
	return true;
}

function cari() 
{
	var spemohon = document.getElementById('spemohon').value;
	var page;
	
	if (spemohon == 1) 
	{
		page = 'pegawai.php?flag=0';	
		newWindow('pegawai.php?flag=0','CariPegawai','600','590','resizable=1, scrollbars=1, status=0,toolbar=0');;	
	} 
	else if (spemohon == 2) 
	{
		page = 'siswa.php?flag=0';
		newWindow('siswa.php?flag=0','CariSiswa','600','618','resizable=1,scrollbars=1,status=0,toolbar=0');
	} 
	else 
	{
		page = 'carilain.php?flag=0';
		newWindow(page,'CariPemohon','550','590','resizable=1,scrollbars=1,status=0,toolbar=0');
	}
}

function acceptCari(id, nama) 
{
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function acceptPegawai(id, nama, flag) 
{	
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function acceptSiswa(id, nama, flag) 
{
	document.getElementById('nama').value = id + " " + nama;
	document.getElementById('penerima').value = nama;
	document.getElementById('idpemohon').value = id;
	document.getElementById('namapemohon').value = nama;
}

function clearvalue(v) 
{
	document.getElementById('spemohon').value = v;
	document.getElementById('nama').value = "";
	document.getElementById('idpemohon').value =  "";
	document.getElementById('penerima').value = "";
	document.getElementById('namapemohon').value = "";
}

function focusNext(elemName, evt) 
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) 
	 {
		document.getElementById(elemName).focus();
      return false;
    }
    return true;
}

function panggil(elem)
{
	var lain = new Array('rekkredit','rekdebet','penerima','jumlah','keperluan','keterangan', 'idpengeluaran', 'alasan');
	for (i = 0; i < lain.length; i++) 
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onLoad="document.getElementById('idpengeluaran').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Pembayaran Pengeluaran :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
   	<form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <input type="hidden" name="idjurnal" id="idjurnal" value="<?=$idjurnal ?>" />
    <input type="hidden" name="idtransaksi" id="idtransaksi" value="<?=$idtransaksi ?>" />
    <table border="0" cellpadding="2" cellspacing="2" width="95%" align="center" >
    <tr>
        <td width="20%"><strong>Tahun Buku</strong></td>
        <td colspan="2">
        <?php 
			OpenDb();
			$sql = "SELECT replid, tahunbuku FROM tahunbuku WHERE aktif = 1 AND departemen = '".$departemen."'";
            $result = QueryDb($sql);
                
            $row = mysqli_fetch_row($result);
        ?>
         <input type="text" name="tahunbuku" id="tahunbuku" size="35" readonly style="background-color:#CCCC99" value="<?=$row[1] ?>">
        <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$row[0] ?>" />
    </td>
    </tr>
    <tr>
        <td ><strong>Pembayaran</strong> </td>
        <td colspan="2">
        <select name="idpengeluaran" id="idpengeluaran" style="width:225px" onKeyPress="return focusNext('rekkredit', event)" onFocus="panggil('idpengeluaran')">
        <?php 	$sql = "SELECT replid, nama FROM datapengeluaran WHERE departemen = '$departemen' ORDER BY nama";	
                $result = QueryDb($sql);
                while ($row = mysqli_fetch_row($result)) { ?>
                    <option value="<?=$row[0] ?>" <?=IntIsSelected($idpengeluaran, $row[0])?> ><?=$row[1] ?></option>
        <?php 	} ?>
        </select>
        </td>
    </tr>
    <tr>
        <td><strong>Rek. Kredit</strong></td>
        <td colspan="2">
        <select name="rekkredit" id="rekkredit" style="width:225px" onKeyPress="return focusNext('rekdebet', event)" onFocus="panggil('rekkredit')">
    <?php $sql = "SELECT kode, nama FROM rekakun WHERE kategori IN (SELECT kategori FROM rekakun ra, datapengeluaran dp WHERE ra.kode = dp.rekkredit AND dp.replid = '$idpengeluaran') ORDER BY kode";
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_row($result)) { ?>    
            <option value="<?=$row[0] ?>" <?=StringIsSelected($row[0], $rekkredit)?> > <?=$row[0] . " " . $row[1] ?></option>
    <?php } ?>    
        </select>
        </td>
    </tr>
    <tr>
        <td><strong>Rek. Debet</strong> </td>
        <td colspan="2">
        <select name="rekdebet" id="rekdebet" style="width:225px" onKeyPress="return focusNext('penerima', event)" onFocus="panggil('rekdebet')">
    <?php $sql = "SELECT kode, nama FROM rekakun WHERE kategori IN (SELECT kategori FROM rekakun ra, datapengeluaran dp WHERE ra.kode = dp.rekdebet AND dp.replid = '$idpengeluaran') ORDER BY kode";
        $result = QueryDb($sql);
        while ($row = mysqli_fetch_row($result)) { ?>    
            <option value="<?=$row[0] ?>" <?=StringIsSelected($row[0], $rekdebet)?> > <?=$row[0] . " " . $row[1] ?></option>
    <?php } ?>    
        </select>
        </td>
    </tr>
    <tr>
        <td><strong>Pemohon</strong></td>
        <td colspan="2">
        <input type="hidden" name="spemohon" id="spemohon" value="<?=$jenispemohon ?>" />
        <input type="radio" name="jpemohon" id="jpemohon" onClick="clearvalue(1)" <?=StringIsChecked($jenispemohon, 1) ?> />&nbsp;Pegawai&nbsp;&nbsp;
        <input type="radio" name="jpemohon" id="jpemohon" onClick="clearvalue(2)" <?=StringIsChecked($jenispemohon, 2) ?>/>&nbsp;Siswa&nbsp;&nbsp;
        <input type="radio" name="jpemohon" id="jpemohon" onClick="clearvalue(3)" <?=StringIsChecked($jenispemohon, 3) ?>/>&nbsp;Lainnya&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="2">
        <input type="text" name="nama" id="nama" size="35" readonly="readonly" value="<?=$idpemohon . " " . $namapemohon ?>" style="background-color:#CCCCCC" onClick="cari();"/> 
        <a href="JavaScript:cari()"><img src="images/ico/lihat.png" border="0" /></a>
        <input type="hidden" name="idpemohon" id="idpemohon" value="<?=$idpemohon ?>" />
        <input type="hidden" name="namapemohon" id="namapemohon" value="<?=$namapemohon ?>" />
        </td>
    </tr>
    <tr>
        <td>Penerima</td>
        <td colspan="2"><input type="text" name="penerima" id="penerima" value="<?=$penerima ?>" size="30" onKeyPress="return focusNext('tcicilan', event)" onFocus="panggil('penerima')"/></td>
    </tr>
    <tr>
    	<td><strong>Tanggal</strong></td>
   		<td>
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal?>" onKeyPress="return focusNext('jumlah', event)" onClick="Calendar.setup()" style="background-color:#CCCC99" ></td>
        <td width="60%">
        <img src="images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
        </td>
    </tr>	
    <tr>
        <td><strong>Jumlah</strong></td>
        <td colspan="2">
        <input type="hidden" name="jumlahawal" id="jumlahawal" value="<?=$jumlah ?>" />
        <input type="text" name="jumlah" id="jumlah" size="18" value="<?=FormatRupiah($jumlah) ?>" onBlur="formatRupiah('jumlah')" onFocus="unformatRupiah('jumlah');panggil('jumlah')" onKeyPress="return focusNext('keperluan', event)" onKeyUp="salinangka()"/>
        <input type="hidden" name="angkabesar" id="angkabesar" value="<?=$jumlah?>"/>
        </td>
    </tr>
   
    <tr>
        <td valign="top"><strong>Keperluan</strong></td>
        <td colspan="2"><textarea name="keperluan" id="keperluan" rows="2" cols="45" onKeyPress="return focusNext('alasan', event)" onFocus="panggil('keperluan')"><?=$keperluan ?></textarea></td>
    </tr>
     <tr>
        <td valign="top"><strong>Alasan Perubahan</strong></td>
        <td colspan="2"><textarea name="alasan" id="alasan" rows="2" cols="45" onKeyPress="return focusNext('keterangan', event)" onFocus="panggil('alasan')"><?=$_REQUEST['alasan'] ?></textarea></td>
    </tr>
    <tr>
        <td valign="top">Keterangan</td>
        <td colspan="2"><textarea name="keterangan" id="keterangan" rows="2" cols="45" onKeyPress="return focusNext('Simpan', event)" onFocus="panggil('keterangan')"><?=$keterangan ?></textarea></td>
    </tr>
    <tr>
        <td align="center" colspan="3">
        <input type="button" name="Simpan" value="Simpan" id="Simpan" class="but" onClick="this.disabled = true; ValidateSubmit();" />&nbsp;
        <input type="button" value="Tutup" class="but" onClick="window.close()" />
        </td>
    </tr>
    </table>
    </form>
<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
<?php
$errmsg = $_REQUEST['errmsg'];
if (strlen(trim((string) $errmsg)) > 0) { ?>
<script language="javascript">
	alert('<?=$errmsg ?>');
</script>
<?php } ?>
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
	/*var spryselect1 = new Spry.Widget.ValidationSelect("idpengeluaran");
	var spryselect1 = new Spry.Widget.ValidationSelect("rekkredit");
	var spryselect1 = new Spry.Widget.ValidationSelect("rekdebet");  
	var sprytextfield1 = new Spry.Widget.ValidationTextField("tcicilan");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("penerima");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("jumlah");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keperluan");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("alasan");*/
</script>