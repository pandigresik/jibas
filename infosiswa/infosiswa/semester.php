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
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');

$semester = $_REQUEST['semester']; 
$pelajaran = $_REQUEST['pelajaran']; 
$nis = $_REQUEST['nis'];
$kelas = $_REQUEST['kelas'];
?>
	
            <table width="100%" border="0" height="100%">
            <tr>
                <td valign="top">
                <?php 	OpenDb();
                    $sql = "SELECT * FROM pelajaran WHERE replid = '".$pelajaran."'";
                    $result = QueryDb($sql);
                    CloseDb();
                    $row = mysqli_fetch_array($result);
                    
                ?>	
                <font size="2" color="#000000"><b>Pelajaran <?=$row['nama']?></b></font></td>      	
                <td valign="top" align="right"> 
                <a href="JavaScript:cetak('<?=$semester?>',3)"><img src="../images/ico/print.png" border="0" onMouseOver="showhint('Cetak!', this, event, '50px')"/>&nbsp;Cetak</a>&nbsp;&nbsp; 
                </td>
            </tr>
   
            
	<?php OpenDb();
        $sql = "SELECT j.replid, j.jenisujian FROM jenisujian j, ujian u WHERE j.idpelajaran = '$pelajaran' AND u.idjenis = j.replid GROUP BY j.jenisujian";
        
        $result = QueryDb($sql);
		if (mysqli_num_rows($result) > 0) {
        while($row = @mysqli_fetch_array($result)){			
    ?>
           	<tr>
                <td colspan="2"> 
                <br>
                <fieldset><legend><strong> <?=$row['jenisujian']?></strong></legend>
                <br />
		<?php 	OpenDb();		
            $sql1 = "SELECT u.tanggal, n.nilaiujian, n.keterangan FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ORDER BY u.tanggal";
            $result1 = QueryDb($sql1);
			
			if (@mysqli_num_rows($result1) > 0){
		?>
           	
                <table border="1" width="100%" id="table19" class="tab">
                <tr class="header" align="center" height="30">		
                    <td width="5%">No</td>
                    <td width="20%">Tanggal</td>
                    <td width="10%">Nilai</td>
                    <td width="*">Keterangan</td>
                </tr>
       	<?php 
				$sql2 = "SELECT AVG(n.nilaiujian) as rata FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kelas' AND u.idpelajaran = '$pelajaran' AND u.idsemester = '".$semester."' AND u.idjenis = '".$row['replid']."' AND u.replid = n.idujian AND n.nis = '$nis' ";
				$result2 = QueryDb($sql2);	
            	$row2 = @mysqli_fetch_array($result2);
            	$rata = $row2['rata'];
            	$cnt = 1;
                while($row1 = @mysqli_fetch_array($result1)){			
         ?>
                <tr>        			
                    <td width="5" height="25" align="center"><?=$cnt?></td>
                    <td width="250" height="25" align="center"><?=format_tgl($row1[0])?></td>
                    <td width="10" height="25" align="center"><?=$row1[1]?></td>
                    <td height="25"><?=$row1[2]?></td>            
                </tr>	
        <?php 	$cnt++;
                } ?>
                <tr>        			
                    <td colspan="2" height="25" align="center"><strong>Nilai rata rata</strong></td>
                    <td width="10" height="25" align="center"><?=round($rata,2)?></td>
                    <td height="25">&nbsp;</td>            
                </tr>
                </table>
                             
        <?php } else { ?>
        		<table width="100%" border="0" align="center" id="table19">          
                <tr>
                    <td align="center" valign="middle" height="50">
                    <font size = "2" color ="red"><b>Tidak ditemukan adanya data.</b></font>
                    
                    </td>
                </tr>
                </table>
                <!--<tr>        			
                    <td colspan="4" height="25" align="center">Tidak ada nilai</td>
                </tr>-->
       	<?php } ?>
                 </fieldset>   
                </td>	
            </tr>
   	<?php } ?>      
	<?php } else { ?>
    		<tr>
            	<td align="center" valign="middle" height="50">
                <table border="0" width="100%" id="table1" cellpadding="0" cellspacing="0">
        		<tr align="center" valign="middle" >
        			<td><font size = "2" color ="red"><b>Tidak ditemukan adanya data.<br /></font></td>
        		</tr>
        		</table>
                </td>
          	</tr>
                
  	<?php } ?>
            </table>