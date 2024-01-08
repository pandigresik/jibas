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
//include('../cek.php');
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');

if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}
if(isset($_POST["idtingkat"])){
	$tingkat = $_POST["idtingkat"];
}elseif(isset($_GET["tingkat"])){
	$tingkat = $_GET["tingkat"];
}
if(isset($_POST["idkelas"])){
	$kelas = $_POST["idkelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}
if(isset($_POST["idtahun"])){
	$tahun = $_POST["idtahun"];
}elseif(isset($_GET["tahun"])){
	$tahun = $_GET["tahun"];
}
if(isset($_POST["idsemester"])){
	$semester = $_POST["idsemester"];
}elseif(isset($_GET["semester"])){
	$semester = $_GET["semester"];
}
if(isset($_POST["idkelas"])){
	$kelas = $_POST["idkelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}
if(isset($_POST["idpelajaran"])){
	$pelajaran = $_POST["idpelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}
if(isset($_POST["idjenis"])){
	$jenis_penilaian = $_POST["idjenis"];
}elseif(isset($_GET["jenis_penilaian"])){
	$jenis_penilaian = $_GET["jenis_penilaian"];
}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript">
    function set_focus() {
        document.tambah_nilai_pelajaran.kodepelajaran.focus();
    }
    function cek_form() {
        var tanggal, deskripsi;
		
        tanggal = document.tambah_nilai_pelajaran.tanggal.value;
        deskripsi = document.tambah_nilai_pelajaran.deskripsi.value;

        if(tanggal.length == 0) {
            alert("Tanggal tidak boleh kosong");
            return false;
        }
        if(deskripsi.length == 0) {
            alert("Deskripsi tidak boleh kosong");
            document.tambah_nilai_pelajaran.deskripsi.value = "";
            document.tambah_nilai_pelajaran.deskripsi.focus();
            return false;
        }
        return true;
    }
    function change_page() {
		var departemen = document.tambah_nilai_pelajaran.departemen.value;
		var jenis_penilaian = document.tambah_nilai_pelajaran.idjenis.value;
		var semester = document.tambah_nilai_pelajaran.idsemester.value;
		var pelajaran = document.tambah_nilai_pelajaran.idpelajaran.value;
		var kelas = document.tambah_nilai_pelajaran.idkelas.value;
		var tingkat = document.tambah_nilai_pelajaran.idtingkat.value;
		var tahun = document.tambah_nilai_pelajaran.idtahun.value;
        document.location.href="tampil_nilai_pelajaran.php?departemen="+departemen+"&semester="+semester+"&jenis_penilaian="+jenis_penilaian+"&pelajaran="+pelajaran+"&kelas="+kelas+"&tingkat="+tingkat+"&tahun="+tahun;
    }
</script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
</head>
<body bgcolor="#FFFFFF" onLoad="set_focus();">
<table border="0" width="100%" height="100%" valign="middle">
<tr>
    <td align="center" valign="center" background="../images/ico/b_daftarpelajaran.png"
    style="margin:0;padding:0;background-repeat:no-repeat"width="150" height="150">

<?php
OpenDb();
if (!isset($_POST['simpan'])) {

?>

    <form action="tambah_nilai_pelajaran.php" method="post" name="tambah_nilai_pelajaran"
    onSubmit="return cek_form()">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header">
			<?php
			$query_jp = "SELECT * FROM jbsakad.jenisujian WHERE jenisujian.replid = '$jenis_penilaian'";
			$result_jp = QueryDb($query_jp);
			
			$row_jp = @mysqli_fetch_array($result_jp);
			?>
			Input Nilai <?=$row_jp['jenisujian'] ?>
			<input type="hidden" name="idjenis" value="<?=$row_jp['replid'] ?>">
			</td>
			<td class="header" align="right">Langkah 1 dari 2</td>
        </tr>
		<tr>
		<td colspan="2">
		<br>
		<fieldset><legend><b>Data Nilai Pelajaran</b></legend>
		<table border="0" width="100%">
        <tr>
            <td>Departemen</td>
            <td><input type="text" name="departemen" size="25" value="<?=$departemen;?>" readonly></td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>
			<?php
			$query_thn = "SELECT * FROM jbsakad.tahunajaran WHERE tahunajaran.replid = '".$tahun."'";
			$result_thn = QueryDb($query_thn);
			
			$row_thn = @mysqli_fetch_array($result_thn);

			?>
			<input type="hidden" name="idtahun" value="<?=$row_thn['replid'] ?>">
			<input type="text" name="tahun_ajaran" size="25" value="<?=$row_thn['tahunajaran']; ?>" readonly></td>
			<td>Semester</td>
			<td>
			<?php
			$query_smt = "SELECT * FROM jbsakad.semester WHERE semester.replid = '".$semester."'";
			$result_smt =QueryDb($query_smt);
			
			$row_smt = @mysqli_fetch_array($result_smt);
			?>
			<input type="hidden" name="idsemester" value="<?=$row_smt['replid'] ?>">
			<input type="text" name="semester" size="25" value="<?=$row_smt['semester'] ?>" readonly></td>
        </tr>
        <tr>
            <td>Tingkat</td>
			<td>
			<?php
			$query_tkt = "SELECT * FROM jbsakad.tingkat WHERE tingkat.replid = '".$tingkat."'";
			$result_tkt = QueryDb($query_tkt);
			
			$row_tkt = @mysqli_fetch_array($result_tkt);
			?>
			<input type="hidden" name="idtingkat" value="<?=$row_tkt['replid'] ?>">
			<input type="text" size="25" name="tingkat" value="<?=$row_tkt['tingkat']; ?>" readonly></td>
			<td>Kelas</td>
			<td>
			<?php
			$query_kls = "SELECT * FROM jbsakad.kelas WHERE kelas.replid = '".$kelas."'";
			$result_kls = QueryDb($query_kls);
			
			$row_kls = @mysqli_fetch_array($result_kls);
			?>
			<input type="hidden" name="idkelas" value="<?=$row_kls['replid'] ?>">
			<input type="text" name="kelas" size="25" value="<?=$row_kls['kelas'] ?>" readonly></td>
        </tr>
        <tr>
            <td>Pelajaran</td>
            <td>
			<?php
			$query_pel = "SELECT * FROM jbsakad.pelajaran WHERE pelajaran.replid = '".$pelajaran."'";
			$result_pel = QueryDb($query_pel);
			
			$row_pel = @mysqli_fetch_array($result_pel);
			?>
			<input type="hidden" name="idpelajaran" value="<?=$row_pel['replid'] ?>">
			<input type="text" name="pelajaran" size="25" value="<?=$row_pel['nama'] ?>" readonly></td>
        </tr>
		<tr>
			<td colspan="4">
			<fieldset><legend><b>Jenis Penilaian : <?=$row_jp['jenisujian'] ?></b></legend>
			<table>
				<tr>
					<td>Tanggal</td>
					<td><input type="text" name="tanggal" id="tanggal" size="25" readonly>
					<img src="../images/ico/calendar_1.png" alt="Tampilkan Tabel" name="tabel" width="22" height="22" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '120px')"/>
					</td>
				</tr>
				<tr>
					<td>Deskripsi</td>
					<td><input type="text" name="deskripsi" size="50"></td>
				</tr>
			</table>
			</fieldset>
			</td>
		</tr>
		</table>
		</fieldset>
		</td>
		</tr>
        <tr>
            <td>&nbsp;</td>
            <td align="right">
            <input type="button" value="<< Batal" name="batal" class="but" onClick="change_page();">
			<input type="submit" value="Lanjut >>" name="simpan" class="but"></td>
        </tr>
    </table>
    </form>

<?php
}
else {
    $query = "INSERT INTO jbsakad.ujian(idpelajaran, idkelas, idsemester, idjenis, deskripsi, tanggal) ".
             "VALUES ('".$_POST['idpelajaran']."', '".$_POST['idkelas']."', '".$_POST['idsemester']."', '".$_POST['idjenis']."', '".CQ($_POST['deskripsi'])."','".$_POST['tanggal']."')";
    $result = QueryDb($query) or die (mysqli_error($mysqlconnection));
		
    if(mysqli_affected_rows($conn) > 0) {
	
		$query_id = "SELECT last_insert_id() FROM jbsakad.ujian";
		$result_id = QueryDb($query_id);
		
		$row_id = @mysqli_fetch_array($result_id);
		
            ?>
            <script language = "javascript" type = "text/javascript">
              //  alert("Data Daftar Pelajaran berhasil diinput");
                document.location.href="tambah_nilai_pelajaran2.php?departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&iduj=<?=$row_id[0] ?>&jenis_penilaian=<?=$jenis_penilaian ?>";
            </script>
            <?php
        }else {
          ?>
           <script language = "javascript" type = "text/javascript">
               alert("Gagal menambah data");
               //document.location.href="tampil_daftarpelajaran.php?departemen=<?=$_POST['departemen'] ?>";
           </script>
           <?php
    }
}
CloseDb();
?>
</td>
</tr>
</table>
</body>
<script type="text/javascript">
  Calendar.setup(
    {
      //inputField  : "tanggalshow","tanggal"
	  inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%Y-%m-%d",    // the date format
      button      : "btntanggal"       // ID of the button
    }
   );
  
</script>
</html>