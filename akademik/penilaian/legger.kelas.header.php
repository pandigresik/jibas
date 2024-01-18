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
require_once('../cek.php');

$departemen = $_REQUEST['departemen'] ?? "";
$tahunajaran = isset($_REQUEST['tahunajaran']) ? (int)$_REQUEST['tahunajaran'] : 0;
$tingkat = isset($_REQUEST['tingkat']) ? (int)$_REQUEST['tingkat'] : 0;
$kelas = isset($_REQUEST['kelas']) ? (int)$_REQUEST['kelas'] : 0;
$semester = isset($_REQUEST['semester']) ? (int)$_REQUEST['semester'] : 0;

OpenDb();
?>

<html>
<head>
    <title>Legger Rapor</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.css">
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language = "javascript" type = "text/javascript" src="legger.kelas.header.js"></script>
</head>
<body topmargin="0" leftmargin="0" onLoad="document.getElementById('departemen').focus()">
<form name="main" method="post" action="filter_penentuan.php">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="64%">

            <table border="0" width="100%">
            <tr>
                <td width="16%"><strong>Departemen</strong></td>
                <td width="32%">
                    <select name="departemen" id="departemen" style="width:180px;" onChange="change_dept()" onkeypress="return focusNext('tingkat', event)">
<?php                 $dep = getDepartemen(SI_USER_ACCESS());
                        foreach($dep as $value) {
                            if ($departemen == "")
                                $departemen = $value; ?>
                            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?>
                            </option>
<?php                     } ?>
                    </select>
                </td>
                <td width="18%"><strong>Tahun Ajaran</strong></td>
                <td>
                    <select name="tahunajaran" id="tahunajaran" style="width:180px;" onchange="change_ta()">
<?php  			    $sql = "SELECT replid, tahunajaran, aktif
                              FROM tahunajaran
                             WHERE departemen = '$departemen'
                             ORDER BY replid DESC";
                    $result = QueryDb($sql);
                    $useactive = ($tahunajaran == 0) ? true : false;
                    while($row = @mysqli_fetch_array($result))
                    {
                        $act = ($row['aktif'] == 1) ? "(A)" : "";

                        $sel = "";
                        if ($useactive)
                        {
                            if ($row['aktif'] == 1)
                            {
                                $tahunajaran = $row['replid'];
                                $sel = "selected";
                            }
                        }
                        else
                        {
                            $sel = ($tahunajaran == $row['replid']) ? "selected" : "";
                        }
                            ?>
                            <option value='<?=$row['replid']?>' <?=$sel?>><?=$row['tahunajaran'] . " $act"?></option>
<?php                  } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>
                    <select name="tingkat" id="tingkat" onChange="change_tingkat()" style="width:60px;" onkeypress="return focusNext('kelas', event)">
<?php                      $sql = "SELECT * 
                                  FROM tingkat 
                                 WHERE departemen='$departemen' 
                                   AND aktif = 1 
                                 ORDER BY urutan";
                        $result = QueryDb($sql);
                        while ($row = @mysqli_fetch_array($result))
                        {
                            if ($tingkat == 0)
                                $tingkat = $row['replid']; ?>
                            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
<?php 	                    } ?>
                    </select>
                    <select name="kelas" id="kelas" onChange="change_kelas()" style="width:112px;" onkeypress="return focusNext('tabel', event)">
<?php                      $sql ="SELECT * 
                                 FROM kelas 
                                WHERE idtahunajaran='$tahunajaran' 
                                  AND idtingkat='$tingkat' 
                                  AND aktif = 1 
                                ORDER BY kelas";
                        $result=QueryDb($sql);
                        while ($row = @mysqli_fetch_array($result))
                        {
                            if ($kelas == 0)
                                $kelas = $row['replid']; ?>
                            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelas)?>><?=$row['kelas']?></option>
<?php 	                    } ?>
                    </select>
                </td>
                <td><strong>Semester </strong></td>
                <td>
                    <select name="semester" id="semester" onchange="change_semester()">
<?php                      $sql = "SELECT replid, semester, aktif
                                  FROM semester
                                 WHERE departemen = '$departemen'
                                 ORDER BY replid DESC";
                        $result = QueryDb($sql);
                        $useactive = ($semester == 0) ? true : false;
                        while($row = @mysqli_fetch_array($result))
                        {
                            $act = ($row["aktif"] == 1) ? "(A)" : "";
                            $sel = "";
                            if ($useactive)
                            {
                                if ($row["aktif"] == 1)
                                {
                                    $semester = $row["replid"];
                                    $sel = "selected";
                                }
                            }
                            else
                            {
                                $sel = ($semester == $row["replid"]) ? "selected" : "";
                            }
                            ?>
                            <option value="<?=$row['replid']?>" <?=$sel?>><?=$row['semester'] . " $act"?></option>
<?php 	                } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Pelajaran</strong></td>
                <td colspan="3" align="left">
                    <select name="pelajaran" id="pelajaran" onchange="change_pel()"	style="width:240px">
                        <option value="0">(Semua Pelajaran)</option>
<?php                      $sql = "SELECT DISTINCT u.idpelajaran, p.nama
                                  FROM ujian u, pelajaran p
                                 WHERE u.idpelajaran = p.replid
                                   AND u.idkelas = '$kelas'
                                   AND u.idsemester = '".$semester."'";
                        $result = QueryDb($sql);
                        while($row = mysqli_fetch_array($result))
                        { ?>
                            <option value="<?=$row['idpelajaran']?>"><?=$row['nama']?></option>
<?php 	                } ?>
                    </select>
                </td>
            </tr>
            </table>

        </td>
        <td align="left" valign="middle" width="*" rowspan="3">
            <img src="../images/view.png" width="48" height="48" id="tabel" border="0" onClick="show()" style="cursor:pointer;" onMouseOver="showhint('Klik untuk menampilkan penentuan nilai rapor!', this, event, '150px')">            </td>
        </td>
        <td align="right" valign="top" width="40%" rowspan="3">
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Legger Nilai Rapor Per Kelas</font><br />
            <a href="../penilaian.php" target="content">
                <font size="1" color="#000000"><b>Penilaian</b></font></a>&nbsp>&nbsp
            <font size="1" color="#000000"><b>Legger Nilai Rapor Per Kelas</b></font>
        </td>
    </tr>
    </table>
</form>
</body>
<?php CloseDb(); ?>
</html>
<script language="javascript">
    var spryselect1 = new Spry.Widget.ValidationSelect("departemen");
    var spryselect3 = new Spry.Widget.ValidationSelect("tingkat");
    var spryselect4 = new Spry.Widget.ValidationSelect("kelas");
    var spryselect5 = new Spry.Widget.ValidationSelect("nip");
</script>