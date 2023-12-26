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
$idtahunbuku = $_REQUEST['idtahunbuku'];
$departemen = $_REQUEST['departemen'];
$tanggal1 = $_REQUEST['tanggal1'];
$tanggal2 = $_REQUEST['tanggal2'];

?>
<frameset border="1" cols="31%,*" frameborder="1">
	<frame name="pilih" src="lappengeluaran_jenis_pilih.php?departemen=<?=$departemen?>&idtahunbuku=<?=$idtahunbuku?>&tanggal1=<?=$tanggal1?>&tanggal2=<?=$tanggal2?>" scrolling="auto" />
    <frame name="content" src="blank_pengeluaran.php" scrolling="auto" style="border-left:1; border-left-color:#000000; border-left-style:solid"//>
</frameset><noframes></noframes>