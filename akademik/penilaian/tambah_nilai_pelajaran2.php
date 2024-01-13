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
OpenDb();
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

if(isset($_POST['simpan'])) {
	$sql_del_nau="DELETE FROM jbsakad.nau WHERE  idpelajaran='".$_POST['idpelajaran']."' AND idkelas='".$_POST['idkelas']."' AND idsemester='".$_POST['idsemester']."' AND idjenis='".$_POST['idjenis']."'";
	$result_del_nau=QueryDb($sql_del_nau);
	$tanggaldb=unformat_tgl($_POST['tanggal']);
	$query_ujian = "INSERT INTO jbsakad.ujian(idpelajaran, idkelas, idsemester, idjenis, deskripsi, tanggal) ".
             "VALUES ('".$_POST['idpelajaran']."', '".$_POST['idkelas']."', '".$_POST['idsemester']."', '".$_POST['idjenis']."', '".CQ($_POST['deskripsi'])."','$tanggaldb')";
    $result_ujian = QueryDb($query_ujian) or die (mysqli_error($mysqlconnection));
	
	$query_id = "SELECT last_insert_id() FROM jbsakad.ujian";
	$result_id = QueryDb($query_id);
	$row_id = @mysqli_fetch_row($result_id);
	$iduj=$row_id[0];
	
	$sql = "SELECT replid FROM jbsakad.nilaiujian WHERE idujian = '".$row_id[0]."' GROUP BY nis, nilaiujian";
	$rs = QueryDb($sql);
	$ndup = mysqli_num_rows($rs);
	
	if ($ndup > 0) {
		
	?>
		<script language = "javascript" type = "text/javascript">
			alert("Data Nilai Pelajaran berhasil diinput");
			parent.opener.change_sel();
			window.close();
			//document.location.href="tampil_nilai_pelajaran.php?departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&jenis_penilaian=<?=$jenis_penilaian ?>";
		</script>
	<?php 	
		exit();
	}
	
	
	$i=1;
	while($i < ($_POST['num_data']+1)){
		$n = "nis$i";
		$sts = "status$i";
		$nuj = "nilaiujian$i";
		$ket = "keterangan$i";		
		
		OpenDbi();
		if ($_REQUEST[$nuj]==""){
		$result = mysqli_query($conni,"CALL spTambahNilaiUjianTanpaNilai('$iduj','".$_REQUEST[$n]."','".$_REQUEST[$ket]."')") or die (mysqli_error($conni));
		} else {
		$result = mysqli_query($conni,"CALL spTambahNilaiUjian('$iduj','".$_REQUEST[$n]."','".$_REQUEST[$nuj]."','".$_REQUEST[$ket]."')") or die (mysqli_error($conni));
		}
		$i++;
		}
		$query_nuj1 = "SELECT * FROM jbsakad.nilaiujian WHERE nilaiujian.idujian = '".$iduj."'";
	$result_nuj1 = QueryDb($query_nuj1) or die (mysqli_error($mysqlconnection));
	
	$t=1;
	while($row_nuj1 = @mysqli_fetch_array($result_nuj1)){
		$tota_nuj1 += $row_nuj1['nilaiujian'];
		$t++;
	}
	$ruk = $tota_nuj1/$t;	
	
	$query_ruk = "INSERT INTO jbsakad.ratauk (idkelas, idsemester, idujian, nilaiRK, keterangan) ".
	             "VALUES ('".$_POST['idkelas']."','".$_POST['idsemester']."','$iduj','$ruk','$ket_ruk')";
	$result_ruk = QueryDb($query_ruk) or die (mysqli_error($mysqlconnection));			 
	
	//echo $query_ruk;
	
    if(mysqli_affected_rows($conn)($conni) >= 0) {
            ?>
            <script language = "javascript" type = "text/javascript">
                alert("Data Nilai Pelajaran berhasil diinput");
				parent.opener.change_sel();
				window.close();
                //document.location.href="tampil_nilai_pelajaran.php?departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&jenis_penilaian=<?=$jenis_penilaian ?>";
            </script>
            <?php
        }else {
          ?>
           <script language = "javascript" type = "text/javascript">
               alert("Gagal menambah data");
               parent.opener.change_sel();
				window.close();
			   //document.location.href="tampil_nilai_pelajaran.php?departemen=<?=$departemen ?>&pelajaran=<?=$pelajaran ?>&tingkat=<?=$tingkat ?>&tahun=<?=$tahun ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&jenis_penilaian=<?=$jenis_penilaian ?>";
           </script>
           <?php
    }
		
		
	
	
}

?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<link rel="stylesheet" type="text/css" href="../style/calendar-win2k-1.css">

