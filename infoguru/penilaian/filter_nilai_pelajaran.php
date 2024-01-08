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
require_once("../include/sessionchecker.php");

$departemen="";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];	
$kelas = "";	
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];
$tahunajaran = "";	
if (isset($_REQUEST['tahunajaran']))
	$tahunajaran=$_REQUEST['tahunajaran'];
$semester = "";
if (isset($_REQUEST['semester']))
	$semester=$_REQUEST['semester'];

OpenDb();
?>
<html>
<head>
<title>Penilaian Pelajaran</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript">

function change_sel(){
    var departemen = document.filter_nilai_pelajaran.departemen.value;
    document.location.href="filter_nilai_pelajaran.php?departemen="+departemen;
    parent.nilai_pelajaran_footer.location.href = "blank_nilai_pelajaran.php";
}

function change_sel2() {
    var nip = document.getElementById('nipguru').value;
	//var nama = document.getElementById('namaguru').value; 
	var departemen = document.filter_nilai_pelajaran.departemen.value;
    var tingkat = document.filter_nilai_pelajaran.tingkat.value;
    
	document.location.href="filter_nilai_pelajaran.php?tingkat="+tingkat+"&departemen="+departemen+"&nip="+nip;
    parent.nilai_pelajaran_footer.location.href = "blank_nilai_pelajaran.php";
}

function change() {
    var nip = document.getElementById('nipguru').value;
	//var nama = document.getElementById('namaguru').value; 
	var departemen = document.filter_nilai_pelajaran.departemen.value;
    var tingkat = document.filter_nilai_pelajaran.tingkat.value;
	var kelas = document.filter_nilai_pelajaran.kelas.value;
    document.location.href="filter_nilai_pelajaran.php?tingkat="+tingkat+"&departemen="+departemen+"&nip="+nip+"&kelas="+kelas;
    parent.nilai_pelajaran_footer.location.href = "blank_nilai_pelajaran.php";
}

function show(){
    var departemen = document.filter_nilai_pelajaran.departemen.value;
    var tingkat = document.filter_nilai_pelajaran.tingkat.value;
    var tahun = document.filter_nilai_pelajaran.tahunajaran.value;
    var semester = document.filter_nilai_pelajaran.semester.value;
    var kelas = document.filter_nilai_pelajaran.kelas.value;
    var nip = document.filter_nilai_pelajaran.nipguru.value;	
    
    if(departemen.length == 0) {
        alert("Departemen tidak boleh kosong!");
        document.filter_nilai_pelajaran.departemen.focus();
        return false;
    } else if(tingkat.length == 0) {
        alert("Tingkat tidak boleh kosong!");
        document.filter_nilai_pelajaran.tingkat.focus();
        return false;
    } else if(tahun.length == 0) {
        alert("Tahun Ajaran tidak boleh kosong!");
        document.filter_nilai_pelajaran.tahun.focus();
        return false;
    } else if(semester.length == 0) {
        alert("Semester tidak boleh kosong!");
        document.filter_nilai_pelajaran.semester.focus();
        return false;
    } else if(kelas.length == 0) {
        alert("Kelas tidak boleh kosong!");
        document.filter_nilai_pelajaran.kelas.focus();
        return false;
    } else {	
        parent.nilai_pelajaran_footer.location.href="nilai_pelajaran_footer.php?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&kelas="+kelas+"&nip="+nip;
    }
}

function pegawai() {	
	var departemen = document.getElementById('departemen').value;
	newWindow('../library/guru.php?flag=0&departemen='+departemen,'Guru','600','600','resizable=1,scrollbars=1,status=0,toolbar=0');
	parent.nilai_pelajaran_footer.location.href = "blank_nilai_pelajaran.php";
}

