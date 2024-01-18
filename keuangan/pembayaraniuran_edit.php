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
require_once('library/jurnal.php');
require_once('include/db_functions.php');
require_once('include/theme.php');
require_once('include/sessioninfo.php');
require_once('library/logger.php');

$idpembayaran = $_REQUEST['idpembayaran'];

OpenDb();

// -- Default Rekening Kas using value from previous input
$sql = "SELECT jd.koderek
		  FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j, jbsfina.jurnaldetail jd, rekakun rk
	     WHERE p.replid = '$idpembayaran'
		   AND p.idjurnal = j.replid
		   AND j.replid = jd.idjurnal
		   AND jd.koderek = rk.kode
		   AND rk.kategori = 'HARTA'";
$defrekkas = FetchSingle($sql);

$sql = "SELECT p.nis, s.nama, p.idjurnal, p.jumlah, date_format(p.tanggal, '%d-%m-%Y') as tanggal, 
			   p.keterangan, pn.nama as namapenerimaan, pn.rekkas, pn.rekpendapatan, pn.rekpiutang, pn.replid AS idpenerimaan  
		  FROM penerimaaniuran p, jbsakad.siswa s, datapenerimaan pn 
		 WHERE p.replid = '$idpembayaran' AND p.nis = s.nis AND p.idpenerimaan = pn.replid";
$result = QueryDb($sql);
$row = mysqli_fetch_array($result);
$nis = $row['nis'];
$nama = $row['nama'];
$idjurnal = $row['idjurnal'];
$tanggal = $row['tanggal'];
$keterangan = $row['keterangan'];
$idpenerimaan = $row['idpenerimaan'];
$namapenerimaan = $row['namapenerimaan'];
$besar = $row['jumlah'];
$rekkas = $row['rekkas'];
$rekpiutang = $row['rekpiutang'];
$rekpendapatan = $row['rekpendapatan'];

$jbayar = $besar;
if (isset($_REQUEST['jcicilan'])) 
	$jbayar = UnformatRupiah($_REQUEST['jcicilan']);
if (isset($_REQUEST['tcicilan'])) 
	$tanggal = $_REQUEST['tcicilan'];