<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/ajax.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/validasi.js"></script>
<script language = "javascript" type = "text/javascript">
    function set_focus() {
        document.tambah_nilai_pelajaran.kodepelajaran.focus();
    }
    function cek_form() {
        var tanggal, deskripsi, jumlah, i;
		
        tanggal = document.tambah_nilai_pelajaran.tanggal.value;
        deskripsi = document.tambah_nilai_pelajaran.deskripsi.value;
		jumlah = document.tambah_nilai_pelajaran.jumlah.value;
		i=1;
		
		
		//return false;
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
		while (i<=jumlah){
			//var nilai = document.tambah_nilai_pelajaran.nilaiujian+i.value;
			var nilai = document.getElementById("nilaiujian"+i).value;
			//alert ('nilai '+nilai);
			if (nilai.length==0){
					alert ('Nilai harus diisi !');
					document.getElementById("nilaiujian"+i).focus();
					//document.tambah_nilai_pelajaran.nilaiujian+i.focus();
					return false;
					} else {
				//return	validateNumber('nilai', 'Nilai') ;
				if (isNaN(nilai)) {
					alert ('Nilai harus berupa bilangan !');
					document.getElementById("nilaiujian"+i).focus();
					//document.tambah_nilai_pelajaran.nilaiujian+i.focus();
					return false;
				}
				
			}
			i++;
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
        parent.document.location.href="lihat_daftarpelajaran.php?departemen="+departemen+"&idhead=dp";
    }
	function set_date() {
        var tanggal = document.tambah_nilai_pelajaran.tanggalhide.value;
		//var tanggal_baru = ;
		//alert ('Tanggal='+tanggal_baru);
		//document.tambah_nilai_pelajaran.tanggal.value=tanggal_baru;
	}
</script>
<script type="text/javascript" src="../script/calendar.js"></script>
<script type="text/javascript" src="../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../script/calendar-setup.js"></script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4" bgcolor="#dcdfc4" onLoad="set_focus();">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<table border="0" width="100%" height="100%" valign="middle">
<tr>
    <td align="center" valign="center" background="../images/ico/b_daftarpelajaran.png"
    style="margin:0;padding:0;background-repeat:no-repeat"width="150" height="150">

    <form action="tambah_nilai_pelajaran2.php" method="post" name="tambah_nilai_pelajaran"
    onSubmit="return cek_form()">
	
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header" colspan="2" align="center">
			<?php
			$query_jp = "SELECT * FROM jbsakad.jenisujian WHERE jenisujian.replid = '$jenis_penilaian'";
			$result_jp = QueryDb($query_jp);
			
			$row_jp = @mysqli_fetch_array($result_jp);
					
			?>
			Input Nilai <?=$row_jp['jenisujian'] ?>
			<input type="hidden" name="idjenis" value="<?=$row_jp['replid'] ?>">
			</td>
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
			<?php 
			
						
		
				$query_sis = "SELECT siswa.nis, siswa.nama FROM jbsakad.siswa ".
							 "WHERE siswa.idkelas= '$kelas' ".
                             "AND siswa.aktif = '1' ORDER BY siswa.nama";
				//echo "2nd = $query_sis<br>";		
				$result_data = QueryDb($query_sis);
				
				$num_data = @mysqli_num_rows($result_data);
		
			?>
			
			<input type="hidden" name="num_data" value="<?=$num_data ?>">
			<table width="100%">
				<tr>
					<td>Tanggal</td>
					<td>
                    <input type="text" name="tanggal" id="tanggal" size="25" readonly>
					<img src="../images/ico/calendar_1.png" alt="Tampilkan Tabel" name="tabel" width="22" height="22" border="0" id="btntanggal" onMouseOver="showhint('Buka kalendar!', this, event, '120px')"/>
					</td>
				</tr>
				<tr>
					<td>Deskripsi</td>
					<td><input type="text" name="deskripsi" size="50" ></td>
				</tr>
				<tr>
					<td colspan="3">
						<table id="table" width="100%">
							<tr>
								<td class="header" height="30">No</td>
								<td class="header" height="30">NIS</td>
								<td class="header" height="30">Nama</td>
								
								<td class="header" height="30" align="center">Nilai</td>
								<td class="header" height="30" align="center">Keterangan</td>
							</tr>
							<?php
							if ($num_data>0){
							$i = 1;
							while($row_data = @mysqli_fetch_array($result_data)){
							?>
								<tr>
									<td align="center"><?=$i ?></td>
									<td><?=$row_data['nis'] ?>
									<input type="hidden" name="nis<?=$i?>" value="<?=$row_data['nis'] ?>">
									</td>
									<td><?=$row_data['nama'] ?><input type="hidden" name="status<?=$i?>" value="0"></td>
								
									<td align="center">
									<input type="text" name="nilaiujian<?=$i?>" id="nilaiujian<?=$i?>" size="5" maxlength="7"></td>
									<td align="center">
									<input type="text" name="keterangan<?=$i?>" value="<?=$row_data['keterangan'] ?>"></td>
								</tr>								
							<?php 						
							$i++;
							}
								} else {
									?>
								<tr>
									<td align="center" colspan="5">Tidak ada siswa</td>
									</td>
								</tr>
								<?php
								}

							?><input type="hidden" name="jumlah" value="<?=(int)$i-1?>">
						</table>
					</td>
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
            <td align="center" colspan="2">
			<input type="submit" name="batal" value="Batal" class="but" onClick="window.close()" >&nbsp;
			<input type="submit" value="Simpan" name="simpan" class="but">
			</td>
        </tr>
    </table>
    </form>


</td></tr>
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
<script type="text/javascript">
  Calendar.setup(
    {
      //inputField  : "tanggalshow","tanggal"
	  inputField  : "tanggal",         // ID of the input field
      ifFormat    : "%d-%m-%Y",    // the date format
      button      : "btntanggal"       // ID of the button
    }
   );
  
</script>
</html>