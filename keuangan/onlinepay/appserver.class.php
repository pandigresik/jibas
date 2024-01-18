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
require_once('appserver.config.php');

class AppServer
{
    static function Send($qs)
    {
        global  $JS_SERVER_ADDR, $JS_SERVER_PORT;

        $appServerAddr = "$JS_SERVER_ADDR:$JS_SERVER_PORT";

        $http = new HttpManager($appServerAddr);
        $http->setData($qs);
        return $http->send();
    }

    static function CreateQs($op, $data = "", $info = "", $extra = "")
    {
        $qs = "op=$op";
        if (strlen((string) $data) > 0) $qs .= "&data=" . urlencode((string) $data);
        if (strlen((string) $info) > 0) $qs .= "&info=" . urlencode((string) $info);
        if (strlen((string) $extra) > 0) $qs .= "&extra=" . urlencode((string) $extra);

        return $qs;
    }

    static function SendQs($op, $data = "", $info = "", $extra = "")
    {
        $qs = "op=$op";
        if (strlen((string) $data) > 0) $qs .= "&data=" . urlencode((string) $data);
        if (strlen((string) $info) > 0) $qs .= "&info=" . urlencode((string) $info);
        if (strlen((string) $extra) > 0) $qs .= "&extra=" . urlencode((string) $extra);

        return AppServer::Send($qs);
    }
}
?>

