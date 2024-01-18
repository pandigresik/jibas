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

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
$tahunajaran = "";
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran = $_REQUEST['tahunajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester = $_REQUEST['semester'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran = $_REQUEST['pelajaran'];

$ERROR_MSG = "";



OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cetak Form Presensi Pelajaran</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript" src="../script/validasi.js"></script>
<script language="javascript">

function refresh() {	
	document.location.reload();
}

function change() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	//var kelas = document.getElementById("kelas").value;
	var pelajaran = document.getElementById("pelajaran").value;
			
	document.location.href = "formpresensi_pelajaran.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&pelajaran="+pelajaran;
}

function change_dep() {
	var departemen = document.getElementById("departemen").value;
	//var semester = document.getElementById("semester").value;
			
	document.location.href = "formpresensi_pelajaran.php?departemen="+departemen;
}

function change_kelas() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	var pelajaran = document.getElementById("pelajaran").value;
		
	document.location.href = "formpresensi_pelajaran.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester+"&tingkat="+tingkat+"&kelas="+kelas+"&pelajaran="+pelajaran;
}

function change_ajaran() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var semester = document.getElementById("semester").value;
	
	document.location.href = "formpresensi_pelajaran.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester;
}

function validate() {
	return validateEmptyText('tahunajaran', 'Tahun Ajaran') && 
		   validateEmptyText('semester', 'Semester') && 	
		   validateEmptyText('kelas', 'Kelas') &&	
		   validateEmptyText('pelajaran', 'Pelajaran');
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

function openwin(kelas)
{
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var semester = document.getElementById('semester').value;
	var kelas = document.getElementById('kelas').value;
	var pelajaran = document.getElementById('pelajaran').value;
	
	newWindow('formpresensi_pelajaran_cetak.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&semester='+semester+'&kelas='+kelas+'&pelajaran='+pelajaran,'CetakFormPresensiPelajaran','790','850','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
</head>

<body>

<table border="0" width="100%" height="100%">
<!-- TABLE BACKGROUND IMAGE -->
<tr><td align="center" valign="top" background="../images/ico/b_cetak.png" style="margin:0;padding:0;background-repeat:no-repeat;">

<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	<!--<td width="180" height="122">&nbsp;</td>-->
	<td align="left" valign="top">

	<table border="0"width="95%" align="center">
    <!-- TABLE TITLE -->
    <tr>
        <td align="right">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Cetak Form Presensi Pelajaran</font><br />
         </td>
   	</tr>
    <tr>
    	<td align="right"><a href="../presensi.php?page=pp" target="framecenter">
      <font size="1" color="#000000"><b>Presensi</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Cetak Form Presensi Pelajaran</b></font>
        </td>
    </tr>
    <tr>
    	<td align="left">&nbsp;</td>
    </tr>
	</table>
   
     <table align="center">
    <tr>
    	<td>
        <form name="main" onSubmit="return validate()">
    <br />
    <fieldset>
        <legend><strong>Data Form Presensi Pelajaran</strong></legend>
    	<table border="0" cellpadding="2" cellspacing="5" width="100%" align="center">
    	<!-- TABLE LINK -->
        <tr>
            <td align="left" width="50%"><strong>Departemen</strong></td>
            <td width="*"> 
            <select name="departemen" id="departemen" onChange="change_dep()" style="width:250px;" onKeyPress="return focusNext('tingkat', event)">
        <?php $dep = getDepartemen(SI_USER_ACCESS());    
            foreach($dep as $value) {
                if ($departemen == "")
                    $departemen = $value; ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > <?=$value ?> </option>
        <?php } ?>
            </select>    </td>
        </tr>
       <tr>
            <td align="left"><strong>Tahun Ajaran</strong></td>
            <td>
                <?php
                OpenDb();
                $sql = "SELECT replid,tahunajaran FROM tahunajaran where departemen='$departemen' AND aktif = 1";
                $result = QueryDb($sql);
				CloseDb();
				$row = mysqli_fetch_array($result);
				$tahun = $row['tahunajaran'];
				$tahunajaran = $row['replid'];
				?>
                
                <input type="text" name="tahun" size="38" value="<?=$tahun ?>" readonly class="disabled"/>
                <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran?>">            </td> 
        </tr>
        <tr>
            <td align="left"><strong>Semester</strong></td>
            <td>            
                <?php
                OpenDb();
                $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1";
                $result = QueryDb($sql);
                CloseDb();
               	$row = @mysqli_fetch_array($result);
                
                ?>
                <input type="text" name="sem" size="38" value="<?=$row['semester'] ?>" readonly class="disabled"/>
                <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">          	</td> 
        </tr>
        <tr>
            <td><strong>Tingkat</strong></td>
            <td>
                <select name="tingkat" id="tingkat" onChange="change()" style="width:250px;" onKeyPress="return focusNext('kelas', event)">
                <?php OpenDb();
                $sql = "SELECT replid,tingkat FROM tingkat WHERE aktif=1 AND departemen='$departemen' ORDER BY urutan";	
                $result = QueryDb($sql);
                CloseDb();
        
                while($row = mysqli_fetch_array($result)) {
                if ($tingkat == "")
                    $tingkat = $row['replid'];				
                ?>
                <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat) ?>><?=$row['tingkat']?></option>
                <?php
                } //while
                ?>
                </select>            </td>   
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>
                <select name="kelas" id="kelas" onChange="change_kelas()" style="width:250px;" onKeyPress="return focusNext('pelajaran', event)">
                <?php OpenDb();
                $sql = "SELECT replid,kelas FROM kelas WHERE aktif=1 AND idtahunajaran = '$tahunajaran' AND idtingkat = '$tingkat' ORDER BY kelas";	
                $result = QueryDb($sql);
                CloseDb();
        
                while($row = mysqli_fetch_array($result)) {
                if ($kelas == "")
                    $kelas = $row['replid'];
                $kls = $row['kelas'];			 
                ?>
                <option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas) ?>><?=$row['kelas']?></option>
                 
                <?php
                } //while
                ?>
                </select>            </td>
        </tr>
        <tr>
            <td align="left"><strong>Pelajaran</strong></td>
            <td><select name="pelajaran" id="pelajaran" onChange="change()" style="width:250px;" onkeypress="return focusNext('cetak', event)">
              <?php
                OpenDb();
                $sql = "SELECT p.replid,p.nama FROM pelajaran p, guru g WHERE p.departemen = '$departemen' AND g.idpelajaran=p.replid AND g.nip='".SI_USER_ID()."' AND p.aktif=1 ORDER BY p.nama";
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
            </select></td>
       	</tr>          
    	<tr>
			<td colspan="2"><div align="center"><br />
            <?php
            if ($kelas!=""){
			OpenDb();
			$sql = "SELECT nis, nama FROM siswa WHERE idkelas = '$kelas' ORDER BY nama";
			
			$result = QueryDb($sql);
			
			if (mysqli_num_rows($result) > 0) {
				if ($result) { ?>
					<input type="button" onclick="openwin('<?=$kelas?>')" name="Cetak" id="cetak" value="Cetak" class="but" style="width:80px;"/>
			<?php }
			} else {
				CloseDb();
				?>
				<span class="style1">Belum ada data siswa yang terdaftar pada kelas ini!</span>				
			<?php } 
			} else {
			?>
            <span class="style1">Belum ada data siswa yang terdaftar pada kelas ini!</span>
            <?php
			}
			?>	
		  </div></td>
	</tr>
    </table>
    </fieldset>
    	</form>
   	</td>
    </tr>
     </table>
    </td>
</tr>     
<!-- END TABLE CENTER -->    
</table>

</td></tr>
<!-- END TABLE BACKGROUND IMAGE -->
</table>    
<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect4 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect5 = new Spry.Widget.ValidationSelect("kelas");
	var spryselect5 = new Spry.Widget.ValidationSelect("pelajaran");
</script>