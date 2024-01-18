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
    global $vendorReplid;
    global $vendorId, $vendorName, $keterangan, $terimaIuran, $kirimPesan, $valMethod;

    $sql = "SELECT * FROM jbsfina.vendor WHERE replid = $vendorReplid";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $vendorId = $row["vendorid"];
        $vendorName = $row["nama"];
        $keterangan = $row["keterangan"];
        if ($row["terimaiuran"] == 1)
            $terimaIuran = "checked";
        if ($row["kirimpesan"] == 1)
            $kirimPesan = "checked";

        // 2023-09-25
        $valMethod = $row["valmethod"];
    }
}

function SimpanVendor()
{
    $vendorReplid = $_REQUEST["vendorreplid"];
    $vendorId = SafeValue($_REQUEST["vendorid"]);
    $vendorName = SafeValue($_REQUEST["vendorname"]);
    $terimaIuran = $_REQUEST["terimaiuran"];
    $valMethod = $_REQUEST["valmethod"]; // 2023-09-25
    $kirimPesan = $_REQUEST["kirimpesan"];
    $keterangan = SafeValue($_REQUEST["keterangan"]);

    if ($vendorReplid == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.vendor 
                 WHERE vendorid = '".$vendorId."'";
        $nData = FetchSingle($sql);
        if ($nData > 0)
            return createJsonReturn(-1, "Vendor Id $vendorId sudah digunakan. Pilih vendor id yang lain");

        $sql = "INSERT INTO jbsfina.vendor 
                   SET vendorid = '$vendorId', nama = '$vendorName', keterangan = '$keterangan', 
                       terimaiuran = $terimaIuran, valmethod = $valMethod, kirimpesan = $kirimPesan, aktif = 1, tanggal = NOW()";
        QueryDb($sql);

        return createJsonReturn(1, "OK");
    }

    $sql = "SELECT COUNT(replid)
              FROM jbsfina.vendor 
             WHERE vendorid = '$vendorId'
               AND replid <> $vendorReplid";
    $nData = FetchSingle($sql);
    if ($nData > 0)
    {
        return createJsonReturn(-1, "Vendor Id $vendorId sudah digunakan. Pilih vendor id yang lain");
    }

    $sql = "UPDATE jbsfina.vendor
               SET vendorid = '$vendorId', nama = '$vendorName', keterangan = '$keterangan', 
                   terimaiuran = $terimaIuran, valmethod = $valMethod, kirimpesan = $kirimPesan
             WHERE replid = $vendorReplid";
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}

function createJsonReturn($status, $message)
{
    $ret = [$status, $message];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}

?>