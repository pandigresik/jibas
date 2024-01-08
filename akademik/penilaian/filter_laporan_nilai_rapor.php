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
if(isset($_POST["tahunajaran"])){
	$tahunajaran = $_POST["tahunajaran"];
}elseif(isset($_GET["tahunajaran"])){
	$tahunajaran = $_GET["tahunajaran"];
}
if(isset($_POST["semester"])){
	$semester = $_POST["semester"];
}elseif(isset($_GET["semester"])){
	$semester = $_GET["semester"];
}
?>

<html>
<head>
<title>Laporan Akhir Rapor Siswa</title>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript">
function change_sel(){
    var departemen = document.filter_laporan_rapor.departemen.value;
    document.location.href="filter_laporan_nilai_rapor.php?departemen="+departemen;
    parent.footer.location.href = "blank_laporan_rapor.php";
}
function change_sel2() {
    var departemen = document.filter_laporan_rapor.departemen.value;
    var tingkat = document.filter_laporan_rapor.tingkat.value;
    document.location.href="filter_laporan_nilai_rapor.php?tingkat="+tingkat+"&departemen="+departemen;
    parent.footer.location.href = "blank_laporan_rapor.php";
}
function change_sel3() {
    parent.footer.location.href = "blank_nilai_pelajaran.php";
}
function change_sel4() {
    parent.footer.location.href = "blank_nilai_pelajaran.php";
}
function change_sel5() {
    parent.footer.location.href = "blank_nilai_pelajaran.php";
}

function show()
{
	var pelajaran = document.filter_laporan_rapor.pelajaran.checked;
	var harian = document.filter_laporan_rapor.harian.checked;
    var departemen = document.filter_laporan_rapor.departemen.value;
    var tingkat = document.filter_laporan_rapor.tingkat.value;
    var tahun = document.filter_laporan_rapor.idtahun.value;
    var semester = document.filter_laporan_rapor.semester.value;
    var kelas = document.filter_laporan_rapor.kelas.value;
    var dd1 = document.filter_laporan_rapor.dd1.value;
    var mm1 = document.filter_laporan_rapor.mm1.value;
    var yy1 = document.filter_laporan_rapor.yy1.value;
    var dd2 = document.filter_laporan_rapor.dd2.value;
    var mm2 = document.filter_laporan_rapor.mm2.value;
    var yy2 = document.filter_laporan_rapor.yy2.value;

    if(departemen.length === 0) {
        alert("Departemen tidak boleh kosong");
        document.filter_laporan_rapor.departemen.value = "";
        document.filter_laporan_rapor.departemen.focus();
        return false;
    }
    else if(tingkat.length === 0) {
        alert("Tingkat tidak boleh kosong");
        document.filter_laporan_rapor.tingkat.value = "";
        document.filter_laporan_rapor.tingkat.focus();
        return false;
    }
    else if(tahun.length === 0) {
        alert("Tahun Ajaran tidak boleh kosong");
        document.filter_laporan_rapor.tahun.value = "";
        document.filter_laporan_rapor.tahun.focus();
        return false;
    }
    else if(semester.length === 0) {
        alert("Semester tidak boleh kosong");
        document.filter_laporan_rapor.semester.value = "";
        document.filter_laporan_rapor.semester.focus();
        return false;
    }
    else if(kelas.length === 0) {
        alert("Kelas tidak boleh kosong");
        document.filter_laporan_rapor.kelas.value = "";
        document.filter_laporan_rapor.kelas.focus();
        return false;
    }
	else if(dd1.length === 0) {
        alert("Tanggal tidak boleh kosong");
        document.filter_laporan_rapor.dd1.value = "";
        document.filter_laporan_rapor.dd1.focus();
        return false;
    }
    else if(mm1.length === 0) {
        alert("Bulan tidak boleh kosong");
        document.filter_laporan_rapor.mm1.value = "";
        document.filter_laporan_rapor.mm1.focus();
        return false;
    }
    else if(yy1.length === 0) {
        alert("Tahun tidak boleh kosong");
        document.filter_laporan_rapor.yy1.value = "";
        document.filter_laporan_rapor.yy1.focus();
        return false;
    }
    else if(dd2.length === 0) {
        alert("Tanggal tidak boleh kosong");
        document.filter_laporan_rapor.dd2.value = "";
        document.filter_laporan_rapor.dd2.focus();
        return false;
    }
    else if(mm2.length === 0) {
        alert("Bulan tidak boleh kosong");
        document.filter_laporan_rapor.mm2.value = "";
        document.filter_laporan_rapor.mm2.focus();
        return false;
    }
    else if(yy2.length === 0) {
        alert("Tahun tidak boleh kosong");
        document.filter_laporan_rapor.yy2.value = "";
        document.filter_laporan_rapor.yy2.focus();
        return false;
    }
    else
    {
	    var tglmulai = yy1 + "-" + mm1 + "-" + dd1;
        var tglakhir = yy2 + "-" + mm2 + "-" + dd2;

        parent.footer.location.href = "laporan_nilai_rapor_footer.php?departemen="+departemen+"&tingkat="+tingkat+"&tahunajaran="+tahun+"&semester="+semester+"&kelas="+kelas+"&harian="+harian+"&pelajaran="+pelajaran+"&tglmulai="+tglmulai+"&tglakhir="+tglakhir;
    }
}
</script>
</head>
<body class="filter" topmargin="0" leftmargin="0">
<?php
OpenDb();
if (!isset($_POST['lihat'])) {
?>
<form action="filter_laporan_nilai_rapor.php" method="post" name="filter_laporan_rapor"
    target="footer">
    <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="120"><strong>Departemen</strong></td>
            <td width="200">
            <select name="departemen" id="departemen"  style="width:150px;" onChange="change_sel();">
              <?php $dep = getDepartemen(SI_USER_ACCESS());    
	foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
                <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> > 
                  <?=$value ?> 
                  </option>
              <?php } ?>
              </select>			</td>
			
            <td width="120"><strong>Semester</strong></td>
            <td width="280">
