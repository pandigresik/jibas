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
require_once('include/sessionchecker.php');
require_once('include/errorhandler.php');
require_once('include/sessioninfo.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('library/departemen.php');

if (getLevel() == 2) 
{ ?>
<script language="javascript"> 
	alert('Maaf, anda tidak berhak mengakses halaman ini!'); 
	window.history.go(-1);
</script>
<?php 	exit();
} // end if

$errmsg = "";
if (isset($_REQUEST['lanjut']))
{
	$dept = $_REQUEST['departemen'];
	$ttutup = $_REQUEST['ttutup'];
		
	OpenDb();
	
	$sql = "SELECT COUNT(replid) FROM tahunbuku WHERE aktif=1 AND departemen='$dept'";
	$n = FetchSingle($sql);
	if ($n == 0)
	{
		CloseDb(); 
		$errmsg = "Belum ada tahun buku untuk departemen $dept!<br>Tentukan terlebih dahulu tahun buku awal di menu Tahun Buku";
	}
	else
	{
		$sql = "SELECT replid, tanggalmulai FROM tahunbuku WHERE aktif=1 AND departemen='$dept'";
		$row = FetchSingleRow($sql);
		$idtahunbuku = $row[0];
		$tanggal1 = $row[1];
		$tanggal2 = MySqlDateFormat($ttutup);
		
		$sql = "SELECT SUM(jd.debet - jd.kredit) 
				FROM jurnal j, jurnaldetail jd, rekakun ra 
				WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = $idtahunbuku 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('HARTA', 'PIUTANG', 'INVENTARIS')";
		$aktiva = (float)FetchSingle($sql);
		
		$sql = "SELECT SUM(jd.kredit - jd.debet) 
				FROM jurnal j, jurnaldetail jd, rekakun ra 
				WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = $idtahunbuku 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('UTANG', 'PENDAPATAN', 'MODAL')";
		$pasiva = (float)FetchSingle($sql);
		
		$sql = "SELECT SUM(jd.debet - jd.kredit) 
				FROM rekakun ra, jurnal j, jurnaldetail jd 
				WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = $idtahunbuku 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'BIAYA'";
		$pasiva = $pasiva - (float)FetchSingle($sql);
		
		CloseDb();
		
		if ($aktiva != $pasiva)
		{
			$errmsg = "Laporan neraca tidak seimbang! Anda perlu memeriksa kembali data-data transaksi agar laporan neraca menjadi seimbang";
		}
		else
		{
			header("location: tutupbuku2.php?dept=$dept&ttutup=$ttutup");
			exit();
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kode Perkiraan</title>
<script src="script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<script language="javascript" src="script/tooltips.js"></script>
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function change_kategori() {
	var kate = document.getElementById('kategori').value;
	document.location.href = "akunrek.php?kategori=" + kate + "&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
}

function refresh() {

	document.location.reload();
}

function del(kode) {
	
 	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		var kate = document.getElementById('kategori').value;
		document.location.href = "akunrek.php?op=12134892y428442323x423&kategori="+kate+"&kode="+kode+"&from=<?=$from?>&sourcefrom=<?=$sourcefrom?>";
	}
}

function cetak() {
	var kategori = document.getElementById('kategori').value;
	var addr = "akunrek_cetak.php?kategori="+kategori;
	newWindow(addr, 'CetakRekAkun','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() {

	var kategori = document.getElementById('kategori').value;
	newWindow('akunrek_add.php?kategori='+kategori,'','450','310','resizable=1,scrollbars=1,status=0,toolbar=0');
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
	var lain = new Array('departemen','ttutup');
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
<?php
OpenDb();
?>
<body onLoad="document.getElementById('kategori').focus();">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="images/bgtutupbuku.jpg" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">
    
	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
		<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Tutup Buku</font>	
        </td>
  	</tr>
    <tr>
    	<td align="right"><a href="referensi.php">
      	<font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Tutup Buku</b></font>
        </td>
   	</tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    
    <table width="70%" align="center" border="1" cellpadding="7" cellspacing="0" style="border-color:#306">
    <tr>
    	<td align="left" width="27%" style="background-color:#306">
        <font style="font-size:20px; color:#FFF">Langkah 1 dari 3</font>
        </td>
        <td align="left" valign="middle" style="background-color:#306">
        <font style="font-size:11px; color:#FFF">Menentukan tanggal tutup buku, memeriksa laporan neraca</font>
        </td>
    </tr>
    <tr>
    	<td colspan="2" align="left" height="300" valign="top" style="background-color:#F9F2FF">
        
        <form name="main" method="post" onSubmit="return validasi();">    
        <table border="0" cellpadding="2" cellspacing="2" width="70%" align="left" background="">
        <!-- TABLE CONTENT -->
        <tr>
            <td align="left" width="35%"><strong>Departemen:</strong></td>
            <td align="left">
            <select name="departemen" id="departemen" style="width:100px" onKeyPress="return focusNext('ttutup', event)">
            <?php
            $dep = getDepartemen(getAccess());
            foreach($dep as $value) 
            {  ?>
                <option value="<?=$value ?>"><?=$value ?></option>
            <?php } ?>    
            </select>&nbsp;
            </td>
	    </tr>
        <tr>
            <td align="left"><strong>Tanggal Tutup Buku:</strong></td>
            <td align="left">
            	<input type="text" name="ttutup" id="ttutup" readonly size="15" value="<?=date("d-m-Y")?>" onKeyPress="return focusNext('ttutup', event);" 
             		onFocus="panggil('tawal')" onClick="Calendar.setup()" style="background-color:#CCCC99">&nbsp;
	        	<img src="images/calendar.jpg" name="tabel" border="0" id="bttutup" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
            </td>
        </tr>
        <td  colspan="2" align="left">
        	<input class="but" type="submit" value="Lanjut" name="lanjut" id="lanjut">
        </td>
        </table>
        </form>
        
<?php 	if (strlen($errmsg) > 0) { ?>
            <br /><br /><br /><br />
            <table style="background-color:#FCF; border-color:#900;" width="80%" align="center">
            <tr>
                <td align="center" height="80" valign="middle">
                <font style="color:#F00"><strong><?=$errmsg?></strong></font>
                </td>
            </tr>
            </table>
<?php 	} ?>        
        </td>
    </tr>
    </table>
   
</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table> 
<?php
CloseDb();
?>
</body>
</html>
<script language="javascript">
Calendar.setup (
{
	inputField  : "ttutup",         // ID of the input field
    ifFormat    : "%d-%m-%Y",    // the date format
    button      : "ttutup"       // ID of the button
});
Calendar.setup (
{
	inputField  : "ttutup",         // ID of the input field
    ifFormat    : "%d-%m-%Y",    // the date format
    button      : "bttutup"       // ID of the button
});
</script>