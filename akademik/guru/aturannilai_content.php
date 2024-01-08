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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');
require_once('../library/dpupdate.php');

$cetak = 0;
$id = $_REQUEST['id'];
$nip = $_REQUEST['nip'];
$idtingkat = $_REQUEST['idtingkat'];
$aspek = $_REQUEST['aspek'];

OpenDb();
$sql = "SELECT j.departemen, j.nama, p.nip, p.nama 
		  FROM guru g, jbssdm.pegawai p, pelajaran j 
		 WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND j.replid = '$id' AND g.nip = '".$nip."'"; 

$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
$pelajaran = $row[1];
$guru = $row[2].' - '.$row[3];

$op = $_REQUEST['op'];
if ($op == "xm8r389xemx23xb2378e23") 
{
	$sql = "DELETE FROM aturangrading WHERE idpelajaran = '$id' AND nipguru = '$nip' AND idtingkat = '$idtingkat' AND dasarpenilaian = '".$aspek."'"; 
	QueryDb($sql);	?>
    <script>refresh();</script> 
<?php
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aturan Penentuan Grading Nilai</title>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function edit(idtingkat,aspek) {
	var id = document.getElementById('id').value;
	var nip = document.getElementById('nip').value;
	newWindow('aturannilai_edit.php?idtingkat='+idtingkat+'&nip='+nip+'&aspek='+aspek+'&id='+id, 'UbahAturanPenentuanGradingNilai','360','660','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function tambah(tingkat) {
	var id = document.getElementById('id').value;
	var nip = document.getElementById('nip').value;
	newWindow('aturannilai_add.php?idtingkat='+tingkat+'&id='+id+'&nip='+nip, 'TambahAturanPenentuanGradingNilai','385','700','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(idtingkat,aspek) {
	var id = document.getElementById('id').value;
	var nip = document.getElementById('nip').value;	
	if (confirm("Apakah anda yakin akan menghapus aspek penilaian ini?"))
		document.location.href = "aturannilai_content.php?op=xm8r389xemx23xb2378e23&id="+id+"&idtingkat="+idtingkat+"&nip="+nip+"&aspek="+aspek;		
}

function cetak() {
	var cetak = document.getElementById('cetak').value;	
	var id = document.getElementById('id').value;	
	var nip = document.getElementById('nip').value;

	if (cetak == '1') 
		newWindow('aturannilai_cetak.php?id='+id+'&nip='+nip, 'CetakAturanPenentuanGradingNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
	else 
		alert ('Tidak ada data yang dapat dicetak');
	
}
</script>
</head>
<body topmargin="0" leftmargin="0">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top" background="../images/ico/b_penilaian.png" style="background-repeat:no-repeat; background-attachment:fixed">
<table width="100%" border="0" height="100%">
<tr>
	<td>
	<table width="100%" border="0">
    <!-- TABLE TITLE -->
	<tr>
		<td valign="top" align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Aturan Perhitungan Grading Nilai</font></td>
	</tr>
    <tr>
        <td valign="top" align="right"><a href="../guru.php?page=p" target="content">
    <font size="1" color="#000000"><b>Guru & Pelajaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Aturan Perhitungan Grading Nilai</b></font></td>
    </tr>
    </table>
    
    <br /><br />
	<table width="100%" border="0">
    <tr>
    	<td width="20%" rowspan="4"></td>
        <td width="10%"><strong>Departemen</strong></td>
    	<td><strong>: <?=$departemen ?>
        <input type="hidden" name="departemen" id="departemen" readonly value="<?=$departemen ?>" />
        <input type="hidden" name="id" id="id" value="<?=$id ?>" />
   		</strong></td>
        <td rowspan="2"></td>
  	</tr>
  	<tr>
    	<td><strong>Pelajaran</strong></td>
    	<td><strong>: <?=$pelajaran ?>
    </strong></td>
    
  	</tr>
  	<tr>
    	<td><strong>Guru</strong></td>
    	<td><strong>: <?=$guru ?>  
        <input type="hidden" name="nip" id="nip" value="<?=$nip ?>" />
         </strong></td>
<?php $sql = "SELECT tingkat,replid FROM tingkat WHERE departemen = '$departemen' AND aktif=1 ORDER BY urutan";
	$result = QueryDb($sql);
	if (@mysqli_num_rows($result) > 0)
	{ ?>
		<td valign="top" align="right" colspan="2"> <a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
      <a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;  
    	</td>
  	</tr>
  	</table>
<?php 	$i = 0;
		while ($row = @mysqli_fetch_array($result)) 
		{
			++$i;
			$sql1 = "SELECT g.dasarpenilaian, dp.keterangan 
					   FROM aturangrading g, tingkat t, dasarpenilaian dp
					  WHERE t.replid = g.idtingkat AND t.departemen = '$departemen' 
						AND g.dasarpenilaian = dp.dasarpenilaian AND dp.aktif = 1
						AND g.idpelajaran = '$id' AND g.idtingkat = '".$row['replid']."' AND g.nipguru = '$nip' GROUP BY g.dasarpenilaian";
			$result1 = QueryDb($sql1);	?>
    	<br />
    	<fieldset>
        <legend><b>Tingkat <?=$row['tingkat']?> &nbsp;&nbsp;&nbsp; 
        <input type="hidden" name="idtingkat" id="idtingkat" value="<?=$row['replid'] ?>" />
<?php 	if (@mysqli_num_rows($result1) > 0) 
		{ 
			$cetak = 1; ?>	    
	        <a href="JavaScript:tambah(<?=$row['replid']?>)" ><img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')"/>&nbsp;Input Aturan Penentuan Grading Nilai</a>
			</b></legend><br />
		<!--<table border="1" width="100%" id="table<?=$i?>" class="tab">-->
        <table class="tab" id="table<?=$i?>" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
		<tr>		
			<td class="header" align="center" height="30" width="10%">No</td>
			<td class="header" align="center" height="30" width="*">Aspek Penilaian</td>
			<td class="header" align="center" height="30" width="*">Grading</td>
            <td class="header" height="30" width="*">&nbsp;</td>
		</tr>
		<?php 
			$cnt= 0;
			while ($row1 = @mysqli_fetch_row($result1)) 
			{	?>	
		<tr>        			
			<td align="center" height="25"><?=++$cnt?></td>
			<td height="25"><?=$row1[1]?><input type="hidden" name="dasar" id="dasar" value="<?=$row1[0] ?>" /></td>
  			<td height="25">
<?php 		$sql2 = "SELECT g.replid, grade, nmin, nmax
					   FROM aturangrading g, tingkat t
					  WHERE t.replid = g.idtingkat AND t.departemen = '$departemen' 
						AND g.idpelajaran = '$id' AND g.idtingkat = '".$row['replid']."' AND g.dasarpenilaian = '".$row1[0]."' 
						AND g.nipguru = '$nip' ORDER BY grade";
			$result2 = QueryDb($sql2);			
			while ($row2 = @mysqli_fetch_row($result2)) 
			{
				echo $row2[1].' : '.$row2[2].' s/d '.$row2[3]. '<br>'; 
			} ?>			
            </td>
            <td align="center" height="25" width="*">        
	<a href="JavaScript:edit('<?=$row['replid']?>','<?=$row1[0]?>')"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah!', this, event, '50px')"/></a>&nbsp;   
 	<a href="JavaScript:hapus('<?=$row['replid']?>','<?=$row1[0]?>')">
    <img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus!', this, event, '50px')" /></a>
        	</td>
		</tr>
<?php 	} ?>
		</table>
	<script language='JavaScript'>Tables('table<?=$i?>', 1, 0);</script>
    
<?php } else { ?>
    	
	</legend>
		<table width="100%" border="0" align="center">          
		<tr>
			<td align="center" valign="middle">
    		<font size = "2" color ="red"><b>Tidak ditemukan adanya data.  
           <br />Klik <a href="JavaScript:tambah(<?=$row['replid']?>)" ><font size = "2" color ="green">di sini</font></a> untuk mengisi data baru pada tingkat <?=$row['tingkat']?>. 
            </b></font>
			</td>
		</tr>
	 	</table>
<?php } ?><br /> 
  </fieldset>
<?php } ?>
 <input type="hidden" name="cetak" id="cetak" value="<?=$cetak ?>" />
    <!-- END TABLE CONTENT -->
 	</td>
  </tr>
</table>
<?php } else { ?>
</td><td width = "50%"></td>
</tr>
<tr height="60"><td colspan="4"><hr style="border-style:dotted" /></td>
</tr>
</table>
<table width="100%" border="0" align="center">          
<tr>
	<td align="center" valign="middle" height="200">
    	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.
        <br />Tambah tingkat kelas pada departemen <?=$departemen?> di menu Referensi.
        </b></font>
	</td>
</tr>
</table>  
<?php } ?>
</td>
  </tr>
</table>
</body>
</html>
<?php
CloseDb();
?>