if (1 == (int)$_REQUEST['issubmit']) 
{		
	$jcicilan = UnformatRupiah($_REQUEST['jcicilan']);	
	$tcicilan = $_REQUEST['tcicilan'];
	$tcicilan = MySqlDateFormat($tcicilan);
	$kcicilan = CQ($_REQUEST['kcicilan']);
	$alasan = CQ($_REQUEST['alasan']);
	$petugas = getUserName();
	
	$selrekkas = $_REQUEST['rekkas']; // selected rekening kas
		
	if ($jcicilan == $besar) 
	{
		//--------------------------------------------------------------
		// Hanya mengubah informasi pembayaran tanpa mengubah besarnya  
		// -------------------------------------------------------------

		BeginTrans();
		$success = true;
		
		$sql = "UPDATE penerimaaniuran
				   SET tanggal='$tcicilan', keterangan='$kcicilan',
					   alasan='$alasan', petugas = '$petugas'
				 WHERE replid=$idpembayaran";
		$result = QueryDbTrans($sql, $success);
		
		// Ambil kode rekening dari jurnal bukan dari datapenerimaan
		$rekkas = AmbilKodeRekJurnal($idjurnal, "HARTA", $idpenerimaan);
		if ($success && $rekkas != $selrekkas)
		{
			$sql = "UPDATE jurnaldetail
					   SET koderek='$selrekkas'
					 WHERE idjurnal='$idjurnal'
					   AND koderek='$rekkas'
					   AND kredit=0";
			QueryDbTrans($sql, $success);	
		}
		
		if ($success) 
		{
			CommitTrans();
			CloseDb();
			echo  "<script language='javascript'>";
			echo  "opener.refresh();";
			echo  "window.close();";
			echo  "</script>";
			exit();
		} 
		else 
		{
			RollbackTrans();
			CloseDb();
			echo  "<script language='javascript'>";
			echo  "alert('Gagal menyimpan data!);";
			echo  "</script>";
		}
		
	} 
	else 
	{
		//----------------------------
		// Mengubah besar pembayaran  
		// ---------------------------

        // 2020-10-05 Check SchoolPay Transaction
        $paymentExist = false;
        $sql = "SELECT COUNT(replid)
		          FROM jbsfina.paymenttrans
		         WHERE idpenerimaaniuran = $idpembayaran";
        $res = QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $paymentExist = $row[0] > 0;
        }

        if ($paymentExist)
        {
            $sql = "SELECT p.nis, a.departemen, pt.idtabungan AS iddatatabungan, p.idjurnaltabcust, t.replid AS idtabungan,
                           dt.rekkas, dt.rekutang, p.jenistrans, IFNULL(p.idpenerimaanjtt, 0) AS idpenerimaanjtt, 
                           IFNULL(p.idpenerimaaniuran, 0) AS idpenerimaaniuran, IFNULL(p.iddatapenerimaan, 0) AS iddatapenerimaan,
                           p.replid AS idpayment
                      FROM jbsfina.paymenttrans p
                     INNER JOIN jbsakad.siswa s ON p.nis = s.nis
                     INNER JOIN jbsakad.angkatan a ON s.idangkatan = a.replid
                     INNER JOIN jbsfina.paymenttabungan pt ON pt.departemen = a.departemen AND pt.jenis = 2
                     INNER JOIN jbsfina.tabungan t ON p.idjurnaltabcust = t.idjurnal
                     INNER JOIN jbsfina.datatabungan dt ON t.idtabungan = dt.replid
                     WHERE p.idpenerimaaniuran = $idpembayaran";
            $res = QueryDb($sql);

            $row = mysqli_fetch_array($res);
            $idDataTabungan = $row["iddatatabungan"];
            $idJurnalTabungan = $row["idjurnaltabcust"];
            $idTabungan = $row["idtabungan"];
            $rekKasTab = $row["rekkas"];
            $rekUtangTab = $row["rekutang"];
            $jenisTrans = $row["jenistrans"];
            $idPenerimaanIuran = $row["idpenerimaaniuran"];
            $idPayment = $row["idpayment"];

            // Cek Saldo
            $sql = "SELECT SUM(kredit) - SUM(debet)
                      FROM jbsfina.tabungan
                     WHERE nis = '$nis'
                       AND idtabungan = '".$idDataTabungan."'";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $jSaldo = $row[0];

            $sql = "SELECT debet
                      FROM jbsfina.tabungan
                     WHERE replid = $idTabungan";
            $res = QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $debetAwal = (int)$row[0];
        }

        $ERROR_MSG = "";
        if ($paymentExist)
        {
            if ($jSaldo + $debetAwal < $jcicilan)
            {
                $ERROR_MSG = "Maaf, pembayaran tidak dapat dilakukan! Saldo tabungan tidak mencukupi untuk penarikan!";
                CloseDb();
            }
        }

        if ($ERROR_MSG == "")
        {
            BeginTrans();
            $success = true;

            $sql = "UPDATE penerimaaniuran
                       SET tanggal='$tcicilan', jumlah='$jcicilan',
                           keterangan='$kcicilan', alasan='$alasan', petugas='$petugas'
                     WHERE replid='$idpembayaran'";
            QueryDbTrans($sql, $success);

            // Ambil kode rekening dari jurnal bukan dari datapenerimaan
            $rekkas = AmbilKodeRekJurnal($idjurnal, "HARTA", $idpenerimaan);
            $rekpendapatan = AmbilKodeRekJurnal($idjurnal, "PENDAPATAN", $idpenerimaan);

            if ($success)
            {
                $sql = "UPDATE jurnaldetail
                           SET debet='$jcicilan'
                         WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
                QueryDbTrans($sql, $success);
            }

            if ($success && $selrekkas != $rekkas)
            {
                $sql = "UPDATE jurnaldetail
                           SET koderek='$selrekkas'
                         WHERE idjurnal='$idjurnal' AND koderek='$rekkas' AND kredit=0";
                QueryDbTrans($sql, $success);
            }

            if ($success)
            {
                $sql = "UPDATE jurnaldetail
                           SET kredit='$jcicilan'
                         WHERE idjurnal='$idjurnal' AND koderek='$rekpendapatan' AND debet=0";
                QueryDbTrans($sql, $success);
            }

            if ($paymentExist && $success)
            {
                // 2020-10-05 SchoolPay Transaction

                $sql = "UPDATE jbsfina.tabungan
                           SET alasan = '$alasan',
                               debet = '$jcicilan',
                               kredit = '0'
                         WHERE replid = $idTabungan";
                QueryDbTrans($sql, $success);

                if ($success)
                {
                    $sql = "UPDATE jbsfina.jurnaldetail
                               SET debet = '0', kredit = '$jcicilan'
                             WHERE idjurnal = '$idJurnalTabungan'
                               AND koderek = '".$rekKasTab."'";
                    QueryDbTrans($sql, $success);
                }

                if ($success)
                {
                    $sql = "UPDATE jbsfina.jurnaldetail
                               SET debet = '$jcicilan', kredit = '0'
                             WHERE idjurnal = '$idJurnalTabungan'
                               AND koderek = '".$rekUtangTab."'";
                    QueryDbTrans($sql, $success);
                }

                if ($success)
                {
                    $sql = "UPDATE jbsfina.paymenttrans
                               SET jumlah = '$jcicilan'
                             WHERE replid = $idPayment";
                    QueryDbTrans($sql, $success);
                }
            }

            if ($success)
            {
                CommitTrans();
                CloseDb();
                echo  "<script language='javascript'>";
                echo  "opener.refresh();";
                echo  "window.close();";
                echo  "</script>";
                exit();
            }
            else
            {
                RollbackTrans();
                CloseDb();
                echo  "<script language='javascript'>";
                echo  "alert('Gagal menyimpan data!);";
                echo  "</script>";
            }
        } // if ($ERROR_MSG == "")
	} // if ($jcicilan == $besar)
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/calendar-green.css">
<title>JIBAS SIMKEU [Ubah Pembayaran Iuran]</title>
<script src="script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="script/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="script/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/rupiah2.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<!--<link rel="stylesheet" type="text/css" href="style/calendar-win2k-1.css">-->
<script type="text/javascript" src="script/calendar.js"></script>
<script type="text/javascript" src="script/lang/calendar-en.js"></script>
<script type="text/javascript" src="script/calendar-setup.js"></script>
<script language="javascript">
function ValidateSubmit() 
{
	var isok = 	validateEmptyText('jcicilan', 'Besarnya Pembayaran') &&
	 	   		validasiAngka() &&
		   		validateEmptyText('tcicilan', 'Tanggal Pembayaran') &&
		   		validateEmptyText('alasan', 'Alasan Perubahan') &&
		   		validateMaxText('alasan', 500, 'Alasan Perubahan') &&
		   		validateMaxText('kcicilan', 255, 'Keterangan Pembayaran') &&
				confirm("Data sudah benar?");
				
	document.getElementById('issubmit').value = isok ? 1 : 0;
	
	if (isok)
		document.main.submit();
	else
		document.getElementById('Simpan').disabled = false;
}

