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
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');

if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}
?>

<html>
<head>
<title>JIBAS INFOGURU [Ubah Nilai Akhir Ujian]</title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript">
    function cek_form() {

      var nilai = document.ubah_nilai_au.kode.value;

        if(nilai.length == 0) {
            alert("Nilai tidak boleh kosong");
            document.ubah_nilai_au.nilai.value = "";
            document.ubah_nilai_au.nilai.focus();
            return false;
        }
        return true;
    }
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" background-image:url(../images/bgpop.jpg); background-repeat:repeat-x">


<?php
OpenDb();

if(isset($_POST['ubah'])) {

    $query_up = "UPDATE jbsakad.nau SET nilaiAU = '".$_POST['nilai']."' WHERE replid  = '".$_POST['id']."'";
	$result_up = QueryDb($query_up) or die (mysqli_error($mysqlconnection));
		
	  if(mysqli_affected_rows($conn)> 0){
            ?>
            <script language = "javascript" type = "text/javascript">
				opener.document.location.href = "tampil_nilai_pelajaran.php?pelajaran=<?=$_POST['pelajaran'] ?>&kelas=<?=$_POST['kelas'] ?>&semester=<?=$_POST['semester'] ?>&jenis_penilaian=<?=$_POST['jenis_penilaian'] ?>&departemen=<?=$_POST['departemen'] ?>&tahun=<?=$_POST['tahun'] ?>&tingkat=<?=$_POST['tingkat'] ?>";
				window.close();
            </script>
            <?php
        }
        elseif(mysqli_affected_rows($conn)($conni) == 0){ 
            ?>
            <script language = "javascript" type = "text/javascript">
               // alert("Gagal mengubah data.");
				opener.document.location.href = "tampil_nilai_pelajaran.php?pelajaran=<?=$_POST['pelajaran'] ?>&kelas=<?=$_POST['kelas'] ?>&semester=<?=$_POST['semester'] ?>&jenis_penilaian=<?=$_POST['jenis_penilaian'] ?>&departemen=<?=$_POST['departemen'] ?>&tahun=<?=$_POST['tahun'] ?>&tingkat=<?=$_POST['tingkat'] ?>";
				window.close();
            </script>
            <?php
        }

}
$query = "SELECT * FROM jbsakad.nau WHERE replid = '".$_GET['id']."'";
$result = QueryDb($query);
$row = @mysqli_fetch_array($result);
 
?>

    <form action="ubah_nilai_au.php" method="post" name="ubah_nilai_au" onSubmit="return cek_form()">
	
    <input type="hidden" name="id" value="<?=$row['replid'] ?>">
	<input type="hidden" name="idujian" value="<?=$row['idujian'] ?>">
	<input type="hidden" name="jenis_penilaian" value="<?=$_GET['jenis_penilaian'] ?>">
	<input type="hidden" name="pelajaran" value="<?=$_GET['pelajaran'] ?>">
	<input type="hidden" name="kelas" value="<?=$_GET['kelas'] ?>">
	<input type="hidden" name="semester" value="<?=$_GET['semester'] ?>">
	<input type="hidden" name="tahun" value="<?=$_GET['tahun'] ?>">
	<input type="hidden" name="tingkat" value="<?=$_GET['tingkat'] ?>">
	<input type="hidden" name="departemen" value="<?=$departemen ?>">
	<table border="0" align="center" width="100%">
        <tr>
            <td class="header">Ubah Nilai Akhir Ujian</td>
        </tr>
		<tr>
			<td>
			<fieldset><legend><b>Data Nilai Ujian</b></legend>
			<table width="100%">
        <tr>
            <td width="19%">NIS</td>
            <td width="81%">
            <input type="hidden" name="u_nis" value="<?=$row['nis']; ?>">
            <input type="text" size="45" name="nis" value="<?=$row['nis']; ?>" readonly></td>
        </tr>
        <tr>
            <td>Nama</td><td>
			<?php
			$query_nm = "SELECT * FROM jbsakad.siswa WHERE siswa.nis = '".$row['nis']."'";
			$result_nm = QueryDb($query_nm);
			$row_nm = @mysqli_fetch_array($result_nm);
			?>
			<input type="text" size="45" name="nama" value="<?=$row_nm['nama'];?>" readonly></td>
        </tr>
       
		<tr>
			<td>Nilai Akhir</td>
			<td><input type="text" name="nilai" size="5" value="<?=$row['nilaiAU'] ?>" maxlength="8"></td>
		</tr>
		</table>
		</fieldset>		</td>
		</tr>
        <tr>
            <td>
              <div align="center">
                <input type="button" value="Batal" name="batal" class="but" onClick="window.close();">
                <input type="submit" value="Simpan" name="ubah" class="but">			
              </div></td>
          </tr>
    </table>
    </form>

<?php
CloseDb();
?>
</body>
</html>