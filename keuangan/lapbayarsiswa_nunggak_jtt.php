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
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');

	
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
if (isset($_REQUEST['idpenerimaan']))
	$idpenerimaan = (int)$_REQUEST['idpenerimaan'];
	
if (isset($_REQUEST['idangkatan']))
	$idangkatan = (int)$_REQUEST['idangkatan'];

if (isset($_REQUEST['idtingkat']))
	$idtingkat = (int)$_REQUEST['idtingkat'];

if (isset($_REQUEST['idkelas']))
	$idkelas = (int)$_REQUEST['idkelas'];
	
if (isset($_REQUEST['telat']))
	$telat = (int)$_REQUEST['telat'];
	
$tanggal = "";
if (isset($_REQUEST['tanggal']))
	$tanggal = $_REQUEST['tanggal'];
	
$tgl = MySqlDateFormat($tanggal);

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Untitled Document</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.href = "lapbayarsiswa_nunggak_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>";
}

function cetak() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarsiswa_nunggak_jtt_cetak.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'CetakLapPembayaranNunggakJtt','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
	var total = document.getElementById("tes").value;
	var addr = "lapbayarsiswa_nunggak_jtt_excel.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total="+total;
	newWindow(addr, 'ExcelLapPembayaranNunggakJtt','1000','580','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_urut(urut,urutan) {		
	
	var varbaris=document.getElementById("varbaris").value;
		
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	
	document.location.href = "lapbayarsiswa_nunggak_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function change_page(page) {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_nunggak_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_nunggak_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&page="+hal+"&hal="+hal;
}

