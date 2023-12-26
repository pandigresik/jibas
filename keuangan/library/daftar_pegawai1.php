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

OpenDb();
$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];
	
$bagian = $_REQUEST['bagian'];	
?>

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
    <td>
    <input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />
    <font size="2"><strong>Daftar Pegawai</strong></font><br />
    </td>
</tr>
<tr>
	<td><font color="#000000"><strong>Bagian </strong></font>
    <select name="bag" id="bag" onChange="change_bagian()">
    <option value="-1" <?php if ($departemen=="-1") echo  "selected"; ?>>(Semua Bagian)</option>
	<?php  $sql_bagian="SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan ASC";
        $result_bagian=QueryDb($sql_bagian);
        while ($row_bagian=@mysqli_fetch_array($result_bagian)){
    ?>
      	<option value="<?=$row_bagian['bagian']?>" <?=StringIsSelected($row_bagian['bagian'],$bagian)?>>
      	<?=$row_bagian['bagian']?>
        </option>
      <?php }  ?>
    </select>
    <!--<input type="text" name="bagian" id="bagian" value="<?=$departemen ?>" size="20" readonly style="background-color:#CCCCCC" /> </strong>&nbsp;&nbsp;-->
    <?php if ($bagian == -1)  {        
        $sql_tambahbag = "";					
    } else	{        
        $sql_tambahbag = "AND p.bagian = '$bagian' "; 					
    } 
	?>
   </td>
</tr>
<tr>
	<td>
    <br />
    <?php
    OpenDb();
    //$sql = "SELECT p.nip, p.nama FROM jbssdm.pegawai p LEFT JOIN (guru g LEFT JOIN pelajaran l ON l.replid = g.idpelajaran AND l.departemen='SMP') ON p.nip = g.nip GROUP BY p.nip";
    $sql = "SELECT p.nip, p.nama, p.bagian FROM jbssdm.pegawai p WHERE p.aktif = 1 $sql_tambahbag GROUP BY p.nip ORDER BY p.nama";
   
    $result = QueryDb($sql);
	if (@mysqli_num_rows($result)>0){

	?>
    <!--<div id="tab_daftar">-->
    <table width="100%" id="table" class="tab" border="1" align="center" cellpadding="2" cellspacing="0">
    <tr height="30">
        <td class="header" width="7%" align="center">No</td>
        <td class="header" width="15%" align="center">N I P</td>
        <td class="header" >Nama</td>
        <?php if ($sql_tambahbag == "") { ?>
            <td class="header" align="center" >Bagian</td>          
            <?php } ?>
        <td class="header" width="10%" align="center">&nbsp;</td>
    </tr>
    <?php
    
    $cnt = 0;
    while($row = mysqli_fetch_row($result)) { ?>
    <tr height="25" onClick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" style="cursor:pointer">
        <td align="center"><?=++$cnt ?></td>
        <td align="center"><?=$row[0] ?></td>
        <td><?=$row[1] ?></td>
        <?php if ($sql_tambahbag == "") { ?>				
		<td align="center" ><?=$row[2]?></td> 
        <?php } ?>		
        <td align="center">
        <input type="button" name="pilih" class="but" id="pilih" value="Pilih" onClick="pilih('<?=$row[0]?>', '<?=$row[1]?>')" />
        </td>
    </tr>
    <?php 	} ?>
    </table>
<?php } else { ?>    		
	<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
	<tr height="30" align="center">
		<td>
	<br /><br />	
	<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />
			Tambah data pegawai pada bagian <?=$bagian?> di menu Kepegawaian pada bagian Referensi. </b></font>	
	<br /><br />
		</td>
	</tr>
	</table>
<?php } ?>	
    </td>    
</tr>
<tr>
	<td align="center" >
	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>
	</td>
</tr>
</table>