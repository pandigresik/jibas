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

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran ="";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$jam = "";
if (isset($_REQUEST['jam']))
	$jam = $_REQUEST['jam'];
$menit = "";
if (isset($_REQUEST['menit']))
	$menit = $_REQUEST['menit'];

$tgl = date('d')."-".date('m')."-".date('Y');
if (isset($_REQUEST['tanggal']))
	$tgl= $_REQUEST['tanggal'];

if ($_REQUEST['replid']<> "") {	
	OpenDb();
	$sql = "SELECT p.replid, p.idkelas, p.idsemester, p.idpelajaran, p.tanggal, p.jam, k.idtahunajaran, a.departemen, k.idtingkat FROM presensipelajaran p, tahunajaran a, kelas k WHERE p.replid = '".$_REQUEST['replid']."' AND k.replid = p.idkelas AND a.replid = k.idtahunajaran";
	$result = QueryDb($sql);
	CloseDb();
	$row = @mysqli_fetch_array($result);
	$departemen = $row['departemen'];
	$tahunajaran = $row['idtahunajaran'];
	$tingkat = $row['idtingkat'];
	$kelas = $row['idkelas'];	
	$semester=$row['idsemester'];
	$pelajaran=$row['idpelajaran'];
	$tgl=TglText($row['tanggal']);
	$jam=substr((string) $row['jam'],0,2);
	$menit=substr((string) $row['jam'],3,2);
}	
	

$ERROR_MSG = "";
if (isset($_REQUEST['tampil'])) {	
	$date = MySqlDateFormat($tgl);
	OpenDb();
	$sql = "SELECT * FROM tahunajaran WHERE replid = '$tahunajaran' AND '$date' BETWEEN tglmulai AND tglakhir";
	
	$result = QueryDb($sql);
	if (mysqli_num_rows($result) > 0) {
		if ($result) { ?>        	
			<script language="javascript">
			
			parent.header.location.href = "presensi_header.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&jam=<?=$jam?>&menit=<?=$menit?>&tanggal=<?=$tgl?>";
			parent.footer.location.href = "presensi_footer.php?departemen=<?=$departemen?>&semester=<?=$semester?>&tingkat=<?=$tingkat?>&pelajaran=<?=$pelajaran?>&kelas=<?=$kelas?>&jam=<?=$jam?>&menit=<?=$menit?>&tanggal=<?=$tgl?>";
								
			</script> 
<?php 	}
	} else {
		CloseDb();			
		$ERROR_MSG = " Waktu data presensi tidak boleh melebihi batas tahun ajaran!";
	}
}
	
OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Presensi Pelajaran</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/cal2.js"></script>
<script language="javascript" src="../script/cal_conf2.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
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

function change() {
	var departemen = document.getElementById("departemen").value;
	//var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var jam = document.getElementById("jam").value;
	var menit = document.getElementById("menit").value;
	var tanggal = document.getElementById("tanggal").value;
		
	parent.header.location.href = "presensi_header.php?departemen="+departemen+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&jam="+jam+"&menit="+menit+"&tanggal="+tanggal;
	parent.footer.location.href = "presensi_blank.php";
	
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	//var tahunajaran = document.getElementById("tahunajaran").value;
	//var semester = document.getElementById("semester").value;
	//var pelajaran = document.getElementById("pelajaran").value;
	var jam = document.getElementById("jam").value;	
	var menit = document.getElementById("menit").value;
	var tanggal = document.getElementById("tanggal").value;
	
	parent.header.location.href = "presensi_header.php?departemen="+departemen+"&jam="+jam+"&menit="+menit+"&tanggal="+tanggal;
	parent.footer.location.href = "presensi_blank.php";
}

function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	//var tahunajaran = document.getElementById("tahunajaran").value;
	//var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;	
	var jam = document.getElementById("jam").value;
	var menit = document.getElementById("menit").value;
	var tanggal = document.getElementById("tanggal").value;
	
	parent.header.location.href = "presensi_header.php?departemen="+departemen+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&jam="+jam+"&menit="+menit+"&tanggal="+tanggal;
	parent.footer.location.href = "blank_presensi.php";
}


function tampil() {
	var departemen = document.getElementById('departemen').value;
	//var semester = document.getElementById('semester').value; 
	///var tahunajaran = document.getElementById('tahunajaran').value; 
	var tingkat = document.getElementById("tingkat").value;	
	var kelas = document.getElementById("kelas").value;
	var tanggal = document.getElementById("tanggal").value;
	
	newWindow('presensi_lihat_header.php?departemen='+departemen+'&kelas='+kelas+'&tanggal='+tanggal+'&tingkat='+tingkat, 'LihatPresensiPelajaran','655','500','resizable=1,scrollbars=0,status=0,toolbar=0')
				//newWindow('presensi_lihat_header.php?departemen='+departemen+'&semester='+semester+'&tingkat='+tingkat+'&kelas='+kelas+'&tanggal='+tanggal, 'LihatPresensiPelajaran','650','500','resizable=1,scrollbars=0,status=0,toolbar=0')
}