function change_baris() {
	var varbaris=document.getElementById("varbaris").value;
	
	document.location.href="lapbayarsiswa_nunggak_jtt.php?departemen=<?=$departemen ?>&idkelas=<?=$idkelas ?>&idangkatan=<?=$idangkatan ?>&idpenerimaan=<?=$idpenerimaan ?>&telat=<?=$telat ?>&tanggal=<?=$tanggal ?>&idtingkat=<?=$idtingkat?>&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function createSms(no)
{
    var penerimaan = document.getElementById("penerimaan" + no).value;
    var tunggakan = encodeURI(document.getElementById('tunggakan' + no).value);
    var nama = encodeURI(document.getElementById('nama' + no).value);
    var nis = encodeURI(document.getElementById('nis' + no).value);
    var departemen = "<?=$departemen ?>";

    var addr = "library/send_sms_tunggakan.php?jenis=SISTUNG&nis=" + nis + "&nama=" + nama + "&penerimaan=" + penerimaan + "&tunggakan=" + tunggakan + "&departemen=" + departemen;
    newWindow(addr, 'SendSmsTunggakan','500','300','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body leftmargin="0" topmargin="0">
<?php
OpenDb();

$sql = "SELECT replid 
          FROM tahunbuku 
         WHERE departemen='$departemen' 
           AND aktif=1";
$res = QueryDb($sql);
$row = @mysqli_fetch_row($res);
$idtahunbuku = $row[0];
if ($idtahunbuku=="")
{
	echo "<script>";
	echo "alert ('Belum ada Tahun buku yang Aktif di departemen ".$departemen.". Silakan isi/aktifkan Tahun Buku di menu Referensi!');";
	echo "</script>";
	exit;
}

if ($idtingkat == -1) 
{
	$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
              FROM penerimaanjtt p, besarjtt b, jbsakad.siswa s 
	  		 WHERE p.idbesarjtt = b.replid 
	  		   AND b.lunas = 0 
	  		   AND b.info2='$idtahunbuku' 
	  		   AND b.idpenerimaan = $idpenerimaan 
			   AND s.nis = b.nis 
			   AND s.idangkatan = $idangkatan 
			 GROUP BY idbesarjtt 
			HAVING x >= $telat";
}
else 
{
	if ($idkelas == -1) 
	{
		$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
		          FROM penerimaanjtt p , besarjtt b, jbsakad.siswa s, jbsakad.kelas k 
				 WHERE p.idbesarjtt = b.replid 
				   AND b.lunas = 0 
				   AND b.info2 = '$idtahunbuku' 
				   AND b.idpenerimaan = $idpenerimaan
				   AND s.nis = b.nis 
				   AND s.idangkatan = $idangkatan 
				   AND s.idkelas = k.replid 
				   AND k.idtingkat = $idtingkat 
			     GROUP BY idbesarjtt 
			    HAVING x >= $telat";
	}
	else 
	{
		$sql = "SELECT idbesarjtt, datediff('$tgl', max(tanggal)) as x 
		          FROM penerimaanjtt p , besarjtt b, jbsakad.siswa s 
				 WHERE p.idbesarjtt = b.replid AND b.lunas = 0 AND b.info2='$idtahunbuku' AND b.idpenerimaan = $idpenerimaan
				   AND s.nis = b.nis AND s.idkelas = $idkelas AND s.idangkatan = $idangkatan  
			     GROUP BY idbesarjtt 
			  	HAVING x >= $telat";
	}
}

$result = QueryDb($sql);
$idstr = "";
while($row = mysqli_fetch_row($result))
{
	if (strlen($idstr) > 0)
		$idstr = $idstr . ",";
	$idstr = $idstr . $row[0];
}

//Dapatkan namapenerimaan
$sql = "SELECT nama FROM datapenerimaan WHERE replid=$idpenerimaan";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$namapenerimaan = $row[0];
?>

<table border="0" width="100%" align="center" background="" style="background-repeat:no-repeat; background-attachment:fixed">
<!-- TABLE CENTER -->
<tr>
	<td>
<?php if (strlen($idstr) > 0)
{
	$sql = "SELECT MAX(jumlah) 
             FROM (SELECT idbesarjtt, count(replid) AS jumlah 
                     FROM penerimaanjtt 
                    WHERE idbesarjtt IN ($idstr) 
                    GROUP BY idbesarjtt) AS X";
	$result = QueryDb($sql);
	$row = mysqli_fetch_row($result);
	$max_n_cicilan = $row[0];
	$table_width = 810 + $max_n_cicilan * 90;
?>
	<table width="100%" border="0" align="center">
    <tr>
    	<td valign="bottom">
    <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;
    <a href="JavaScript:cetak()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;
    <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
    	</td>
	</tr>
	</table>
	<br />
	<table class="tab" id="table" border="1" style="border-collapse:collapse" width="<?=$table_width ?>" align="left" bordercolor="#000000" cellpadding="5" cellspacing="0">
    <tr height="30" align="center" class="header">
        <td width="30">No</td>
        <td width="80" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nis','<?=$urutan?>')">N I S <?=change_urut('nis',$urut,$urutan)?></td>
        <td width="140" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></td>
        <td width="50" >Kelas</td>
        <?php 	for($i = 0; $i < $max_n_cicilan; $i++) { 
                $n = $i + 1; ?>
                <td class="header" width="120" align="center"><?="Bayaran-$n" ?></td>	
        <?php  } ?>
        <td width="80">Telat<br /><em>(hari)</em></td>
        <td width="125" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('besar','<?=$urutan?>')"><?=$namapenerimaan ?> <?=change_urut('besar',$urut,$urutan)?></td>
        <td width="125">Total Pembayaran</td>
        <td width="125">Total Diskon</td>
        <td width="125">Total Tunggakan</td>
        <td width="200" align="center">Keterangan</td>
        <td width="120">Kirim SMS Tunggakan</td>
    </tr>
<?php

$sql_tot = "SELECT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas 
              FROM jbsakad.siswa s, jbsakad.kelas k, besarjtt b 
             WHERE s.nis = b.nis AND s.idkelas = k.replid AND b.replid IN ($idstr) 
             ORDER BY s.nama";

$sql = "SELECT b.nis, s.nama, k.kelas, b.replid AS id, b.besar, b.keterangan, b.lunas, t.tingkat 
          FROM jbsakad.siswa s, jbsakad.kelas k, besarjtt b, jbsakad.tingkat t 
         WHERE s.nis = b.nis AND s.idkelas = k.replid AND k.idtingkat = t.replid AND b.replid IN ($idstr) 
         ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";

$result_tot = QueryDb($sql_tot);
$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
$jumlah = mysqli_num_rows($result_tot);
$akhir = ceil($jumlah/5)*5;

$result = QueryDb($sql);
if ($page==0)
	$cnt = 0;
else 
	$cnt = (int)$page*(int)$varbaris;

$totalbiayaall = 0;
$totalbayarall = 0;
$totaldiskonall = 0;

$totalbayarallB = 0;
$totaldiskonallB = 0;
$besarjttallA = 0;
$x = 1;
while ($rowA = @mysqli_fetch_array($result_tot)) {
	$besarjttA = 0;
	$idbesarjttA = $rowA['id'];
	$besarjttA = $rowA['besar'];
	$sqlB = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjtt WHERE idbesarjtt = $idbesarjttA ORDER BY tanggal";
	$resultB = QueryDb($sqlB);
	$totalbayarB = 0;
	$totaldiskonB = 0;
	while ($rowB = @mysqli_fetch_row($resultB)) {
		$totalbayarB = $totalbayarB + $rowB[1];
        $totaldiskonB = $totaldiskonB + $rowB[2];
	}
	$totalbayarallB += $totalbayarB;
    $totaldiskonallB += $totaldiskonB;
	$besarjttallA += $besarjttA;
}

while ($row = mysqli_fetch_array($result)) {
	$idbesarjtt = $row['id'];
	$besarjtt = $row['besar'];
	$ketjtt = $row['keterangan'];
	$lunasjtt = $row['lunas'];
	$infojtt = "<font color=red><strong>Belum Lunas</strong></font>";
	if ($lunasjtt == 1)
		$infojtt = "<font color=blue><strong>Lunas</strong></font>";
	$totalbiayaall += $besarjtt;
    $nama = $row['nama'];
    $nis = $row['nis'];
?>
    <tr height="40">
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row['nis'] ?></td>
        <td><?=$row['nama'] ?></td>
        <td align="center"><?php if ($idkelas == -1) echo  $row['tingkat']." - "; ?><?=$row['kelas'] ?></td>
    <?php
	$sql = "SELECT count(*) FROM penerimaanjtt WHERE idbesarjtt = $idbesarjtt";
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	$nbayar = $row2[0];
	$nblank = $max_n_cicilan - $nbayar;
	$totalbayar = 0;
	$totaldiskon = 0;
	if ($nbayar > 0) {
		$sql = "SELECT date_format(tanggal, '%d-%b-%y'), jumlah, info1 FROM penerimaanjtt WHERE idbesarjtt = $idbesarjtt ORDER BY tanggal";
		$result2 = QueryDb($sql);
		
		while ($row2 = mysqli_fetch_row($result2)) {
			$totalbayar = $totalbayar + $row2[1];
            $totaldiskon = $totaldiskon + $row2[2]; ?>
            <td>
                <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
                <tr height="20"><td align="center"><?=FormatRupiah($row2[1]) ?></td></tr>
                <tr height="20"><td align="center"><?=$row2[0] ?></td></tr>
                </table>
            </td>
 <?php 	}
 		$totalbayarall += $totalbayar - $totaldiskon;
	}	
	for ($i = 0; $i < $nblank; $i++) { ?>
	    <td>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse" bordercolor="#000000">
            <tr height="20"><td align="center">&nbsp;</td></tr>
            <tr height="20"><td align="center">&nbsp;</td></tr>
            </table>
        </td>
    <?php }?>
    	<td align="center">
<?php $sql = "SELECT datediff('$tgl', max(tanggal)) FROM penerimaanjtt WHERE idbesarjtt = $idbesarjtt";
	//echo  $sql;
	$result2 = QueryDb($sql);
	$row2 = mysqli_fetch_row($result2);
	echo  $row2[0]; ?>
        </td>
        <td align="right"><?=FormatRupiah($besarjtt) ?></td>
        <td align="right"><?=FormatRupiah($totalbayar) ?></td>
        <td align="right"><?=FormatRupiah($totaldiskon) ?></td>
        <td align="right"><?=FormatRupiah($besarjtt - $totalbayar - $totaldiskon) ?></td>
        <td><?=$ketjtt ?></td>
        <td align="center">
            <input type="hidden" name="penerimaan<?=$cnt?>" id="penerimaan<?=$cnt?>" value='<?=str_replace("'", "`", (string) $namapenerimaan)?>' ?>
            <?php $tunggakan = $besarjtt - $totalbayar - $totaldiskon ?>
            <input type="hidden" name="tunggakan<?=$cnt?>" id="tunggakan<?=$cnt?>" value='<?=$tunggakan?>' ?>
            <input type="hidden" name="nama<?=$cnt?>" id="nama<?=$cnt?>" value='<?=str_replace("'", "`", (string) $nama)?>' ?>
            <input type="hidden" name="nis<?=$cnt?>" id="nis<?=$cnt?>" value='<?=str_replace("'", "`", (string) $nis)?>' ?>
            <input type="button" class="but" value="Kirim" onclick="createSms(<?=$cnt?>)">
        </td>
    </tr>
<?php
}
?>
	<input type="hidden" name="tes" id="tes" value="<?=$total?>"/>
    <?php if($page==$total-1){ ?>
	<tr height="40">
        <td align="center" colspan="<?=5 + $max_n_cicilan ?>" bgcolor="#999900"><font color="#FFFFFF"><strong>T O T A L</strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($besarjttallA) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totalbayarallB) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($totaldiskonallB) ?></strong></font></td>
        <td align="right" bgcolor="#999900"><font color="#FFFFFF"><strong><?=FormatRupiah($besarjttallA - $totalbayarallB - $totaldiskonallB) ?></strong></font></td>
        <td bgcolor="#999900">&nbsp;</td>
        <td bgcolor="#999900">&nbsp;</td>
    </tr>
	<?php } ?>
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
     <?php if ($page==0){ 
		$disback="style='display:none;'";
		$disnext="style=''";
		}
		if ($page<$total && $page>0){
		$disback="style=''";
		$disnext="style=''";
		}
		if ($page==$total-1 && $page>0){
		$disback="style=''";
		$disnext="style='display:none;'";
		}
		if ($page==$total-1 && $page==0){
		$disback="style='display:none;'";
		$disnext="style='display:none;'";
		}
	?>
    </td>
</tr> 
<tr>
    <td>
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left" colspan="2">Halaman
        <input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<select name="hal" id="hal" onChange="change_hal()">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
	  	dari <?=$total?> halaman
	     
 		</td>
        <td width="30%" align="right">Jumlah baris per halaman
      	<select name="varbaris" id="varbaris" onChange="change_baris()">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></td>
    </tr>
    </table>
<?php } else { ?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya siswa yang menunggak pembayaran.
            </font>
        </td>
    </tr>
    </table>  
<?php } ?>
    </td>
</tr>
</table>
<?php CloseDb() ?>

</body>
</html>