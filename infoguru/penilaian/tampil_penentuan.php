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
if(isset($_POST["tingkat"])){
	$tingkat = $_POST["tingkat"];
}elseif(isset($_GET["tingkat"])){
	$tingkat = $_GET["tingkat"];
}
if(isset($_POST["kelas"])){
	$kelas = $_POST["kelas"];
}elseif(isset($_GET["kelas"])){
	$kelas = $_GET["kelas"];
}
if(isset($_POST["tahun"])){
	$tahun = $_POST["tahun"];
}elseif(isset($_GET["tahun"])){
	$tahun = $_GET["tahun"];
}
if(isset($_POST["semester"])){
	$semester = $_POST["semester"];
}elseif(isset($_GET["semester"])){
	$semester = $_GET["semester"];
}
if(isset($_POST["pelajaran"])){
	$pelajaran = $_POST["pelajaran"];
}elseif(isset($_GET["pelajaran"])){
	$pelajaran = $_GET["pelajaran"];
}

$op = "";
if (isset($_GET["op"])) 
	$op = $_GET["op"];

OpenDb();
if ($op == "del") {
	$sql = "SELECT replid FROM jbsakad.infonap WHERE idpelajaran ='$pelajaran' AND idsemester = '$semester' AND idkelas = '$kelas' ";
	$result = QueryDb($sql) or die(mysqli_error($mysqlconnection));
	$num = mysqli_num_rows($result);
	
	if ($num > 0) {
		$row = mysqli_fetch_array($result);
		
		$sql = "DELETE FROM jbsakad.nap WHERE idinfo = '".$row['replid']."'";
		$result = QueryDb($sql) or die(mysqli_error($mysqlconnection));
		
		$sql = "DELETE FROM jbsakad.komennap WHERE idinfo = '".$row['replid']."'";
		$result = QueryDb($sql) or die(mysqli_error($mysqlconnection));
		
		$sql = "DELETE FROM jbsakad.infonap WHERE replid = '".$row['replid']."'";
		$result = QueryDb($sql) or die(mysqli_error($mysqlconnection));
	}
}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
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
function hapus() {
    return window.confirm("Anda yakin akan menghapus data ini?");
}

function cek() {
  	var nlulus = document.tampil_penentuan.nlulus.value;
  	if(nlulus.length == 0) {
	    alert("Nilai standard kelulusan tidak boleh kosong");
	    return false;
	}
	return true;
}

function delnap() {

	if (confirm("Apakah anda yakin akan menghapus nilai rapor pelajaran ini?")) {
		document.location.href = "tampil_penentuan.php?op=del&departemen=<?=$departemen ?>&tingkat=<?=$tingkat ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&semester=<?=$semester ?>&pelajaran=<?=$pelajaran ?>";
	}
}

function hitungulang(nis) {
	var page = "";
	page = "hitungulang.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&kelas=<?=$kelas?>&tahun=<?=$tahun?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>&nis=" + nis;
	newWindow(page, "test", 600, 220, '');
}

function refreshpage() {
	var page = "tampil_penentuan.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&kelas=<?=$kelas?>&tahun=<?=$tahun?>&semester=<?=$semester?>&pelajaran=<?=$pelajaran?>";
	document.location.href = page;
}
</script>
</head>
<body bgcolor="#FFFFFF">
<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top">
    
<?php
    
$query_p = "SELECT nama FROM jbsakad.pelajaran WHERE replid = '".$pelajaran."'";
$result_p = QueryDb($query_p);
$row_p = @mysqli_fetch_array($result_p);

$query_infonap = "SELECT replid FROM jbsakad.infonap " .
                 " WHERE infonap.idkelas = '$kelas'  ".
        		 " AND infonap.idpelajaran = '$pelajaran' ".
                 " AND infonap.idsemester = '".$semester."'";
$result_infonap = QueryDb($query_infonap);
$row_infonap = @mysqli_fetch_array($result_infonap);
$num_infonap = @mysqli_num_rows($result_infonap);

$replid_infonap = 0;
if ($num_infonap > 0) {
	$replid_infonap = $row_infonap['replid'];
}
          
