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
require_once('library/repairdatajtt.php');

$idkategori = $_REQUEST['idkategori'];
$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
$nis = (string)$_REQUEST['nis'];
$idtahunbuku = (int)$_REQUEST['idtahunbuku'];
$departemen = (string)$_REQUEST['departemen'];
$keterangan = (string)$_REQUEST['keterangan'];
$lunas = 0;
if (isset($_REQUEST['lunas']))
	$lunas = $_REQUEST['lunas'];
$errmsg = $_REQUEST['errmsg'];

OpenDb();

// ambil tahunbuku yang aktif di departemen terpilih 
$sql = "SELECT replid FROM tahunbuku WHERE departemen='$departemen' AND aktif=1";
$idtahunbuku_aktif = FetchSingle($sql);

// cek data siswa 
$sql = "SELECT s.replid as replid, nama, telponsiswa as telpon, hpsiswa as hp, 
		       kelas as namakelas, alamatsiswa as alamattinggal, tingkat as namatingkat 
        FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t 
		  WHERE s.idkelas = k.replid AND nis = '$nis' AND t.replid = k.idtingkat";
$result = QueryDb($sql);
if (mysqli_num_rows($result) == 0) 
{
	// tidak ditemukan data siswa, aplikasi keluar!
	CloseDb();
	exit();
} 
else 
{
	$row = mysqli_fetch_array($result);
	$replid = $row['replid'];
	$nama = $row['nama'];
	$telpon = $row['telpon'];
	$hp = $row['hp'];
	$namatingkat = $row['namatingkat'];
	$namakelas = $row['namakelas'];
	$alamattinggal = $row['alamattinggal'];
}

// ambil nama penerimaan
$sql = "SELECT nama FROM datapenerimaan WHERE replid = '".$idpenerimaan."'";
$namapenerimaan = FetchSingle($sql);

$input_awal = "onload=\"document.getElementById('besar').focus();\"";
$keterangan = "";
$besar = "";
$idbesarjtt = 0;

//// Cari tahu besar pembayaran
// FIXED: 27 Agustus 2010
$sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas, b.info1 AS idjurnal
	       FROM besarjtt b
		   WHERE b.nis = '$nis' AND b.idpenerimaan = '$idpenerimaan' AND b.info2 = '".$idtahunbuku."'";
