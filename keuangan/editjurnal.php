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
require_once('include/theme.php');

$MAX_INPUT_JOURNAL = 15;

if (isset($_REQUEST['idjurnal']))
	$idjurnal = (int)$_REQUEST['idjurnal'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['jurnal']))
	$jurnal = $_REQUEST['jurnal'];
/*
$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];

$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
*/
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];
	
if (isset($_REQUEST['kriteria']))
	$kriteria = (int)$_REQUEST['kriteria'];

$errmsg = "";
if (isset($_REQUEST['errmsg']))
	$errmsg = $_REQUEST['errmsg'];

if (isset($_REQUEST['Simpan'])) {
	//$idtahunbuku = $_REQUEST['idtahunbuku'];
	$tanggal = MySqlDateFormat($_REQUEST['tcicilan']);
	//$nokas = $_REQUEST['nokas'];
	$transaksi = CQ($_REQUEST['keperluan']);
	$keterangan = CQ($_REQUEST['keterangan']);
	$alasan = CQ($_REQUEST['alasan']);
	$petugas = getUserName();
		
	OpenDb();
	
	//Begin Database Transaction
	BeginTrans();
	$success = 0;
	
	$sql = "SELECT * FROM jurnal WHERE replid = $idjurnal";
	$result = QueryDbTrans($sql);
	$row = mysqli_fetch_array($result);
	$idtahunbuku = $row['idtahunbuku'];
	$nokas = $row['nokas'];
	$sumberjurnal = $row['sumber'];
	$tgljurnal = $row['tanggal'];
	$transaksijurnal = $row['transaksi'];
	$petugasjurnal = $row['petugas'];
	$ketjurnal = $row['keterangan'];
		
	$sql = "INSERT INTO auditinfo SET departemen='$departemen', sumber='$sumberjurnal', idsumber='$idjurnal', tanggal=now(), petugas='$petugas', alasan = '".$alasan."'";
	QueryDbTrans($sql, $success);
	//echo  "$success $sql<br>";
	
	if ($success) {
		$sql = "SELECT LAST_INSERT_ID()";
		$result = QueryDbTrans($sql, $success);
		//echo  "$success $sql<br>";
		$row = mysqli_fetch_row($result);
		$idaudit = $row[0];
	};
	
	if ($success) {
		$sql = "INSERT INTO auditjurnal SET status=0, idaudit=" . $idaudit . ", replid=" . $idjurnal . ", " .
			   " tanggal='$tgljurnal', transaksi='$transaksijurnal', petugas='$petugasjurnal', " .
			   " nokas='$nokas', idtahunbuku='$idtahunbuku', keterangan='$ketjurnal', sumber='$sumberjurnal'";
		QueryDbTrans($sql, $success);
		//echo  "$success $sql<br>";
	}
	
	if ($success) {
		$sql = "SELECT * FROM jurnaldetail WHERE idjurnal='$idjurnal'";
		$result = QueryDb($sql);
		while($row = mysqli_fetch_array($result)) {
			$sql = "INSERT INTO auditjurnaldetail SET status=0, idaudit=" . $idaudit . ", idjurnal=". $idjurnal . ", " .
				   " koderek='" . $row['koderek'] . "', debet=" . $row['debet'] . ", kredit=" . $row['kredit'];
			if ($success) QueryDbTrans($sql, $success);
			//echo  "$success $sql<br>";
		}
	}
		   
	
	$sql = "UPDATE jurnal SET tanggal='$tanggal', transaksi='$transaksi', petugas='$petugas', idtahunbuku='$idtahunbuku', keterangan='$keterangan' WHERE replid='$idjurnal'";
	if ($success) QueryDbTrans($sql, $success);
	//echo  "$success $sql<br>";
	
	$sql = "INSERT INTO auditjurnal SET status=1, idaudit=$idaudit, replid=$idjurnal, " .
		   " tanggal='$tanggal', transaksi='$transaksi', petugas='$petugas', " .
		   " nokas='$nokas', idtahunbuku='$idtahunbuku', keterangan='$keterangan', sumber='$sumberjurnal'";
	if ($success) QueryDbTrans($sql, $success);
	//echo  "$success $sql<br>";
	
	$sql = "DELETE FROM jurnaldetail WHERE idjurnal='$idjurnal'";
	if ($success) QueryDbTrans($sql, $success);
	//echo  "$success $sql<br>";
	
	for($i = 1; $i <= $MAX_INPUT_JOURNAL; $i++) {
		
		$koderek = $_REQUEST['koderek' . $i];
		$debet = UnformatRupiah($_REQUEST['debet' . $i]);
		$debet = (int)$debet;
		$kredit = UnformatRupiah($_REQUEST['kredit' . $i]);
		$kredit = (int)$kredit;
		
	
		if (strlen(trim((string) $koderek)) > 0) {
			$sql = "INSERT INTO jurnaldetail SET idjurnal='$idjurnal', koderek='$koderek', debet='$debet', kredit='$kredit'";
			if ($success) QueryDbTrans($sql, $success);
			//echo  "$success $sql<br>";
			
			$sql = "INSERT INTO auditjurnaldetail SET status=1, idaudit='$idaudit', idjurnal='$idjurnal', koderek='$koderek', debet='$debet', kredit='$kredit'";
			if ($success) QueryDbTrans($sql, $success);
			//echo  "$success $sql<br>";
		}
	}
		
	if ($success) {
		CommitTrans();
		CloseDb(); ?>
		<script language="javascript">
            alert('Data telah disimpan');
            opener.change_hal();
            window.close();
        </script>	
<?php 	exit();
	} else {
		RollbackTrans();
		CloseDb();
		$errmsg = urlencode("Gagal Menyimpan Data");
		header("Location: editjurnal.php?departemen=$departemen&idjurnal=$idjurnal&errmsg=$errmsg");
		exit();
	}
}