function show() {	
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;	
	var tingkat = document.getElementById("tingkat").value;
	var pelajaran = document.getElementById("pelajaran").value;
	var kelas = document.getElementById("kelas").value;
	var jam = document.getElementById("jam").value;
	var menit = document.getElementById("menit").value;
	var tanggal = document.getElementById("tanggal").value;
	
	if (tingkat.length == 0){
		alert ('Tingkat tidak boleh kosong!');
	} else if (kelas.length == 0) {	
		alert ('Kelas tidak boleh kosong!');
		document.getElementById("kelas").focus();
	} else if (pelajaran.length == 0){
		alert ('Pelajaran tidak boleh kosong!');
		document.getElementById("pelajaran").focus();
	} else if (jam.length == 0|| menit.length == 0){
		alert ('Jam dan menit tidak boleh kosong dan harus lengkap!');
		document.getElementById("jam").focus();
	} else if (isNaN(jam) || isNaN(menit)){
		alert ('Jam dan menit harus berupa bilangan!');
		document.getElementById("jam").focus();
	} else if (jam > 24) {
		alert ('Jam tidak boleh lebih besar dari 24 jam');
		document.getElementById("jam").focus();
	} else if (menit > 59){	
		alert ('Menit tidak boleh lebih atau sama dengan 60 menit');
		document.getElementById("menit").focus();			
	} else {		
		parent.header.location.href = "presensi_header.php?departemen="+departemen+"&semester="+semester+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&jam="+jam+"&menit="+menit+"&tanggal="+tanggal+"&tampil=1";
		//parent.footer.location.href = "presensi_footer.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran+"&kelas="+kelas+"&jam="+jam+"&menit="+menit+"&tanggal="+tanggal;
	}
}

function panggil() {
	parent.footer.location.href = "presensi_blank.php";
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode :
        ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
		document.getElementById(elemName).focus();
        if (elemName == 'tabel')
			show();
		return false;
    }
    return true;
}

</script>
</head>
	
<body topmargin="0" leftmargin="0" onLoad="document.getElementById('jam').focus();">
<form action="presensi_header.php" method="post" name="main">
<table border="0" width="100%" align="center"  cellpadding="0" cellspacing="0">
<!-- TABLE CENTER -->
<tr>
	<td width="70%">
	<table width = "100%" border = "0">
    <tr>
    	<td width="14%"><strong>Departemen </strong></td>
    	<td> 
    	<select name="departemen" id="departemen" onChange="change_dep()" style="width:250px;" onKeyPress="return focusNext('departemen', event)">
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
			$sql = "SELECT replid,tahunajaran, tglmulai, tglakhir FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			$row = @mysqli_fetch_array($result);	
			$tahunajaran = $row['replid'];				
		?>
        <input type="text" name="tahun" id="tahun" class="disabled" readonly style="width:140px;" value="<?=$row['tahunajaran']?>" />
        <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">           
        </td> 
    </tr>
    <tr>
    	<td><strong>Tingkat </strong></td>
    	<td>
		<select name="tingkat" id="tingkat" onChange="change_tingkat()" style="width:250px;" onKeyPress="return focusNext('kelas', event)">
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
        ?>
            <input type="text" name="sem" id="sem" class="disabled" style="width:140px" readonly value="<?=$row['semester']?>" />
            <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">      	</td>
    </tr>
    <tr>
   		<td><strong>Kelas </strong></td>
    	<td>
        	<select name="kelas" id="kelas" onChange="change()" style="width:250px;" onKeyPress="return focusNext('pelajaran', event)">
			<?php OpenDb();
			$sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
			$result = QueryDb($sql);
			CloseDb();
	
			while($row = mysqli_fetch_array($result)) {
			if ($kelas == "")
				$kelas = $row['replid'];				 
			?>
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
             
    		<?php
			} //while
			?>
    		</select>        </td>
        <td><strong>Tanggal </strong></td>
        <td><input type="text" onClick="showCal('Calendar2');panggil()" style="width:140px;" name="tanggal" id ="tanggal" readonly class="disabled" value = "<?=$tgl?>" />
         
        <a href="javascript:showCal('Calendar2');panggil()"><img src="../images/calendar.jpg" border="0" onMouseOver="showhint('Buka kalender!', this, event, '100px')"></a>
        </td>
        
    </tr>
    <tr>
    	
        <td align="left"><strong>Pelajaran</strong></td>
      	<td>
        	<select name="pelajaran" id="pelajaran" onChange="change()" style="width:250px;" onKeyPress="return focusNext('jam', event)">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,nama FROM pelajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY nama";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
			if ($pelajaran == "") 				
				$pelajaran = $row['replid'];			
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $pelajaran)?> ><?=$row['nama']?></option>
                  
    		<?php
			}
    		?>
    		</select>		</td> 
            <td><strong>Jam</strong></td>
        <td><input type="text" name="jam" id="jam" size="3" maxlength="2" value="<?=$jam?>" onKeyPress="return focusNext('menit', event)"/> : 
        	<input type="text" name="menit" id="menit" size="3" maxlength="2" value="<?=$menit?>" onKeyPress="return enter('tabel', event)"/>             
        	(Jam:Menit)    	</td> 
      	
    </tr>
	</table>
    </td>
    <td width="*" rowspan="4" align="left" valign="middle"><a href="#" onClick="show()" ><img src="../images/view.png" onMouseOver="showhint('Klik untuk menampilkan presensi berdasarkan pelajaran!', this, event, '150px')" name="tabel" width="48" height="48" border="0" id="tabel2"/></a></td>
    <td width="*" align="right" valign="top">
    <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Presensi Pelajaran</font><br />
    <a href="../presensi.php?page=pp" target="content">
    <font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
    <font size="1" color="#000000"><b>Presensi Pelajaran </b></font><p>
    <a href="javascript:tampil()"><font size="1" color="#000000"><b>[Lihat Data Presensi Pelajaran]</b></font></a>
    </td>
</tr>
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect2 = new Spry.Widget.ValidationSelect("pelajaran");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("jam");
	var sprytextfield2 = new Spry.Widget.ValidationTextField("menit");
	 
</script>