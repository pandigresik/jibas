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
require_once("../include/sessionchecker.php");
require_once('../library/dpupdate.php');

$cetak = 0;
$id_pelajaran = $_REQUEST['id_pelajaran'];
$nip_guru = SI_USER_ID();
$id_tingkat = $_REQUEST['id_tingkat'];
$aspek = $_REQUEST['aspek'];

OpenDb();
$sql = "SELECT j.departemen, j.nama, p.nip, p.nama 
		    FROM guru g, jbssdm.pegawai p, pelajaran j 
		   WHERE g.nip=p.nip AND g.idpelajaran = j.replid AND j.replid = '$id_pelajaran' AND g.nip = '$nip_guru'"; 
$result = QueryDb($sql);
$row = @mysqli_fetch_row($result);
$departemen = $row[0];
$pelajaran = $row[1];
$guru = $row[2].' - '.$row[3];

$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22") {
	$newaktif=(int)$_REQUEST['newaktif'];
	$replid=(int)$_REQUEST['replid'];
	OpenDb();
	$sql = "UPDATE aturannhb SET aktif = '$newaktif' WHERE replid = '$replid' ";
	QueryDb($sql);
	CloseDb();
} else if ($op == "xm8r389xemx23xb2378e23") {	
	$sql = "DELETE FROM aturannhb WHERE idpelajaran = '$id_pelajaran' AND nipguru = '$nip_guru' AND idtingkat = '$id_tingkat' AND dasarpenilaian = '".$aspek."'"; 
	QueryDb($sql);	
	CloseDb();
	?>
    <script>
    	refresh();
    </script> 
	<?php
}	

?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript">

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

function refresh() {
    document.location.reload;
}

function changepage() {
	var departemen = document.tampil_aturannhb.departemen.value;
	
	document.location.href = "tampil_daftarpelajaran.php?departemen="+departemen;
}
function setaktif(replid,aktif) {
	var msg;
	var newaktif;	
	var nip = document.getElementById('nip_guru').value;
	var id_pelajaran = document.getElementById('id_pelajaran').value;
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah bobot penilaian ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah bobot penilaian ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
	document.location.href = "perhitungan_rapor_content.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&nip_guru="+nip+"&id_pelajaran="+id_pelajaran;
}

