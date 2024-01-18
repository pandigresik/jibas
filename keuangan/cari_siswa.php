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
$nama = $_REQUEST['nama'];
$nis = $_REQUEST['nis'];
$departemen = $_REQUEST['departemen'];
$filter = "";
if ($departemen <> -1) 
	$filter = "AND t.departemen = '".$departemen."'";

$varbaris1=10;
if (isset($_REQUEST['varbaris1']))
	$varbaris1 = $_REQUEST['varbaris1'];
$page1=0;
if (isset($_REQUEST['page1']))
	$page1 = $_REQUEST['page1'];
$hal1=0;
if (isset($_REQUEST['hal1']))
	$hal1 = $_REQUEST['hal1'];
$urut1 = "s.nama";	
if (isset($_REQUEST['urut1']))
	$urut1 = $_REQUEST['urut1'];	
$urutan1 = "ASC";	
if (isset($_REQUEST['urutan1']))
	$urutan1 = $_REQUEST['urutan1'];

OpenDb();
?>
<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td>
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <input type="hidden" name="urut1" id="urut1" value="<?=$urut1 ?>" />
    <input type="hidden" name="urutan1" id="urutan1" value="<?=$urutan1 ?>" />
	<font size="2" color="#000000"><strong>Cari Siswa</strong></font>
 	</td>
</tr>
<tr>
    <td width="15%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td><select name="depart1" id="depart1" onChange="change_departemen(1)" style="width:150px" onkeypress="return focusNext('nis', event)">
    	<option value=-1>(Semua Departemen)</option>
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
   	<td rowspan="2" width="15%" align="center">
    <input type="button" class="but" name="submit" id="submit" value="Cari" onclick="carilah()" style="width:70px;height:40px"/>
    </td>
</tr>
<tr>
    <td><font color="#000000"><strong>N I S</strong></font></td>
    <td><input type="text" name="nis" id="nis" value="<?=$_REQUEST['nis'] ?>" size="22" onKeyPress="return focusNext('submit', event)"/>&nbsp;
        <font color="#000000"><strong>Nama </strong></font>	
        <input type="text" name="nama" id="nama" value="<?=$_REQUEST['nama'] ?>" size="20" onKeyPress="return focusNext('submit', event)"/>     
  	</td>   
</tr>
<tr>
    <td align="center" colspan="3">
    <hr />
	<div id="caritabel">
<?php 
if (isset($_REQUEST['submit']) || $_REQUEST['submit'] == 1) { 
	OpenDb();    
   	
	if ((strlen((string) $nama) > 0) && (strlen((string) $nis) > 0)) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY d.departemen, k.kelas, s.nama"; 	
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1, k.kelas ASC LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
		//$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.statusmutasi=0 AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid AND t.departemen = d.departemen ORDER BY d.departemen, k.kelas, s.nama"; 	
		
	} else if (strlen((string) $nama) > 0) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY d.departemen, k.kelas, s.nama"; 
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1, k.kelas ASC LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
	} else if (strlen((string) $nis) > 0) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat t, jbsakad.departemen d WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND s.alumni = 0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ";
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM jbsakad.siswa s,jbsakad.kelas k ,jbsakad.tingkat t, jbsakad.departemen d WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND s.alumni = 0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1, k.kelas ASC LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
	}
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris1);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	$result = QueryDb($sql); 
	if (@mysqli_num_rows($result)>0){
?>   
	
   	<table width="100%" id="table1" class="tab" align="center" border="1" bordercolor="#000000">
    <tr height="30" class="header" align="center">
        <td width="7%">No</td>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nis','<?=$urutan1?>','cari')">N I S <?=change_urut('s.nis',$urut1,$urutan1)?></td>
        <td widt="*" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('s.nama','<?=$urutan1?>','cari')">Nama <?=change_urut('s.nama',$urut1,$urutan1)?></td>
        <?php if ($departemen == -1)  { ?>
        <td width="15%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('t.departemen','<?=$urutan1?>','cari')">Dept. <?=change_urut('t.departemen',$urut1,$urutan1)?></td>
        <?php } ?>
        <td width="12%" onMouseOver="background='style/formbg2agreen.gif';height=30;" onMouseOut="background='style/formbg2.gif';height=30;" background="style/formbg2.gif" style="cursor:pointer;" onClick="change_urut('k.kelas','<?=$urutan1?>','cari')">Kelas <?=change_urut('t.tingkat',$urut1,$urutan1)?></td>
        <td width="10%">&nbsp;</td>
    </tr>
<?php
	$cnt = 0;
		while($row = mysqli_fetch_row($result)) { ?>
   	<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>')" style="cursor:pointer">
        <td align="center" ><?=++$cnt ?></td>
        <td align="center" ><?=$row[0] ?></td>
        <td align="left"><?=$row[1] ?></td>
        <?php if ($departemen == -1)  { ?>
        <td align="center"><?=$row[3] ?></td>
        <?php } ?>
        <td align="center"><?=$row[4].' - '.$row[2] ?></td>
        <td align="center"><input type="button" value="Pilih" onclick="pilih('<?=$row[0]?>','<?=$row[1]?>')" class="but"></td>
	</tr>
<?php } CloseDb(); ?>
 	</table>
    <?php  if ($page1==0){ 
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:visible;'";
	}
	if ($page1<$total && $page1>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:visible;'";
	}
	if ($page1==$total-1 && $page1>0){
		$disback="style='visibility:visible;'";
		$disnext="style='visibility:hidden;'";
	}
	if ($page1==$total-1 && $page1==0){
		$disback="style='visibility:hidden;'";
		$disnext="style='visibility:hidden;'";
	}
	?>
   
    <table border="0"width="100%" align="center"cellpadding="2" cellspacing="2">
    <tr>
       	<td width="30%" align="left"><font color="#000000">Hal
        <select name="hal1" id="hal1" onChange="change_hal('cari')">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal1,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
		<?php 
     	// Navigasi halaman berikutnya dan sebelumnya
        ?>
        </font></td>
    	<td align="center">
    	<input <?=$disback?> type="button" class="but" name="back" value=" << " onClick="change_page('<?=(int)$page1-1?>','cari')" >
		<?php
		for($a=0;$a<$total;$a++){
			if ($page1==$a){
				echo  "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo  "<a href='#' onClick=\"change_page('".$a."','cari')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="but" name="next" value=" >> " onClick="change_page('<?=(int)$page1+1?>','cari')" >
 		</td>
        <td width="30%" align="right"><font color="#000000">Jml baris per hal
      	<select name="varbaris1" id="varbaris1" onChange="change_baris('cari')">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris1,$m) ?>><?=$m ?></option>
        <?php 	} ?>
       
      	</select></font></td>
    </tr>
    </table>
<?php } else { ?>    		
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
	<tr height="200" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data</b></font>	
	<br /><br />
   		</td>
    </tr>
    </table>
<?php 	} 
} else { ?>

<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
<tr height="200" align="center">
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