$result = QueryDb($sql);
$bayar = mysqli_num_rows($result);
$tgl_jurnal = date('d-m-Y');
if (mysqli_num_rows($result) > 0) 
{
	$row = mysqli_fetch_array($result);
	$idbesarjtt = $row['id'];
	$lunas = $row['lunas'];
	$besar = $row['besar'];
	if (isset($_REQUEST['besar']) && $_REQUEST['besar'] <> 0)
		$besar = $_REQUEST['besar']; 
		
	$keterangan = $row['keterangan'];
	if (isset($_REQUEST['keterangan']))
		$keterangan = $_REQUEST['keterangan'];
	
	$idjurnal = $row['idjurnal'];
	$sql = "SELECT DATE_FORMAT(tanggal, '%d-%m-%Y') FROM jurnal WHERE replid='$idjurnal'";
	$tgl_jurnal = FetchSingle($sql);
	
	$input_awal = "";
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pembayaran Tunggakan</title>
<script src="script/rupiah.js" language="javascript"></script>
<script src="script/validasi.js" language="javascript"></script>
<script src="script/tables.js" language="javascript"></script>
<script src="script/tooltips.js" language="javascript"></script>
<script src="script/tools.js" language="javascript"></script>
<script language="javascript">
var win = null;
function newWindow(mypage,myname,w,h,features) 
{
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

function salinangka()
{	
	var angka = document.getElementById("besar").value;
	document.getElementById("angkabesar").value = angka;
}

function validasi_besar()
{
	return validateEmptyText('besar','Besarnya Pembayaran') &&
			 validasiAngka() &&
			 validateMaxText('keterangan',255,'Keterangan Besarnya Pembayaran');
}

function validasiAngka() 
{
	var angka = document.getElementById("angkabesar").value;
	if(isNaN(angka)) 
	{
		alert ('Besarnya pembayaran harus berupa bilangan!');
		document.getElementById('besar').value = "";
		document.getElementById('besar').focus();
		return false;
	}
	else if (angka < 0)
	{
		alert ('Besarnya pembayaran harus positif!');
		document.getElementById('besar').focus();
		return false;
	}
	return true;
}

function simpan_besar() 
{	
	if (validasi_besar()) 
	{
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

function cetakkuitansi(id) 
{
	newWindow('kuitansijtt.php?id='+id, 'CetakKuitansi','360','850','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function editpembayaran(id) 
{
	newWindow('pembayaranjtt_edit.php?idpembayaran='+id,'EditPembayaran','425','392','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit() 
{
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var nis = document.getElementById('nis').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	var addr = "pembayaran_jtt.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&nis="+nis+"&idtahunbuku="+idtahunbuku+"&idbesarjtt="+idbesarjtt+"&edit=1";	
	document.location.href = addr;
}

function refresh() 
{	
	var idkategori = document.getElementById('idkategori').value;
	var idpenerimaan = document.getElementById('idpenerimaan').value;
	var nis = document.getElementById('nis').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var idbesarjtt = document.getElementById('idbesarjtt').value;
	var departemen = document.getElementById('departemen').value;
	var addr = "pembayaran_tunggak_jtt.php?idkategori="+idkategori+"&idpenerimaan="+idpenerimaan+"&nis="+nis+"&idtahunbuku="+idtahunbuku+"&idbesarjtt="+idbesarjtt+"&departemen="+departemen;	
	document.location.href = addr;
}

function cetak() 
{		
	var addr = "pembayaranjtt_cetak.php?idkategori=<?=$idkategori ?>&idpenerimaan=<?=$idpenerimaan ?>&nis=<?=$nis ?>&idtahunbuku=<?=$idtahunbuku ?>"
	newWindow(addr, 'CetakPembayaranJtt','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah() 
{	
//alert ('woi');
newWindow('pembayaranjtt_tunggak_add.php?idpenerimaan=<?=$idpenerimaan?>&idkategori=<?=$idkategori?>&nis=<?=$nis?>&idtahunbuku=<?=$idtahunbuku?>&idtahunbuku_aktif=<?=$idtahunbuku_aktif?>','tes','435','390','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function focusNext(elemName, evt) 
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        return false;
    }
    return true;
}

function panggil(elem)
{
	var lain = new Array('besar','keterangan');
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
<body topmargin="0" leftmargin="0" <?=$input_awal?>>
<input type="hidden" name="idkategori" id="idkategori" value="<?=$idkategori ?>" />
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen?>" />
<input type="hidden" name="idpenerimaan" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
<input type="hidden" name="nis" id="nis" value="<?=$nis ?>" />
<input type="hidden" name="idtahunbuku" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<input type="hidden" name="idtahunbuku_aktif" id="idtahunbuku_aktif" value="<?=$idtahunbuku_aktif ?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" background="" style="background-repeat:no-repeat; background-attachment:fixed">
    <table width="100%" border="0" height="100%" cellspacing="2" cellpadding="2">
   	<tr>
    	<td colspan="2">
        <font size="5" color="#990000"><strong><?=$namapenerimaan ?></strong></font><p></td>
   	</tr>
    <tr>
    	<td valign="top" width="265">
        	<fieldset style="background:url(images/bttable400.png);height:240px">
            <legend></legend>
            <table border="0" cellpadding="2" cellspacing="2" align="center">
            <tr height="25">
                <td colspan="2" class="header" align="center">Pembayaran yang Harus Dilunasi</td>
            </tr>
            <tr>
                <td width="25%"><strong>Pembayaran</strong></td>                
                <td><input type="text" readonly="readonly" size="20" value="<?=$namapenerimaan?>" style="background-color:#CCCC99"  /></td>
            </tr>
            <tr>
                <td><strong>Jumlah</strong></td>
            	<td><input type="text" readonly="readonly" style="background-color:#CCCC99"   name="besar" id="besar" size="20" value="<?=FormatRupiah($besar) ?>" onKeyPress="return focusNext('keterangan', event)" onkeyup="salinangka()"/>
                	<input type="hidden" name="angkabesar" id="angkabesar" value="<?=$besar ?>" />
                </td>
            </tr>
            <tr>
                <td>Tgl.Jurnal</td>                
                <td><input type="text" readonly="readonly" size="20" value="<?=$tgl_jurnal?>" style="background-color:#CCCC99"  /></td>
            </tr>
            <tr>
			    <td>Status</td>
                <td>
                <?php 
                if ($lunas == 1)
                    $info = "<font color=blue><strong>Lunas</strong></font>";
					 elseif ($lunas == 2)
						  $info = "<font color=brown><strong>Gratis</strong></font>";
					 else 
						  $info = "<font color=red><strong>Belum Lunas</strong></font>";
                echo  $info;
            	?>
                </td>
            </tr>
            </table>
            <input type="hidden" id="idbesarjtt" name="idbesarjtt" value="<?=$idbesarjtt ?>" />
            </fieldset>
        </td>
        <td valign="top">
            <fieldset style="background:url(images/bttable400.png);height:240px">
            <legend></legend>
            <table border="0" width="100%" cellpadding="2" cellspacing="2">
            <tr height="25">
                <td colspan="4" class="header" align="center">Data Siswa</td>
            </tr>
            <tr valign="top">                    
                <td width="5%"><strong>N I S</strong></td>
                <td><strong>:</strong></td>
               	<td><strong><?=$nis ?></strong> </td><td rowspan="5" width="25%">
                <img src='<?="library/gambar.php?replid=".$replid."&table=jbsakad.siswa";?>' width='100' height='100'></td>
            </tr>
            <tr>
                <td valign="top"><strong>Nama</strong></td>
                <td valign="top"><strong>:</strong></td> 
				<td><strong><?=$nama ?></strong></td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                 <td><strong>:</strong></td>
                <td><strong><?=$namatingkat.' - '.$namakelas ?></strong></td>
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
                <td colspan="2" rowspan="2" valign="top" height="80"><strong>
                  <?=$alamattinggal ?>
                </strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>           
            
            </table>            
            </fieldset>
  		</td>
  	</tr>
    <tr>
        <td align="center" colspan="2"> 
<?php if ($bayar > 0 && $lunas <> 2) 
	{ 
   	$sql = "SELECT count(*) FROM penerimaanjtt WHERE idbesarjtt = '".$idbesarjtt."'";
      $result = QueryDb($sql);
      $row = mysqli_fetch_row($result);
      $nbayar = $row[0];
        
      $info = "Pembayaran Pertama";
      if ($nbayar > 0) 
		{
			$sql = "SELECT p.replid AS id, j.nokas, j.idtahunbuku, date_format(p.tanggal, '%d-%b-%Y') as tanggal,
						   p.keterangan, p.jumlah, p.petugas, p.info1 AS diskon, jd.koderek AS rekkas, ra.nama AS namakas
					  FROM penerimaanjtt p, besarjtt b, jurnal j, jurnaldetail jd, rekakun ra 
					 WHERE p.idbesarjtt = b.replid
					   AND j.replid = p.idjurnal
					   AND j.replid = jd.idjurnal
                       AND jd.koderek = ra.kode
                       AND ra.kategori = 'HARTA'
					   AND b.replid = '$idbesarjtt'
					 ORDER BY p.tanggal ASC";

			$result = QueryDb($sql);
			if (mysqli_num_rows($result) > 1) 
				$info = "Pembayaran Cicilan";  ?> 
        <fieldset>
        <legend><font size="2" color="#003300"><strong><?=$info?></strong></font></legend>
        <form name="main">   	
        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tr>
            <td align="right">
            <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;        
        	<?php if ($lunas == 0) { ?>		
            <a href="#" onClick="JavaScript:tambah()">
            <img src="images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Tambah Cicilan</a>&nbsp;
         <?php } ?>
            </td>
        </tr>
        </table>        
        <br />
        <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
        <tr height="30" align="center">
            <td class="header" width="5%">No</td>
            <td class="header" width="15%">No. Jurnal/Tgl</td>
			<td class="header" width="15%">Rek. Kas</td>
            <td class="header" width="15%">Besar</td>
			<td class="header" width="15%">Diskon</td>
            <td class="header" width="*">Keterangan</td>
            <td class="header" width="12%">Petugas</td>
            <td class="header">&nbsp;</td>
        </tr>
        <?php 
		  $cnt = 0;
		  $total = 0;
		  $total_diskon = 0;
		  while ($row = mysqli_fetch_array($result)) 
		  {
				$total += $row['jumlah'] + $row['diskon'];
				$total_diskon += $row['diskon'];
				?>
        		<tr height="25">
               <td align="center"><?=++$cnt?></td>
               <td align="center"><?="<strong>" . $row['nokas'] . "</strong><br><i>" . $row['tanggal']?></i></td>
			   <td align="left"><?= $row['rekkas'] . " " . $row['namakas']  ?> </td>
               <td align="right"><?=FormatRupiah($row['jumlah'] + $row['diskon'])?></td>
			   <td align="right"><?=FormatRupiah($row['diskon'])?></td>
               <td align="left"><?=$row['keterangan'] ?></td>
               <td align="center"><?=$row['petugas'] ?></td>
               <?php // Hanya bisa mengedit transaksi di tahunbuku yang aktif
						if ($idtahunbuku_aktif == $row['idtahunbuku']) { ?>
                  <td align="center">
                     <a href="#" onclick="cetakkuitansi(<?=$row['id'] ?>)"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Kuitansi Pembayaran!', this, event, '100px')"/></a>&nbsp;
                     <?php  if (getLevel() != 2) { ?>
                        <a href="#" onclick="editpembayaran(<?=$row['id'] ?>)"><img src="images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah Pembayaran Cicilan!', this, event, '120px')" /></a>
                     <?php } ?>
                  </td>
               <?php } else { echo  "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>"; } ?>
	        </tr>
        <?php
        	}
        	$sisa = $besar - $total;?>
        <tr height="35">
            <td bgcolor="#996600" colspan="3" align="center"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total) ?></strong></font></td>
			<td bgcolor="#996600" align="right"><font color="#FFFFFF"><strong><?=FormatRupiah($total_diskon) ?></strong></font></td>
            <td bgcolor="#996600" align="right"><font color="#FFFFFF">Sisa <strong><?=FormatRupiah($sisa) ?></strong></font></td>
            <td bgcolor="#996600" colspan="3">&nbsp;</td>
        </tr>
        </table>
        <script language='JavaScript'>
        Tables('table', 1, 0);
        </script>
        </form>
        </fieldset>
   <?php } else { ?>
   		<fieldset>
        <legend><font size="2" color="#003300"><strong>Pembayaran Pertama</strong></font></legend>
        <table width="100%" border="0" align="center">          
        <tr>
            <td align="center" valign="middle" height="100">    
                <font size = "2" color ="red"><b>Tidak ditemukan adanya data.                 
                <br />Klik &nbsp;<a href="JavaScript:tambah()"><font size = "2" color ="green">di sini</font></a>&nbsp;untuk melakukan pembayaran pertama.                
                </b></font>
            </td>
        </tr>
        </table>  
		</fieldset>   		
   <?php 	} ?>     
<?php } ?>       
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

<!--<script language="javascript">
	var sprytextfield2 = new Spry.Widget.ValidationTextField("besar");
	var sprytextarea1 = new Spry.Widget.ValidationTextarea("keterangan");
</script>-->