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
require_once('../inc/common.php');
require_once('../inc/rupiah.php');
require_once('../inc/config.php');
require_once('../inc/sessionchecker.php');
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
	
$idtahunbuku = 0;
if (isset($_REQUEST['idtahunbuku']))
	$idtahunbuku = $_REQUEST['idtahunbuku'];

OpenDb();
$sql = "SELECT day(now()), month(now()), year(now()), 
               day(date_sub(now(), INTERVAL 30 DAY)), month(date_sub(now(), INTERVAL 30 DAY)), year(date_sub(now(), INTERVAL 30 DAY))";
$result = QueryDb($sql);
$row = mysqli_fetch_row($result);
$tgl2 = $row[0];
$bln2 = $row[1];
$thn2 = $row[2];

$tgl1 = 0;
if (isset($_REQUEST['tgl1']))
	$tgl1 = (int)$_REQUEST['tgl1'];

$bln1 = 0;
if (isset($_REQUEST['bln1']))
	$bln1 = (int)$_REQUEST['bln1'];

$thn1 = 0;
if (isset($_REQUEST['thn1']))
	$thn1 = (int)$_REQUEST['thn1'];

if (isset($_REQUEST['tgl2']))
	$tgl2 = (int)$_REQUEST['tgl2'];

if (isset($_REQUEST['bln2']))
	$bln2 = (int)$_REQUEST['bln2'];

if (isset($_REQUEST['thn2']))
	$thn2 = (int)$_REQUEST['thn2'];

$n1 = JmlHari($bln1,$thn1);
$n2 = JmlHari($bln2,$thn2);

$tanggal1 = "";
if (isset($_REQUEST['tanggal1']))
	$tanggal1 = $_REQUEST['tanggal1'];
	