function validasiAngka() 
{
	var angka = document.getElementById("angkacicilan").value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya pembayaran harus berupa bilangan!');
		document.getElementById('jcicilan').value = "";
		document.getElementById('jcicilan').focus();
		return false;
	}
	else if(angka < 0)
	{
		alert ('Besarnya iuran tidak boleh negatif!');
		document.getElementById('jcicilan').focus();
		return false;
	}
	return true;
}

function salinangka()
{	
	var angka = document.getElementById("jcicilan").value;
	document.getElementById("angkacicilan").value = angka;
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
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" background="" style='background-color:#dfdec9' onload="document.getElementById('jcicilan').focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: Ubah Pembayaran :.
    </div>
	</td>
    <td width="28" background="<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    
    <form name="main" method="post">
    <input type="hidden" name="issubmit" id="issubmit" value="0" />
    <input type="hidden" name="idpembayaran" id="idpembayaran" value="<?=$idpembayaran ?>" />
    <table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
	<!-- TABLE CONTENT -->
    <tr>
        <td width="55%" align="left"><strong>Pembayaran</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$namapenerimaan?>" readonly="readonly" style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Nama</strong></td>
        <td colspan="2"><input type="text" size="30" value="<?=$nis . " - " . $nama ?>" readonly style="background-color:#CCCC99"/></td>
    </tr>
    <tr>
        <td><strong>Jumlah</strong></td>
        <td colspan="2"><input type="text" name="jcicilan" id="jcicilan" value="<?=FormatRupiah($jbayar) ?>" onblur="formatRupiah('jcicilan')" onfocus="unformatRupiah('jcicilan')" onKeyPress="return focusNext('alasan', event)" onkeyup="salinangka()"/>
        <input type="hidden" name="angkacicilan" id="angkacicilan" value="<?=$jbayar?>" />
        </td>
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
        <td align="left"><strong>Tanggal</strong></td>
        <td>
        <input type="text" name="tcicilan" id="tcicilan" readonly size="15" value="<?=$tanggal ?>" onKeyPress="return focusNext('alasan', event)" style="background-color:#CCCC99"> </td>
        <td width="60%">
        &nbsp;
	     </td> 
    </tr>
    <tr>
        <td valign="top"><strong>Alasan Perubahan</strong></td>
        <td colspan="2"><textarea id="alasan" name="alasan" rows="3" cols="30" onKeyPress="return focusNext('kcicilan', event)"><?=$alasan ?></textarea>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">Keterangan</td>
        <td colspan="2"><textarea id="kcicilan" name="kcicilan" rows="3" cols="30" onKeyPress="return focusNext('Simpan', event)"><?=$keterangan ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
        <input type="button" name="Simpan" id="Simpan" class="but" value="Simpan" onclick="this.disabled = true; ValidateSubmit();" />
        <input type="button" name="tutup" id="tutup" class="but" value="Tutup" onclick="window.close()" />
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

<?php if (strlen((string) $mysqli_ERROR_MSG) > 0) { ?>
	<center>
    <font color="red"><strong><?=$mysqli_ERROR_MSG?></strong></font>
    </center>
<?php } ?>
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
	<center>
    <font color="red"><strong><?=$ERROR_MSG?></strong></font>
    </center>
    <script language="javascript">alert('<?=$ERROR_MSG?>');</script>
<?php } ?>

</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("tcicilan");
var sprytextfield1 = new Spry.Widget.ValidationTextField("jcicilan");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("kcicilan");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("alasan");
</script>
<?php
CloseDb();
?>