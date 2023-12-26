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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pembayaran DSP</title>
<script language="javascript" src="script/tables.js"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript" src="script/rupiah.js"></script>
<script language="javascript">
function refresh() {
	document.location.reload();
}

function show_input(no) {
	eval("var check = document.getElementById('ch" + no + "').checked");
	document.getElementById('besar' + no).disabled = !check;
	document.getElementById('tgljt' + no).disabled = !check;
	document.getElementById('pbayar' + no).disabled = !check;
	document.getElementById('tglbyr' + no).disabled = !check;
}
</script>
</head>

<body background="images/bkmain.png">
<table border="0" cellpadding="10" cellspacing="10" width="80%" align="center">
<tr><td align="left">
<!-- BOF CONTENT -->

	<font size="5" color="#660000"><b>Pendataan DSP</b></font><br />
	<a href="index.php" style="color:#0000FF">Menu Utama</a> > <strong>Pendataan DSP</strong>
    <br /><br /><br />
    <a href="JavaScript:refresh()">Refresh</a><br />
        
    <table border="0" width="70%">
    <tr>
    	<td width="120">Siswa:</td>
    	<td><input type="text" name="nis" id="nis" readonly style="background-color:#CCCC99" size="15" /><input type="text" name="nama" id="nama" readonly style="background-color:#CCCC99" size="30" />&nbsp;<a href="#" onclick="cari_siswa()"><img src="images/ico/lihat.png" border="0" /></a> </td>
    </tr>
    <tr>
    	<td>Besar DSP:</td>
        <td><input type="text" name="besar" id="besar" size="20" onblur="formatRupiah('besar')" onfocus="unformatRupiah('besar')" /></td>
    </tr>
    </table>
    <br />
    <table id="table"  class="tab" border="0" cellpadding="0" cellspacing="0" width="600">
    <tr>
    	<td class="header" width="6%" align="center">Cicilan</td>
        <td class="header" width="16%" align="center">Besar</td>
        <td class="header" width="16%" align="center">Tgl Jatuh Tempo<br /><i>dd-mm-yy</i></td>
        <td class="header" width="16%" align="center">Pembayaran</td>
        <td class="header" width="16%" align="center">Tgl Pembayaran<br /><i>dd-mm-yy</i></td>
    </tr>
    <tr height="35" bgcolor="#FFFF99">
        <td style="background-color:#33CCCC" align="center">1</td>
        <td style="background-color:#33CCCC" align="center"><input type="text" name="besar1" id="besar1" size="12" onblur="formatRupiah('besar1')" onfocus="unformatRupiah('besar1')" /></td>
        <td style="background-color:#33CCCC" align="center"><input type="text" name="tgljt1" id="tgljt1" size="8" /></td>
        <td style="background-color:#33CCCC" align="center"><input type="text" name="pbayar1" id="pbayar1" onblur="formatRupiah('pbayar1')" onfocus="unformatRupiah('pbayar1')" size="12" /></td>
        <td style="background-color:#33CCCC" align="center"><input type="text" name="tglbyr1" id="tglbyr1" size="8" /></td>
    </tr>
    <?php for($i = 2; $i <= 10; $i++) { ?>
    <tr>
        <td align="center"><?=$i?></td>
        <td align="center"><input type="text" name="besar<?=$i?>" id="besar<?=$i?>" size="12" onblur="formatRupiah('besar<?=$i?>')" onfocus="unformatRupiah('besar<?=$i?>')" /></td>
        <td align="center"><input type="text" name="tgljt<?=$i?>" id="tgljt<?=$i?>" size="8" /></td>
        <td align="center"><input type="text" name="pbayar<?=$i?>" id="pbayar<?=$i?>" size="12" onblur="formatRupiah('pbayar<?=$i?>')" onfocus="unformatRupiah('pbayar<?=$i?>')" /></td>
        <td align="center"><input type="text" name="tglbyr<?=$i?>" id="tglbyr<?=$i?>" size="8" /></td>
    </tr>
    <?php } ?> 
    <tr height="40">
    	<td style="background-color:#999966" align="center"><strong>T O T A L</strong></td>
        <td style="background-color:#999966" align="center"><input type="text" readonly name="totalbesar" id="totalbesar" size="12"/></td>
        <td style="background-color:#999966" align="center">&nbsp;</td>
        <td style="background-color:#999966" align="center"><input type="text" readonly name="pbayar" id="pbayar" size="12"/></td>
        <td style="background-color:#999966" align="center">&nbsp;</td>
    </tr>
    </table>
    <script language='JavaScript'>
	    Tables('table', 1, 0);
    </script>
    
<!-- EOF CONTENT -->
</td></tr>
</table>
</body>
</html>