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
require_once('../inc/getheader.php');
require_once('../inc/db_functions.php');

$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
$nama = $_REQUEST['nama'];
$nip = $_REQUEST['nip'];
$bagian = $_REQUEST['bagian'];

$varbaris1=10;
if (isset($_REQUEST['varbaris1']))
	$varbaris1 = $_REQUEST['varbaris1'];
$page1=0;
if (isset($_REQUEST['page1']))
	$page1 = $_REQUEST['page1'];
$hal1=0;
if (isset($_REQUEST['hal1']))
	$hal1 = $_REQUEST['hal1'];
$urut1 = "nama";	
if (isset($_REQUEST['urut1']))
	$urut1 = $_REQUEST['urut1'];	
$urutan1 = "ASC";	
if (isset($_REQUEST['urutan1']))
	$urutan1 = $_REQUEST['urutan1'];

OpenDb();
?>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #CCCCCC}
-->
</style>

<table border="0" width="100%" cellspacing="2" align="center">
<tr>
	<td colspan="2">
	<input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <input type="hidden" name="urut1" id="urut1" value="<?=$urut1 ?>" />
    <input type="hidden" name="urutan1" id="urutan1" value="<?=$urutan1 ?>" />
	<!--<font size="2" color="#000000"><strong>Cari Pegawai</strong></font>--> 	</td>
</tr>
<tr>
	<td width="10%" class="news_content1"><strong><font color="#000000">Bagian  </font></strong></td>
    <td><select name="bag" class="cmbfrm" id="bag" style="width:135px" onChange="change_bagian('cari')" onKeyPress="return focusNext('nip', event)">
    	<option value="-1" <?php if ($bagian=="-1") echo "selected"; ?>>(Semua Bagian)</option>
	<?php  $sql_bagian="SELECT bagian FROM  $db_name_sdm.bagianpegawai ORDER BY urutan ASC";
        $result_bagian=QueryDb($sql_bagian);
        while ($row_bagian=@mysqli_fetch_array($result_bagian)){
    ?>
      	<option value="<?=$row_bagian['bagian']?>" <?=StringIsSelected($row_bagian['bagian'],$bagian)?>>
      	<?=$row_bagian['bagian']?>
        </option>
      <?php }  ?>
    	</select>
    <!--<input type="text" name="bagian" id="bagian" value="<?=$departemen ?>" size="20" readonly style="background-color:#CCCCCC" /> </strong>&nbsp;&nbsp;-->   </td>
   <td rowspan="2" width="15%" align="center">
   <input type="button" class="cmbfrm2" name="submit" id="submit" value="Cari" onclick="carilah();"  style="width:70px;height:40px"/>   </td>
</tr>
<tr>
	<td width="10%" class="news_content1"><strong><font color="#000000">N I P </font></strong></td>
<td><input name="nip" type="text" class="inputtxt" id="nip" onKeyPress="return focusNext('submit', event);" value="<?=$_REQUEST['nip'] ?>" size="20" />&nbsp;
		<span class="news_content1"><font color="#000000"><b>Nama</b></font></span><font color="#000000"><b> &nbsp;&nbsp;</b></font>
    	<input name="nama" type="text" class="inputtxt" id="nama" onKeyPress="return focusNext('submit', event);" value="<?=$_REQUEST['nama'] ?>" size="20" /></td>
</tr>

<tr>
	<td colspan="3">
	<hr />
    <div id = "caritabel">