//echo $query_p;
if ($replid_infonap == 0) {
	$query = "SELECT siswa.nis, siswa.nama, nau.nilaiAU, nau.idjenis ".
   	      "FROM jbsakad.siswa, jbsakad.nau ".
            "WHERE siswa.nis = nau.nis ".
            "AND nau.idkelas = '$kelas'  ".
            "AND idpelajaran = '$pelajaran' ".
            "AND idsemester = '$semester' AND siswa.aktif = '1' ORDER BY nama";
} else {
   $query = "SELECT siswa.nis, siswa.nama, nau.nilaiAU, nau.idjenis ".
   	      "FROM jbsakad.siswa, jbsakad.nau ".
            "WHERE siswa.nis = nau.nis ".
            "AND nau.idkelas = '$kelas'  ".
            "AND idpelajaran = '$pelajaran' ".
            "AND idsemester = '$semester'" .
            "AND siswa.idkelas = '$kelas'" .
            //"AND siswa.idtingkat = '$tingkat'" .
            "AND siswa.aktif = '1'".
            "AND siswa.nis IN " .
            " ( select nis from nap where nap.idinfo = '$replid_infonap' ) ORDER BY nama";
};     
$result = QueryDb($query) or die(mysqli_error($mysqlconnection));
$num = @mysqli_num_rows($result);
//echo $query;
$my_data = "";
 
while($row = @mysqli_fetch_array($result)) {
    $my_data[$row['nis']]['nama'] = $row['nama'];
    $my_data[$row['nis']][$row['idjenis']] = $row['nilaiAU'];
}

$query_cek = "SELECT replid, nilaimin FROM jbsakad.infonap ".
                "WHERE idpelajaran = '$pelajaran' ".
                "AND idsemester = '$semester' ".
                "AND idkelas = '$kelas' ";
                //"AND tingkat = '".$tingkat."'";
$result_cek = QueryDb($query_cek);
$num_cek = @mysqli_num_rows($result_cek);
$row_cek = @mysqli_fetch_array($result_cek);

$query_nhb2 = "SELECT replid, dasarpenilaian, bobot ".
            "FROM jbsakad.aturannhb WHERE idpelajaran = '$pelajaran' ".
            "AND idtingkat = '".$tingkat."'";
$result_nhb2 = QueryDb($query_nhb2) or die(mysqli_error($mysqlconnection));
$num_nhb2 = @mysqli_num_rows($result_nhb2);

$qq = "SELECT tingkat FROM jbsakad.tingkat WHERE replid = '".$tingkat."'";
$rr = QueryDb($qq);
$rw = mysqli_fetch_array($rr);

