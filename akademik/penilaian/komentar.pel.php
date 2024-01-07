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
require_once('komentar.pel.func.php');

OpenDb();

ReadParams();

SimpanData();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Komentar Nilai Rapor</TITLE>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" type="text/javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="../script/tools.js"></script>
<script language="javascript" type="text/javascript" src="../script/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
    mode : "textareas",
    theme : "simple",
});

function validate()
{
    return true;
}

function addPilihKomen(no)
{
    //var komentar = document.getElementById('komentar'+no).value;
    var komentar = tinyMCE.get('komentar'+no).getContent();

    if (komentar.trim().length == 0)
    {
        alert("Tentukan dahulu komentar nilai rapor!");
        return;
    }

    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;
    var kdaspek = document.getElementById('kdaspek'+no).value;

    komentar = encodeURI(komentar);

    var addr = "template.komenpel.add.php?idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&kdaspek="+kdaspek+"&komentar="+komentar+"&no="+no;
    newWindow(addr, 'TambahTemplateKomenPel','500','280','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refreshListKomentar(no)
{
    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;
    var kdaspek = document.getElementById('kdaspek'+no).value;

    $.ajax({
        url: "komentar.pel.ajax.php",
        data: "op=getlistkomentar&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&kdaspek="+kdaspek+"&no="+no,
        type: 'get',
        success : function(html) {
            $('#divpilihkomentar'+no).html(html);
        }
    });
}

function pilihKomentar(no)
{
    var na = document.getElementById('na'+no).value;
    if (na.trim().length == 0)
    {
        alert("Tidak dapat memilih komentar karena nilai rapor belum ditentukan!")
        return;
    }

    var replid = document.getElementById('pilihkomentar'+no).value;
    if (replid == "") return;

    $.ajax({
        url: "komentar.pel.ajax.php",
        data: "op=getkomentar&replid="+replid,
        type: 'get',
        success : function(html) {
            document.getElementById('komentar'+no).value = html;
            tinyMCE.get('komentar'+no).setContent(html);
        }
    });
}

function editKomentar(no)
{
    var replid = document.getElementById('pilihkomentar'+no).value;
    if (replid == "") return;

    var addr = "template.komenpel.edit.php?replid="+replid+"&no="+no;
    newWindow(addr, 'EditTemplateKomenPel','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function delKomentar(no)
{
    var replid = document.getElementById('pilihkomentar'+no).value;
    if (replid == "") return;

    if (!confirm("Apakah akan menghapus komentar ini?"))
        return;

    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;
    var kdaspek = document.getElementById('kdaspek'+no).value;

    $.ajax({
        url: "komentar.pel.ajax.php",
        data: "op=delkomentar&replid="+replid+"&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&kdaspek="+kdaspek+"&no="+no,
        type: 'get',
        success : function(html) {
            $('#divpilihkomentar'+no).html(html);
        }
    });
}
</script>
</HEAD>

<BODY>
<?php
ShowUserInfo();

echo "<br>";

$sql = "SELECT k.komentar, k.replid, k.predikat
  	      FROM jbsakad.komennap k, jbsakad.infonap i 
		 WHERE k.nis = '$nis' 
		   AND i.replid = k.idinfo 
		   AND i.idpelajaran = '$pelajaran' 
		   AND i.idsemester = '$semester' 
		   AND i.idkelas='$kelas'";
$res = QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $komentar = $row[0];
    $idkomennap = $row[1];
    $predikat = $row[2];
}


$sql = "SELECT nilaimin 
 		  FROM infonap
		 WHERE idpelajaran = $pelajaran
		   AND idsemester = $semester
		   AND idkelas = $kelas";
$res = QueryDb($sql);
$row = mysqli_fetch_row($res);
$nilaimin = $row[0];

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
    	  FROM infonap i, nap n, aturannhb a, dasarpenilaian d
		 WHERE i.replid = n.idinfo 
		   AND n.nis = '$nis' 
		   AND i.idsemester = '$semester' 
		   AND i.idkelas = '$kelas'
		   AND n.idaturan = a.replid 	   
		   AND a.dasarpenilaian = d.dasarpenilaian
		   AND d.aktif = 1";
$res = QueryDb($sql);
$i = 0;
while($row = mysqli_fetch_row($res))
{
    $aspekarr[$i++] = [$row[0], $row[1]];
}

$naspek = count($aspekarr);
echo "<form name='main' method='post' onsubmit='validate()' action='komentar.pel.php'>";
echo "<input type='hidden' id='naspek' name='naspek' value='$naspek'>";
echo "<input type='hidden' id='idkomennap' name='idkomennap' value='$idkomennap'>";
echo "<input type='hidden' id='departemen' name='departemen' value='$departemen'>";
echo "<input type='hidden' id='semester' name='semester' value='$semester'>";
echo "<input type='hidden' id='tingkat' name='tingkat' value='$tingkat'>";
echo "<input type='hidden' id='tahunajaran' name='tahunajaran' value='$tahunajaran'>";
echo "<input type='hidden' id='pelajaran' name='pelajaran' value='$pelajaran'>";
echo "<input type='hidden' id='kelas' name='kelas' value='$kelas'>";
echo "<input type='hidden' id='nis' name='nis' value='$nis'>";
echo "<input type='hidden' id='idpelajaran' name='idpelajaran' value='$pelajaran'>";
echo "<input type='hidden' id='idtingkat' name='idtingkat' value='$tingkat'>";
$counter = count($aspekarr);
for($i = 0; $i < $counter; $i++)
{
    $kdaspek = $aspekarr[$i][0];
    $nmaspek = strtoupper((string) $aspekarr[$i][1]);

    $sql = "SELECT n.nilaiangka, n.nilaihuruf, n.replid, n.komentar
			  FROM infonap i, nap n, aturannhb a 
			 WHERE i.replid = n.idinfo 
			   AND n.idaturan = a.replid 
			   AND n.nis = '$nis' 
			   AND i.idpelajaran = '$pelajaran' 
			   AND i.idsemester = '$semester' 
			   AND i.idkelas = '$kelas'	   
			   AND a.dasarpenilaian = '".$kdaspek."'";
    $res = QueryDb($sql);
    $nilaiExist = false;
    $na = "";
    $nh = "";
    $idnap = 0;
    $nkomentar = "";
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $na = $row[0];
        $nh = $row[1];
        $idnap = $row[2];
        $nkomentar = $row[3];
        $nilaiExist = true;
    }

    echo "<fieldset><legend><strong>Komentar $nmaspek</strong></legend>";
    echo "<input type='hidden' id='kdaspek$i' name='kdaspek$i' value='$kdaspek'>";
    echo "<input type='hidden' id='idnap$i' name='idnap$i' value='$idnap'>";
    echo "<input type='hidden' id='na$i' name='na$i' value='$na'>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; border-width: 1px;'>";
    echo "<tr>";
    echo "<td width='100' style='background-color: #e5f6ff'>Nilai Angka</td>";
    echo "<td width='50' style='font-size: 12px;' align='center'><strong>$na</strong></td>";
    echo "<td width='400' rowspan='3'>";
    echo "<textarea name='komentar$i' id='komentar$i' style='width:100%; height:100px;'>$nkomentar</textarea><br>";
    echo "<div align='right'>";
    echo "<a onclick='addPilihKomen($i)' onmouseover='' style='color: blue; cursor: pointer; font-weight: normal; font-style: italic;'><img src='../images/ico/tambah.png'> simpan komentar sebagai template</a>";
    echo "</div>";
	echo "</td>";
    echo "<td width='400' rowspan='3' valign='top'>";
    echo "<strong>Pilih Komentar dari Template: </strong><br>";
    echo "<table border='0'><tr><td>";
    ShowListKomentar($pelajaran, $tingkat, $kdaspek);
    echo "</td><td>";
    echo "<input type='button' class='but' value='pilih' onclick='pilihKomentar($i)'>&nbsp;&nbsp;";
    echo "<a onclick='editKomentar($i)'><img src='../images/ico/ubah.png'></a>&nbsp;";
    echo "<a onclick='delKomentar($i)'><img src='../images/ico/hapus.png'></a>";
    echo "</td></tr></table>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td style='background-color: #e5f6ff'>Nilai Huruf</td>";
    echo "<td style='font-size: 12px;' align='center'><strong>$nh</strong></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td style='background-color: #e5f6ff'>Nilai KKM</td>";
    echo "<td style='font-size: 12px' align='center'><strong>$nilaimin</strong></td>";
    echo "</tr>";
    echo "</table>";
    echo "</fieldset><br>";
}

echo "<input type='submit' class='but' name='Simpan' id='Simpan' style='width: 140px; height: 40px' value='Simpan'>";
echo "</form>";
?>
</BODY>
</HTML>
<?php
CloseDb();
?>
