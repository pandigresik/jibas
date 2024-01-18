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
require_once('../cek.php');
require_once('../library/dpupdate.php');

if(isset($_REQUEST["departemen"]))
	$departemen = $_REQUEST["departemen"];

if(isset($_REQUEST["tingkat"]))
	$tingkat = $_REQUEST["tingkat"];

if(isset($_REQUEST["semester"]))
	$semester = $_REQUEST["semester"];

if(isset($_REQUEST["kelas"]))
	$kelas = $_REQUEST["kelas"];

if(isset($_REQUEST["nip"]))
	$nip = $_REQUEST["nip"];

$idpelajaran = 0;
if(isset($_REQUEST["idpelajaran"]))
    $idpelajaran = $_REQUEST["idpelajaran"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<title>Menu</title>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript">

function klik(kelas,semester,idaturan)
{	
	parent.nilai_pelajaran_content.location.href="nilai_pelajaran_content.php?&kelas="+kelas+"&semester="+semester+"&idaturan="+idaturan;
}

function changePel()
{
    var idpelajaran = document.getElementById('pelajaran').value;
    var addr = "nilai_pelajaran_menu.php?departemen=<?=$departemen?>&tingkat=<?=$tingkat?>&semester=<?=$semester?>&kelas=<?=$kelas?>&nip=<?=$nip?>&idpelajaran="+idpelajaran;
    document.location.href = addr;
}

</script>
</head>
<body style="margin-left:5px; margin-top:5px; margin-right:5px; background-color: #f5f5f5">
<?php 
OpenDb();
$query_aturan = "SELECT DISTINCT aturannhb.idpelajaran, pelajaran.nama 
				   FROM jbsakad.aturannhb aturannhb, jbsakad.pelajaran pelajaran 
				  WHERE aturannhb.nipguru = '$nip' 
				    AND idpelajaran=pelajaran.replid 
				    AND pelajaran.departemen='$departemen' 
				    AND aturannhb.idtingkat='$tingkat' 
				    AND aturannhb.aktif = 1 
				  ORDER BY pelajaran.nama";

$arrpel = [];

$res = QueryDb($query_aturan);
while($row = mysqli_fetch_row($res))
{
    $arrpel[] = [$row[0], $row[1]];
}

if (count($arrpel) == 0)
{ ?>
    <table width="100%" border="0" align="center">
    <tr>
        <td align="center" valign="middle" height="300">
            <font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />Tambah aturan perhitungan nilai rapor yang akan diajar oleh guru <?=$_REQUEST['nama']?> di menu Aturan Perhitungan Nilai Rapor pada bagian Guru & Pelajaran. </b></font>
        </td>
    </tr>
    </table>
    </body>
    </html>
<?php
    CloseDb();
    exit;
}

echo "<strong>Pelajaran:</strong>";
echo "<select id='pelajaran' name='pelajaran' style='width: 185px; height: 30px; font-size: 14px; background-color: #fcffc6' onchange='changePel()'>";
for($p = 0; $p < count($arrpel); $p++)
{
    $idpel = $arrpel[$p][0];
    $nmpel = $arrpel[$p][1];

    if ($idpelajaran == 0) $idpelajaran = $idpel;
    $selected = $idpelajaran == $idpel ? "selected" : "";

    echo "<option value='$idpel' $selected>$nmpel</option>";
}
echo "</select><br><br>";

echo "<strong>Jenis Ujian:</strong>";

$query_ap = "SELECT DISTINCT a.dasarpenilaian, dp.keterangan 
               FROM jbsakad.aturannhb a, dasarpenilaian dp
              WHERE idpelajaran = '$idpelajaran' 
                AND a.dasarpenilaian = dp.dasarpenilaian
                AND dp.aktif = 1 
                AND idtingkat = '$tingkat' 
                AND nipguru = '$nip' 
              ORDER BY keterangan";
$result_ap = QueryDb($query_ap);

$cnt = 0;
while($row_ap = @mysqli_fetch_array($result_ap))
{
    $cnt++;	?>
    <table class="tab" id="table<?=$cnt?>" border="1" style="border-collapse:collapse; border-width: 1px;"
           width="100%" align="left" bordercolor="#000000" cellpadding="3">
    <tr height="22" class="header" align="left">
        <td><?=$row_ap['keterangan']?></td>
    </tr>
<?php 	$query_jp = "SELECT a.idjenisujian, j.jenisujian, j.replid, a.replid 
				   FROM jbsakad.aturannhb a, jbsakad.jenisujian j 
				  WHERE a.idpelajaran = '$idpelajaran' 
				    AND a.dasarpenilaian = '".$row_ap['dasarpenilaian']."' 
				    AND a.idjenisujian = j.replid 
				    AND a.idtingkat = '$tingkat' 
				    AND a.nipguru='$nip' 
				  ORDER BY j.jenisujian";
    $result_jp = QueryDb($query_jp);
    while($row_jp = @mysqli_fetch_row($result_jp))
    {	?>
        <tr>
        <td height="22" style="cursor:pointer" onclick="klik('<?=$kelas?>','<?=$semester?>','<?=$row_jp[3]?>')" align="left">
            <?=$row_jp[1]?>
        </td>
        </tr>
	<?php } ?>
    </table>
    <font style="font-size: 15px"><br>&nbsp;</font>
    <script language='JavaScript'>Tables('table<?=$cnt?>', 1, 0);</script>

<?php
} // while
?>
</body>
</html>
<?php CloseDb(); ?>