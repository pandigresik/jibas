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
$source="";
if (isset($_REQUEST['source']))
	$source=$_REQUEST['source'];
?>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	width:315px;
	height:237px;
	z-index:0;
	left: 460px;
	top: 30px;
}
#apDiv3 {
	position:absolute;
	width:315px;
	height:237px;
	z-index:10;
	left: 460px;
	top: 30px;
}
#apDiv2 {
	position:absolute;
	width:315px;
	height:237px;
	z-index:0;
	left: 450px;
	top: 280px;
}
-->
</style>
<table width="320">
<tr><td align="center" valign="top"><img src="../<?=$source?>" width="320" height="240" style="z-index:0; position:absolute; left: 450px;"  />
	<table width="100%" border="0" cellspacing="0">
      <tr>
        <td><img src="../design/dummy_login.png" style="z-index:1000; position:absolute; left: 450px;"/></td>
      </tr>
    </table>
</td></tr>
</table>