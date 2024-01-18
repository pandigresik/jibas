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
require_once('../inc/common.php');
require_once('../inc/config.php');
require_once('../inc/db_functions.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

$departemen = $_REQUEST['departemen'];
$tahunajaran = $_REQUEST['tahunajaran'];
$tingkat = $_REQUEST['tingkat'];
$kelas = $_REQUEST['kelas'];

$varbaris=10;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];
$urut = "s.nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
OpenDb();
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
    <td colspan="4">
    <input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
    <input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />
    <!--<font size="2" color="#000000"><strong>Daftar Siswa</strong></font><br />-->
    </td>
</tr>
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td><select name="depart" class="cmbfrm" id="depart" style="width:150px" onChange="change_departemen(0)" onkeypress="return focusNext('tahunajaran', event)">
	<option value=-1>(Semua Departemen)</option>
	<?php $sql = "SELECT departemen FROM $db_name_akad.departemen ORDER BY urutan";
        $result = QueryDb($sql);
		while ($row=@mysqli_fetch_array($result)) {
			if ($departemen == "")
                $departemen = $row['departemen']; ?>
        <option value="<?=$row['departemen'] ?>" <?=StringIsSelected($row['departemen'], $departemen) ?> >
        <?=$row['departemen'] ?>
        </option>
        <?php } ?>
  	</select>
    </td>
    <td><font color="#000000"><strong>Tingkat</strong></font></td>
    <td>
            <select name="tingkat" class="cmbfrm" id="tingkat" style="width:150px;" onChange="change()" onkeypress="return focusNext('kelas', event)">
        <?php
            OpenDb();
			$sql="SELECT * FROM  $db_name_akad.tingkat WHERE departemen='$departemen' AND aktif = 1 ORDER BY urutan";
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
    <td><select name="tahunajaran" class="cmbfrm" id="tahunajaran" style="width:150px;" onChange="change()" onkeypress="return focusNext('tingkat', event)">
   		 	<?php
			OpenDb();
			$sql = "SELECT replid,tahunajaran,aktif FROM  $db_name_akad.tahunajaran where departemen='$departemen' ORDER BY aktif DESC, replid DESC";
			$result = QueryDb($sql);
			CloseDb();
			while ($row = @mysqli_fetch_array($result)) {
				if ($tahunajaran == "") 
					$tahunajaran = $row['replid'];
				if ($row['aktif']) 
					$ada = '(Aktif)';
				else 
					$ada = '';			 
			?>
            
	<option value="<?=urlencode((string) $row['replid'])?>" <?=IntIsSelected($row['replid'], $tahunajaran)?> ><?=$row['tahunajaran'].' '.$ada?></option>
    		<?php
			}
    		?>
   	</select>        </td>
        
    <td><font color="#000000"><strong>Kelas</strong></font></td>
    <td><select name="kelas" class="cmbfrm" id="kelas" style="width:150px" onChange="change_kelas()">
<?php if ($tahunajaran <> "") {
		OpenDb();
		$sql="SELECT k.replid,k.kelas FROM  $db_name_akad.kelas k, $db_name_akad.tahunajaran ta, $db_name_akad.tingkat ti WHERE k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ti.departemen='$departemen' AND ta.replid='$tahunajaran' AND ti.replid = '$tingkat' AND k.aktif=1 ORDER BY k.kelas";
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
	$sql_tot = "SELECT s.nis, s.nama, k.kelas FROM  $db_name_akad.siswa s, $db_name_akad.kelas k WHERE s.aktif=1 AND k.replid=s.idkelas AND s.alumni=0 AND k.replid='$kelas' ORDER BY s.nama"; 	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	
	$sql = "SELECT s.nis, s.nama, k.kelas FROM  $db_name_akad.siswa s, $db_name_akad.kelas k WHERE s.aktif=1 AND k.replid=s.idkelas AND s.alumni=0 AND k.replid='$kelas' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
?>
	<table width="100%" id="table" class="tab" align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
	<tr height="30" class="header" align="center">
        <td width="7%" >No</td>
        <td width="15%">N I S</td>
        <td>Nama </td>
        <td width="10%">&nbsp;</td>
	</tr>
<?php
	if ($page==0)
		$cnt = 1;
	else 
		$cnt = (int)$page*(int)$varbaris+1;
	while($row = mysqli_fetch_row($result)) { 
?>
	<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')" style="cursor:pointer">
		<td align="center" ><?=$cnt ?></td>
		<td align="center"><?=$row[0] ?></td>
		<td align="left"><?=$row[1] ?></td>
		<!--<td align="center"><?=$row[2] ?></td>-->
		<td align="center"><input type="button" value="Pilih" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')"  class="cmbfrm2"></td>
	</tr>
	<?php
	$cnt++;
	}
	CloseDb();	?>
    </table>
    <?php  if ($page==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
	}
	if ($page<$total && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
	}
	if ($page==$total-1 && $page>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
	}
	if ($page==$total-1 && $page==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
	}
	?>
    </td>
</tr> 
<tr>
    <td colspan="4">
    <table border="0"width="100%" align="center"cellpadding="0" cellspacing="0">	
    <tr>
       	<td width="30%" align="left"><font color="#000000">Hal
        <select name="hal" class="cmbfrm" id="hal" onChange="change_hal('daftar')">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
		<?php 
     	// Navigasi halaman berikutnya dan sebelumnya
        ?>
        </font></td>
    	<!--td align="center">
    	<input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onClick="change_page('<?=(int)$page-1?>','daftar')" >
		<?php
		for($a=0;$a<$total;$a++){
			if ($page==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."','daftar')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="cmbfrm2" name="next" value=" >> " onClick="change_page('<?=(int)$page+1?>','daftar')" >
 		</td-->
        <td width="30%" align="right"><font color="#000000">Jml baris per hal
      	<select name="varbaris" class="cmbfrm" id="varbaris" onChange="change_baris('daftar')">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
        <?php 	} ?>
      	</select>
        </font></td>
    </tr>
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
	<input type="button" class="cmbfrm2" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>
	</td>
</tr>
</table>