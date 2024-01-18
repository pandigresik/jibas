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
require_once('../include/sessioninfo.php');
require_once('departemen.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

$departemen = $_REQUEST['departemen'];
$proses = $_REQUEST['proses'];
$kelompok = $_REQUEST['kelompok'];
$urut2 = "c.nama";	
if (isset($_REQUEST['urut2']))
	$urut2 = $_REQUEST['urut2'];	
$urutan2 = "ASC";	
if (isset($_REQUEST['urutan2']))
	$urutan2 = $_REQUEST['urutan2'];
OpenDb();
?>
<table border="0" width="100%" align="center">
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td>
     <input type="text" name="departemen" id="departemen" value="<?=$_REQUEST['departemen']?>" readonly="readonly" style="background-color:#CCCC99;width:150px">
    <input type="hidden" name="depart2" id="depart2" value="<?=$_REQUEST['departemen']?>" />
    
    <!--<select name="depart2" id="depart2" onChange="change_departemen(2)" style="width:150px" onkeypress="return focusNext('proses', event)">
   
	<?php $dep = getDepartemen(getAccess());    
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
        <?=$value ?>
        </option>
        <?php } ?>
  	</select>-->
    </td>
</tr>

<tr>
    <td><font color="#000000"><strong>Proses </strong></font></td>
    <td><select name="proses" id="proses" onChange="change_proses()" style="width:155px;" onkeypress="return focusNext('kelompok', event)">
   		 	<?php
			$sql = "SELECT replid,proses,aktif FROM jbsakad.prosespenerimaansiswa WHERE departemen='$departemen' ORDER BY aktif DESC, replid DESC";				
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) {
				if ($proses == "") 
					$proses = $row['replid'];
				if ($row['aktif']) 
					$ada = '(A)';
				else 
					$ada = '';			 
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $proses)?> ><?=$row['proses'].' '.$ada?></option>
    		<?php
			}
    		?>
    	</select>        </td>
</tr>
<tr>
    <td><font color="#000000"><strong>Kelompok</strong></font></td>
    <td><select name="kelompok" id="kelompok" onChange="change_kelompok()" style="width:155px;" onkeypress="return focusNext1('calon', event, 'pilih', 1, 0)">
	<?php if ($proses <> "") {
			$sql = "SELECT replid,kelompok,kapasitas FROM jbsakad.kelompokcalonsiswa WHERE idproses = '$proses' ORDER BY kelompok";
            $result=QueryDb($sql);
            while ($row=@mysqli_fetch_array($result)){
                if ($kelompok=="")
                    $kelompok=$row['replid'];
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $kelompok)?>><?=$row['kelompok']?></option>
        <?php 	} 
		} else { ?>
        	<option></option>	
	<?php 	} ?> 
            </select>
   	</td>    
</tr>
<tr>
	<td colspan="4" align="center">
    <p>
<?php 
OpenDb();
if ($kelompok <> "" && $proses <> "") { 	
	$sql = "SELECT c.nopendaftaran,c.nama,c.replid FROM jbsakad.calonsiswa c, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p WHERE c.idproses = $proses AND c.idkelompok = $kelompok AND k.idproses = p.replid AND c.idproses = p.replid AND c.idkelompok = k.replid AND c.aktif = 1 ORDER BY $urut2 $urutan2 ";	
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
?>
	<table width="100%" id="table" class="tab" align="center" border="1" bordercolor="#000000">
	<tr height="30" align="center" class="header">
        <td width="7%">No</td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('c.nopendaftaran','<?=$urutan2?>','daftarcalon')">No Reg. <?=change_urut('c.nopendaftaran',$urut2,$urutan2)?></td>
        <td onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('c.nama','<?=$urutan2?>','daftarcalon')">Nama <?=change_urut('c.nama',$urut2,$urutan2)?></td>
	</tr>
<?php
	$cnt = 1;
	while($row = mysqli_fetch_row($result)) { 
?>
	<tr height="25" onClick="pilih('<?=$row[2]?>')" style="cursor:pointer"  id="calonpilih<?=$cnt?>">
		<td align="center" ><?=$cnt ?></td>
		<td align="center">
		<input type="text" name="pilihcalon<?=$cnt?>" id="pilihcalon<?=$cnt?>" readonly="readonly" size="10" style="border:none; background:none; text-align:center;" value="<?=$row[0]?>" onkeypress="pilih('<?=$row[2]?>');return focusNext1('calon', event, 'pilih', <?=$cnt?>, 1)" />
		<?php // $row[0] ?></td>
		<td align="left"><?=$row[1] ?></td>		
	</tr>
	<?php
	$cnt++;
	}?>
    </table>
<?php } else { ?>
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="200" align="center">
		<td>   
   
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />Belum ada calon siswa yang terdaftar pada kelompok ini.</font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php }
} else {?>
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="200" align="center">
		<td>   
   		
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data</b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php } ?>
</td>    
</tr>
</table>
<?php
CloseDb();
?>