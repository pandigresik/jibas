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
if ($page==0)
{ 
	$disback="style='display:none;'";
	$disnext="style=''";
}

if ($page < $total && $page > 0)
{
	$disback="style=''";
	$disnext="style=''";
}

if ($page == $total - 1 && $page > 0)
{
	$disback="style=''";
	$disnext="style='display:none;'";
}

if ($page == $total - 1 && $page == 0)
{
	$disback="style='display:none;'";
	$disnext="style='display:none;'";
}

if ($ndata == 0)
{
    $disback="style='display:none;'";
	$disnext="style='display:none;'";
}
?>
<table border="0" width="1000" align="left" cellpadding="0" cellspacing="0">	
<tr>
	<td width="30%" align="left" colspan="2">
		Halaman	<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>')" onMouseOver="showhint('Sebelumnya', this, event, '75px')">
		<select name="hal" id="hal" onChange="change_hal()">
		<?php for ($m=0; $m<$total; $m++) {?>
			 <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
		<?php } ?>
		</select>
		<input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>')" onMouseOver="showhint('Berikutnya', this, event, '75px')">
		dari <?=$total?> halaman, <?=$ndata?> data
	</td>
	<td width="*" align="right">Jumlah baris per halaman
		<select name="varbaris" id="varbaris" onChange="change_baris()">
	<?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
		<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
	<?php 	} ?>
		</select>
	</td>
</tr>
</table>