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
class GenericReturn
{
    public $Code;
    public $Message;
    public $Data;

    public function __construct($code, $message, $data)
    {
        $this->Code = $code;
        $this->Message = $message;
        $this->Data = $data;
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public static function fromJson($json)
    {
        return json_decode($json);
    }

    public static function createJson($code, $message, $data)
    {
        $result = new GenericReturn($code, $message, $data);
        return $result->toJson();
    }
}
?>