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
require_once('../sessionchecker.php');
require_once('expnilai.header.func.php');

OpenDb();

ReadParams();
?>
<html>
<head>
    <title>Ekspor Nilai Pelajaran</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
    <script language = "javascript" type = "text/javascript" src="../script/tools.js"></script>
    <script language = "javascript" type = "text/javascript">
<?php  require_once("expnilai.header.js.php"); ?>
    </script>
</head>
<body leftmargin="0" topmargin="0" onLoad="document.getElementById('departemen').focus()">

<form name="filter_nilai_pelajaran" method="post" action="filter_nilai_pelajaran.php">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td width="64%">

    <table width = "100%" border = "0">
    <tr>
        <td width="16%"><strong>Departemen</strong></td>
        <td width="32%">
            <select name="departemen" id="departemen" style="width:180px;" onChange="change_sel();" onKeyPress="focusNext('tingkat',event)">
                <?php $dep = getDepartemen(SI_USER_ACCESS());
                foreach($dep as $value) {
                    if ($departemen == "")
                        $departemen = $value; ?>
                    <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?>
                    </option>
                <?php } ?>
            </select>
        </td>
        <td width="18%"><strong>Tahun Ajaran</strong></td>
        <td>
            <?php  $sql = "SELECT replid, tahunajaran FROM tahunajaran WHERE departemen='$departemen' AND aktif=1 ORDER BY replid DESC";
            $result = QueryDb($sql);
            $row = @mysqli_fetch_array($result);
            $tahunajaran = $row['replid'];
            ?>
            <input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">
            <input type="text" name="tahun" id="tahun" readonly class="disabled" style="width:150px" value="<?=$row['tahunajaran']?>" />
        </td>
    </tr>

    <tr>
        <td><strong>Kelas</strong></td>
        <td>
            <select name="tingkat" id="tingkat" onChange="change_sel2()" style="width:60px;" onkeypress="return focusNext('kelas', event)">
                <?php $sql="SELECT * FROM tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
                $result=QueryDb($sql);
                while ($row=@mysqli_fetch_array($result)){
                    if ($tingkat=="")
                        $tingkat=$row['replid'];
                    ?>
                    <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
                <?php 	} ?>
            </select>

            <select name="kelas" id="kelas" onChange="change()" style="width:112px;" onKeyPress="focusNext('tabel',event)">
                <?php
                $sql="SELECT * FROM kelas WHERE idtahunajaran='$tahunajaran' AND idtingkat='$tingkat' AND aktif = 1 ORDER BY kelas";
                $result=QueryDb($sql);
                while ($row=@mysqli_fetch_array($result)){
                    if ($kelas=="")
                        $kelas=$row['replid'];
                    ?>
                    <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelas)?>><?=$row['kelas']?></option>
                <?php 	} ?>
            </select>
        </td>
        <td><strong>Semester </strong></td>
        <td>
            <?php $sql = "SELECT replid,semester FROM semester where departemen='$departemen' AND aktif = 1 ORDER BY replid DESC";
            $result = QueryDb($sql);
            $row = @mysqli_fetch_array($result);
            ?>
            <input type="text" name="sem" id="sem" class="disabled" style="width:150px" readonly value="<?=$row['semester']?>" />
            <input type="hidden" name="semester" id="semester" value="<?=$row['replid']?>">
        </td>
    </tr>
    </table>

    </td>
    <td align="left" valign="middle" width="*" rowspan="3">
        <img src="../images/ico/view.png" width="48" height="48" border="0" id="tabel" onClick="show()" style="cursor:pointer;" onMouseOver="showhint('Klik untuk menampilkan penilaian pelajaran!', this, event, '150px')">
    </td>
    <td valign="top" width="40%" align="right">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Penilaian Pelajaran</font><br />
        <a href="../penilaian.php" target="content">
            <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Penilaian Pelajaran</b></font></td>
    </td>
</tr>
</table>
</form>
</body>
</html>
<?php
CloseDb();
?>