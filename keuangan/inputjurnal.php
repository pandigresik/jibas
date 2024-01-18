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

$MAX_INPUT_JOURNAL = 15;

$msg = "";
if (isset($_REQUEST['msg']))
	$msg = $_REQUEST['msg'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$tanggal = date('d-m-Y');
if (isset($_REQUEST['tcicilan']))
	$tanggal = $_REQUEST['tcicilan'];

$jumdeb = 0;
$jumkre = 0;

if (1 == (int)$_REQUEST['issubmit']) 
{
	$idtahunbuku = $_REQUEST['idtahunbuku'];
	$tanggal = MySqlDateFormat($_REQUEST['tcicilan']);
	$idpetugas = getIdUser();
	$petugas = getUserName();
	$idpetugas_value = $idpetugas == "landlord" ? "NULL" : "'$idpetugas'";
	
	OpenDb();
	
	//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
	$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) 
	{
		CloseDb();
		trigger_error("Tidak ditemukan data tahunbuku", E_USER_ERROR);
	} 
	else 
	{
		$row = mysqli_fetch_row($result);
		$awalan = $row[0];
		$cacah = $row[1];
	}
	$cacah += 1;
	$nokas = $awalan . rpad($cacah, "0", 6);
	
	//Begin Database Transaction
	BeginTrans();
	$idjurnal = 0;
	$success = 0;
	
	$sql = "INSERT INTO jurnal 
			   SET tanggal='$tanggal', transaksi='".CQ($_REQUEST['keperluan'])."', idpetugas=$idpetugas_value, petugas='$petugas', 
			   	   nokas='$nokas', idtahunbuku='$idtahunbuku', keterangan='".CQ($_REQUEST['keterangan'])."', sumber='jurnalumum'";
	QueryDbTrans($sql, $success);
	
	$sql = "SELECT LAST_INSERT_ID()";
	if ($success) 
	{
		$result = QueryDbTrans($sql, $success);
		$row = mysqli_fetch_row($result);
		$idjurnal = $row[0];
	}
	
	for($i = 1; $i <= $MAX_INPUT_JOURNAL; $i++) 
	{
		$koderek = $_REQUEST['koderek' . $i];
		$debet = UnformatRupiah($_REQUEST['debet' . $i]);
		$kredit = UnformatRupiah($_REQUEST['kredit' . $i]);
		
		if (strlen(trim((string) $koderek)) > 0) 
		{
			$sql = "INSERT INTO jurnaldetail SET idjurnal='$idjurnal', koderek='$koderek', debet='$debet', kredit='$kredit'";
			if ($success) 
				QueryDbTrans($sql, $success);
		}
	}
	
	//increment cacah di tahunbuku
	$sql = "UPDATE tahunbuku SET cacah=cacah+1 WHERE replid='$idtahunbuku'";
	if ($success) 
		QueryDbTrans($sql, $success);
	
	if ($success) 
		CommitTrans();
	else 
		RollbackTrans();
	CloseDb();
	
	$msg = urlencode("Data telah disimpan"); ?>
	<script language="javascript">
		alert ('Data telah disimpan');
		document.location.href="inputjurnal.php";
	</script>
<?php exit();
}
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Input Jurnal</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/string.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script src="script/rupiah2.js" language="javascript"></script>
<script src="script/validasi.js" language="javascript"></script>
<script src="script/tables.js" language="javascript"></script>
<script language="javascript">

function change_dep() {
	var dep = document.getElementById('departemen').value;
	document.location.href = "inputjurnal.php?departemen="+dep;
}

