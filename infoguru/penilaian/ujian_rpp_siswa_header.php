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

$departemen="";
if (isset($_REQUEST['departemen']))
	$departemen=$_REQUEST['departemen'];
$tingkat = "";
if (isset($_REQUEST['tingkat']))
	$tingkat=$_REQUEST['tingkat'];	
$kelas = "";
if (isset($_REQUEST['kelas']))
	$kelas=$_REQUEST['kelas'];	
$semester = "";
if (isset($_REQUEST['semester']))
	$semester=$_REQUEST['semester'];	
$pelajaran = "";
if (isset($_REQUEST['pelajaran']))
	$pelajaran=$_REQUEST['pelajaran'];
OpenDb();

?>

<html>
<head>
<title>Rata-rata Ujian Siswa per RPP</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript">
function change_sel(){
    var departemen = document.filter_penentuan.departemen.value;
    document.location.href="ujian_rpp_siswa_header.php?departemen="+departemen;
    parent.footer.location.href = "blank_rppsiswa.php";
}
function change_sel3() {
    parent.footer.location.href = "blank_rppsiswa.php";
}
function change_sel2() {
    var departemen = document.filter_penentuan.departemen.value;
    var tingkat = document.filter_penentuan.tingkat.value;
    var pelajaran = document.filter_penentuan.pelajaran.value;
    document.location.href="ujian_rpp_siswa_header.php?tingkat="+tingkat+"&departemen="+departemen+"&pelajaran="+pelajaran;
    parent.footer.location.href = "blank_rppsiswa.php";
}
function show(){
    var dep = document.filter_penentuan.departemen.value;
    var tingkat = document.filter_penentuan.tingkat.value;
    var pelajaran = document.filter_penentuan.pelajaran.value;
    var semester = document.filter_penentuan.semester.value;
    var kelas = document.filter_penentuan.kelas.value;


    if(dep.length == 0) {
        alert("Departemen tidak boleh kosong");
        document.filter_penentuan.departemen.value = "";
        document.filter_penentuan.departemen.focus();
        return false;
    }
    else if(tingkat.length == 0) {
        alert("Tingkat tidak boleh kosong");
        document.filter_penentuan.tingkat.value = "";
        document.filter_penentuan.tingkat.focus();
        return false;
    }
    else if(pelajaran.length == 0) {
        alert("Pelajaran tidak boleh kosong");
        document.filter_penentuan.pelajaran.value = "";
        document.filter_penentuan.pelajaran.focus();
        return false;
    }
    else if(semester.length == 0) {
        alert("Semester tidak boleh kosong");
        document.filter_penentuan.semester.value = "";
        document.filter_penentuan.semester.focus();
        return false;
    }
    else if(kelas.length == 0) {
        alert("Kelas tidak boleh kosong");
        document.filter_penentuan.kelas.value = "";
        document.filter_penentuan.kelas.focus();
        return false;
    }
	else {
        parent.footer.location.href="ujian_rpp_siswa_footer.php?departemen="+dep+"&pelajaran="+pelajaran+"&semester="+semester+"&kelas="+kelas;
    }
}
</script>
</head>
<body leftmargin="0" topmargin="0">  
<form method="post" name="filter_penentuan">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="62%">
    <table border="0">
    <tr>
        <td width="15%"><strong>Departemen</strong></td>
        <td width="38%">
            <select name="departemen" id="departemen" style="width:175px;" onChange="change_sel();">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
					foreach($dep as $value) {
						if ($departemen == "")
							$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                </option>
              <?php } ?>
              </select>			
       	</td>
        <td width="15%"><strong>Semester</strong></td>
        <td>
		<?php
        $query_s = "SELECT replid, semester, aktif FROM jbsakad.semester ".
                    "WHERE departemen = '$departemen' AND aktif = '1' ORDER BY semester ASC";
        $result_s = QueryDb($query_s);
        $row_s = @mysqli_fetch_array($result_s);
					
        ?>
            <input type="hidden" name="semester" id="semester" value="<?=$row_s['replid']?>">
            <input type="text" size="34" value="<?=$row_s['semester']?>" readonly class="disabled"></td>
    </tr>
	<tr>
        <td><strong>Kelas</strong></td>
        <td>
        	<select name="tingkat" id="tingkat" size="1" style="width:60px;" onChange="change_sel2();">
		<?php $query_t = 	"SELECT replid, tingkat FROM jbsakad.tingkat ".
                    	"WHERE departemen = '$departemen' AND aktif = '1' ORDER BY urutan ASC ";
        	$result_t = QueryDb($query_t);

			$i = 0;
			while ($row_t = @mysqli_fetch_array($result_t)) {
				if($tingkat == "") {
					$tingkat = $row_t['replid'];
					$sel[$i] = "selected";
				}
				elseif($tingkat == $row_t['replid']) {
					$sel[$i] = "selected";
				}else {
					$sel[$i] = "";
				}
				echo "
					<option value='".$row_t['replid']."' $sel[$i]>".$row_t['tingkat']."</option>
				";
				$i++;
			}
        ?>
        	</select>
            <select name="kelas" id="kelas" size="1" style="width:108px;" onChange="change_sel3()">
        <?php
            $query_k = "SELECT k.replid, k.kelas FROM jbsakad.kelas k, jbsakad.tahunajaran a WHERE k.idtingkat = '$tingkat' AND k.aktif = 1 AND k.idtahunajaran = a.replid AND a.departemen = '$departemen' AND a.aktif = 1 ORDER BY k.kelas";
			//echo $query_k;
						
            $result_k = QueryDb($query_k);

            $i = 0;
            while ($row_k = @mysqli_fetch_array($result_k)) {
                if($kelas == "") {
                    $kelas = $row_k['replid'];
                    $sel[$i] = "selected";
                }
                elseif($kelas == $row_k['replid']) {
				    $sel[$i] = "selected";
                }else {
                    $sel[$i] = "";
                }
                echo "
                    <option value='".$row_k['replid']."' $sel[$i]>".$row_k['kelas']."</option>
                ";
                $i++;
            }
            ?>
            </select>						 
        </td>
        <td><strong>Pelajaran</strong></td>
        <td>
        	<select name="pelajaran" id="pelajaran" size="1" style="width:225px;" onChange="change_sel2();">
          	<?php 	
			$query_p = "SELECT p.replid,p.nama FROM pelajaran p, guru g WHERE p.departemen = '$departemen' AND g.idpelajaran=p.replid AND g.nip='".SI_USER_ID()."' AND p.aktif=1 ORDER BY p.nama";
			//$query_p = "SELECT replid, nama FROM jbsakad.pelajaran ".
            //	        	"WHERE departemen = '$departemen' AND aktif = 1 ORDER BY nama";
       		$result_p = QueryDb($query_p);
			$i = 0;
			while ($row_p = @mysqli_fetch_array($result_p)) {
				if($pelajaran == "") {
					$pelajaran = $row_p['replid'];
					$sel[$i] = "selected";
				}
				elseif($pelajaran == $row_p['replid']) {
					$sel[$i] = "selected";
				}else {
					$sel[$i] = "";
				}
				echo "
					<option value='".$row_p['replid']."' $sel[$i]>".$row_p['nama']."</option>
				";
				$i++;
			}
        	?>
        	</select></td>
	</tr>
    </table>
    </td> 
    <td align="left" valign="middle" width="*" rowspan="2">
    	<img src="../images/ico/view.png" width="48" height="48" border="0" onClick="show()" style="cursor:pointer;" OnMouseOver="showhint('Klik untuk menampilkan rata-rata ujian kelas!', this, event, '150px')">            </td>
    <td align="right" valign="top" width="333" rowspan="2">
     <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Rata-rata RPP Setiap Siswa</font><br />
    <a href="../penilaian.php" target="framecenter"> <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Rata-rata RPP Setiap Siswa</b></font>
    </td>
</tr>	
</table>
</form>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect2 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect3 = new Spry.Widget.ValidationSelect("pelajaran");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
</script>