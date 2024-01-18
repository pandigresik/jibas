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
require_once('../inc/config.php');
require_once('../inc/db_functions.php');
require_once('../inc/common.php');
$penulis=$_REQUEST['penulis'];
OpenDb();
$sql = "SELECT replid,kode,nama FROM penulis ORDER BY nama";
$result = QueryDb($sql);
$max=0;
while ($row = @mysqli_fetch_row($result)){
	$newmax = strlen((string) $row[2]);
	if ($newmax>$max)
		$max=$newmax;
}
?>
<select name="penulis" id="penulis" class="cmbfrm" style="width:100%; font-family:'Courier New'">
<?php
$sql = "SELECT replid,kode,nama FROM penulis ORDER BY nama";
$result = QueryDb($sql);
while ($row = @mysqli_fetch_row($result)){
$len = strlen((string) $row[2]);
$space = GetSpace($max,$len);
if ($penulis=="")
	$penulis = $row[0];	
?>
	<option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$penulis)?>>
	<?=$row[2]?><?=$space?> - <?=$row[1]?> 
	</option>
<?php
}
?>
</select>
<?php

function GetSpace($maxlength,$length){
	$spacer="";
	for ($i=1;$i<=$maxlength-$length;$i++)
		$spacer .="&nbsp;";
	return $spacer;	
}
?>