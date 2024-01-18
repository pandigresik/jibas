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
class CbeSession
{
    public $UserId;
    public $UserName;
    public $UserPassword;
    public $UserDept;
    public $UserType;
    public $SessionId;
    public $LoginTime;
    public $LocalIp;
    public $AppCompVersion;
    public $AndroidVersion;
    public $Mode = 1; // 0 OFFLINE 1 ONLINE
    public $ClientType = 4;
    public $Info = "";

    public function toJson()
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

    public static function fromJson($json)
    {
        return json_decode((string) $json, null, 512, JSON_THROW_ON_ERROR);
    }
}
?>