function tambah(tingkat) {
	var id = document.getElementById('id_pelajaran').value;
	var nip = document.getElementById('nip_guru').value;
	newWindow('perhitungan_rapor_add.php?id_tingkat='+tingkat+'&id_pelajaran='+id+'&nip_guru='+nip, 'TambahAturanPerhitunganNilaiRapor','400','550','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(tingkat,aspek) {
	var id = document.getElementById('id_pelajaran').value;
	var nip = document.getElementById('nip_guru').value;
	newWindow('perhitungan_rapor_edit.php?id_tingkat='+tingkat+'&id_pelajaran='+id+'&nip_guru='+nip+"&aspek="+aspek, 'UbahAturanPerhitunganNilaiRapor','400','550','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(tingkat,aspek) {
	var id = document.getElementById('id_pelajaran').value;
	var nip = document.getElementById('nip_guru').value;
	
	if (confirm("Apakah anda yakin akan menghapus aspek penilaian ini?"))
		document.location.href = "perhitungan_rapor_content.php?op=xm8r389xemx23xb2378e23&id_pelajaran="+id+"&id_tingkat="+tingkat+"&nip_guru="+nip+"&aspek="+aspek;
}

function cetak() {
	var nip = document.getElementById('nip_guru').value;
	var id = document.getElementById('id_pelajaran').value;
	var cetak = document.getElementById('cetak').value;	
	if (cetak == '1') 
		newWindow('perhitungan_rapor_cetak.php?id_pelajaran='+id+'&nip_guru='+nip, 'CetakPerhitunganNilaiRapor','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	else 
		alert ('Tidak ada data yang dapat dicetak');
}
</script>
</head>
<body topmargin="0" leftmargin="0">

<form name="tampil_aturannhb" action="perhitungan_rapor_content.php" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td background="../images/ico/b_aturannilai.png" style="background-attachment:fixed; background-repeat:no-repeat" valign="top">
    <table border="0" width="100%">
    <!-- TABLE TITLE -->
    <tr>     
      <td align="right" valign="top"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Aturan Perhitungan Nilai Rapor</font></td>
    </tr>
    
    <tr>
      <td align="right" valign="top"><a href="../pelajaran.php" target="framecenter">
	<font size="1" color="#000000"><b>Pelajaran</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Aturan Perhitungan Nilai Rapor</b></font></td>
    </tr>
    </table>
    
    <br /><br /><br>
	<table width="100%" border="0">   
	<tr>
    	<td width="20%" rowspan="4"></td>
    	<td width="10%"><b>Departemen</b></td>
        <td><strong>: <?=$departemen ?></strong>
		<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>">	
        </td>
        <td rowspan="2"></td>
	</tr>
	<tr>
		<td><b>Pelajaran</b></td>
        <td><b>: <?=$pelajaran ?></b>	
    	<input type="hidden" name="id_pelajaran" id="id_pelajaran" value="<?=$id_pelajaran ?>">   
   
     	</td>
	</tr>
	<tr>
    	<td><b>Guru</b></td>
    	<td><b>: <?=$guru ?></b>
    	<input type="hidden" name="nip_guru" id="nip_guru" value="<?=$nip_guru ?>" />
   	 	</td>
	<?php 
	     
    OpenDb();
	$sql = "SELECT tingkat,replid FROM tingkat WHERE departemen = '$departemen' AND aktif=1 ORDER BY urutan";
	$result = QueryDb($sql);
	CloseDb();
	if (@mysqli_num_rows($result) > 0){
		
	?>
  		<td align="right" colspan="2" valign="top"><a href="#" onClick="document.location.reload()"><img src="../images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
    	<a href="JavaScript:cetak()"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp;  
   		</td>
	</tr>
	</table>
  <?php
   	$cnt = 0;  
    while ($row_tkt = @mysqli_fetch_array($result)) {
		++$cnt;
		OpenDb();
		$query_at = "SELECT a.dasarpenilaian, dp.keterangan
		               FROM aturannhb a, tingkat t, dasarpenilaian dp 
			 		  WHERE a.idtingkat='".$row_tkt['replid']."' AND a.idpelajaran = '$id_pelajaran' AND t.departemen='$departemen' 
					    AND a.dasarpenilaian = dp.dasarpenilaian AND dp.aktif = 1
  					    AND t.replid = a.idtingkat AND a.nipguru = '$nip_guru' GROUP BY a.dasarpenilaian";
		
		$result_at = QueryDb($query_at);
        CloseDb();
  ?>
  	<br>
  		<fieldset>
        <legend><b>Tingkat <?=$row_tkt['tingkat'] ?> &nbsp;&nbsp;&nbsp;
  	<?php if (@mysqli_num_rows($result_at)>0){ 
			$cetak = 1; ?>	
    		<a href = "JavaScript:tambah(<?=$row_tkt['replid']?>)">
            <img src="../images/ico/tambah.png" border="0" onMouseOver="showhint('Tambah!', this, event, '50px')">&nbsp;Input Aturan Perhitungan Nilai Rapor</a>
	</b></legend><br />
   
  	<!--<table border="1" width="100%" id="table<?=$cnt?>" class="tab">-->
    <table class="tab" id="table<?=$cnt?>" border="1" style="border-collapse:collapse" width="100%" align="center">
  	<tr>
		<td class="header" align="center" height="30" width="10%">No</td>
		<td class="header" align="center" height="30">Aspek Penilaian</td>
		<td class="header" align="center" height="30">Bobot Perhitungan Nilai Rapor </td>
        <td class="header" colspan="2" height="30">&nbsp;</td>
	</tr>
	<?php
	
	$i=1;
	
	while($row_at = mysqli_fetch_row($result_at)){

	?>
	<tr height="25">
		<td align="center"><?=$i ?></td>
		<td><?=$row_at[1] ?></td>
		<td>
		<?php
		OpenDb();
		$query_ju = "SELECT j.jenisujian, a.bobot, a.aktif, a.replid FROM aturannhb a, tingkat t, jenisujian j ".
				 	"WHERE a.idtingkat = '".$row_tkt['replid']."' AND a.idpelajaran = '$id_pelajaran' AND j.replid = a.idjenisujian ".
					"AND t.departemen = '$departemen' AND a.dasarpenilaian = '".$row_at[0]."' AND a.nipguru = '$nip_guru' ".
					"AND t.replid = a.idtingkat";
		
		$result_ju = QueryDb($query_ju);
		CloseDb();
		while($row_ju = mysqli_fetch_row($result_ju)){
			if ($row_ju[2] == 1) { ?>
				<a href="JavaScript:setaktif(<?=$row_ju[3] ?>, <?=$row_ju[2] ?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Status Aktif!', this, event, '50px')" /></a>&nbsp;
<?=$row_ju[0]." = ".$row_ju[1]."<br>";
			} else { ?>
				<a href="JavaScript:setaktif(<?=$row_ju[3] ?>, <?=$row_ju[2] ?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Status Tidak Aktif!', this, event, '50px')" /></a>&nbsp; 
<?=$row_ju[0]." = ".$row_ju[1]."<br>";
			} //end if
		}
        ?>
		</td>
     	<td align="center" width="*"> 
            <a href = "JavaScript:edit('<?=$row_tkt['replid']?>','<?=$row_at[0]?>')">
            <img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah!', this, event, '50px')"></a>
            <a href = "JavaScript:hapus('<?=$row_tkt['replid']?>','<?=$row_at[0]?>')">
            <img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus!', this, event, '50px')" /></a> 
   		</td>
        </tr>
	<?php
	$i++;
	}
	?>
	
  	</table>
    <script language='JavaScript'>
          Tables('table<?=$cnt?>', 1, 0);
    </script>
    
<?php } else { ?>
	</legend>	
		<table width="100%" border="0" align="center">          
		<tr>
			<td align="center" valign="middle">
    		<font size = "2" color ="red"><b>Tidak ditemukan adanya data.    
           <br />Klik <a href="JavaScript:tambah(<?=$row_tkt['replid']?>)" ><font size = "2" color ="green">di sini</font></a> untuk mengisi data baru pada tingkat <?=$row_tkt['tingkat']?>. 
            </b></font>
			</td>
		</tr>
	 	</table>
<?php } 	?> 
	<br>
  	</fieldset>
<?php }	?>
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
        <br />Tambah tingkat kelas pada departemen <?=$departemen?> di menu referensi
        </b></font>
	</td>
</tr>
</table>  
<?php } ?>
</td>
</tr>
</table>
</form>
</body>
</html>