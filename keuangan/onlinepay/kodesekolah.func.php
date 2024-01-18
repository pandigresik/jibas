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
function GetPgAddr()
{
    global $PG_ADDR;
    global $PGMAIN_ADDR;

    try
    {
        if ($PG_ADDR != "")
            return "[1,\"$PG_ADDR\"]";

        $schoolId = strtoupper((string) $_REQUEST["schoolid"]);
        $dbId = strtoupper((string) $_REQUEST["dbid"]);

        $pgMainServiceAddr = "$PGMAIN_ADDR/jbsfina/getpgaddr.php";

        $http = new HttpManager($pgMainServiceAddr);
        $http->setData("schoolid=$schoolId&dbid=$dbId");
        $sendGr = $http->send();

        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            return "[-1,\"$msg\"]";
        }

        $jsonInfo = $sendGr->Data;
        $info = json_decode((string) $jsonInfo, null, 512, JSON_THROW_ON_ERROR);
        $valid = $info[0];
        $message = $info[1];
        $pgAddr = $info[2];

        if ($valid == 1)
        {
            $content  = "<?php\n";
            $content .= '$PGMAIN_ADDR = "https://paygate.jendelasekolah.id";';
            $content .= "\n";
            $content .= '$PG_ADDR = "' . $pgAddr . '";';
            $content .= "\n?>";

            file_put_contents("./pgserver.config.php", $content);

            return "[1,\"$pgAddr\"]";
        }

        return "[-1,\"$message\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\",0]";
    }
}

function GetServiceFee()
{
    try
    {
        $schoolId = strtoupper((string) $_REQUEST["schoolid"]);
        $dbId = strtoupper((string) $_REQUEST["dbid"]);

        $jsonResult = GetPgAddr();
        $lsResult = json_decode((string) $jsonResult, null, 512, JSON_THROW_ON_ERROR);
        if ($lsResult[0] != 1)
        {
            $message = $lsResult[1];
            echo "[-1,\"$message\",0]";
            return;
        }

        $pgAddr = $lsResult[1];
        $pgServiceAddr = "$pgAddr/jbsfina/svcf.php";

        $http = new HttpManager($pgServiceAddr);
        $http->setData("schoolid=$schoolId&dbid=$dbId");
        $sendGr = $http->send();

        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            echo "[-1,\"$msg\"]";
            return;
        }

        $jsonInfo = $sendGr->Data;
        $info = json_decode((string) $jsonInfo, null, 512, JSON_THROW_ON_ERROR);
        $valid = $info[0];
        $message = $info[1];
        $serviceFee = $info[2];

        if ($valid == 1)
        {
            $content  = "<?php\n";
            $content .= '$PG_SERVICE_VALID = ' . $valid . ';';
            $content .= "\n";
            $content .= '$PG_SERVICE_FEE = ' . $serviceFee . ';';
            $content .= "\n?>";

            file_put_contents("./pgservice.config.php", $content);

            echo "[1,\"OK\",$serviceFee]";

            $_SESSION["SERVICE_FEE"] = $serviceFee;
        }
        else
        {
            echo "[-1,\"$message\",0]";

            unset($_SESSION["SERVICE_FEE"]);
        }
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\",0]";
    }
}

function CheckSchoolId()
{
    try
    {
        $jsonResult = GetPgAddr();
        $lsResult = json_decode((string) $jsonResult, null, 512, JSON_THROW_ON_ERROR);
        if ($lsResult[0] != 1)
        {
            $message = $lsResult[1];
            echo "[-1,\"$message\",0]";
            return;
        }

        $schoolId = strtoupper((string) $_REQUEST["schoolid"]);
        $dbId = strtoupper((string) $_REQUEST["dbid"]);

        $pgAddr = $lsResult[1];
        $pgServiceAddr = "$pgAddr/jbsfina/cscid.php";

        $http = new HttpManager($pgServiceAddr);
        $http->setData("schoolid=$schoolId&dbid=$dbId");
        $sendGr = $http->send();
        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            echo "[-1,\"$msg\",0]";
            return;
        }

        $jsonInfo = $sendGr->Data;
        $info = json_decode((string) $jsonInfo, null, 512, JSON_THROW_ON_ERROR);

        $valid = $info[0];
        $message = $info[1];
        $serviceFee = $info[2];

        if ($valid == 1)
        {
            $content  = "<?php\n";
            $content .= '$PG_SCHOOL_ID = "' . $schoolId . '";';
            $content .= "\n";
            $content .= '$PG_DATABASE_ID = "' . $dbId . '";';
            $content .= "\n?>";

            file_put_contents("./pgschoolid.config.php", $content);

            $content  = "<?php\n";
            $content .= '$PG_SERVICE_VALID = ' . $valid . ';';
            $content .= "\n";
            $content .= '$PG_SERVICE_FEE = ' . $serviceFee . ';';
            $content .= "\n?>";

            file_put_contents("./pgservice.config.php", $content);

            echo "[1,\"OK\",$serviceFee]";

            $_SESSION["SERVICE_FEE"] = $serviceFee;
        }
        else
        {
            echo "[-1,\"$message\",0]";

            unset($_SESSION["SERVICE_FEE"]);
        }
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\",0]";
    }
}
?>