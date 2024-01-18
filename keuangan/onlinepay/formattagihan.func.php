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
function SimpanNomorTagihan()
{
    try
    {
        $nDept = $_REQUEST["ndept"];
        for($i = 1; $i <= $nDept; $i++)
        {
            $key = "dept$i";
            $dept = $_REQUEST[$key];

            $key = "awalan$i";
            $awalan = $_REQUEST[$key];

            $sql = "UPDATE jbsfina.formatnomortagihan SET awalan = '$awalan', issync = 0 WHERE departemen = '".$dept."'";
            QueryDbEx($sql);
        }

        return createJsonReturn(1, "OK");
    }
    catch (Exception $ex)
    {
        return createJsonReturn(-1, $ex->getMessage());
    }

}

function createJsonReturn($status, $message, $data)
{
    $ret = [$status, $message, $data];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}
?>
