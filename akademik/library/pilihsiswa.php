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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if ($departemen == -1) 
	$departemen = 'Semua departemen';
	
?>


	<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
	<tr>
    	<td>
		<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
		<font size="2"><strong>Daftar Siswa</strong></font><br />	
		Departemen: 
        <strong><input type="text" name="departemen" id="departemen" value="<?=$departemen ?>" size="10" readonly style="background-color:#CCCCCC" /></strong>&nbsp;&nbsp;</td>
	</tr>
	<tr>
    <td>
		<br />
		<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" bordercolor="#000000">
		<tr height="30">
			<td class="header" width="7%" align="center">No</td>
    		<td class="header" width="15%" align="center">N I S</td>
    		<td class="header" >Nama</td>
    		<td class="header" width="10%">&nbsp;</td>
		</tr>
		<?php
		OpenDb();
		$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p LEFT JOIN (guru g LEFT JOIN pelajaran l ON l.replid = g.idpelajaran) ON p.nip = g.nip GROUP BY p.nip";
		//$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p LEFT JOIN (guru g LEFT JOIN pelajaran l ON l.replid = g.idpelajaran) ON p.nip = g.nip GROUP BY p.nip";
		$result = QueryDb($sql);
		$cnt = 0;
		while($row = mysqli_fetch_row($result)) { ?>
		<tr>
			<td align="center"><?=++$cnt ?></td>
    		<td align="center"><?=$row[0] ?></td>
    		<td><?=$row[1] ?></td>
    		<td align="center">
    		<input type="button" name="pilih" class="but" id="pilih" value="Pilih" onClick="pilih('<?=$row[0]?>', '<?=$row[1]	?>')" />
    	   	</td>
		</tr>
		<?php 	} ?>
		<tr height="26">
			<td colspan="4" align="center" >
        	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onClick="window.close()" /></td>
		</tr>	
		</table>
		</td>
	</tr>
	</table>