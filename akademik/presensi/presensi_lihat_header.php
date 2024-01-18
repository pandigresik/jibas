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
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$semester = $_REQUEST['semester'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$pelajaran = -1;
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];

$bln = $_REQUEST['bln'];
$thn = $_REQUEST['thn'];	

if (isset($_REQUEST['tanggal'])) {
	$bln = (int)substr((string) $_REQUEST['tanggal'],3,2);
	$thn = (int)substr((string) $_REQUEST['tanggal'],6,4);	
}

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS SIMAKA [Lihat Data Presensi Pelajaran]</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;	
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var bln = document.getElementById("bln").value;
	var thn = document.getElementById("thn").value;

	document.location.href = "presensi_lihat_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&bln="+bln+"&thn="+thn;	
		
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;	
	var bln = document.getElementById("bln").value;
		
	document.location.href = "presensi_lihat_header.php?departemen="+departemen+"&bln="+bln;			
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	var bln = document.getElementById("bln").value;
	var thn = document.getElementById("thn").value;
	
	document.location.href = "presensi_lihat_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&bln="+bln+"&thn="+thn;	
	
}

function pilih(replid) {	
	opener.parent.header.location.href = "presensi_header.php?replid="+replid;
	opener.parent.footer.location.href = "presensi_footer.php?replid="+replid;	
	window.close();
}
/*
function tampil() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;	
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var bln = document.getElementById("bln").value;
	var thn = document.getElementById("thn").value;

	
	document.location.href =  "presensi_lihat_header.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&bln="+bln+"&thn="+thn;	
	//parent.footer.location.href = "presensi_lihat_footer.php?semester="+semester+"&pelajaran="+pelajaran+"&kelas="+kelas+"&bln="+bln+"&thn="+thn;
}*/	

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'lihat')
			change();
        return false;
    }
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" onload="document.getElementById('departemen').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height = "400" valign="top">
    <!-- CONTENT GOES HERE //--->
    
<form name="main" onSubmit="return validate()">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
	<td class="header" colspan="5" align="center">Presensi Pelajaran</td>
</tr>
<tr>
    <td width="14%"><strong>Departemen </strong></td>
    <td> 
    <select name="departemen" id="departemen" onChange="change_dep()" style="width:240px;" onKeyPress="return focusNext('tingkat', event)">
    <?php $dep = getDepartemen(SI_USER_ACCESS());    
        foreach($dep as $value) {
        if ($departemen == "")
            $departemen = $value; ?>
    <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
<?php } ?>
    </select>        </td>
    <td><strong>Tahun Ajaran</strong></td>
    <td>
    <?php  OpenDb();
        $sql = "SELECT replid,tahunajaran, YEAR(tglmulai) AS tahun1, YEAR(tglakhir) AS tahun2 FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
		
        $result = QueryDb($sql);
        CloseDb();
        $row = @mysqli_fetch_array($result);	
        $tahunajaran = $row['replid'];
		$tahun1 = $row['tahun1'];
		$tahun2 = $row['tahun2'];
						
    ?>
    <input type="text" name="tahun" id="tahun" class="disabled" readonly style="width:120px;" value="<?=$row['tahunajaran']?>" />
    <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">    
    </td>  
   
</tr>
<tr>
    <td><strong>Tingkat </strong></td>
    <td>
    <select name="tingkat" id="tingkat" onchange="change_tingkat()" style="width:240px;" onKeyPress="return focusNext('kelas', event)">
      <?php OpenDb();
        $sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
        $result = QueryDb($sql);
        CloseDb();

        while($row = mysqli_fetch_array($result)) {
        if ($tingkat == "")
            $tingkat = $row['replid'];				
        ?>
      <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat) ?>>
        <?=$row['tingkat']?>
        </option>
      <?php
        } //while
        ?>
    </select></td>
   <td><strong>Semester </strong></td>
    <td>
     <?php OpenDb();
        $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1 ORDER BY replid DESC";
        $result = QueryDb($sql);
        CloseDb();
        $row = @mysqli_fetch_array($result);	
		$semester = $row['replid'];		
    ?>
        <input type="text" name="sem" id="sem" class="disabled" style="width:120px" readonly value="<?=$row['semester']?>" />
        <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">      	</td>
