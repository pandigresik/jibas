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
require_once('../include/getheader.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('departemen.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
$nama = $_REQUEST['nama'];
$nis = $_REQUEST['nis'];
$departemen = $_REQUEST['departemen'];
OpenDb();
?>
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td>
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
	<font size="2" color="#000000"><strong>Cari Siswa</strong></font>
 	</td>
</tr>
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td><select name="depart" id="depart" onChange="change_departemen(1)" style="width:150px" onkeypress="return focusNext('nama', event)">
	<?php $dep = getDepartemen(SI_USER_ACCESS());    
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
        <?=$value ?>
        </option>
        <?php } ?>
  	</select>
    </td>
   	<td rowspan="2" width="15%" align="center">
    <input type="button" class="but" name="submit" id="submit" value="Cari" onclick="carilah()" style="width:70px;height:40px"/>
    </td>
</tr>
<tr>
    <td><font color="#000000"><strong>Nama Siswa</strong></font></td>
    <td><input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="22" onKeyPress="return focusNext('submit', event)"/>
		&nbsp;
        <font color="#000000"><strong>NIS</strong></font>	
        <input type="text" name="nis" id="nis" value="<?=$_REQUEST['nis'] ?>" size="20" onKeyPress="return focusNext('submit', event)"/>     
  	</td>   
</tr>
<tr>
    <td align="center" colspan="3">
	<div id="caritabel">
<?php 
if (isset($_REQUEST['submit']) || $_REQUEST['submit'] == 1) { 
	OpenDb();    
   	
	if ((strlen((string) $nama) > 0) && (strlen((string) $nis) > 0))
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.statusmutasi=0 AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid AND t.departemen = d.departemen ORDER BY d.departemen, k.kelas, s.nama"; 	
	else if (strlen((string) $nama) > 0)
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND s.statusmutasi=0 AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid AND t.departemen = d.departemen ORDER BY d.departemen, k.kelas, s.nama"; 
	else if (strlen((string) $nis) > 0)
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat t, jbsakad.departemen d WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND s.statusmutasi=0 AND s.alumni = 0 AND s.aktif=1 AND k.idtingkat = t.replid AND t.departemen = d.departemen ORDER BY d.departemen, k.kelas, s.nama"; 
	//else if ((strlen($nama) == 0) || (strlen($nis) == 0))
	//	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat ti,jbsakad.tahunajaran ta WHERE k.replid=s.idkelas AND s.statusmutasi=0 AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$departemen' AND ti.departemen='$departemen' AND s.aktif=1  ORDER BY s.nama"; 
	$result = QueryDb($sql); 
	if (@mysqli_num_rows($result)>0){
?>   
	<br>
   	<table width="100%" id="table1" class="tab" align="center" cellpadding="2" cellspacing="0" border="1">
    <tr height="30">
        <td class="header" width="7%" align="center" height="30">No</td>
        <td class="header" width="15%" align="center" height="30">N I S</td>
        <td class="header" >Nama</td>
        <td class="header" align="center">Departemen</td>
        <td class="header" align="center">Kelas</td>
        <td class="header" width="10%">&nbsp;</td>
    </tr>
<?php
	$cnt = 0;
		while($row = mysqli_fetch_row($result)) { ?>
   	<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')" style="cursor:pointer">
        <td align="center" ><?=++$cnt ?></td>
        <td align="center"><?=$row[0] ?></td>
        <td ><?=$row[1] ?></td>
        <td align="center"><?=$row[3] ?></td>
        <td align="center"><?=$row[2] ?></td>
        <td align="center"><input type="button" value="Pilih" onclick="pilih('<?=$row[0]?>','<?=$row[1]?>')" class="but"></td>
	</tr>
<?php } CloseDb(); ?>
 	</table>
<?php } else { ?>    		
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />            
		Tambah data siswa di menu Pendataan Siswa pada bagian Kesiswaan. </b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php 	} 
} else { ?>

<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
<tr height="30" align="center">
    <td>   

<br /><br />	
<font size="2" color="#757575"><b>Klik pada tombol "Cari" di atas untuk melihat data calon siswa <br />sesuai dengan NIS atau Nama Siswa berdasarkan <i>keyword</i> yang dimasukkan</b></font>	
<br /><br />
    </td>
</tr>
</table>


<?php }?>	
    </div>
	 </td>    
</tr>
<tr>
	<td align="center" colspan="3">
	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>
	</td>
</tr>
</table>