<?php
if (isset($_REQUEST['submit']) || $_REQUEST['submit'] == 1) { 
	  	
	if ($bagian == -1)  {        
        $sql_tambahbag = "";					
    } else	{        
        $sql_tambahbag = "AND bagian = '$bagian' "; 					
    } 
	
	OpenDb();
	
	if ((strlen((string) $nama) > 0) && (strlen((string) $nip) > 0)) {
		$sql_tot = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nama LIKE '%$nama%' AND nip LIKE '%$nip%' $sql_tambahbag ORDER BY nama"; 
		$sql_pegawai = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nama LIKE '%$nama%' AND nip LIKE '%$nip%' $sql_tambahbag ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1";
		//$sql = "SELECT nip, nama, bagian FROM jbssdm.pegawai WHERE nama LIKE '%$nama%' AND nip LIKE '%$nip%' $sql_tambahbag ORDER BY nama"; 
	} else if (strlen((string) $nama) > 0) {
		$sql_tot = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nama LIKE '%$nama%' $sql_tambahbag ORDER BY nama"; 
		$sql_pegawai = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nama LIKE '%$nama%' $sql_tambahbag ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1";
	} else if (strlen((string) $nip) > 0) {
		$sql_tot = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nip LIKE '%$nip%' $sql_tambahbag ORDER BY nama"; 		
		$sql_pegawai = "SELECT nip, nama, bagian FROM  $db_name_sdm.pegawai WHERE aktif = 1 AND nip LIKE '%$nip%' $sql_tambahbag ORDER BY $urut1 $urutan1 LIMIT ".(int)$page1*(int)$varbaris1.",$varbaris1";
	} 
	
	$result_tot = QueryDb($sql_tot);
	$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris1);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;
	$result = QueryDb($sql_pegawai);
	if (@mysqli_num_rows($result)>0){ ?>

    <table width="100%" class="tab" cellpadding="2" cellspacing="0" id="table1" border="1" align="center" bordercolor="#000000">
    <tr height="30" class="header" align="center">
        <td width="7%">No</td>
        <td width="15%">N I P </td>
        <td>Nama </td>
        <?php if ($sql_tambahbag == "") { ?>
        <td width="25%">Bagian </td>
        <?php } ?>       
        <td width="10%">&nbsp;</td>
    </tr>
<?php if ($page1==0)
		$cnt = 0;
	else 
		$cnt = (int)$page1*(int)$varbaris1;
		
	while($row = mysqli_fetch_row($result)) { ?>
    <tr height="25"  onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer"> 
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row[0] ?></td>
        <td><?=$row[1] ?></td>
        <?php if ($sql_tambahbag == "") { ?>	
        <td align="center"><?=$row[2] ?></td>
        <?php } ?>
        <td align="center">
        <input type="button" name="pilih" class="cmbfrm2" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" />        </td>
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
       	<td width="30%" align="left"><font color="#000000" class="news_content1">Hal
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
        <td width="30%" align="right"><span class="news_content1"><font color="#000000">Jml baris per hal
      	</font></span><font color="#000000">
      	<select name="varbaris1" class="cmbfrm" id="varbaris1" onChange="change_baris('cari')">
        <?php 	for ($m=5; $m <= $akhir; $m=$m+5) { ?>
        	<option value="<?=$m ?>" <?=IntIsSelected($varbaris1,$m) ?>><?=$m ?></option>
        <?php 	} ?>
      	</select></font></td>
    </tr>
    </table>
<?php } else { ?>    		
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
	<tr height="30" align="center">
		<td>   
   
	<br /><br />	
	<font color ="red" size = "2" class="err"><b>Tidak ditemukan adanya data.  </b></font>	
	<br /><br />   		</td>
    </tr>
    </table>
<?php 	}  
} else { ?>

<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
<tr height="30" align="center">
    <td>   

<br /><br />	
<span class="style1"><font size="2" class="welc"><b>Klik pada tombol "Cari" di atas untuk melihat data pegawai <br />
sesuai dengan NIP atau Nama Pegawai berdasarkan <i>keyword</i> yang dimasukkan</b></font>	
<br />
</span><br />    </td>
</tr>
</table>


<?php }?>	
    </div>    </td>    
</tr>
<tr>
	<td align="center" colspan="3" height="30">
	<input type="button" class="cmbfrm2" name="tutup" id="tutup" value="Tutup" onclick="window.close();opener.tutup();" style="width:80px;"/>	</td>
</tr>
</table>
</table>

</body>
</html>