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
require_once("include/config.php");
require_once("../include/cbe.config.php");
require_once("include/session.php");
require_once("include/db_functions.php");
require_once("library/genericreturn.php");
require_once("library/httprequest.php");
require_once("library/httpmanager.php");
require_once("library/cbe.state.php");
require_once("library/cbe.system.php");
require_once("library/cbe.session.php");
require_once("library/cbe.protocol.php");
require_once("library/debugger.php");
require_once("library/userkeyinfo.php");
require_once("common.func.php");

function getUserOfflineList()
{
    try
    {
        $jsonSession = $_SESSION["Json"];

        $http = new HttpManager();
        $http->setData("userofflinelist", CbeState::DaftarPesertaUjianOffline, "0", $jsonSession, "");
        $result = $http->send();

        if ((int) $result->Code < 0)
            return $result->Message;

        $jsonData = $result->Data;
        $protocol = CbeDataProtocol::fromJson($jsonData);
        if ((int) $protocol->Status < 0)
            return $protocol->Data;

        $jsonUserList = trim($protocol->Data);
        $lsUser = json_decode($jsonUserList);
        $nUser = count($lsUser);
        ?>

        <input type="hidden" id="of_jsonUserList" value='<?=$jsonUserList?>'>
        Total Peserta: <span style="font-size: 18px"><?= $nUser ?></span>
        <table border='1' cellpadding='2' cellspacing='0' width='1210' style='border-collapse: collapse; border-width: 1px;'>
            <tr style='background-color: #2c8058; color: #fff; height: 25px'>
                <td width='40px' align='center'>No</td>
                <td width='140px' align='center'>Tanggal</td>
                <td width='140px' align='center'>Selisih</td>
                <td width='140px' align='center'>User Id</td>
                <td width='220px' align='center'>User Name</td>
                <td width='140px' align='center'>Kelompok</td>
                <td width='250px' align='center'>Ujian</td>
                <td width='140px' align='center'>Guru</td>
            </tr>
        <?php
        $no = 0;
        for($i = 0; $i < $nUser; $i++)
        {
            $no += 1;

            echo "<tr style='height: 25px'>";
            echo "<td align='center' style='background-color: #ddd'>$no</td>";

            $nInfo = count($lsUser[$i]);
            for($j = 0; $j < $nInfo; $j++)
            {
                echo "<td align='left'>" . $lsUser[$i][$j] . "</td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
    }
}
?>