function pilihrek(no) {
	newWindow('carirek.php?flag=' + no, 'CariRekening','500','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function accept_rekening(kode, nama, flag) {
	document.getElementById('koderek' + flag).value = kode;
	document.getElementById('namarek' + flag).value = nama;
}

function hapusrek(no) {
	if (confirm("Apakah anda yakin akan menghapus data ini?")) {
		document.getElementById('koderek' + no).value = "";
		document.getElementById('namarek' + no).value = "";
		document.getElementById('debet' + no).value = "";
		document.getElementById('kredit' + no).value = "";
	}
}

function ValidateSubmit() 
{
	var isok = 	validateEmptyText('tcicilan', 'Tanggal Transaksi Jurnal') &&
		   		validateEmptyText('keperluan', 'Keperluan') &&
		   		validateMaxText('keperluan', 255, 'Keperluan') &&
		   		validateMaxText('keterangan', 255, 'Keterangan') &&
		   		validate_jumlah() && 
		   		confirm('Data sudah benar?');
	
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function validate_jumlah() {	
	var i = 1;
	var ok = true;
	var totaldebet = 0;
	var totalkredit = 0;
	var isi = 0;
	
	while ((i <= <?=$MAX_INPUT_JOURNAL ?>) && ok) {
		var koderek = document.getElementById('koderek' + i).value;
		//var debet = trim(document.getElementById('debet' + i).value);
		var debet = rupiahToNumber(document.getElementById('debet' + i).value);	
		//var kredit = trim(document.getElementById('kredit' + i).value);
		var kredit = rupiahToNumber(document.getElementById('kredit' + i).value);	
		koderek = trim(koderek);
		
		if (koderek.length > 0) {
			isi = 1;
			var jdebet = document.getElementById('debet' + i).value;
			jdebet = trim(jdebet);
			if (jdebet.length == 0) {
				document.getElementById('debet' + i).value = 0;
				jdebet = 0;
			} else {
				jdebet = rupiahToNumber(jdebet);
			}
			totaldebet = parseFloat(totaldebet) + parseFloat(jdebet);
		
			var jkredit = document.getElementById('kredit' + i).value;
			jkredit = trim(jkredit);
			if (jkredit.length == 0) {
				document.getElementById('kredit' + i).value = 0;
				jkredit = 0;
			} else {
				jkredit = rupiahToNumber(jkredit);
			}
			totalkredit = parseFloat(totalkredit) + parseFloat(jkredit);
			
			if (debet == 0 && kredit == 0) {
				alert ("Anda harus mengisikan data di kolom debet atau kredit!");
				document.getElementById('debet'+i).focus();
				return false;
			}
		}
		
		if ((debet.length > 0 && debet != 0) || (kredit.length > 0 && kredit != 0)) {
			if (koderek.length == 0) {
				alert ("Anda harus mengisikan data untuk kode rekening!");
				pilihrek(i);				
				return false;
			}
		}
		i = i + 1
	}
	
	if (isi == 0) {
		alert ("Anda harus mengisi setidaknya satu data untuk transaksi!");
		pilihrek(1);
		return false; 
	}
		
	if (totalkredit != totaldebet) {
		alert("Transaksi tidak bisa disimpan! Total debet tidak sama dengan total kredit!");
		ok = false;
	}
	
	return ok;
}

function jumlah(kas, cnt) {	
 	var i = 1;
	var ok = true;
	var totaldebet = 0;
	var totalkredit = 0;
	
	if (kas == 'debet') {
		var debet = rupiahToNumber(document.getElementById('debet' + cnt).value);	
		if (debet != 0) {			
			document.getElementById('kredit' + cnt).value = 0;
			formatRupiah('kredit'+cnt);
		} 
	} else {
		var kredit = rupiahToNumber(document.getElementById('kredit' + cnt).value);
		if (kredit != 0) {
			document.getElementById('debet' + cnt).value = 0;			
			formatRupiah('debet'+ cnt);
		} 
	}
		
	
	while (i <= <?=$MAX_INPUT_JOURNAL ?>) {		
		var jdebet = document.getElementById('debet' + i).value;
		jdebet = trim(jdebet);
		if (jdebet.length == 0)
			jdebet = 0;
		else 
			jdebet = rupiahToNumber(jdebet);
		totaldebet = parseFloat(totaldebet) + parseFloat(jdebet);
	
		var jkredit = document.getElementById('kredit' + i).value;
		jkredit = trim(jkredit);
		if (jkredit.length == 0) 
			jkredit = 0;
		else 
			jkredit = rupiahToNumber(jkredit);
		
		totalkredit = parseFloat(totalkredit) + parseFloat(jkredit);
		i = i + 1;
	}
	
	document.getElementById('totaldebet').value= totaldebet;
	document.getElementById('totalkredit').value= totalkredit;
	
	formatRupiah('totaldebet');
	formatRupiah('totalkredit');
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
<body onLoad="document.getElementById('departemen').focus()">
<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="" style="background-repeat:no-repeat">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<td align="left" valign="top">
	<table border="0"width="100%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
    	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Input Data Jurnal Umum</font>
     	</td>
  	</tr>
    <tr>
    	<td align="right">
    	<a href="jurnalumum.php">
      	<font size="1" color="#000000"><b>Jurnal Umum</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Input Data Jurnal Umum</b></font>
    </tr>
    <tr>
      	<td align="left">&nbsp;</td>
    </tr>
	</table><br />
    
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <table border="0" height="100%" cellspacing="2" cellpadding="2" align="center">
    <tr>
    	<td valign="top" width="35%">
       	<fieldset style="background:url(images/bttablelong.png)">
        <legend></legend>
        <table border="0"  cellpadding="2" cellspacing="2" width="100%" align="center">
    	<tr>
            <td width="12%" align="left"><strong>Departemen </strong></td>
            <td colspan="2">
                <select name="departemen" id="departemen" style="background-color:#FFFF99;width:180px" onChange="change_dep()" onKeyPress="return focusNext('keperluan', event)">
    <?php          $dep = getDepartemen(getAccess());
                foreach($dep as $value) {
                    if ($departemen == "")
                        $departemen = $value; ?>
                    <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
                <?php } ?>    
                </select>&nbsp;
            </td>
        </tr>
        <tr>
            <td><strong>Tahun Buku </strong></td>
            <td colspan="2">
			<?php $sql = "SELECT replid, tahunbuku FROM tahunbuku WHERE aktif = 1 AND departemen = '".$departemen."'";
               $result = QueryDb($sql);
                    
               $row = mysqli_fetch_row($result);
            ?>
                <input type="text" name="tahunbuku" id="tahunbuku" size="27" readonly style="background-color:#CCCC99" value="<?=$row[1] ?>">
                <input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$row[0] ?>" />
            </td>
        </tr>
        <tr>
            <td><strong>Tanggal </strong></td>
            <td>
                <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('keperluan', event)" onClick="Calendar.setup()" style="background-color:#CCCC99"></td>
           	<td width="70%" >
                <img src="images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
        	</td>
        </tr>
        <tr>
            <td valign="top"><strong>Keperluan</strong></td>
            <td colspan="2"><textarea rows="6" cols="28" name="keperluan" id="keperluan" onKeyPress="return focusNext('keterangan', event)"><?=$_REQUEST['keperluan'] ?></textarea></td>
        </tr>
        <tr>
            <td valign="top">Keterangan </td>
            <td colspan="2"><textarea rows="6" cols="28" name="keterangan" id="keterangan" onKeyPress="return focusNext('Simpan', event)"><?=$_REQUEST['keterangan'] ?></textarea></td>
        </tr>
  		<tr height="30">
    		<td colspan="3" align="center">
        	<input type="button" class="but" name="Simpan" id="Simpan" value="Simpan" onClick="this.disabled = true; ValidateSubmit();" /></td>
    	</tr>
        </table>
        </fieldset>
    	</td>
        
        <td>
            <table border="0" width="100%" class="tab" id="table">
            <tr height="30">
                <td class="header" align="center" width="4%">No</td>
                <td class="header" align="center" width="*">Rekening</td>
                <td class="header" align="center" width="18%">Debet</td>
                <td class="header" align="center" width="18%">Kredit</td>
            </tr>
            <?php for($i = 1; $i <= $MAX_INPUT_JOURNAL; $i++) { ?>
            <tr height="25">
                <td align="center"><?=$i ?></td>
                <td><input type="text" name="koderek<?=$i ?>" id="koderek<?=$i ?>" size="8" maxlength="8" readonly="readonly" style="background-color:#CCCCCC" onClick="pilihrek(<?=$i?>)"  />
                	<input type="text" name="namarek<?=$i ?>" id="namarek<?=$i ?>" size="21" maxlength="26" readonly="readonly" style="background-color:#CCCCCC" onClick="pilihrek(<?=$i?>)"/>
                    <a href="JavaScript:pilihrek(<?=$i ?>)"><img src="images/ico/lihat.png" border="0" onMouseOver="showhint('Pilih Rekening!', this, event, '100px')"/></a>&nbsp;
                    <a href="JavaScript:hapusrek(<?=$i ?>)"><img src="images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Rekening!', this, event, '100px')"/></a>
                </td>
                <td align="center">
                	<input type="text" name="debet<?=$i ?>" id="debet<?=$i ?>" size="15" maxlength="15" onBlur="formatRupiah('debet<?=$i ?>');jumlah('debet', <?=$i?>);" onKeyPress="if (document.getElementById('debet<?=$i?>').value != 0) return focusNext('debet<?=(int)$i+1?>',event); else return focusNext('kredit<?=$i?>', event);" onFocus="unformatRupiah('debet<?=$i ?>')" style="text-align:right"/> </td>
                <td align="center">
                	<input type="text" name="kredit<?=$i ?>" id="kredit<?=$i ?>" size="15" maxlength="15" onblur="formatRupiah('kredit<?=$i ?>');jumlah('kredit', <?=$i?>);" onfocus="unformatRupiah('kredit<?=$i ?>')" <?php if ($i!=$MAX_INPUT_JOURNAL) { ?> onKeyPress="return focusNext('debet<?=(int)$i+1?>',event)" <?php } else { ?> onkeypress="return focusNext('Simpan',event)" <?php } ?>  style="text-align:right"/></td>
            </tr>
            <?php } ?>          
        	<tr height="30">
        	<td colspan="2" align="center" bgcolor="#999900">
            	<font color="#FFFFFF"><strong>T O T A L</strong></font>
        	</td>
        	<td align="right" bgcolor="#999900">
            	<input type="text" name="totaldebet" id="totaldebet" readonly="readonly" size="15" style="background-color:#999900; border:none; text-align:right; color:#FFFFFF; font-weight:bold" value="<?=FormatRupiah($jumdeb)?>" onFocus="formatRupiah('totaldebet')"/></td>
            <td align="right" bgcolor="#999900">    
            	<input type="text" name="totalkredit" id="totalkredit" readonly="readonly" size="15" style="background-color:#999900; border:none; text-align:right; color:#FFFFFF; font-weight:bold" value="<?=FormatRupiah($jumdeb)?>" onFocus="formatRupiah('totalkredit')"/></td>
    	</tr>
        
        </table>  
		<script language='JavaScript'>
	    Tables('table', 1, 0);
    	</script>
        </td>
    </tr>
   
    </table>
    </form>
    </td>
</tr>
</table>    
<!-- EOF CONTENT -->
</td></tr>
</table>
<?php CloseDb() ?>

<?php if (strlen((string) $msg) > 0) {?>
<script language="javascript">
	alert('<?=$msg ?>');
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
  var sprytextarea1 = new Spry.Widget.ValidationTextarea("departemen");
  var sprytextarea1 = new Spry.Widget.ValidationTextarea("keperluan");
  var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
  for (x=1;x<=<?=$MAX_INPUT_JOURNAL?>;x++){
	var sprytextfield1 = new Spry.Widget.ValidationTextField("debet"+x);	
	var sprytextfield2 = new Spry.Widget.ValidationTextField("kredit"+x);	
  }

</script>