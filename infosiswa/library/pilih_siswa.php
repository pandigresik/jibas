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

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
/*if(isset($_POST["departemen"])){
	$departemen = $_POST["departemen"];
}elseif(isset($_GET["departemen"])){
	$departemen = $_GET["departemen"];
}*/

OpenDb();
?>
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
    <td colspan="4">
    <input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <font size="2" color="#000000"><strong>Daftar Siswa</strong></font><br />
    </td>
</tr>
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td><select name="depart" id="depart" onChange="change_departemen(0)" style="width:150px" onkeypress="return focusNext('tingkat', event)">
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
    <td><font color="#000000"><strong>Tingkat</strong></font></td>
    <td>
            <select name="tingkat" id="tingkat" onChange="change()" style="width:150px;" onkeypress="return focusNext('kelas', event)">
        <?php
            OpenDb();
			$sql="SELECT * FROM tingkat WHERE departemen='$departemen' ORDER BY urutan";
            $result=QueryDb($sql);
            while ($row=@mysqli_fetch_array($result)){
                if ($tingkat=="")
                    $tingkat=$row['replid'];
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
        <?php 	} ?> 
            </select></td>
</tr>
<tr>
    <td><font color="#000000"><strong>Tahun Ajaran </strong></font></td>
    <td><select name="tahunajaran" id="tahunajaran" onChange="change()" style="width:150px;" onkeypress="return focusNext('tingkat', event)">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
				if ($row['aktif']) 
					$ada = '(A)';
				else 
					$ada = '';			 
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
    		<?php
			}
    		?>
    	</select>        </td>
        
    <td><font color="#000000"><strong>Kelas</strong></font></td>
    <td><select name="kelas" id="kelas" onChange="change_kelas()" style="width:150px">
<?php if ($tahunajaran <> "") {
		OpenDb();
		$sql="SELECT k.replid,k.kelas FROM jbsakad.kelas k,jbsakad.tahunajaran ta,jbsakad.tingkat ti WHERE k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ta.replid='$tahunajaran' AND ti.replid = '$tingkat' AND k.aktif=1 ORDER BY k.kelas";
    	$result=QueryDb($sql);
		CloseDb();
    	while ($row=@mysqli_fetch_array($result)){
            if ($kelas == "")
                $kelas = $row['replid']; 
                ?>
    	<option value="<?=$row['replid'] ?>" <?=StringIsSelected($row['replid'], $kelas) ?> >
    	<?=$row['kelas'] ?>
    	</option>
    <?php } 
	} else {	?>
    	<option></option>
<?php } ?> 
  	</select>
   	</td>    
</tr>
<tr>
	<td colspan="4" align="center">
    <br>
<?php 
OpenDb();

if ($kelas <> "" && $tingkat <> "" && $tahunajaran <> "") { 
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k WHERE s.aktif=1 AND k.replid=s.idkelas AND s.statusmutasi=0 AND s.alumni=0 AND k.replid='$kelas' ORDER BY s.nama"; 
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
?>
	<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" border="1">
	<tr height="30">
        <td class="header" width="7%" align="center" height="30">No</td>
        <td class="header" width="15%" align="center" height="30">N I S</td>
        <td class="header" align="center">Nama</td>
        <td class="header" align="center">Kelas</td>
        <td class="header" width="10%">&nbsp;</td>
	</tr>
<?php
	$cnt = 1;
	while($row = mysqli_fetch_row($result)) { 
?>
	<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')" style="cursor:pointer">
		<td align="center" ><?=$cnt ?></td>
		<td align="center"><?=$row[0] ?></td>
		<td><?=$row[1] ?></td>
		<td align="center"><?=$row[2] ?></td>
		<td align="center"><input type="button" value="Pilih" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')"  class="but"></td>
	</tr>
	<?php
	$cnt++;
	}
	CloseDb();	?>
    </table>
<?php } else { ?>
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />           
		Tambah data siswa di menu Pendataan Siswa pada bagian Kesiswaan. </b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php }
} else {?>
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br />          
		Tambah data Tahun Ajaran, Tingkat atau Kelas pada bagian Referensi. </b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php } ?>
</td>    
</tr>
<tr>
	<td align="center" colspan="4">
	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>
	</td>
</tr>
</table>