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

$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
$tanggal = date('d-m-Y');
if (isset($_REQUEST['tcicilan']))
	$tanggal = $_REQUEST['tcicilan'];

$errmsg = $_REQUEST['errmsg'];

OpenDb();

// -- ambil nama penerimaan -------------------------------
$sql = "SELECT nama, rekkas FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$row = FetchSingleRow($sql);
$namapenerimaan = $row[0];
$defrekkas = $row[1];

if (1 == (int)$_REQUEST['issubmit']) 
{
	$jbayar = $_REQUEST['jbayar'];
	$jbayar = UnformatRupiah($jbayar);
	$tbayar = $_REQUEST['tcicilan'];
	$tbayar = MySqlDateFormat($tbayar);
	$kbayar = CQ($_REQUEST['kbayar']);
	$sumber = CQ($_REQUEST['sumber']);
	
	$idpetugas = getIdUser();
	$petugas = getUserName();
	
	//Ambil nama penerimaan
	$namapenerimaan = "";
	$rekkas = "";
	$rekpendapatan = "";
	$rekpiutang = "";
	$sql = "SELECT nama, rekkas, rekpendapatan, rekpiutang FROM datapenerimaan WHERE replid='$idpenerimaan'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) 
	{
		CloseDb();
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
	
	//Ambil awalan dan cacah tahunbuku untuk bikin nokas;
	$sql = "SELECT awalan, cacah FROM tahunbuku WHERE replid = '".$idtahunbuku."'";
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) == 0) 
	{
		CloseDb();
		trigger_error("Tidak ditemukan data tahun buku!", E_USER_ERROR);
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

	//Simpan ke jurnal
	$ketjurnal = "Dana $namapenerimaan tanggal {$_REQUEST['tcicilan']} dari $sumber";
	$idjurnal = 0;
	$success = SimpanJurnal($idtahunbuku, $tbayar, $ketjurnal, $nokas, "", $idpetugas, $petugas, "penerimaanlain", $idjurnal);
	
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
		$sql = "INSERT INTO penerimaanlain SET idpenerimaan='$idpenerimaan', idjurnal='$idjurnal', sumber='$sumber', jumlah='$jbayar', tanggal='$tbayar', keterangan='$kbayar', petugas='$petugas'";
		QueryDbTrans($sql, $success);
	}
	
	if ($success) 
		CommitTrans();
	else 
		RollbackTrans();

	CloseDb();
	
	$r = random_int(10000, 99999);
	header("Location: pembayaran_lainnya.php?r=$r&idkategori=$idkategori&idpenerimaan=$idpenerimaan&idtahunbuku=$idtahunbuku");
	
	exit();
}

//Muncul pertama kali
$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pembayaran Lainnya</title>
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tools.js"></script>
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

function salinangka(){	
	var angka = document.getElementById("jbayar").value;
	document.getElementById("angkabesar").value=angka;
}

function ValidateSubmit() 
{
	var isok = validateEmptyText('sumber','Sumber Penerimaan') &&
		   	   validateEmptyText('jbayar','Jumlah Penerimaan') &&
		   	   validasiAngka() &&
		   	   validateMaxText('kbayar', 255, 'Keterangan Penerimaan') &&
		   	   confirm('Data sudah benar?');	
			   
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function validasiAngka() {
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) {
		alert ('Jumlah Penerimaan harus berupa bilangan!');
		document.getElementById('jbayar').value = "";
		document.getElementById('jbayar').focus();
		return false;
	}
	else if (angka <= 0)
	{
		alert ('Jumlah Penerimaan harus positif!');
		document.getElementById('jbayar').focus();
		return false;
	}
	return true;
}

