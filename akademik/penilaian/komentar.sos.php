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
require_once('komentar.sos.func.php');

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

function addPilihKomen(jenis)
{
    var komentar = tinyMCE.get('komentar'+jenis).getContent();
    if (komentar.trim().length == 0)
    {
        alert("Tentukan dahulu komentar nilai rapor!");
        return;
    }

    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;

    komentar = encodeURI(komentar);

    var addr = "template.komensos.add.php?idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&komentar="+komentar+"&jenis="+jenis;
    newWindow(addr, 'TambahTemplateKomenSos','500','280','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refreshListKomentarSos(jenis)
{
    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;

    $.ajax({
        url: "komentar.sos.ajax.php",
        data: "op=getlistkomentar&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&jenis="+jenis,
        type: 'get',
        success : function(html) {
            $('#divpilihkomentar'+jenis).html(html);
        }
    });
}

function pilihKomentar(jenis)
{
    var replid = document.getElementById('pilihkomentar'+jenis).value;
    if (replid == "") return;

    $.ajax({
        url: "komentar.sos.ajax.php",
        data: "op=getkomentar&replid="+replid,
        type: 'get',
        success : function(html) {
            tinyMCE.get('komentar'+jenis).setContent(html);
        }
    });
}

function editKomentar(jenis)
{
    var replid = document.getElementById('pilihkomentar'+jenis).value;
    if (replid == "") return;

    var addr = "template.komensos.edit.php?replid="+replid+"&jenis="+jenis;
    newWindow(addr, 'EditTemplateKomenSos','500','280','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function delKomentar(jenis)
{
    var replid = document.getElementById('pilihkomentar'+jenis).value;
    if (replid == "") return;

    if (!confirm("Apakah akan menghapus komentar ini?"))
        return;

    var idpelajaran = document.getElementById('idpelajaran').value;
    var idtingkat = document.getElementById('idtingkat').value;

    $.ajax({
        url: "komentar.sos.ajax.php",
        data: "op=delkomentar&replid="+replid+"&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&jenis="+jenis,
        type: 'get',
        success : function(html) {
            $('#divpilihkomentar'+jenis).html(html);
        }
    });
}
</script>
</HEAD>

<BODY>
<?php
ShowUserInfo();

echo "<br>";
echo "<form name='main' method='post' onsubmit='validate()' action='komentar.sos.php'>";
echo "<input type='hidden' id='departemen' name='departemen' value='$departemen'>";
echo "<input type='hidden' id='semester' name='semester' value='$semester'>";
echo "<input type='hidden' id='tingkat' name='tingkat' value='$tingkat'>";
echo "<input type='hidden' id='tahunajaran' name='tahunajaran' value='$tahunajaran'>";
echo "<input type='hidden' id='pelajaran' name='pelajaran' value='$pelajaran'>";
echo "<input type='hidden' id='kelas' name='kelas' value='$kelas'>";
echo "<input type='hidden' id='nis' name='nis' value='$nis'>";
echo "<input type='hidden' id='idpelajaran' name='idpelajaran' value='$pelajaran'>";
echo "<input type='hidden' id='idtingkat' name='idtingkat' value='$tingkat'>";

$arrjenis = ["SPI", "SOS"];
$arrnmjenis = ["Spiritual", "Sosial"];
$counter = count($arrjenis);

for($i = 0; $i < $counter; $i++)
{
    $jenis = $arrjenis[$i];
    $lwjenis = strtolower($jenis);
    $nmjenis = $arrnmjenis[$i];

    $predikat = 3;
    $komentar = "";
    $idkomenrapor = 0;

    $sql = "SELECT replid, predikat, komentar
              FROM jbsakad.komenrapor
             WHERE nis = '$nis'
               AND idsemester = '$semester'
               AND idkelas = '$kelas'
               AND jenis = '".$jenis."'";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idkomenrapor = $row[0];
        $predikat = $row[1];
        $komentar = $row[2];
    }

    echo "<fieldset><legend><strong>Sikap $nmjenis</strong></legend>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; border-width: 1px;'>";
    echo "<tr style='height: 150px;'>";
    echo "<td width='160' valign='top' style='background-color: #e5f6ff'><strong>Predikat:</strong><br>";
    echo "<select name='predikat$lwjenis' id='predikat$lwjenis'>";
    $sel = $predikat == 4 ? "selected" : "";
    echo "<option value='4' $sel>Istimewa</option>";
    $sel = $predikat == 3 ? "selected" : "";
    echo "<option value='3' $sel>Baik</option>";
    $sel = $predikat == 2 ? "selected" : "";
    echo "<option value='2' $sel>Cukup</option>";
    $sel = $predikat == 1 ? "selected" : "";
    echo "<option value='1' $sel>Kurang</option>";
    $sel = $predikat == 0 ? "selected" : "";
    echo "<option value='0' $sel>Buruk</option>";
    echo "</select>";
    echo "</td>";
    echo "<td width='400'>";
    echo "<input type='hidden' name='idkomen$lwjenis' id='idkomen$lwjenis' value='$idkomenrapor'>";
    echo "<textarea name='komentar$lwjenis' id='komentar$lwjenis' style='width:100%; height:150px;'>$komentar</textarea><br>";
    echo "<div align='right'>";
    echo "<a onclick=\"addPilihKomen('$lwjenis')\" onmouseover='' style='color: blue; cursor: pointer; font-weight: normal; font-style: italic;'><img src='../images/ico/tambah.png'> simpan komentar sebagai template</a>";
    echo "</div>";
    echo "</td>";
    echo "<td width='400' rowspan='3' valign='top'>";
    echo "<strong>Pilih Komentar dari Template: </strong><br>";
    echo "<table border='0'><tr><td>";
    ShowListKomentar($pelajaran, $tingkat, $lwjenis);
    echo "</td><td>";
    echo "<input type='button' class='but' value='pilih' onclick=\"pilihKomentar('$lwjenis')\">&nbsp;&nbsp;";
    echo "<a onclick=\"editKomentar('$lwjenis')\"><img src='../images/ico/ubah.png'></a>&nbsp;";
    echo "<a onclick=\"delKomentar('$lwjenis')\"><img src='../images/ico/hapus.png'></a>";
    echo "</td></tr></table>";
    echo "</td>";
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