if($num_nhb2 == 0) {
  	?>
  	<script language="javascript">
  		alert("Masukkan terlebih dahulu Dasar Penilaian dan Bobot Penilaian untuk Pelajaran <?=$row_p['nama'] ?> dan Tingkat <?=$rw['tingkat'] ?>");
  	</script>
  	<?php
}else {
if($num_cek > 0) {
        include('mode_edit_penentuan.php');
}else {
    if($num == 0) {
        echo "
            <font color='red' size='2'><b>Nilai Akhir Ujian untuk pelajaran</font>
            <font color='black' size='2'>'".$row_p['nama']."'</font><font color='red' size='2'>belum ada.
            Masukkanlah terlebih dahulu nilai akhir pelajaran tersebut !</b></font>
        ";
    }else {
        $n = 0;
        foreach($my_data as $tt => $yy) {
            $n++;
        }

    ?>
    <form action="tampil_penentuan.php" method="post" name="tampil_penentuan" onSubmit="return cek()">
    <table width="95%">
        <tr><td><?echo "Jumlah data : $n ";?>
            <input type="hidden" name="pelajaran" value="<?=$pelajaran?>">
            <input type="hidden" name="semester" value="<?=$semester?>">
            <input type="hidden" name="kelas" value="<?=$kelas?>">
            <input type="hidden" name="tingkat" value="<?=$tingkat?>">
        </td></tr>
    </table>
    <table width="95%" class="tab" border="1" id="table">
    <tr>
        <td rowspan="2" class="headerlong" width="30" height="30">No</td>
        <td rowspan="2" class="headerlong" width="70" height="30">NIS</td>
        <td rowspan="2" class="headerlong" width="150" height="30">Nama</td>
        <?php
        $query_ju = "SELECT replid, jenisujian FROM jbsakad.jenisujian WHERE idpelajaran = '".$pelajaran."'";
        $result_ju = QueryDb($query_ju) or die(mysqli_error($mysqlconnection));
        $num_ju = @mysqli_num_rows($result_ju);
		
		$cnt = 0;
		while($row_ju = @mysqli_fetch_array($result_ju)) {
			$idx_ju[$cnt] = $row_ju['replid'];
			$cnt++;
		}
		$result_ju = QueryDb($query_ju) or die(mysqli_error($mysqlconnection));
        ?>
        
        <td class="headerlong" colspan="<?=$num_ju;?>" align="center">Nilai Akhir</td>
        
        <?php
        $query_nhb = "SELECT replid, dasarpenilaian, bobot ".
                     "FROM jbsakad.aturannhb WHERE idpelajaran = '$pelajaran' ".
                     "AND idtingkat = '".$tingkat."'";
        $result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
        $num_nhb = @mysqli_num_rows($result_nhb);
		
        //echo $num_nhb;
        $r = 0;
        $v = 0;
		$idpraktek = "#";
		$idkonsep = "#";
        while($row_nhb = @mysqli_fetch_array($result_nhb)) {
            $plit = explode(";", (string) $row_nhb['bobot']);
            if($plit != "") {
                foreach($plit as $pl) {
                    $r++;
                    [$ujian, $bobot] = explode(":", $pl);
                    if($bobot != "") {
						$cnt = 0;
						$found = false;
						while (($cnt < $num_ju) && !$found) {
							if ($idx_ju[$cnt] == $ujian)
								$found = true;
							else
								$cnt++;
						}
					    $as[$cnt] = $bobot;
                    }
					if ($row_nhb['dasarpenilaian'] == "Praktik") {
						$idpraktek = $idpraktek . "[" . $ujian . "]";
					} else {
						$idkonsep = $idkonsep . "[" . $ujian . "]";
					}
                }
            }
            $v++;
			$r_aturan[] = $row_nhb['replid'];
			$color = "white";
			if ($row_nhb['dasarpenilaian'] == "Praktik")
				$color = "cyan";
			else if ($row_nhb['dasarpenilaian'] == "Pemahaman Konsep")
				$color = "yellow";
				
            echo "<td class='headerlong' colspan='2' align='center'>
                <input type='hidden' name='aturan$v' value='".$row_nhb['replid']."'>
                <font size='1' color='$color'>Nilai '".$row_nhb['dasarpenilaian']."'</font></td>";
        }
        ?>
        <td rowspan="2" class="headerlong" align="center">
        <input type='hidden' name='num_nhb' value='<?=$num_nhb?>'>Predikat</td>
    </tr>
    <tr>
        <?php
        $c = 0;
		$pos = 0;
        while($row_ju = @mysqli_fetch_array($result_ju)) {
            $c++;
			$pos = (int)strpos($idpraktek, "[" . $row_ju['replid'] . "]");
			$color = "white";
			if ($pos > 0) {
				$color = "cyan";
			} else {
				$pos = (int)strpos($idkonsep, "[" . $row_ju['replid'] . "]");
				if ($pos > 0) 
					$color = "yellow";
			}
			$cnt = 0;
			$found = false;
			while ($cnt < $num_ju && !$found) {
				if ($idx_ju[$cnt] == $row_ju['replid'])
					$found = true;
				else
					$cnt++;
			}
            echo "<td class='headerlong' align='center'><font color='$color'>".$row_ju['jenisujian'].  $as[$cnt]."</font></td>";
            $kolom[$row_ju['replid']] = $row_ju['replid'];
        }

        for($i=1;$i<=$num_nhb;$i++) {
            echo "<td class='headerlong' align='center'>Angka</td><td class='header' align='center'>Huruf</td>";
        }
		
        ?>
    </tr>
    
    <?php

    if($my_data != "") {
        $i = 0;
        foreach($my_data as $ns => $d) {
            $i++;
            echo "
                <tr>
                <td align='center'>$i</td>
                <td>$ns <input type='hidden' name='nis[]' value='$ns'></td>
                <td>".$d['nama']."</td>
            ";
            $z = 0;
            foreach($kolom as $k => $v) {
                $z++;
                echo "
                    <td align='center'>".$d[$k]."</td>
                ";
            }
		if($r_aturan != 0){            
				$id_aturan = null;
				$nau_b = null;
				$ttl_bbt = null;
				$ttl_nau_b = null;
				$nilaiangka = null;
				$t = 0;
           foreach($r_aturan as $id_aturan){		
		   $t++;
				$query_nhb = "SELECT bobot ".
							 "FROM jbsakad.aturannhb WHERE aturannhb.replid = '$id_aturan'";
				$result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
				
				while($row_nhb = @mysqli_fetch_array($result_nhb)) {
					$plit = explode(";", (string) $row_nhb['bobot']);
					if($plit != "") {
						foreach($plit as $pl) {
							$r++;
							[$ujian, $bobot] = explode(":", $pl);
							if($bobot != "") {
								$as[$r] = $bobot;
							}
							$query_nau = "SELECT nau.nilaiAU FROM jbsakad.nau WHERE nis  = '$ns' ".
                                                                     "AND idjenis = '$ujian'" .
                                                                     "AND idkelas = '$kelas'  ". 
        							     "AND idpelajaran = '$pelajaran' ". 
                                                                     "AND idsemester = '".$semester."'";
							$result_nau = QueryDb($query_nau);
							$row_nau = mysqli_fetch_array($result_nau);

							$nau_b = $row_nau['nilaiAU']*$bobot;
							$ttl_bbt[$id_aturan] += $bobot;
							$ttl_nau_b[$id_aturan] += $nau_b;
							//echo "$ujian-$row_nau['NilaiAU']-$nau_b-$ttl_nau_b[$id_aturan]<br><br>";
						
                        }
                    }
                }

                $query_nap = "SELECT nap.nilaihuruf FROM jbsakad.nap WHERE nis = '$ns' ".
                            "AND idaturan = '$id_aturan'";
				$result_nap = QueryDb($query_nap);
				$row_nap = mysqli_fetch_array($result_nap);
				$nilaiangka[$id_aturan] = $ttl_nau_b[$id_aturan]/$ttl_bbt[$id_aturan];
				$f = sprintf("%01.2f", $nilaiangka[$id_aturan]);
				
                echo "
                    <td align='center'><input type='text' name='nA$i$t' value='$f' size='5'></td>
                    <td align='center'><input type='text' name='nH$i$t' value='' maxlength='2' size='5'></td>
                ";
            }
        }

            echo "
                <td align='center'><select name='predikat$i'>
                <option value='0' $selK$i></option>
                <option value='1' $selA$i>Amat Baik</option>
                <option value='2' $selB$i>Baik</option>
                <option value='3' $selC$i>Cukup</option>
                <option value='4' $selD$i>Kurang</option>
                </select>

                </td>
                </tr>
            ";
        }
    }
        
    ?>
    </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
	<input type="hidden" name="num_data" value="<?=$i ?>">
    <table width="95%" bgcolor="#a5ae0e" border="1">
        <tr><td align='left'>Nilai Standar Kelulusan : <input type="text" name="nlulus">
            <input type="submit" value="Simpan" name="simpan" class="but">
        </td></tr>
    </table>
    </form>
<?php }?>
    </td>
    </tr>
</table>
</body>
</html>
<?php
if(isset($_POST['simpan'])) {
    $query_p = "INSERT INTO jbsakad.infonap(idpelajaran, idsemester, idkelas,  nilaimin) ".
                "VALUES('".$_POST['pelajaran']."', '".$_POST['semester']."', '".$_POST['kelas']."', '".$_POST['nlulus']."')";
    $result_p = QueryDb($query_p) or die(mysqli_error($mysqlconnection));

    $query_l = "SELECT LAST_INSERT_ID(replid) As replid FROM jbsakad.infonap ORDER BY replid DESC LIMIT 1";
    $result_l = QueryDb($query_l);
    $row_l = @mysqli_fetch_array($result_l);
    $rep = $row_l['replid'];

    if(isset($_POST['nis'])) {
      $i = 0;
       foreach($_POST['nis'] as $key => $value) {
            $i++;
            $p = "predikat$i";
            $query_komen = "INSERT INTO jbsakad.komennap(nis, idinfo, predikat) ".
                           "VALUES('$value', '$rep', '$_POST[$p]')";
           $result_komen = QueryDb($query_komen) or die(mysqli_error($mysqlconnection));
		   
		   echo "$query_komen<br>";

            for($x=1;$x<=$_POST['num_nhb'];$x++) {
 			    
                $at = "aturan$x";
                echo $nang = "nA$i$x";
                $nihu ="nH$i$x";
				
                $query_nap = "INSERT INTO jbsakad.nap(nis, idaturan, idinfo, nilaiangka, nilaihuruf) ".
                            "VALUES('$value', '$_POST[$at]', '$rep', '$_POST[$nang]', '$_POST[$nihu]')";
              	$result_nap = QueryDb($query_nap) or die(mysqli_error($mysqlconnection));
		 
			   echo "$query_nap<br>";
            }
		}
        
  
    if($result_nap) {
       
        ?>
        <script languange="javascript">
            document.location.href = "tampil_penentuan.php?pelajaran=<?=$_POST['pelajaran']?>&tingkat=<?=$_POST['tingkat']?>&kelas=<?=$_POST['kelas']?>&semester=<?=$_POST['semester']?>";
        </script>
        <?php
	}
	}
	}
}
}
?>