$tanggal2 = "";
if (isset($_REQUEST['tanggal2']))
	$tanggal2 = $_REQUEST['tanggal2'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Laporan Audit Perubahan Data</title>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function change_sel() 
{	
	//parent.contentblank.location.href ="lapaudit_blank.php";
}

function change_dep() 
{
	var dep = document.getElementById('departemen').value;
	var tgl1 = document.getElementById('tgl1').value;
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var tgl2 = document.getElementById('tgl2').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	
	document.location.href = "lapaudit.php?idtahunbuku="+idtahunbuku+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep;
}

function show_laporan() 
{
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
		
	if (idtahunbuku.length == 0) {
		alert ('Tahun buku tidak boleh kosong !');
		document.getElementById('departemen').focus();
		return false;
	} else if (tgl1.length == 0) {	
		alert ('Tanggal awal tidak boleh kosong!');	
		document.main.tgl1.focus();
		return false;	
	} else if (tgl2.length == 0) {	
		alert ('Tanggal akhir tidak boleh kosong!');	
		document.main.tgl2.focus();
		return false;	
	}
	
	var validasi = validateTgl(tgl1,bln1,thn1,tgl2,bln2,thn2);
	if (validasi) 	
		document.location.href = "lapaudit.php?idtahunbuku="+idtahunbuku+"&tgl1="+tgl1+"&bln1="+bln1+"&thn1="+thn1+"&tgl2="+tgl2+"&bln2="+bln2+"&thn2="+thn2+"&departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&showpembayaran=true";
	//parent.content.location.href = "lapaudit_blank.php";
}
function cetak(id)
{
	var idtahunbuku = document.getElementById('idtahunbuku').value;
	var dep = document.getElementById('departemen').value;
	var tgl1 = parseInt(document.getElementById('tgl1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var thn1 = parseInt(document.getElementById('thn1').value);
	var tgl2 = parseInt(document.getElementById('tgl2').value);
	var bln2 = parseInt(document.getElementById('bln2').value);
	var thn2 = parseInt(document.getElementById('thn2').value);
	var tanggal1 = escape(thn1 + "-" + bln1 + "-" + tgl1);
	var tanggal2 = escape(thn2 + "-" + bln2 + "-" + tgl2);
	var addr = "lapaudit_cetak.php?departemen="+dep+"&tanggal1="+tanggal1+"&tanggal2="+tanggal2+"&id="+id+"&idtahunbuku="+idtahunbuku;
	newWindow(addr, 'LapAuditDataKeuangan','750','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function change_tgl1() 
{
	var th1 = parseInt(document.getElementById('thn2').value);
	var bln1 = parseInt(document.getElementById('bln2').value);
	var tgl1 = parseInt(document.main.tgl2.value);
	var th = parseInt(document.getElementById('thn1').value);
	var bln = parseInt(document.getElementById('bln1').value);
	var tgl = parseInt(document.main.tgl1.value);

	validateTgl(tgl,bln,th,tgl1,bln1,th1);		
	
	var namatgl = "tgl1";
	var namabln = "bln1";
	sendRequestText("../lib/gettanggal.php", show1, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);					
}

function change_tgl2() 
{
	var th1 = parseInt(document.getElementById('thn1').value);
	var bln1 = parseInt(document.getElementById('bln1').value);
	var tgl1 = parseInt(document.main.tgl1.value);
	
	var th = parseInt(document.getElementById('thn2').value);
	var bln = parseInt(document.getElementById('bln2').value);
	var tgl = parseInt(document.main.tgl2.value);
	
	validateTgl(tgl1,bln1,th1,tgl,bln,th);
	
	var namatgl = "tgl2";
	var namabln = "bln2";	
	sendRequestText("../lib/gettanggal.php", show2, "tahun="+th+"&bulan="+bln+"&tgl="+tgl+"&namatgl="+namatgl+"&namabln="+namabln);					
}

function show1(x) 
{
	document.getElementById("InfoTgl1").innerHTML = x;
}

function show2(x) 
{
	document.getElementById("InfoTgl2").innerHTML = x;
}

function show_detail(lap) 
{
	var addr;
	
	if (lap == 'penerimaanjtt' || lap == 'penerimaanjttcalon') 
	{
		addr = "get_lapaudit_jtt.php";
	} 
	else if (lap == 'penerimaaniuran' || lap == 'penerimaaniurancalon') 
	{
		addr = "get_lapaudit_iuran.php";
	} 
	else if (lap == 'penerimaanlain') 
	{
		addr = "get_lapaudit_lain.php";
	} 
	else if (lap == 'pengeluaran') 
	{
		addr = "get_lapaudit_pengeluaran.php";
	} 
	else if (lap == 'jurnalumum') 
	{
		addr = "get_lapaudit_jurnalumum.php";
	}
	else if (lap == 'besarjtt' || lap == 'besarjttcalon') 
	{
		addr = "get_lapaudit_besarjtt.php";
	}
	
	//alert (addr);
	sendRequestText(addr, showLap, "idtahunbuku=<?=$idtahunbuku?>&departemen=<?=$departemen?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>&lap="+lap);
}

function showLap(x)
{
	document.getElementById('lapInfo').innerHTML = x;
}

function focusNext(elemName, evt) 
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) 
	{
		document.getElementById(elemName).focus();
		if (elemName == 'tabel')
			show_laporan();
		return false;
	}
	return true;
}

function panggil(elem)
{
	parent.contentblank.location.href = "lapaudit_blank.php";
	var lain = new Array('tgl1','bln1','thn1','tgl2','bln2','thn2','departemen');
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
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<form name="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
	<td rowspan="3" width="60%">
    <table border="0" width = "100%">
    <tr>
        <td width="15%" class="news_content1">Departemen </td>
        <td colspan="4">
        <select name="departemen" class="cmbfrm" id="departemen" style="width:188px" onchange="change_dep()">
    	        <?php 	$sql = "SELECT departemen FROM departemen WHERE aktif = 1 ORDER BY urutan";
            $result = QueryDb($sql);
            while($row = mysqli_fetch_row($result)) {
                if ($departemen == "")
                    $departemen = $row[0]; ?>
    	      <option value="<?=$row[0]?>" <?=StringIsSelected($row[0], $departemen)?> > 
    	        <?=$row[0]?>
    	        </option>
   	              <?php } ?>
  	        </select>
        <span class="news_content1">Tahun Buku</span> 
        <select name="idtahunbuku" id="idtahunbuku" onchange="change_dep()" style="width:160px">        
<?php 			if ($departemen != "") 
            { 
                $sql = "SELECT replid, tahunbuku, DAY(tanggalmulai), MONTH(tanggalmulai), YEAR(tanggalmulai), aktif 
                        FROM jbsfina.tahunbuku WHERE departemen='$departemen' ORDER BY replid DESC";
                $result = QueryDb($sql);
                while ($row = mysqli_fetch_row($result))
                {
                    if ($idtahunbuku == 0)
                        $idtahunbuku = $row[0];
                    
                    $sel = "";
                    if ($idtahunbuku == $row[0])
                    {
                        $sel = "selected";
                        
                        if ($tgl1 == 0)	$tgl1 = $row[2];
                        if ($bln1 == 0) $bln1 = $row[3];
                        if ($thn1 == 0) $thn1 = $row[4];
                    }
                    
                    $A = "";
                    if ($row[5] == 1)
                        $A = "(A)";
                    
                    echo "<option value='".$row[0]."' $sel>$row[1] $A</option>";
                }
            } ?>
            </select>
        </td>
    </tr>
    <tr>
 <?php 	if ($tgl1 == 0)	$tgl1 = $tgl2;
			if ($bln1 == 0) $bln1 = $bln2;
			if ($thn1 == 0) $thn1 = $thn2;
					
			$n1 = JmlHari($bln1, $thn1);
			$n2 = JmlHari($bln2, $thn2);	?>     
        <td class="news_content1">Tanggal </td>
        <td width="10">
        	<div id="InfoTgl1"> 
            <select name="tgl1" class="cmbfrm" id="tgl1" onchange="change_tgl1()" >
             <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
            </div>       	</td>
        <td width="160"><select name="bln1" class="cmbfrm" id="bln1" onchange="change_tgl1()" >
          <?php for($i = 1; $i <= 12; $i++) { ?>
          <option value="<?=$i ?>" <?=IntIsSelected($i, $bln1) ?> >
          <?=$bulan[$i] ?>
            </option>
          <?php } ?>
        </select>
          <select name="thn1" class="cmbfrm" id="thn1" onchange="change_tgl1()" >
            <?php for($i = $G_START_YEAR; $i <= $thn1+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn1) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
          <span class="news_content1">s/d       	</span></td>
        <td width="10">
        	<div id="InfoTgl2">
            <select name="tgl2" class="cmbfrm" id="tgl2" onchange="change_tgl2()" >
             <option value="">[Tgl]</option>
            <?php for($i = 1; $i <= $n2; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $tgl2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>
             </div>        </td>
        <td>
            <select name="bln2" class="cmbfrm" id="bln2" onchange="change_tgl2()" >
            <?php for($i = 1; $i <= 12; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $bln2) ?> > <?=$bulan[$i] ?></option>
            <?php } ?>
            </select>
            <select name="thn2" class="cmbfrm" id="thn2" onchange="change_tgl2()" >
            <?php for($i = $G_START_YEAR; $i <= $thn2+1; $i++) { ?>
                <option value="<?=$i ?>" <?=IntIsSelected($i, $thn2) ?> > <?=$i ?></option>
            <?php } ?>
            </select>        </td>
    </tr>
    </table>
	</td>
	<td rowspan="2" valign="middle">
    	<a href="#" onclick="show_laporan()"><img src="../img/view.png" border="0" height="48" width="48" id="tabel" onmouseover="showhint('Klik untuk menampilkan data laporan audit perubahan data keuangan!', this, event, '220px')" /></a>    </td>
    <td width="40%" align="right">
    	<font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<span class="news_title2">Laporan Audit<br />Perubahan Data Keuangan</span>
	</td>
</tr>
</table>
</form>
<div>
<?php 
if (isset($_REQUEST['showpembayaran']))
{    
	 //// Maintenance script to update info1 in auditinfo as idjurnal for each source
	 $sql = "SELECT * FROM jbsfina.auditinfo WHERE info1 IS NULL";
	 $res = QueryDb($sql);
	 
	 if (mysqli_num_rows($res) > 0)
	 	  set_time_limit(1000);
		
	 while ($row = mysqli_fetch_array($res))
	 {
		 $id = $row['replid'];
		 $table = $row['sumber'];
		 $idsumber = $row['idsumber'];
		 
		 if ($table == "besarjtt")
		 	$sql = "SELECT info1 FROM jbsfina.besarjtt WHERE replid = $idsumber";
		 elseif ($table == "besarjttcalon")
		   $sql = "SELECT info1 FROM jbsfina.besarjttcalon WHERE replid = $idsumber";
		 elseif ($table == "penerimaanjtt")
		   $sql = "SELECT idjurnal FROM jbsfina.penerimaanjtt WHERE replid = $idsumber";
       	 elseif ($table == "penerimaanjttcalon")
		   $sql = "SELECT idjurnal FROM jbsfina.penerimaanjttcalon WHERE replid = $idsumber";			
  		 elseif ($table == "penerimaaniuran")
		   $sql = "SELECT idjurnal FROM jbsfina.penerimaaniuran WHERE replid = $idsumber";
		 elseif ($table == "penerimaaniurancalon")
		   $sql = "SELECT idjurnal FROM jbsfina.penerimaaniurancalon WHERE replid = $idsumber";			
		 elseif ($table == "penerimaanlain")
		   $sql = "SELECT idjurnal FROM jbsfina.penerimaanlain WHERE replid = $idsumber";
		 elseif ($table == "pengeluaran")
		   $sql = "SELECT idjurnal FROM jbsfina.pengeluaran WHERE replid = $idsumber";
       	 elseif ($table == "jurnalumum")
		   $sql = "SELECT $idsumber";			
			
		 $res2 = QueryDb($sql);
		 if (mysqli_num_rows($res2) > 0)
		 {
			 $row2 = mysqli_fetch_row($res2);
			 $idjurnal = $row2[0];
			 
			 $sql = "UPDATE jbsfina.auditinfo SET info1='$idjurnal' WHERE replid=$id";
			 QueryDb($sql);
		 }
		 else
		 {
			 $sql = "UPDATE jbsfina.auditinfo SET info1='na' WHERE replid=$id";
			 QueryDb($sql);
		 }
	 }
?>
<table width="100%" border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td width="33%" valign="top"><br />
		<table border="0" width="100%" align="center">
            <!-- TABLE CENTER -->
            <tr>
                <td align="left" valign="top">
            <?php 	OpenDb();
                $sql = "SELECT a.sumber, count(a.replid) 
						  FROM jbsfina.auditinfo a, jbsfina.jurnal j 
						 WHERE a.info1 = j.replid AND j.idtahunbuku = '$idtahunbuku' AND a.departemen = '$departemen' 
						   AND a.tanggal >= '$tanggal1 00:00:00' AND a.tanggal <= '$tanggal2 23:59:59' 
						 GROUP BY a.sumber 
						 ORDER BY a.sumber";
						
                $result = QueryDb($sql);
                if (mysqli_num_rows($result) > 0) {
            ?>
                <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="2" width="95%" align="center" bordercolor="#000000">
                <tr height="30" align="center">
                    <td class="header" width="7%">No</td>
                    <td class="header" width="73%">Perubahan</td>
                    <td class="header" width="10%">Jumlah</td>
                    <td class="header" width="10%">&nbsp;</td>
                </tr>
                <?php
                
                $cnt = 0;
                while($row = mysqli_fetch_row($result)) { 
                    switch($row[0]) {	
                        case 'jurnalumum':
							$jurnal = "Jurnal Umum"; break;
						case 'penerimaanjtt':
							$jurnal = "Penerimaan Iuran Wajib Siswa"; break;
						case 'penerimaaniuran':
							$jurnal = "Penerimaan Iuran Sukarela Siswa"; break;
						case 'penerimaanlain':
							$jurnal = "Penerimaan Lain-Lain"; break;
						case 'pengeluaran':
							$jurnal = "Pengeluaran"; break;
						case 'penerimaanjttcalon':
							$jurnal = "Penerimaan Iuran Wajib Calon Siswa"; break;
						case 'penerimaaniurancalon':
							$jurnal = "Penerimaan Iuran Sukarela Calon Siswa"; break;
						case 'besarjtt':
							$jurnal = "Pendataan Besar Iuran Wajib Siswa"; break;
						case 'besarjttcalon':
							$jurnal = "Pendataan Besar Iuran Wajib Calon Siswa"; break;
                    }
                            
                ?>
                <tr height="25">
                    <td align="center"><?=++$cnt ?></td>
                    <td align="left"><strong><?=$jurnal ?></strong></td>
                    <td align="center"><font size="2"><strong><?=$row[1] ?></strong></font></td>
                    <td align="center"><input name="button" type="submit" class="cmbfrm2" id="button" value=">"  onclick="show_detail('<?=$row[0]?>')"/></td>
                </tr>
                <?php
                }
                ?>
                </table>
            	<script language='JavaScript'>
                    //Tables('table', 1, 0);
                </script>
            <?php } else { ?>	
            
                <table width="100%" border="0" align="center">          
                <tr>
                    <td align="center" valign="middle" height="300">    
                        <font color ="red" size = "2" class="err"><b>Tidak ditemukan adanya data pada tanggal <?=LongDateFormat($tanggal1)." s/d ".LongDateFormat($tanggal2) ?>.
                        </b></font>                    </td>
                </tr>
                </table>  
            <?php } ?>
            </td></tr>
            <!-- END TABLE BACKGROUND IMAGE -->
         </table>
    </td>
    <td valign="top"><div id="lapInfo"></div></td>
  </tr>
</table>
<?php } ?>
</div>
<?php CloseDb() ?>
</body>
</html>