</tr>
<tr>
	<td><strong>Kelas</strong></td>
    <td>
       	<select name="kelas" id="kelas" onChange="change()" style="width:240px;" onKeyPress="return focusNext('pelajaran', event)">
		<?php OpenDb();
			$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
			$result = QueryDb($sql);
			CloseDb();
		
			while($row = mysqli_fetch_array($result)) {
			if ($kelas =="") 
				$kelas = $row['replid'];	
		?>
    	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
             
    	<?php
			} //while
		?>
    	</select></td>
    <td><strong>Bulan</strong></td>
	<td>
    	<select name="bln" id ="bln" onchange="change()" onKeyPress="return focusNext('thn', event)" style="width:60px">
      	<?php for ($i=1;$i<=12;$i++) { ?>
      		<option value="<?=$i?>" <?=IntIsSelected($bln, $i)?>><?=$bulan[$i]?></option>
      	<?php } ?>
    	</select>
		<select name="thn" id = "thn" onchange="change()" onKeyPress="focusNext('lihat',event)" style="width:60px">
        <?php  for ($i = $tahun1; $i <= $tahun2; $i++) { 
			if ($thn =="") 
				$thn = $i;	
			?>
		<?php  //for($i=$th1-10;$i<=$th1;$i++){ ?>
          	<option value="<?=$i?>" <?=IntIsSelected($thn, $i)?>><?=$i?></option>	   
<?php } ?>	
       	</select> 
 	</td> 
</tr>
<tr>
	<td><strong>Pelajaran</strong></td>
    <td><select name="pelajaran" id="pelajaran" onChange="change()" style="width:240px;" onKeyPress="return focusNext('bln', event)">
    	<option value="-1">(Semua pelajaran)</option>
      <?php
			OpenDb();
			$sql = "SELECT replid,nama FROM pelajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY nama";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];	
		?>
       	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> >
        <?=$row['nama']?>
        </option>
      <?php
			}
    		?>
    </select>
    
    <?php 	$pel = "";
		if ($pelajaran <> -1) 
			$pel = "AND p.replid = $pelajaran";
	?>
    
    
    </td>
	<!--<td colspan="2">
    	<input type="submit" name="Simpan" value="Lihat" id="lihat" class="but" onClick="tampil()" style="width:150px;"/> </td>-->
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>

<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
<!-- TABLE UTAMA -->
<tr>
	<td align="center">
<?php 	
if ($kelas <> "" && $semester <> "" && $tingkat <> "") { 
	OpenDb();
	$sql = "SELECT DAY(pp.tanggal), MONTH(pp.tanggal), pp.jam, p.nama, g.nama, pp.materi, pp.replid FROM presensipelajaran pp, pelajaran p, jbssdm.pegawai g WHERE pp.idkelas = '$kelas' AND pp.idsemester = '$semester' '$pel' AND MONTH(pp.tanggal) = '$bln' AND YEAR(pp.tanggal) = '$thn' AND pp.idpelajaran = p.replid AND pp.gurupelajaran = g.nip ORDER BY pp.tanggal, pp.jam ";
		
	$result = QueryDb($sql);			 
	$jum = mysqli_num_rows($result);
	if ($jum > 0) {
	?>
   		<br />
        <table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" border="1">
		<tr height="30">		
			<td class="header" align="center" width="4%">No</td>
			<td class="header" align="center" width="5%">Tgl</td>
			<td class="header" align="center" width="9%">Jam</td>
            <td class="header" align="center" width="20%">Pelajaran</td>
            <td class="header" align="center" width="20%">Guru</td>
            <td class="header" align="center" width="*">Materi</td>
            <td class="header" align="center" width="8%"></td>
		</tr>
		<?php 
		$cnt = 1;
		while ($row = @mysqli_fetch_row($result)) {					
		?>	
        <tr height="25">        			
			<td align="center"><?=$cnt?></td>
			<td align="center"><?=$row[0].'/'.$row[1]?></td>
  			<td><?=$row[2]?></td>
           	<td><?=$row[3]?></td>
            <td><?=$row[4]?></td>
            <td><?=$row[5] ?></td>            
            <td align="center"><input type="button" name="pilih" class="but" id="pilih" value="Pilih" onClick="pilih('<?=$row[6]?>')" /></td>
    	</tr>
 	<?php 	$cnt++;
		} 
		CloseDb();	?>
    		
		</table>
		<script language='JavaScript'>
   			Tables('table', 1, 0);
		</script>

<?php } else { ?>
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />           
		Tambah data presensi di menu Presensi Harian atau <br />Presensi Pelajaran pada bagian Presensi </b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php }
} else {?>
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />          
		Tambah data Tahun Ajaran, Tingkat atau Kelas pada bagian Referensi. </b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php } ?>
</td></tr>
<tr>
	<td align="center">
    <br />
    <!--<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="parent.tutup()" style="width:80px;" />-->
    <input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="window.close()" style="width:80px;" />
    </td>
</tr>	
<!-- END OF TABLE UTAMA -->
</table>


<!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect2 = new Spry.Widget.ValidationSelect("pelajaran");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	var spryselect5 = new Spry.Widget.ValidationSelect("bln");
	var spryselect6 = new Spry.Widget.ValidationSelect("thn");	
</script>