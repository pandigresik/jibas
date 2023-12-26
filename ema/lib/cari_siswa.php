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
$nama = $_REQUEST['nama'];
$nis = $_REQUEST['nis'];
$departemen = $_REQUEST['departemen'];
$filter = "";
if ($departemen <> -1) 
	$filter = "AND t.departemen = '$departemen'";

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
<link href="../style/style.css" rel="stylesheet" type="text/css" />

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td>
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <input type="hidden" name="urut1" id="urut1" value="<?=$urut1 ?>" />
    <input type="hidden" name="urutan1" id="urutan1" value="<?=$urutan1 ?>" />
	<!--<font size="2" color="#000000"><strong>Cari Siswa</strong></font>--> 	</td>
</tr>
<tr>
    <td width="15%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td><select name="depart1" class="cmbfrm" id="depart1" style="width:150px" onChange="change_departemen(1)" onkeypress="return focusNext('nis', event)">
    	<option value=-1>(Semua Departemen)</option>
	<?php $sql = "SELECT departemen FROM  $db_name_akad.departemen ORDER BY urutan";
        $result = QueryDb($sql);
		while ($row=@mysqli_fetch_array($result)) {
			if ($departemen == "")
                $departemen = $row['departemen']; ?>
        <option value="<?=$row['departemen'] ?>" <?=StringIsSelected($row['departemen'], $departemen) ?> >
        <?=$row['departemen'] ?>
        </option>
        <?php } ?>
  	</select>    </td>
   	<td rowspan="2" width="15%" align="center">
    <input type="button" class="cmbfrm2" name="submit" id="submit" value="Cari" onclick="carilah()" style="width:70px;height:40px"/>    </td>
</tr>
<tr>
    <td colspan="2"><font color="#000000"><strong>N I S</strong></font>
      <input name="nis" type="text" class="inputtxt" id="nis" onKeyPress="return focusNext('submit', event)" value="<?=$_REQUEST['nis'] ?>" size="22"/>
      &nbsp;
        <font color="#000000"><strong>Nama </strong></font>	
        <input name="nama" type="text" class="inputtxt" id="nama" onKeyPress="return focusNext('submit', event)" value="<?=$_REQUEST['nama'] ?>" size="20"/>  	</td>
  </tr>
<tr>
    <td align="center" colspan="3">
    <hr />
	<div id="caritabel">
<?php 
if (isset($_REQUEST['submit']) || $_REQUEST['submit'] == 1) { 
	OpenDb();    
   	
	if ((strlen($nama) > 0) && (strlen($nis) > 0)) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM  $db_name_akad.siswa s, $db_name_akad.kelas k, $db_name_akad.tingkat t,  $db_name_akad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY d.departemen, k.kelas, s.nama"; 	
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM $db_name_akad.siswa s,$db_name_akad.kelas k,$db_name_akad.tingkat t, $db_name_akad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
		//$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM jbsakad.siswa s,jbsakad.kelas k,jbsakad.tingkat t, jbsakad.departemen d WHERE s.nama LIKE '%$nama%' AND s.nis LIKE '%$nis%' AND k.replid=s.idkelas AND s.statusmutasi=0 AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid AND t.departemen = d.departemen ORDER BY d.departemen, k.kelas, s.nama"; 	
		
	} else if (strlen($nama) > 0) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM $db_name_akad.siswa s,$db_name_akad.kelas k,$db_name_akad.tingkat t, $db_name_akad.departemen d WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY d.departemen, k.kelas, s.nama"; 
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM $db_name_akad.siswa s,$db_name_akad.kelas k,$db_name_akad.tingkat t, $db_name_akad.departemen d WHERE s.nama LIKE '%$nama%' AND k.replid=s.idkelas AND s.alumni=0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
	} else if (strlen($nis) > 0) {
		$sql_tot = "SELECT s.nis, s.nama, k.kelas, t.departemen FROM $db_name_akad.siswa s,$db_name_akad.kelas k ,$db_name_akad.tingkat t, $db_name_akad.departemen d WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND s.alumni = 0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ";
		$sql = "SELECT s.nis, s.nama, k.kelas, t.departemen, t.tingkat FROM $db_name_akad.siswa s,$db_name_akad.kelas k ,$db_name_akad.tingkat t, $db_name_akad.departemen d WHERE k.replid=s.idkelas AND s.nis LIKE '%$nis%' AND s.alumni = 0 AND s.aktif=1 AND k.idtingkat = t.replid $filter GROUP BY s.nis ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1"; 	
	}
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris1);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	$result = QueryDb($sql); 
	if (@mysqli_num_rows($result)>0){
?>   
	
   	<table width="100%" id="table1" class="tab" align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
    <tr height="30" class="header" align="center">
        <td width="7%">No</td>
        <td width="15%">N I S</td>
        <td width="*">Nama</td>
        <?php if ($departemen == -1)  { ?>
        <td width="15%">Dept. </td>
        <?php } ?>
        <td width="12%" >Kelas </td>
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
        <td align="center"><input type="button" value="Pilih" onclick="pilih('<?=$row[0]?>','<?=$row[1]?>')" class="cmbfrm2"></td>
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
        <select name="hal1" class="cmbfrm" id="hal1" onChange="change_hal('cari')">
        <?php for ($m=0; $m<$total; $m++) {?>
             <option value="<?=$m ?>" <?=IntIsSelected($hal1,$m) ?>><?=$m+1 ?></option>
        <?php } ?>
     	</select>
	  	dari <?=$total?> hal
		
		<?php 
     	// Navigasi halaman berikutnya dan sebelumnya
        ?>
        </font></td>
    	<!--td align="center">
    	<input <?=$disback?> type="button" class="cmbfrm2" name="back" value=" << " onClick="change_page('<?=(int)$page1-1?>','cari')" >
		<?php
		for($a=0;$a<$total;$a++){
			if ($page1==$a){
				echo "<font face='verdana' color='red'><strong>".($a+1)."</strong></font> "; 
			} else { 
				echo "<a href='#' onClick=\"change_page('".$a."','cari')\">".($a+1)."</a> "; 
			}
				 
	    }
		?>
	     <input <?=$disnext?> type="button" class="cmbfrm2" name="next" value=" >> " onClick="change_page('<?=(int)$page1+1?>','cari')" > 		</td-->
        <td width="30%" align="right"><font color="#000000">Jml baris per hal
      	<select name="varbaris1" class="cmbfrm" id="varbaris1" onChange="change_baris('cari')">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris1,$m) ?>><?=$m ?></option>
        <?php 	} ?>
      	</select>
        </font></td>
    </tr>
    </table>
<?php } else { ?>    		
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />            
		Tambah data siswa di menu Pendataan Siswa pada bagian Kesiswaan. </b></font>	
	<br /><br />   		</td>
    </tr>
    </table>
<?php 	} 
} else { ?>

<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
<tr height="30" align="center">
    <td>   

<br /><br />	
<font size="2" color="#757575"><b>Klik pada tombol "Cari" di atas untuk melihat data calon siswa <br />sesuai dengan NIS atau Nama Siswa berdasarkan <i>keyword</i> yang dimasukkan</b></font>	
<br /><br />    </td>
</tr>
</table>


<?php }?>	
    </div>	 </td>    
</tr>
<tr>
	<td align="center" colspan="3">
	<input type="button" class="cmbfrm2" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>	</td>
</tr>
</table>