function acceptPegawai(nip, nama, flag, dep) {
	var departemen = document.getElementById('departemen').value;
	var tingkat = document.getElementById('tingkat').value;
	var kelas = document.getElementById('kelas').value;
	if (departemen == dep)
		document.location.href = "../penilaian/filter_nilai_pelajaran.php?departemen="+dep+"&nip="+nip+"&nama="+nama+"&tingkat="+tingkat+"&kelas="+kelas;		
	else
		document.location.href = "../penilaian/filter_nilai_pelajaran.php?departemen="+dep+"&nip="+nip+"&nama="+nama;		
		
	document.getElementById('nip').value = nip;
	document.getElementById('nipguru').value = nip;
	document.getElementById('nama').value = nama;
	document.getElementById('namaguru').value = nama;	
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

<body leftmargin="0" topmargin="0" onLoad="document.getElementById('departemen').focus()">
<form name="filter_nilai_pelajaran" method="post" action="filter_nilai_pelajaran.php">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="64%">
    <table width = "100%" border = "0">
    <tr>
        <td width="16%"><strong>Departemen</strong></td>
        <td width="32%">
        	<select name="departemen" id="departemen" style="width:180px;" onChange="change_sel();" onKeyPress="focusNext('tingkat',event)">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
					foreach($dep as $value) {
						if ($departemen == "")
							$departemen = $value; ?>
          		<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?> 
                </option>
              <?php } ?>
           	</select>
		</td>
        <td width="18%"><strong>Tahun Ajaran</strong></td>
        <td>
        <?php  $sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen='$departemen' AND aktif=1 ORDER BY replid DESC";
            $result = QueryDb($sql);
            $row = @mysqli_fetch_array($result);	
            $tahunajaran = $row['replid'];				
        ?>
        <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">        
        <input type="text" name="tahun" id="tahun" readonly class="disabled" style="width:150px" value="<?=$row['tahunajaran']?>" /></td> 
	</tr>
    <tr>
        <td><strong>Kelas</strong></td>
        <td>
        
        <select name="tingkat" id="tingkat" onChange="change_sel2()" style="width:60px;" onkeypress="return focusNext('kelas', event)">
    <?php  $sql="SELECT * FROM tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
        $result=QueryDb($sql);
        while ($row=@mysqli_fetch_array($result)){
            if ($tingkat=="")
                $tingkat=$row['replid'];
    ?> 
        <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
    <?php 	} ?> 
        </select>
        <select name="kelas" id="kelas" onChange="change()" style="width:112px;" onKeyPress="focusNext('tabel',event)">
    <?php  $sql="SELECT * FROM jbsakad.kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' AND aktif = 1 ORDER BY kelas";
        $result=QueryDb($sql);
        while ($row=@mysqli_fetch_array($result)){
        if ($kelas=="")
            $kelas=$row['replid'];
    ?> 
        <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelas)?>><?=$row['kelas']?></option>
    <?php 	} ?> 
        </select>
        </td>
        <td><strong>Semester </strong></td>
        <td>
     <?php $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1 ORDER BY replid DESC";
        $result = QueryDb($sql);
        $row = @mysqli_fetch_array($result);			
    ?>
        <input type="text" name="sem" id="sem" class="disabled" style="width:150px" readonly value="<?=$row['semester']?>" />
        <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">      	</td>
    </tr>
    <input type="hidden" name="nipguru" id="nipguru" value="<?=SI_USER_ID() ?>"/>
    </table>
    </td>
    <td align="left" valign="middle" width="*" rowspan="3">
       <img src="../images/ico/view.png" name="tabel" width="48" height="48" border="0" id="tabel" style="cursor:pointer;" onClick="show()" onMouseOver="showhint('Klik untuk menampilkan penilaian pelajaran!', this, event, '150px')">    </td>
    <td valign="top" width="40%" align="right">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Penilaian Pelajaran</font><br />
        <a href="../penilaian.php" target="framecenter">
        <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Penilaian Pelajaran</b></font></td>
    </td>
</tr>	
</table>
</form>
</body>
</html>
<?php
CloseDb();
?>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
	//var spryselect5 = new Spry.Widget.ValidationSelect("nip");
</script>