<?php

            $query_s = "SELECT replid, semester FROM jbsakad.semester ".
                        "WHERE departemen = '$departemen' AND aktif = '1' ORDER BY semester ASC";
            $result_s = QueryDb($query_s);

            $row_s = @mysqli_fetch_array($result_s);
                
            ?>
            <input type="hidden" name="semester" value="<?=$row_s['replid']?>">
			<input type="text" size="21" value="<?=$row_s['semester']?>" readonly class="disabled">			</td>
            <td align="left" valign="middle" width="72" rowspan="4"><img src="../images/view.png" width="48" height="48" border="0" onClick="show()" style="cursor:pointer;"></td>
          <td align="right" valign="top" width="*" rowspan="4">
          <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Nilai Rapor Siswa</font><br />
            <a href="../penilaian.php?flag=1" target="content"> <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp <font size="1" color="#000000"><b>Nilai Rapor Siswa</b></font>          </td>
        </tr>
        <tr>
            <td><strong>Tingkat</strong></td>
            <td><select name="tingkat" id="tingkat" size="1" style="width:150px;" onChange="change_sel2();">
<?php

            $query_t = "SELECT replid, tingkat FROM jbsakad.tingkat ".
                        "WHERE departemen = '$departemen' AND aktif = '1' ORDER BY urutan ASC";
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
            </select>			</td>
			
			<td><strong>Kelas</strong></td>
            <td><select name="kelas" id="kelas" size="1" style="width:150px;" onChange="change_sel4()">
            <?php
            $query_th = "SELECT replid, tahunajaran FROM jbsakad.tahunajaran ".
                        "WHERE departemen = '$departemen' AND aktif = '1' ";
            $result_th = QueryDb($query_th);
            $row_th = @mysqli_fetch_array($result_th);
            $tahun = $row_th['tahunajaran'];
            $replid = $row_th['replid'];

            $query_k = "SELECT replid, kelas FROM jbsakad.kelas ".
                        "WHERE idtingkat = '$tingkat' ".
                        "AND idtahunajaran = '$replid' ORDER BY replid";
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
            </select>		</td>
        </tr>
        <tr>
            <td><strong>Tahun Ajaran</strong></td>
            <td>
                <input type="hidden" name="idtahun" value="<?=$replid;?>">
                <input type="text" name="tahun" size="22" value="<?=$tahun;?>" readonly class="disabled">            </td>
            <td><strong>Presensi</strong></td>
			<td><input type="checkbox" name="harian" id="harian" >			  Harian&nbsp;			  <input type="checkbox" name="pelajaran" id="pelajaran" >Pelajaran</td>
          </tr>
        <tr>
            <td><strong>Tanggal Presensi</strong></td>
            <td colspan="3">
<?php
            $sql = "SELECT YEAR(tglmulai) AS yy1, MONTH(tglmulai) AS mm1, DAY(tglmulai) as dd1,
                           YEAR(tglakhir) AS yy2, MONTH(tglakhir) AS mm2, DAY(tglakhir) as dd2
                      FROM jbsakad.tahunajaran 
                     WHERE replid='$replid'";
            $res = QueryDb($sql);
            $row = @mysqli_fetch_array($res);
            $yy1 = $row[0];
            $mm1 = $row[1];
            $dd1 = $row[2];
            $yy2 = $row[3];
            $mm2 = $row[4];
            $dd2 = $row[5];

            echo CreateSelect("dd1", 1, 31, $dd1, 50); echo "-";
            echo CreateSelect("mm1", 1, 12, $mm1, 50); echo "-";
            echo CreateSelect("yy1", $yy1, $yy2, $yy1, 80); echo " s/d ";
            echo CreateSelect("dd2", 1, 31, $dd2, 50); echo "-";
            echo CreateSelect("mm2", 1, 12, $mm2, 50); echo "-";
            echo CreateSelect("yy2", $yy1, $yy2, $yy2, 80);
?>
            </td>
        </tr>
    </table>
    </form>

<?php
}
CloseDb();

function CreateSelect($name, $min, $max, $value, $width)
{
    $select = "<select name='$name' id='$name' style='width: $width px' onChange='change_sel4()'>";
    for($i = $min; $i <= $max; $i++)
    {
        $sel = $i == $value ? "selected" : "";
        $select .= "<option value='$i' $sel>$i</option>";
    }
    $select .= "</select>";

    return $select;
}
?>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
	var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
</script>