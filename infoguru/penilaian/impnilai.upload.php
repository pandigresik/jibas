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
require_once('../include/compatibility.php');

$fexcel = $_FILES['fexcel'];

$rand = "";
$dict = "0123456789abcdefghijklmnopqrstuvwxyz";
$dictLen = strlen($dict);
for($i = 0; $i < 32; $i++)
    $rand .= $dict[random_int(0, $dictLen - 1)];

$upFile = "../temp/$rand.xlsx";
move_uploaded_file($fexcel["tmp_name"], $upFile);

echo $upFile;
http_response_code(200);
?>
