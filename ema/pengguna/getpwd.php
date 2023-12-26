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
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/getheader.php');
$nip = $_REQUEST['nip'];
OpenDb();
$sql = "SELECT * FROM $db_name_user.login WHERE login='$nip'";
//echo $sql;
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<?php
if ($num==0){
?>
<tr>
<td width="158" class="news_content1"><strong>Password</strong></td>
<td width="1073"><input type="password" size="25" maxlength="100" name="password" <?=$dis ?> id="password" onKeyPress="return focusNext('konfirmasi', event)" onFocus="panggil('password')" value="<?=$_REQUEST['password']?>"></td>
</tr>
<tr>
<td width="158" class="news_content1"><strong>Konfirmasi</strong></td>
<td><input type="password" size="25" maxlength="100" name="konfirmasi" <?=$dis ?> id="konfirmasi" onKeyPress="return focusNext('status_user', event)" onFocus="panggil('konfirmasi')" value="<?=$_REQUEST['konfirmasi']?>" ></td>
</tr>
<input type="hidden" id="haspwd" value="0" />
<?php } else { ?>
<tr>
<td colspan="2" align="center"><span style="color: #FF9900; font-weight: bold;">Pengguna sudah memiliki password</span></td>
</tr>
<input type="hidden" id="haspwd" value="1" />
<?php } ?>
</table>