OpenDb();

$sql = "SELECT date_format(tanggal, '%d-%m-%Y') as tanggal, transaksi, petugas, nokas, idtahunbuku, keterangan FROM jurnal WHERE replid = '".$idjurnal."'";

$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tanggal = $row[0];
$transaksi = $row[1];
$petugas = $row[2];
//$nokas = $row[3];
$idtahunbuku = $row[4];
$keterangan = $row[5];

if (isset($_REQUEST['tcicilan']))
	$tanggal = $_REQUEST['tcicilan'];
if (isset($_REQUEST['keperluan']))
	$transaksi = $_REQUEST['keperluan'];
if (isset($_REQUEST['keterangan']))
	$keterangan = CQ($_REQUEST['keterangan']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS KEU [Ubah Jurnal <?=$jurnal?>]</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/string.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<script type="text/javascript" src="script/calendar.js" ></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script src="script/rupiah.js" language="javascript"></script>
<script src="script/validasi.js" language="javascript"></script>
<script src="script/tables.js" language="javascript"></script>
<script src="script/rupiah.js" language="javascript"></script>
<script language="javascript">

function change_dep() {
	var dep = document.getElementById('departemen').value;
	document.location.href = "editjurnal.php?departemen="+dep;
}

function pilihrek(no) {
	newWindow('carirek.php?flag=' + no, 'CariRekening','550','438','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function accept_rekening(kode, nama, flag) {
	document.getElementById('koderek' + flag).value = kode;
	document.getElementById('namarek' + flag).value = nama;
}

function hapusrek(no) {
	document.getElementById('koderek' + no).value = "";
	document.getElementById('namarek' + no).value = "";
	document.getElementById('debet' + no).value = "";
	document.getElementById('kredit' + no).value = "";
}

function validate() {
	return validateEmptyText('tcicilan', 'Tanggal Transaksi Jurnal') &&
		   validateEmptyText('keperluan', 'Keperluan') &&
		   validateMaxText('keperluan', 255, 'Keperluan') &&
		   validateEmptyText('alasan', 'Alasan Perubahan') &&
		   validateMaxText('alasan', 500, 'Alasan Perubahan') &&
		   validateMaxText('keterangan', 255, 'Keterangan') &&
		   validate_jumlah() &&
		   confirm('Data sudah benar?');
}

function validate_jumlah() {
	var i = 1;
	var ok = true;
	var totaldebet = 0;
	var totalkredit = 0;
	var isi = 0;
	
	while ((i <= <?=$MAX_INPUT_JOURNAL ?>) && ok) {
		var koderek = document.getElementById('koderek' + i).value;
		var debet = rupiahToNumber(trim(document.getElementById('debet' + i).value));
		var kredit = rupiahToNumber(trim(document.getElementById('kredit' + i).value));
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
			totaldebet = parseInt(totaldebet) + parseInt(jdebet);
		
			var jkredit = document.getElementById('kredit' + i).value;
			jkredit = trim(jkredit);
			if (jkredit.length == 0) {
				document.getElementById('kredit' + i).value = 0;
				jkredit = 0;
			} else {
				jkredit = rupiahToNumber(jkredit);
			}
			totalkredit = parseInt(totalkredit) + parseInt(jkredit);
			
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
		
		i = i + 1;
	}
	
	if (isi == 0) {
		alert ("Anda harus mengisi setidaknya satu data untuk transaksi!");
		document.getElementById('debet1').focus;
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
		totaldebet = parseInt(totaldebet) + parseInt(jdebet);
	
		var jkredit = document.getElementById('kredit' + i).value;
		jkredit = trim(jkredit);
		if (jkredit.length == 0) 
			jkredit = 0;
		else 
			jkredit = rupiahToNumber(jkredit);
		
		totalkredit = parseInt(totalkredit) + parseInt(jkredit);
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" onLoad="document.getElementById('keperluan').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Jurnal <?=$jurnal?> :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    
    <form name="main" method="post" onSubmit="return validate()">
    <input type="hidden" name="keyword" value="<?=$keyword ?>" />
    <input type="hidden" name="kriteria" value="<?=$kriteria ?>" />
    <input type="hidden" name="idjurnal" value="<?=$idjurnal ?>" />
    <input type="hidden" name="departemen" value="<?=$departemen ?>" />
    <!--<input type="hidden" name="nokas" value="<?=$nokas ?>" />-->
    
    <table border="0" cellpadding="2" cellspacing="2" align="center" >
	<!-- TABLE CONTENT -->
    <tr>
    	<td><strong>Departemen</strong></td>
        <td colspan="4">
        	<input type="text" name="departemen" id="departemen" style="background-color:#dfdec9" size="27" value="<?=$departemen ?>" />
        </td>
    </tr>
    <tr>
    	<td><strong>Tahun Buku</strong></td>
        <td colspan="4">
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
        <td colspan="4">
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('keperluan', event)" onClick="Calendar.setup()" style="background-color:#CCCC99">
        <!--</td>
		<td width="60%" colspan="3">-->
        <img src="images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
        </td>
        <!--<td width="*" colspan="2">&nbsp;
        </td>-->
        
    </tr>
    <tr>
    	<td valign="top"><strong>Keperluan </strong></td>
        <td colspan="2"><textarea rows="3" cols="30" name="keperluan" id="keperluan" onKeyPress="return focusNext('alasan',event);"><?=$transaksi ?></textarea>
        </td>
        <td valign="top" width="50"><strong>Alasan Perubahan</strong></td>
        <td>
        <textarea rows="3" cols="30" name="alasan" id="alasan" onKeyPress="return focusNext('keterangan',event);"><?=$_REQUEST['alasan'] ?></textarea>
        </td>
    </tr>
    <tr>
    	<td valign="top">Keterangan</td>
        <td colspan="4"><textarea rows="2" cols="80" name="keterangan" id="keterangan" onKeyPress="return focusNext('debet1',event);"><?=$keterangan ?></textarea>
        </td>
    </tr>
    <tr>
    	<td colspan="5">
        <fieldset><legend><b>Rekening</b></legend>
		<br />
		<table id="table" class="tab" border="0" align="center">
		<tr height="30">		
        	<td class="header" align="center">No</td>
            <td class="header" align="center">Rekening</td>
            <td class="header" align="center">Debet</td>
            <td class="header" align="center">Kredit</td>
        </tr>
<?php 	$sql = "SELECT jd.koderek, ra.nama, jd.debet, jd.kredit FROM jurnaldetail jd, rekakun ra WHERE jd.koderek = ra.kode AND jd.idjurnal='$idjurnal' ORDER BY jd.replid"; 
		$result = QueryDb($sql);
		$i = 1;
		$jumdeb = 0;
		$jumkre = 0;
		while ($row = mysqli_fetch_row($result)) { 
			$koderek[$i] = $row[0];
			$namarek[$i] = $row[1];
			$debet[$i] = $row[2];
			$kredit[$i] = $row[3];							
			$jumdeb = $jumdeb+$row[2];			
			$jumkre = $jumkre+$row[3];
			$i++;
		}
		
	
		for($cnt = 1; $cnt <= $MAX_INPUT_JOURNAL; $cnt++) {
		
		?>
        <tr height="25">
        	<td align="center"><?=$cnt ?></td>
            <td><input type="text" name="koderek<?=$cnt ?>" id="koderek<?=$cnt ?>" value="<?=$koderek[$cnt] ?>" size="10" readonly="readonly" style="background-color:#CCCCCC" onClick="pilihrek(<?=$cnt?>)"/>
            	<input type="text" name="namarek<?=$cnt ?>" id="namarek<?=$cnt ?>"  value="<?=$namarek[$cnt] ?>" size="30" readonly="readonly" style="background-color:#CCCCCC" onClick="pilihrek(<?=$cnt?>)" />
            <a href="JavaScript:pilihrek(<?=$cnt ?>)"><img src="images/ico/lihat.png" border="0" onMouseOver="showhint('Pilih Rekening!', this, event, '100px')"/></a>&nbsp;
            <a href="JavaScript:hapusrek(<?=$cnt ?>)"><img src="images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus Rekening!', this, event, '100px')"/></a>
            </td>
            <td align="center">
            	<input type="text" name="debet<?=$cnt ?>" id="debet<?=$cnt ?>"  value="<?=FormatRupiah($debet[$cnt]) ?>" size="15" maxlength="15" onBlur="formatRupiah('debet<?=$cnt?>');jumlah('debet', <?=$cnt?>);" onKeyPress="if (document.getElementById('debet<?=$cnt?>').value != 0) return focusNext('debet<?=(int)$cnt+1?>',event); else return focusNext('kredit<?=$cnt?>', event);" onFocus="unformatRupiah('debet<?=$cnt ?>')" style="text-align:right"/> </td>               
            <td align="center">
            	<input type="text" name="kredit<?=$cnt ?>" id="kredit<?=$cnt ?>"  value="<?=FormatRupiah($kredit[$cnt]) ?>" size="15" maxlength="15" onblur="formatRupiah('kredit<?=$cnt?>');jumlah('kredit', <?=$cnt?>);" onfocus="unformatRupiah('kredit<?=$cnt ?>')" style="text-align:right" <?php if ($cnt!=$MAX_INPUT_JOURNAL) { ?> onKeyPress="return focusNext('debet<?=(int)$cnt+1?>',event)" <?php } else { ?> onkeypress="return focusNext('Simpan',event)" <?php } ?>/></td>
        </tr>
<?php 	} //end while ?>
		
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
        </fieldset>
       
        </td>
   
    <tr height="30">
    	<td colspan="5" align="center">
        <input type="submit" class="but" name="Simpan" id="Simpan" value="Simpan" />&nbsp;
        <input type="button" class="but" name="Tutup" id="Tutup" value="Tutup" onClick="window.close()" />
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
</table><?php CloseDb() ?>

<?php if (strlen((string) $errmsg) > 0) {?>
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
  var sprytextarea1 = new Spry.Widget.ValidationTextarea("keperluan");
   var sprytextarea1 = new Spry.Widget.ValidationTextarea("alasan");
  var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
  for (x=1;x<=<?=$MAX_INPUT_JOURNAL?>;x++){
	var sprytextfield1 = new Spry.Widget.ValidationTextField("debet"+x);	
	var sprytextfield2 = new Spry.Widget.ValidationTextField("kredit"+x);	
	}

</script>