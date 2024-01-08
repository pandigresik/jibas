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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../sessionchecker.php');

OpenDb();

if(isset($_REQUEST["idaturan"]))
	$idaturan = $_REQUEST["idaturan"];
if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];
if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];
$idrpp="";
if(isset($_REQUEST["idrpp"]))
	$idrpp = $_REQUEST["idrpp"];
	
$sql_get_nhb = 
	"SELECT DISTINCT k.kelas, t.tingkat, t.replid AS idtingkat, t.departemen, 
			p.nama, p.replid AS idpelajaran, ta.tahunajaran, s.semester, j.jenisujian, j.replid AS idjenis 
	   FROM jbsakad.aturannhb a, kelas k, tahunajaran ta, tingkat t, pelajaran p, semester s, jenisujian j 
	  WHERE a.replid='$idaturan' AND k.idtingkat = t.replid AND k.replid = '$kelas' AND s.replid = '$semester' 
	    AND p.replid = a.idpelajaran AND j.replid = a.idjenisujian AND ta.replid = k.idtahunajaran";
$result_get_nhb = QueryDb($sql_get_nhb);
$row = @mysqli_fetch_array($result_get_nhb);
$departemen = $row['departemen'];
$namakelas = $row['kelas'];
$namatingkat = $row['tingkat'];
$namasemester = $row['semester'];
$namapelajaran = $row['nama'];
$tahunajaran = $row['tahunajaran'];
$jenisujian = $row['jenisujian'];
$tingkat = $row['idtingkat'];
$pelajaran = $row['idpelajaran'];
$jenis = $row['idjenis'];
?>
<html>
<head>
<title>JIBAS SIMAKA [Tambah Data Nilai Pelajaran]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-system.css">
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/validasi.js"></script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
<script language = "javascript" type = "text/javascript">

function refresh() {
	opener.refresh();
}

function cek_form() {
	var tanggal, deskripsi, jumlah, i;
	tanggal = document.tambah_nilai_pelajaran.tanggal.value;
	deskripsi = document.tambah_nilai_pelajaran.deskripsi.value;
	jumlah = document.tambah_nilai_pelajaran.jumlah.value;
	idrpp = document.tambah_nilai_pelajaran.idrpp.value;
	
	if(tanggal.length == 0) {
		alert("Anda harus mengisikan data untuk Tanggal! \nKlik ikon Kalender untuk membuka kalender");
		document.getElementById('tanggal').focus();
		return false;
	}
	/*
	if(idrpp.length == 0) {
		alert("Anda harus mengisikan data untuk RPP!");
		document.getElementById('idrpp').focus();
		return false;
	}
	*/
	if(deskripsi.length == 0) {
		alert("Anda harus mengisikan data untuk Materi!");	
		document.tambah_nilai_pelajaran.deskripsi.focus();
		return false;
	}
	
	for (i=1;i<=jumlah;i++) {			
		var nau = document.getElementById("nilaiujian"+i).value;
		if (nau.length == 0){
			alert ('Masih ada siswa yang belum mendapat nilai!');
			document.getElementById("nilaiujian"+i).focus();
			return false;
		} else {
			if (isNaN(nau)){
				alert ('Nilai Akhir harus berupa bilangan!');
				document.getElementById("nilaiujian"+i).focus();
				return false;
			}
			
			if (parseInt(nau) > 100){
				alert ('Rentang Nilai Akhir antara 0 - 100 !');
				document.getElementById("nilaiujian"+i).focus();
				return false;
			}
		}
	}
	document.getElementById('tambah_nilai_pelajaran').submit(); 
}
	
