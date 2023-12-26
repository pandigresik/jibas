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
require_once("../include/common.php") 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../style/style.css" />
<title>JIBAS Kepegawaian</title>
<script language="javascript">
function Lihat() {
	var bln1 = document.getElementById('bln1').value;
	var thn1 = document.getElementById('thn1').value;
	var bln2 = document.getElementById('bln2').value;
	var thn2 = document.getElementById('thn2').value;
	
	parent.dagendacontent.location.href = "dagenda_content.php?bln1="+bln1+"&thn1="+thn1+"&bln2="+bln2+"&thn2="+thn2;
}

function ShowBlank() {
	parent.dagendacontent.location.href = "blank.php";
}
</script>
</head>

<body>
<?php
$M = date('n');
$Y = date('Y');
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="60%" align="left">
    	Bulan: 
        <select name="bln1" id="bln1" onchange="JavaScript:ShowBlank()">
<?php 	for($i = 1; $i <= 12; $i++) { ?>        
			<option value="<?=$i?>" <?=IntIsSelected($i, $M)?>><?=NamaBulan($i)?></option>
<?php 	} ?>
        </select>
        <input type="text" name="thn1" id="thn1" maxlength="4" size="4" value="<?=$Y?>" onchange="JavaScript:ShowBlank()" /> s/d 
        <select name="bln2" id="bln2" onchange="JavaScript:ShowBlank()">
<?php 	for($i = 1; $i <= 12; $i++) { ?>        
			<option value="<?=$i?>" <?=IntIsSelected($i, $M)?>><?=NamaBulan($i)?></option>
<?php 	} ?>
        </select>
        <input type="text" name="thn2" id="thn2" maxlength="4" size="4" value="<?=$Y?>"  onchange="JavaScript:ShowBlank()"/> &nbsp;&nbsp;
        <input type="button" name="Lihat" value="Lihat" class="but" onclick="JavaScript:Lihat()" />
        
    </td>
    <td width="40%" align="right">
	    <font style="background-color:#FFCC33; font-size:14px">&nbsp;&nbsp;</font>
        <font class="subtitle">Daftar Agenda Kepegawaian</font><br />
        <a href="pegawai.php" target="_parent">Kepegawaian</a> &gt; Daftar Agenda Kepegawaian
    </td>
</tr>
</table>
</body>
</html>