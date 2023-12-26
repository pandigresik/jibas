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
require_once('library/jurnal.php');

if (getLevel() == 2) 
{ ?>
<script language="javascript">
	alert('Maaf, anda tidak berhak mengakses halaman ini!');
	window.history.go(-1);
</script>
<?php 	exit();
} // end if

$dept = $_REQUEST['dept'];
$ttutup = $_REQUEST['ttutup'];
$tahunbuku = CQ($_REQUEST['tahunbuku']);
$tawal = $_REQUEST['tawal'];
$awalan = CQ($_REQUEST['awalan']);
$rekre = $_REQUEST['rekre'];
$keterangan = CQ($_REQUEST['keterangan']);
$idpetugas = getIdUser();
$petugas = getUserName();

$errmsg = "";
if (isset($_REQUEST['lanjut']))
{
	OpenDb();
	
	$sql = "SELECT replid, tanggalmulai FROM tahunbuku WHERE aktif=1 AND departemen='$dept'";
	$row = FetchSingleRow($sql);
	$idtahunbuku = $row[0];
	$tanggal1 = $row[1];
	$tanggal2 = MySqlDateFormat($ttutup);
	
	$sql = "SELECT COUNT(replid) FROM tahunbuku WHERE tahunbuku='$tahunbuku' AND departemen='$dept'";
	$n = FetchSingle($sql);
	
	$continue = true;
	if ($n > 0)
	{
		$errmsg = "Nama tahun buku '$tahunbuku' sudah dipakai sebelumnya di departemen $dept! Gunakan nama tahun buku lainnya";
		$continue = false;
	}
	
	if ($continue)
	{
		$sql = "SELECT COUNT(replid) FROM tahunbuku WHERE awalan='$awalan' AND departemen='$dept'";
		$n = FetchSingle($sql);
		
		if ($n > 0)
		{
			$errmsg = "Kode awalan '$awalan' sudah dipakai sebelumnya di departemen $dept! Gunakan kode awalan lainnya";
			$continue = false;
		}
	}
	
	if ($continue)
	{
		$n_aktiva = 0;
		$n_pasiva = 0;
	
		$sql = "SELECT jd.koderek, SUM(jd.debet - jd.kredit) 
				FROM jurnal j, jurnaldetail jd, rekakun ra 
				WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('HARTA', 'PIUTANG', 'INVENTARIS') 
				GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
		//echo  "$sql<br>";				
		$result = QueryDb($sql); 
		while($row = mysqli_fetch_row($result))
		{
			$aktiva[$n_aktiva]["kode"] = $row[0];
			$aktiva[$n_aktiva]["jumlah"] = (float)$row[1];
			$n_aktiva++;
		}
		
		$sql = "SELECT jd.koderek, sum(jd.kredit - jd.debet) 
				FROM jurnal j, jurnaldetail jd, rekakun ra 
				WHERE j.replid = jd.idjurnal AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori = 'UTANG' 
				GROUP BY jd.koderek, ra.nama ORDER BY jd.koderek";
		//echo  "$sql<br>";				
		$result = QueryDb($sql); 
		while($row = mysqli_fetch_row($result))
		{
			$pasiva[$n_pasiva]["kode"] = $row[0];
			$pasiva[$n_pasiva]["jumlah"] = (float)$row[1];
			$n_pasiva++;
		}
		
		$sql = "SELECT SUM(jd.kredit - jd.debet) 
				FROM rekakun ra, jurnal j, jurnaldetail jd 
				WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori IN ('PENDAPATAN', 'MODAL')";
		//echo  "$sql<br>";					
		$income = (float)FetchSingle($sql);
				
		$sql = "SELECT SUM(jd.debet - jd.kredit) 
				FROM rekakun ra, jurnal j, jurnaldetail jd 
				WHERE jd.idjurnal = j.replid AND jd.koderek = ra.kode AND j.idtahunbuku = '$idtahunbuku' 
					AND j.tanggal BETWEEN '$tanggal1' AND '$tanggal2' AND ra.kategori='BIAYA'";
		//echo  "$sql<br>";					
		$outcome = (float)FetchSingle($sql);
		
		$re = $income - $outcome;
		
		/*
		echo  "<pre>";
		print_r($aktiva);
		print_r($pasiva);
		echo  $re;
		echo  "</pre>";
		exit();
		*/
		
		$success = true;
		BeginTrans();
		
		// Ubah tahun buku lainnya menjadi tidak aktif
		$sql = "UPDATE tahunbuku SET aktif=0 WHERE departemen='$dept'";
		QueryDbTrans($sql, $success);
		
		// Bikin Tahun Buku Baru
		$tawal = MySqlDateFormat($tawal);
		if ($success)
		{
			$sql = "INSERT INTO tahunbuku SET tahunbuku='$tahunbuku', tanggalmulai='$tawal', awalan='$awalan', 
					aktif=1, keterangan='$keterangan', departemen='$dept'";
			// echo  "$sql<br>";				
			QueryDbTrans($sql, $success);				
		}

		// Ambil Id Tahun Buku Baru
		$idtahunbaru = 0;
		$nokas = "";
		if ($success)
		{
			$sql = "SELECT LAST_INSERT_ID()";
			$idtahunbaru = FetchSingle($sql);
			
			$cacah = 1; //cacah
			$nokas = $awalan . rpad($cacah, "0", 6); //form nokas
			// echo  "$nokas<br>";
		}

		// Simpan ke jurnal
		$idjurnal = 0;
		if ($success)
			$success = SimpanJurnal($idtahunbaru, $tawal, "Saldo Awal Tahun Buku $tahunbuku Dept $dept", $nokas, "", $idpetugas, $petugas, "saldoawal", $idjurnal);
		
		// Save Aktiva
		for($i = 0; $success && $i < count($aktiva); $i++)
		{
			$kode = $aktiva[$i]["kode"];
			$jumlah = $aktiva[$i]["jumlah"];
			
			if ($jumlah > 0)
				$success = SimpanDetailJurnal($idjurnal, "D", $kode, $jumlah);
			else
				$success = SimpanDetailJurnal($idjurnal, "K", $kode, abs($jumlah));
		}
		
		// Save Pasiva
		for($i = 0; $success && $i < count($pasiva); $i++)
		{
			$kode = $pasiva[$i]["kode"];
			$jumlah = $pasiva[$i]["jumlah"];
			
			if ($jumlah > 0)
				$success = SimpanDetailJurnal($idjurnal, "K", $kode, $jumlah);
			else
				$success = SimpanDetailJurnal($idjurnal, "D", $kode, abs($jumlah));
		}
		
		// Retained Earning
		if ($success)
		{
			if ($re > 0)
				$success = SimpanDetailJurnal($idjurnal, "K", $rekre, $re);
			else
				$success = SimpanDetailJurnal($idjurnal, "D", $rekre, abs($re));
		}
		
		//increment cacah di tahunbuku
		if ($success) 
		{
			$sql = "UPDATE tahunbuku SET cacah=cacah+1, info1='$idjurnal' WHERE replid='$idtahunbaru'";
			QueryDbTrans($sql, $success);
		}
			
		if ($success)
			CommitTrans();
		else
			RollbackTrans();
		CloseDb();
		
		if ($success)
			header("location: tutupbuku3.php");
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
<script language="javascript" src="script/validasi.js"></script>
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
	var lain = new Array('tahunbuku','tawal','rekre','awalan');
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

function validasi() 
{
	return validateEmptyText('dept', 'Departemen') && 
	       validateEmptyText('ttutup', 'Tanggal Tutup Buku') && 
		   validateEmptyText('tahunbuku', 'Tahun Buku') && 
		   validateEmptyText('tawal', 'Tanggal Mulai Tahun Buku Baru') && 
		   validateEmptyText('awalan', 'Awalan Kuitansi') && 
		   validateEmptyText('rekre', 'Kode Rekening Laba Ditahan') && 
		   validateMaxText('keterangan', 255, 'Keterangan Tahun Buku') &&
		   confirm("Data sudah lengkap dan benar?\r\nPERINGATAN: Tahun buku yang sudah ditutup tidak dapat diakses lagi!");
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
        <font style="font-size:20px; color:#FFF">Langkah 2 dari 3</font>
        </td>
        <td align="left" valign="middle" style="background-color:#306">
        <font style="font-size:11px; color:#FFF">Menentukan tahun buku baru, membuat saldo awal untuk tahun buku baru<br>
        <font color="#FF0000"><strong>PERINGATAN:</strong> Tahun buku lama tidak dapat diakses lagi setelah tahun buku baru dibuat</font>
        </font>
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
            <input type="text" name="dept" id="dept" width="10" readonly="readonly" style="background-color:#CCC" value="<?=$dept?>" />
            </td>
	    </tr>
        <tr>
            <td align="left"><strong>Tanggal Tutup Buku:</strong></td>
            <td align="left">
            <input type="text" name="ttutup" id="ttutup" width="20" readonly="readonly" style="background-color:#CCC" value="<?=$ttutup?>" />
            </td>
        </tr>
        <tr>
            <td align="left"><strong>Tahun Buku Baru</strong></td>
            <td align="left"><input type="text" name="tahunbuku" id="tahunbuku" value="<?=$tahunbuku?>" maxlength="100" size="25" onKeyPress="return focusNext('tawal', event)" onFocus="panggil('tahunbuku')"></td>
        </tr>
        <tr>
            <td align="left"><strong>Tanggal Mulai</strong></td>
            <td align="left">
            <input type="text" name="tawal" id="tawal" readonly size="15" value="<?=$tawal?>" onKeyPress="return focusNext('awalan', event);" onFocus="panggil('tawal')" onClick="Calendar.setup()" style="background-color:#CCCC99" value="<?=date("d-m-Y")?>">&nbsp;
            <img src="images/calendar.jpg" name="tabel" border="0" id="btawal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/>
            </td>
        </tr>
        <tr>
            <td align="left"><strong>Awalan Kuitansi</strong></td>
            <td align="left"><input type="text" name="awalan" id="awalan" value="<?=$awalan?>" maxlength="5" size="7" onKeyPress="return focusNext('rekre', event)" onFocus="panggil('awalan')"></td>
        </tr>
        <tr>
            <td align="left" valign="top"><strong>Kode Akun Laba Ditahan (Retained Earning)</strong></td>
            <td align="left">
            <select name="rekre" id="rekre"  style="width:220px" onKeyPress="return focusNext('keterangan', event)">
            <?php
            $sql = "SELECT kode, nama FROM rekakun WHERE kategori='MODAL' ORDER BY kode";
            $result = QueryDb($sql);
            while ($row = mysqli_fetch_row($result)) {
            ?>
                <option value="<?=$row[0]?>"><?=$row[0] . "  " . $row[1]?></option>
            <?php
            }
            ?>
            </select><br />
            <font style="font-family:Arial, Helvetica, sans-serif; font-size:10px;"><em>
            Kode akun untuk menampung laba/rugi yang diperoleh tahun berjalan dan menjadi akun Retained Earning (Laba ditahan) untuk tahun buku baru
            </em></font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">Keterangan</td>
            <td align="left"><textarea name="keterangan" id="keterangan" rows="3" cols="40" onKeyPress="return focusNext('lanjut', event)" onFocus="panggil('keterangan')"><?=$_REQUEST['keterangan']?></textarea></td>
        </tr>
        <tr>
            <td  colspan="2" align="left">
                <input class="but" type="submit" value="Lanjut" name="lanjut" id="lanjut">
            </td>
        </tr>
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
	inputField  : "tawal",         // ID of the input field
    ifFormat    : "%d-%m-%Y",    // the date format
    button      : "btawal"       // ID of the button
});
Calendar.setup (
{
	inputField  : "tawal",         // ID of the input field
    ifFormat    : "%d-%m-%Y",    // the date format
    button      : "tawal"       // ID of the button
});
</script>