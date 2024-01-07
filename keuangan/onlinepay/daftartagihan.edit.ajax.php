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
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/errorhandler.php');

$op = $_REQUEST["op"];
if ($op == "439278934234")
{
    OpenDb();
    try
    {
        BeginTrans();

        $idTagihanData = $_REQUEST["idtagihandata"];
        $noTagihan = $_REQUEST["notagihan"];
        $jTagihan = $_REQUEST["jtagihan"];
        $jDiskon = $_REQUEST["jdiskon"];

        $sql = "UPDATE jbsfina.tagihansiswadata 
                   SET jtagihan = $jTagihan, jdiskon = $jDiskon, issync = 0
                 WHERE replid = $idTagihanData";
        QueryDbEx($sql);

        $jumlah = 0;
        $sql = "SELECT SUM(jtagihan - jdiskon) 
                  FROM jbsfina.tagihansiswadata 
                 WHERE notagihan = '".$noTagihan."'";
        $res = QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
            $jumlah = $row[0];

        $sql = "UPDATE jbsfina.tagihansiswainfo
                   SET jumlah = $jumlah
                 WHERE notagihan = '".$noTagihan."'";
        QueryDbEx($sql);

        CommitTrans();

        echo "[1,\"OK\",\"\"]";
    }
    catch (Exception $ex)
    {
        RollbackTrans();

        $msg = $ex->getMessage();
        echo "[-1,\"ERROR\",\"$ex\"]";
    }
    finally
    {
        CloseDb();
    }

}
?>