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
OpenDb();
if (isset($_REQUEST['departemen']))
	$dep = $_REQUEST['departemen'];
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
if (isset($_REQUEST['nama']))
	$nama = $_REQUEST['nama'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daftar Mutasi Siswa</title>
<script language="javascript" src="../script/ajax.js"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">

function change_departemen() {
	var departemen = document.getElementById("departemen").value;
	//alert ('Dep='+departemen);
	//wait_tingkat();
	wait_kelas();
	sendRequestText("mutasi_getkelas.php", showKelas, "departemen="+departemen);
	//sendRequestText("topkelas_gettahunajaran.php", showtahunajaran, "departemen="+bagian);
	parent.mutasi_siswa_footer.location.href = "siswa_mutasi_blank.php";
}
function wait_kelas() {
	show_wait("kelasInfo");
}

function show_wait(areaId) {
	var x = document.getElementById("waitBox").innerHTML;
	document.getElementById(areaId).innerHTML = x;
}

function showKelas(x) {
	document.getElementById("kelasInfo").innerHTML = x;
}

function change_kelas() {
	parent.mutasi_siswa_footer.location.href = "siswa_mutasi_blank.php";	
}

function tampil() {
//alert ('Brubah');
	var departemen = document.siswa_cari_mutasi.departemen.value;	
	var nis = document.siswa_cari_mutasi.nis.value;
	var nama = document.siswa_cari_mutasi.nama.value;
	if (nis==""){
		if (nama==""){
			alert ('NIS atau Nama tidak boleh kosong !');
			document.siswa_cari_mutasi.nis.focus();
			return false;

		}
	}
	if (nama==""){
		if (nis==""){
			alert ('NIS atau Nama tidak boleh kosong !');
			document.siswa_cari_mutasi.nis.focus();
			return false;

		}
	}
	if (!nama==""){
		if (nama.length<3){
			alert ('Nama yang akan dicari tidak boleh kurang dari 3 karakter !');
			document.siswa_cari_mutasi.nama.focus();
			return false;

		}
	}
	if (!nis==""){
		if (nis.length<3){
			alert ('NIS yang akan dicari tidak boleh kurang dari 3 karakter !');
			document.siswa_cari_mutasi.nis.focus();
			return false;

		}
	}
	parent.mutasi_siswa_footer.location.href = "mutasi_siswa_footer.php?mode=text&departemen="+departemen+"&nis="+nis+"&nama="+nama;
	//parent.daftar_mutasi_siswa_footer.location.href = "../blank4.php";
}
function tampil_kelas() {
//alert ('Brubah');
	var departemen = document.siswa_cari_mutasi.departemen.value;	
	var kelas = document.siswa_cari_mutasi.kelas.value;
	if (kelas==""){
		alert ('Tidak ada kelas yang sesuai dengan departemen terpilih !');
		document.siswa_cari_mutasi.departemen.focus();
		return false;
		}
	parent.mutasi_siswa_footer.location.href = "mutasi_siswa_footer.php?mode=kelas&departemen="+departemen+"&kelas="+kelas;	
	}
/**/
</script>
</head>
	
<body>
<div id="waitBox" style="position:absolute; visibility:hidden;">
<img src="../images/movewait.gif" border="0" />&nbsp;please wait...
</div>
<table border="0" width="100%" align="center">
<!-- TABLE CENTER -->
<tr height="300">
	
	<td align="left" valign="top">
<form id="siswa_cari_mutasi" name="siswa_cari_mutasi" method="post" action="#">
  <table border="0" width="100%" cellpadding="0" cellspacing="0"  >
    <!-- TABLE TITLE -->
    
    <tr>
      <td width="38%" align="left">
        <br />
      <td width="39%" align="left" valign="middle"> </td>
      <td width="23%" rowspan="4" align="right" valign="top"><font size="4" color="#660000"><b> MUTASI
            SISWA</b></font><a href="../mutasi.php" target="content"><br />
        <font size="1" color="#000000"><b>Mutasi</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Mutasi
        Siswa</b></font></td>
    </tr>
    
    <tr>
      	<td colspan="2" align="left" valign="top"><div align="center"><strong>Departemen</strong>
      	      <select name="departemen" id="departemen"  onchange="change_departemen()"  style="width:100px">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                  </option>
              <?php } ?>
              </select>
			      	            
    	      </div>
      	  <div align="center"></div>      	</tr>
    <tr>
      <td colspan="2" align="left" valign="top">    
    </tr>
	<tr height="50">
    	<td align="left">
    	  <fieldset>
    	  <legend>Tampilkan berdasarkan NIS atau Nama</legend>    
      	
      	  <table width="100%" border="0">
            <tr>
              <td width="18%"><strong>NIS</strong></td>
              <td width="40%"><input type="text" name="nis" id="nis" 
		<?php
		if ($nis<>"")
		echo "value='$nis'";
		?>
		/></td>
              <td width="42%" rowspan="2"><a href="#" onclick="tampil()" onMouseOver="showhint('Tampilkan Daftar Siswa Berdasarkan NIS atau Nama', this, event, '120px')"><img src="../images/view.png" alt="Tampilkan Tabel" height="30" border="0" name="tabel" id="tabel2"/></a></td>
            </tr>
            <tr>
              <td><strong>Nama</strong></td>
              <td><input type="text" name="nama" id="nama" 
		<?php
		if ($nama<>"")
		echo "value='$nama'";
		?>
		/></td>
              </tr>
          </table>
    	  </fieldset>    	        
    	<td width="39%" align="left" valign="middle"><fieldset>
	    <legend>Tampilkan berdasarkan kelas</legend>
          <table width="100%" height="50" border="0">
            <tr>
              <td width="18%"><strong>Kelas</strong></td>
              <td width="44%"><div id="kelasInfo"><select name="kelas" id="kelas" onchange="change_kelas()" style="width:100px">
              <?php
              OpenDb();
				$sql_kelas = "SELECT k.replid,k.kelas FROM jbsakad.kelas k,jbsakad.tahunajaran t WHERE k.aktif=1 AND t.departemen='$departemen' AND k.idtahunajaran=t.replid ORDER BY k.replid";
				
				$result_kelas = QueryDb($sql_kelas);
				
			echo $sql_kelas;
				$kelas = "";	
				while($row_kelas=@mysqli_fetch_row($result_kelas)) {
					if ($kelas == "")
						$kelas = $row_kelas[0];
			?> 
              <option value="<?=urlencode((string) $row_kelas[0])?>" <?=StringIsSelected($row_kelas[0], $kelas) ?>>
              <?=$row_kelas[1]?>
              </option>
              <?php
				} //while
				CloseDb();
			?>
                  </select></div></td>
              <td width="38%"><a href="#" onclick="tampil_kelas()" onMouseOver="showhint('Tampilkan Daftar Siswa Berdasarkan Kelas', this, event, '120px')"><img src="../images/view.png" alt="Tampilkan Tabel" name="tabel" width="30" height="30" border="0" id="tabel2"/></a></td>
            </tr>
          </table>
	  </fieldset> </td>
	</tr>
	<tr>
	  <td align="left"> 
	  <td width="39%" align="left" valign="middle">&nbsp;</td>
	  <td align="right" valign="top">&nbsp;</td>
	  </tr>
    <tr>
    	<td align="left">    	        
    	<td width="39%" align="left" valign="middle">&nbsp;</td>
    </tr>
	</table>
</form> 

    </td>
</tr>

</table>
</body>
</html>