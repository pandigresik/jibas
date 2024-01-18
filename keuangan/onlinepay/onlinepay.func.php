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
function CheckAllConfigReady()
{
    global $JS_SERVER_ADDR;
    global $PG_SCHOOL_ID, $PG_ADDR, $PG_SERVICE_VALID;

    if ($JS_SERVER_ADDR === "")
    {
        echo "[-1,\"Atur dahulu konfigurasi JIBAS Sinkronisasi Jendela Sekolah\"]";
        return;
    }

    if ($PG_SCHOOL_ID == "" || $PG_ADDR == "" || $PG_SERVICE_VALID == 0)
    {
        echo "[-1,\"Atur dahulu konfigurasi Kode Sekolah\"]";
        return;
    }

    try
    {
        OpenDb();

        $sql = "SELECT COUNT(*) FROM jbsfina.bank";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Daftar Rekening Bank\"]";
            return;
        }

        $sql = "SELECT COUNT(*) FROM jbsfina.formatnomortagihan";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Format Nomor Tagihan\"]";
            return;
        }

        $sql = "SELECT COUNT(*) FROM jbsfina.formatpesanpg";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Format Pesan Tagihan\"]";
            return;
        }

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "ktpeq");

        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
    finally
    {
        CloseDb();
    }

}

function CheckJsSyncAddrConfig()
{
    global $JS_SERVER_ADDR;

    if ($JS_SERVER_ADDR === "")
        echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
}

function CheckPgServiceConfig()
{
    global $PG_SCHOOL_ID, $PG_ADDR, $PG_SERVICE_VALID;

    if ($PG_SCHOOL_ID == "" || $PG_ADDR == "" || $PG_SERVICE_VALID == 0)
        echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
}

function CheckBankConfig()
{
    try
    {
        OpenDb();

        $sql = "SELECT COUNT(*) FROM jbsfina.bank";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        CloseDb();
    }
}

function CheckInfoBayarConfig()
{
    try
    {
        OpenDb();

        $sql = "SELECT COUNT(*) FROM jbsfina.infobayar";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        CloseDb();
    }
}

function CheckFormatNomorTagihanConfig()
{
    try
    {
        OpenDb();

        $sql = "SELECT COUNT(*) FROM jbsfina.formatnomortagihan";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        CloseDb();
    }
}


function CheckPesanPgConfig()
{
    try
    {
        OpenDb();

        $sql = "SELECT COUNT(*) FROM jbsfina.formatpesanpg";
        $res = QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        CloseDb();
    }
}

function CheckGetServiceFee()
{
    if (isset($_SESSION["SERVICE_FEE"]))
        return;

    global $PG_ADDR, $PG_SCHOOL_ID, $PG_DATABASE_ID;

    try
    {
        $schoolId = $PG_SCHOOL_ID;
        $dbId = $PG_DATABASE_ID;

        if (strlen((string) $schoolId) != 5 || strlen((string) $dbId) != 5)
            return;

        $pgServiceAddr = $PG_ADDR . "/jbsfina/svcf.php";

        $http = new HttpManager($pgServiceAddr);
        $http->setData("schoolid=$schoolId&dbid=$dbId");
        $sendGr = $http->send();
        if ($sendGr->Value < 0)
            return;

        $jsonInfo = $sendGr->Data;
        $info = json_decode((string) $jsonInfo, null, 512, JSON_THROW_ON_ERROR);
        $valid = $info[0];
        $message = $info[1];
        $serviceFee = $info[2];

        if ($valid == 1)
        {
            $content = "<?php\n";
            $content .= '$PG_SERVICE_VALID = ' . $valid . ';';
            $content .= "\n";
            $content .= '$PG_SERVICE_FEE = ' . $serviceFee . ';';
            $content .= "\n?>";

            file_put_contents("./pgservice.config.php", $content);

            $_SESSION["SERVICE_FEE"] = $serviceFee;
        }
        else
        {
            unset($_SESSION["SERVICE_FEE"]);
        }
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k27xy");
    }
}
?>