function get_rpp(tingkat,pelajaran,semester){
	newWindow('rpp_tampil.php?tingkat='+tingkat+'&semester='+semester+'&pelajaran='+pelajaran,'TambahRPP','750','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

/*function refresh_rpp(idrpp){
	
	
	var semester = document.tambah_nilai_pelajaran.semester.value;
	var idaturan = document.tambah_nilai_pelajaran.idaturan.value;
	var kelas = document.tambah_nilai_pelajaran.kelas.value;
	document.location.href="nilai_pelajaran_add.php?semester="+semester+"&idaturan="+idaturan+"&kelas="+kelas+"&idrpp="+idrpp;	
}*/

function kirim_rpp(idrpp_kiriman){	
	idrpp=idrpp_kiriman;
	setTimeout("refresh_rpp(idrpp)",1);
}

function refresh_rpp(kode){
	wait_rpp();
	if (kode==0){
		sendRequestText("getrpp.php", show_rpp, "tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>");
	} else {
		sendRequestText("getrpp.php", show_rpp, "rpp="+kode+"&tingkat=<?=$tingkat?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>");
	}
}

function wait_rpp() {
	show_wait("rpp_info"); 
}

function show_rpp(x) {
	document.getElementById("rpp_info").innerHTML = x;
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function ref_del_rpp(){
	setTimeout("refresh_rpp(0)",1);
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

function simpan(evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode :
		((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById('tambah_nilai_pelajaran').submit();
		return false;
	}
	return true;
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
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="#dcdfc4" onLoad="document.getElementById('deskripsi').focus();">
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />Silahkan&nbsp;tunggu...
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58" style="background-color:#EEE">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#000; font-size:16px; font-weight:bold">
    .: Tambah Nilai Pelajaran :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF" height="510" valign="top">
    <!-- CONTENT GOES HERE //--->

<form action="nilai_pelajaran_simpan.php" method="post" name="tambah_nilai_pelajaran"  id="tambah_nilai_pelajaran" onSubmit="return cek_form()"/>
<input type="hidden" name="idaturan" value="<?=$idaturan?>">
<input type="hidden" name="semester" value="<?=$semester?>">
<input type="hidden" name="kelas" value="<?=$kelas?>">
<input type="hidden" name="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" name="jenis" value="<?=$jenis?>">

<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr>
    <td width="18%"><strong>Departemen</strong></td>
    <td><input class="disabled" type="text" size="15" value="<?=$departemen?>" readonly></td>
    <td width="20%"><strong>Tahun Ajaran</strong></td>
    <td><input type="text" class="disabled" value="<?=$tahunajaran?>" size="27" readonly></td>
</tr>
<tr>
	<td><strong>Tingkat</strong></td>
    <td><input type="text" class="disabled" value="<?=$namatingkat?>" size="15" readonly></td>
    <td><strong>Semester</strong></td>
    <td><input type="text" class="disabled" value="<?=$namasemester?>" size="27" readonly></td>
</tr>
<tr>
    <td><strong>Kelas</strong></td>
    <td><input type="text" class="disabled" value="<?=$namakelas?>" size="15" readonly></td>
    <td><strong>Pelajaran</strong></td>
    <td><input type="text" class="disabled" value="<?=$namapelajaran?>" size="27" readonly></td>
</tr>
<tr>
    <td colspan="4">
    
    <fieldset><legend><b>Jenis Pengujian <?=$jenisujian?></b></legend>
    
    <table>
    <tr>
    	<td>Kode&nbsp;Ujian</td>
        <td><input type="text" name="kode" id="kode" size="25" onKeyPress="return focusNext('idrpp', event);"></td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td><input type="text" name="tanggal" id="tanggal" size="25" readonly class="disabled" value='<?=date("d")."-".date("m")."-".date("Y"); ?>' onClick="Calendar.setup()">&nbsp;
        	<img src="../images/calendar.jpg" name="tabel" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '100px')"/></td>
    </tr>
    <tr>
      	<td><strong>RPP</strong></td>
      	<td><div id="rpp_info">
        	<select name="idrpp" id="idrpp" style="width:170px;" onkeypress="return focusNext('deskripsi', event)">
      		<option value="" <?=IntIsSelected("", $idrpp) ?> >Tanpa RPP</option>
		<?php $sql_rpp="SELECT * FROM rpp WHERE idtingkat='$tingkat' AND idsemester='$semester' AND idpelajaran='$pelajaran' AND aktif=1 ORDER BY rpp";
      		$result_rpp=QueryDb($sql_rpp);
      		while ($row_rpp=@mysqli_fetch_array($result_rpp)){
				if ($idrpp == "")
					$idrpp = $row_rpp['replid'];
      	?>
      			<option value="<?=$row_rpp['replid'] ?>" <?=IntIsSelected($row_rpp['replid'], $idrpp) ?> ><?=$row_rpp['rpp'] ?>
          		</option>
      	<?php } ?>
     
      		</select>
            <img src="../images/ico/tambah.png" onClick="get_rpp('<?=$tingkat?>','<?=$pelajaran?>','<?=$semester?>')" onMouseOver="showhint('Tambah RPP!', this, event, '80px')">
            </div>
      	</td>
	</tr>
	<tr>
    	<td><strong>Materi</strong></td>
        <td><input type="text" name="deskripsi" id="deskripsi" size="65" onKeyPress="return focusNext('nilaiujian1', event);"></td>
    </tr>
    
    </table>  
    
    <table id="table" class="tab" border="1" width="100%">
    <tr height="30" align="center">
        <td class="header" width="6%">No</td>
        <td class="header" width="15%">N I S</td>
        <td class="header">Nama</td>
        <td class="header" width="10%">Nilai</td>
        <td class="header" width="20%">Keterangan</td>
    </tr>
<?php  $sql_siswa="SELECT * FROM siswa WHERE idkelas='$kelas' AND aktif=1 AND alumni=0 ORDER BY nama ASC";
    $result_siswa=QueryDb($sql_siswa);
    $numsiswa=@mysqli_num_rows($result_siswa);
    while ($row_siswa=@mysqli_fetch_array($result_siswa)){

?>
    <tr height="25">
        <td align="center"><?=++$i ?></td>
        <td align="center"><?=$row_siswa['nis'] ?></td>
        <td><?=$row_siswa['nama'] ?></td>
        <td align="center">
            <input type="text" name="nilaiujian[<?=$row_siswa['nis']?>][0]" id="nilaiujian<?=$i?>" size="5" maxlength="5" onKeyPress="return focusNext('keterangan<?=$i?>', event);" ></td>
        <td align="center">
            <input type="text" name="nilaiujian[<?=$row_siswa['nis']?>][1]" id="keterangan<?=$i?>" <?php if ($i==$numsiswa){ ?> onKeyPress="focusNext('Simpan',event);" <?php } else { ?> onKeyPress="return focusNext('nilaiujian<?=(int)$i+1?>',event);" <?php } ?> ></td>
    </tr>
<?php  } ?>								
    <input type="hidden" name="jumlah" id="jumlah" value="<?=$numsiswa?>">
    </table>
    <script language='JavaScript'>
        Tables('table', 1, 0);
    </script>
    <br>
    </fieldset>
    </td>
</tr>
<?php if ($numsiswa==0){ ?>
<tr>
    <td align="center" colspan="4">
    	<span class="style1">Tidak ada siswa yang terdaftar, pengisian nilai tidak dapat dilakukan!        </span></td>
</tr>
<?php } ?>
<tr>
    <td align="center" colspan="4">
    <?php if ($numsiswa!=0){ ?>
    <input type="Button" value="Simpan" id="Simpan" name="Simpan" class="but" onClick="return cek_form();document.getElementById('tambah_nilai_pelajaran').submit();">&nbsp;
    <?php } ?>
    <input type="button" name="tutup" value="Tutup" class="but" onClick="window.close()" >
    </td>
</tr>
</table>
</form>

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
<script type="text/javascript">
  Calendar.setup(
    {
	  inputField  : "tanggal",         
      ifFormat    : "%d-%m-%Y",  
      button      : "btntanggal"    
    }
   );
   Calendar.setup(
    {
	  inputField  : "tanggal",      
      ifFormat    : "%d-%m-%Y",   
      button      : "tanggal"     
    }
   );
  
</script>
</html>
<script language="javascript">
	var sprytextfield1 = new Spry.Widget.ValidationTextField("kode");
	var spryselect1 = new Spry.Widget.ValidationSelect("idrpp");
	var sprytextfield1 = new Spry.Widget.ValidationTextField("deskripsi");
	var num = document.getElementById("jumlah").value;
	var x;
	for (x=1;x<=num;x++){
		var sprytextfield1 = new Spry.Widget.ValidationTextField("nilaiujian"+x);
		var sprytextfield2 = new Spry.Widget.ValidationTextField("keterangan"+x);
	}
</script>