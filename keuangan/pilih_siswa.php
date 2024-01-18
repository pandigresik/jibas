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
require_once('include/common.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

$departemen = $_REQUEST['departemen'];
$angkatan = $_REQUEST['angkatan'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];
$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];

OpenDb();
?>
<table border="0" width="100%" align="center">
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td>
    <!--<input type="text" name="departemen" id="departemen" value="<?=$_REQUEST['departemen']?>" readonly="readonly" style="background-color:#CCCC99;width:150px">
    <input type="hidden" name="depart" id="depart" value="<?=$_REQUEST['departemen']?>" />-->
    <select name="depart" id="depart" onChange="change_departemen(0)" style="width:155px" onkeypress="return focusNext('tingkat', event)">
   
	<?php $dep = getDepartemen(getAccess());    
        foreach($dep as $value) {
            if ($departemen == "")
                $departemen = $value; ?>
        <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
        <?=$value ?>
        </option>
        <?php } ?>
  	</select>
    </td>
</tr>
<tr>
    <td><font color="#000000"><strong>Angkatan </strong></font></td>
    <td><select name="angkatan" id="angkatan" onChange="change()" style="width:155px;" onkeypress="return focusNext('tingkat', event)">
   		 	<?php
			$sql = "SELECT replid,angkatan,aktif FROM jbsakad.angkatan where departemen='$departemen' AND aktif = 1 ORDER BY replid";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result)) {
				if ($angkatan == "") 
					$angkatan = $row['replid'];
					 
			?>
            
    		<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $angkatan)?> ><?=$row['angkatan']?></option>
    		<?php
			}
    		?>
    	</select>        </td>
</tr>

<!--<tr>
    <td><font color="#000000"><strong>Th. Ajaran </strong></font></td>
    <td><select name="tahunajaran" id="tahunajaran" onChange="change()" style="width:155px;" onkeypress="return focusNext('tingkat', event)">
   		 	<?php
			$sql = "SELECT replid,tahunajaran,aktif FROM jbsakad.tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
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
</tr>-->
<tr>
    <td><font color="#000000"><strong>Kelas</strong></font></td>
    <td><select name="tingkat" id="tingkat" onChange="change()" style="width:50px;" onkeypress="return focusNext('kelas', event)">
        <?php
			$sql="SELECT * FROM jbsakad.tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
            $result=QueryDb($sql);
            while ($row=@mysqli_fetch_array($result)){
                if ($tingkat=="")
                    $tingkat=$row['replid'];
        ?> 
            <option value="<?=$row['replid']?>" <?=IntIsSelected($row['replid'], $tingkat)?>><?=$row['tingkat']?></option>
        <?php 	} ?> 
            </select>
            <!--</td>
</tr>
<tr>
    <td><font color="#000000"><strong>Kelas</strong></font></td>
    <td> -->
    
    <select name="kelas" id="kelas" onChange="change_kelas()" style="width:98px" onkeypress="return focusNext1('siswa', event, 'pilih', 1, 0)">
<?php if ($tingkat <> "") {
		$sql="SELECT k.replid,k.kelas FROM jbsakad.kelas k,jbsakad.tahunajaran ta,jbsakad.tingkat ti WHERE k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ti.replid = '$tingkat' AND k.aktif=1 AND ta.aktif = 1 ORDER BY k.kelas";
    	$result=QueryDb($sql);
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
    <p>
<?php 
if ($kelas <> "" && $tingkat <> "" && $angkatan <> "") { 
	$sql = "SELECT s.nis, s.nama, k.kelas FROM jbsakad.siswa s,jbsakad.kelas k WHERE s.aktif=1 AND k.replid=s.idkelas AND s.alumni=0 AND k.replid='$kelas' AND s.idangkatan = '$angkatan' ORDER BY $urut $urutan"; 
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
?>
	<table width="100%" id="table" class="tab" align="center" border="1" bordercolor="#000000">
	<tr height="30" align="center" class="header">
        <td width="7%" >No</td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan?>','daftar')">N I S <?=change_urut('s.nis',$urut,$urutan)?></td>
        <td onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan?>','daftar')">Nama <?=change_urut('s.nama',$urut,$urutan)?></td>
	</tr>
<?php
	$cnt = 1;
	while($row = mysqli_fetch_row($result)) { 
?>
	<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')" style="cursor:pointer" id="siswapilih<?=$cnt?>">
		<td align="center" ><?=$cnt ?></td>
		<td align="center">
		<input type="text" name="pilihsiswa<?=$cnt?>" id="pilihsiswa<?=$cnt?>" readonly="readonly" size="10" style="border:none; background:none; text-align:center;" value="<?=$row[0]?>" onclick="pilih('<?=$row[0]?>','<?=$row[1]?>')" onkeypress="pilih('<?=$row[0]?>','<?=$row[1]?>');return focusNext1('siswa', event, 'pilih', <?=$cnt?>, 1)" />
		
		<?php //$row[0] ?></td>
		<td align="left"><?=$row[1] ?></td>		
	</tr>
	<?php
	$cnt++; //pilih('08091001','Amalia Karima')
	}	?>
    </table>
<?php } else { ?>
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="20" align="center">
		<td>   
   
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br />Belum ada siswa yang menempati kelas ini.</font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php }
} else {?>
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="20" align="center">
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