function cetakkuitansi(id) {
	newWindow('kuitansilain.php?id='+id,'CetakKuitansiLain','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function editpembayaran(id) {
	newWindow('pembayaranlain_edit.php?idpembayaran='+id,'EditPembayaran','425','390','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh() {
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	
	var addr = "pembayaran_lainnya.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&idtahunbuku="+idtahunbuku;
	document.location.href = addr;
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
	
	var lain = new Array('sumber','jbayar','kbayar');
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

<body topmargin="0" leftmargin="0" onload="document.getElementById('sumber').focus();">
<form name="main" id="main" method="post">
<input type="hidden" name="issubmit" id="issubmit" value="0" />
<input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
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
    	<td valign="top" width="35%">
            <fieldset style="background:url(images/bttablelong.png)">
            <legend></legend>
            <table border="0"  cellpadding="2" cellspacing="2" width="100%" align="center">
            <tr height="25">
                <td colspan="3" class="header" align="center">Penerimaan <?=$namapenerimaan?></td>
            </tr>
            <tr>
                <td width="30%"><strong>Penerimaan</strong></td>
                <td colspan="2"><input type="text" readonly="readonly" size="30" value="<?=$namapenerimaan?>" style="background-color:#CCCC99" /></td>
            </tr>
            <tr>
                <td width="25%"><strong>Sumber</strong></td>
                <td colspan="2"><input type="text" name="sumber" id="sumber" size="30" maxlength="30" onKeyPress="return focusNext('jbayar', event)" onfocus="panggil('sumber')"/></td>
            </tr>
            <tr>
                <td width="25%"><strong>Jumlah</strong></td>
                <td colspan="2"><input type="text" name="jbayar" id="jbayar" onblur="formatRupiah('jbayar')" onfocus="unformatRupiah('jbayar');panggil('jbayar')" onKeyPress="return focusNext('tcicilan', event)" onkeyup="salinangka()"/>
                	<input type="hidden" name="angkabesar" id="angkabesar" value="<?=$jbayar ?>" />
            </tr>
			<tr>
				<td><strong>Rek. Kas</strong></td>
				<td colspan="2">
					<select name="rekkas" id="rekkas" style="width: 200px">
		<?php 			OpenDb();
						$sql = "SELECT kode, nama
								  FROM jbsfina.rekakun
								 WHERE kategori = 'HARTA'
								 ORDER BY nama";        
						$res = QueryDb($sql);
						while($row = mysqli_fetch_row($res))
						{
							$sel = $row[0] == $defrekkas ? "selected" : "";
							echo "<option value='".$row[0]."' $sel>{$row[0]} {$row[1]}</option>";
						} ?>                
					</select>
				</td>
			</tr>			
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>
                <input type="text" name="tcicilan" id="tcicilan" readonly value="<?=$tanggal ?>" onKeyPress="return focusNext('kbayar', event)" style="background-color:#CCCC99"> </td>
                <td width="60%">
                &nbsp;
                </td>        
            </tr>
            <tr>
                <td width="25%" align="left" valign="top">Keterangan</td>
                <td colspan="2"><textarea id="kbayar" name="kbayar" rows="3" cols="27" onKeyPress="return focusNext('Simpan', event)" style="width:225px; height:50px" onfocus="panggil('kbayar')"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                <input type="button" name="Simpan" id="Simpan" class="but" value="Simpan" onclick="this.disabled = true; ValidateSubmit(); " />
                <br /><br />
                </td>
            </tr>
            </table>
        	</fieldset>
 		</td>
     	<td valign="top">
<?php      $sql = "SELECT p.replid AS id, j.nokas, p.sumber,  date_format(p.tanggal, '%d-%b-%Y') as tanggal, 
					   p.keterangan, p.jumlah, p.petugas, jd.koderek AS rekkas, ra.nama AS namakas 
				  FROM penerimaanlain p, jurnal j, jurnaldetail jd, rekakun ra   
				 WHERE j.replid = p.idjurnal
				   AND j.replid = jd.idjurnal
			       AND jd.koderek = ra.kode
				   AND p.idpenerimaan = '$idpenerimaan'
				   AND ra.kategori = 'HARTA'
				 ORDER BY p.tanggal, p.replid DESC";
        $result = QueryDb($sql);
        if (mysqli_num_rows($result) > 0) 
		{ ?>            
            <table class="tab" id="table" width="100%" cellpadding="5" cellspacing="0">
            <tr height="30" align="center">
                <td class="header" width="5%">No</td>
                <td class="header" width="15%">No. Jurnal/Tgl</td>
                <td class="header" width="15%">Sumber</td>
				<td class="header" width="15%">Rek. Kas</td>
                <td class="header" width="15%">Jumlah</td>
                <td class="header" width="*">Keterangan</td>
                <td class="header" width="8%">Petugas</td>
                <td class="header">&nbsp;</td>
            </tr>
<?php          $cnt = 0;
            $total = 0;
            while ($row = mysqli_fetch_array($result)) 
			{
                $total += $row['jumlah']; ?>
            <tr>
                <td align="center"><?=++$cnt?></td>
                <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
                <td align="left"><?=$row['sumber'] ?></td>
				<td align="left"><?= $row['rekkas'] . " " . $row['namakas']  ?> </td>
                <td align="right"><?=FormatRupiah($row['jumlah'])?></td>
                <td><?=$row['keterangan'] ?></td>
                <td><?=$row['petugas'] ?></td>
                <td align="center">
                <a href="javascript:cetakkuitansi(<?=$row['id'] ?>)" ><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Kuitansi Pembayaran!', this, event, '100px')" /></a>&nbsp;
            <?php  if (getLevel() != 2) { ?>       
                <a href="javascript:editpembayaran(<?=$row['id'] ?>)"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pembayaran Cicilan!', this, event, '120px')"/></a>
            <?php } ?>  
                </td>
            </tr>
            <?php
            }
            ?>
            <tr height="35">
                <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
                <td bgcolor="#996600" align="right" colspan="2"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
                <td bgcolor="#996600" colspan="3">&nbsp;</td>
            </tr>
            </table>
            <script language='JavaScript'>
            Tables('table', 1, 0);
            </script>
     	<?php } ?>
		</td>
	</tr>
	</table>
<!-- EOF CONTENT -->
</td></tr>
</table>
</form>
</body>

</html>
