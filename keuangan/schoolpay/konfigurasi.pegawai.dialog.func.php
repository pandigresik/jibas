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
function LoadValue()
{
    global $idPt;
    global $dept, $idTabungan, $rekKasVendor, $rekUtangVendor, $maxTransVendor, $isReadOnly;

    if ($idPt == 0)
        return;

    $sql = "SELECT * FROM jbsfina.paymenttabungan WHERE replid = $idPt";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $dept = $row['departemen'];
        $idTabungan = $row['idtabungan'];
        $rekKasVendor = $row['rekkasvendor'];
        $rekUtangVendor = $row['rekutangvendor'];
        $maxTransVendor = $row['maxtransvendor'];
    }

    $sql = "SELECT replid
              FROM jbsfina.paymenttrans
             WHERE jenis = 1
             LIMIT 1";
    $res = QueryDb($sql);
    $isReadOnly = mysqli_num_rows($res) > 0;
}

function ShowSelectDepartemen($selDept)
{
    global $dept;
    global $isReadOnly;

    $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
    $res = QueryDb($sql);

    $readOnly = $isReadOnly ? "disabled" : "";

    echo "<select id='departemen' name='departemen' style='width: 250px;' onchange='changeDept()' $readOnly>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selDept == "") $selDept = $row[0];
        $sel = $selDept == $row[0] ? "selected" : "";

        if ($sel == "selected") $dept = $selDept;

        echo "<option value='".$row[0]."' $sel>".$row[0]."</option>";
    }
    echo "</select>";
}

function ShowSelectTabunganPegawai()
{
    global $dept;
    global $idTabungan;
    global $isReadOnly;

    $sql = "SELECT replid, nama FROM jbsfina.datatabunganp WHERE departemen = '$dept' AND aktif = 1 ORDER BY nama";
    $res = QueryDb($sql);

    $readOnly = $isReadOnly ? "disabled" : "";

    echo "<select id='tabungan' name='tabungan' style='width: 250px;' $readOnly>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $idTabungan == $row[0] ? "selected" : "";
        echo "<option value='".$row[0]."' $sel>".$row[1]."</option>";
    }
    echo "</select>";
}

function ShowSelectRekVendor($kategori, $nama, $defValue)
{
    global $isReadOnly;

    $sql = "SELECT kode, nama FROM jbsfina.rekakun WHERE kategori='$kategori' ORDER BY kode";
    $res = QueryDb($sql);

    $readOnly = $isReadOnly ? "disabled" : "";

    echo "<select id='$nama' name='$nama' style='width: 250px' $readOnly>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $row[0] == $defValue ? "selected" : "";
        echo "<option value='".$row[0]."' $sel>".$row[0] $row[1]."</option>";
    }
    echo "</select>";
}

function SimpanKonfigurasiPegawai()
{
    $dept = $_REQUEST["dept"];
    $idPt = $_REQUEST["idpt"];
    $idTabungan = $_REQUEST["idtabungan"];
    $maxTrans = $_REQUEST["maxtrans"];
    $rekKasVendor = $_REQUEST["rekkas"];
    $rekUtangVendor = $_REQUEST["rekutang"];

    if ($idPt == 0)
    {
        $sql = "INSERT INTO jbsfina.paymenttabungan
                   SET departemen = '$dept', jenis = 1, idtabunganp = $idTabungan,
                       rekkasvendor = '$rekKasVendor', rekutangvendor = '$rekUtangVendor', maxtransvendor = '".$maxTrans."'";
    }
    else
    {
        $sql = "UPDATE jbsfina.paymenttabungan
                   SET departemen = '$dept', idtabunganp = $idTabungan, rekkasvendor = '$rekKasVendor', rekutangvendor = '$rekUtangVendor', maxtransvendor = '$maxTrans'
                 WHERE replid = $idPt";
    }
    QueryDb($sql);
}
?>