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

OpenDb();
$flag = 0;
if (isset($_REQUEST['flag']))
	$flag = (int)$_REQUEST['flag'];

$departemen = $_REQUEST['departemen'];
/*$sql_tambahdep = "AND pel.departemen = '$departemen' "; 	
if (isset($_REQUEST['departemen']) == -1){
	$sql_tambahdep = "";
}*/

$pelajaran = $_REQUEST['pelajaran'];
//echo 'pelajaran '.strlen($pelajaran).' pelajaran '.$pelajaran;
//$sql_tambahpel ="AND pel.replid=$pelajaran "; 


//if (isset($_REQUEST['pelajaran']) == -1 || strlen($pelajaran) == 0){	
//	$sql_tambahpel ="";
//} 


?>

<table border="0" width="100%" cellpadding="2" cellspacing="2" align="center">
<tr>
    <td colspan="2">
    <input type="hidden" name="flag" id="flag" value="<?=$flag ?>" />    </td>
</tr>     
<tr>
    <td width="9%"><font color="#000000"><strong>Departemen </strong></font><br /></td>
    <td width="91%"><select name="depart" id="depart" onchange="change_departemen()" style="width:80px;" onkeypress="return focusNext('pelajaran', event)">
      <!--<option value="-1" <?php if ($departemen=="-1") echo "selected"; ?>>(Semua)</option>-->
      <option value="-1" <?=StringIsSelected("-1", $departemen) ?>>(Semua)</option>
      <?php $dep = getDepartemen(SI_USER_ACCESS());    
		foreach($dep as $value) {
		if ($departemen == "")
			$departemen = $value; ?>
      <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> >
      <?=$value ?>
      </option>
      <?php } ?>
    </select>
      <?php if ($departemen == -1)  {
        $disable = 'disabled="disabled"';
        $sql_tambahdep = "";					
    } else	{
        $disable = "";
        $sql_tambahdep = "AND pel.departemen = '$departemen' "; 					
    } 
    
    ?></td>
</tr>
<tr>
  <td><font color="#000000"><strong>Pelajaran</strong></font></td>
  <td><select name="pelajaran" id="pelajaran" onchange="get_guru()" <?=$disable?> style="width:250px;">
    <option value="-1" <?php if ($pelajaran=="-1") echo "selected"; ?>>(Semua Pelajaran)</option>
    <?php
        $sql_pel="SELECT * FROM jbsakad.pelajaran pel WHERE pel.aktif=1 $sql_tambahdep ORDER BY pel.nama";
    
        $result_pel=QueryDb($sql_pel);
        while ($row_pel=@mysqli_fetch_array($result_pel)){
            
        ?>
    <option value="<?=$row_pel['replid']?>" <?=StringIsSelected($pelajaran,$row_pel['replid'])?>>
      <?=$row_pel['nama']?>
      </option>
    <?php
        }
        ?>
  </select>
    <?php if ($pelajaran == -1 || strlen((string) $pelajaran) == 0)  {
            $sql_tambahpel = "";					
        } else	{			
            $sql_tambahpel = "AND pel.replid = $pelajaran "; 					
        } 
    ?></td>
</tr>
<tr>
	<td colspan="2" align="center">
        <br />
        <?php 
        OpenDb();
		$sql = "SELECT p.nip, p.nama, pel.replid, pel.departemen FROM jbssdm.pegawai p, jbsakad.guru g, jbsakad.pelajaran pel, jbsakad.departemen d WHERE g.nip=p.nip AND g.idpelajaran=pel.replid AND pel.departemen = d.departemen AND g.aktif = 1 $sql_tambahpel $sql_tambahdep GROUP BY p.nip ORDER BY p.nama";
		
		$result = QueryDb($sql);
		if (@mysqli_num_rows($result)>0){
			
		?>
		
		<table width="100%" align="center" cellpadding="2" cellspacing="0" class="tab" border="1" id="table" bordercolor="#000000">
		<tr height="30">
			<td class="header" width="7%" align="center">No</td>
    		<td class="header" width="15%" align="center">N I P</td>
            <td class="header" align="center" >Nama</td>
            <?php if ($sql_tambahdep == "") { ?>
            <td class="header" align="center" >Departemen</td>          
            <?php } ?>
    		<td class="header" width="10%" align="center">&nbsp;</td>
		</tr>
		<?php
		
		$cnt = 0;
		while($row = @mysqli_fetch_row($result)) { 
			if ($sql_tambahdep == "") {
				unset($depart);
				unset($ajar);
				//$sql1 = "SELECT d.departemen, pel.departemen FROM pelajaran pel, departemen d, guru g WHERE g.idpelajaran = pel.replid AND g.nip = '".$row[0]."' AND pel.departemen = d.departemen GROUP BY pel.departemen ORDER BY d.urutan";					
				$sql1 = "SELECT pel.departemen, pel.replid FROM pelajaran pel, departemen d, guru g WHERE g.idpelajaran = pel.replid AND g.nip = '".$row[0]."' AND pel.departemen = d.departemen GROUP BY pel.departemen ORDER BY d.urutan, pel.nama";
				$result1 = QueryDb($sql1);
				$i = 0;
				while ($row1=mysqli_fetch_array($result1)) {									
					$depart[$i] = $row1['departemen'];
					$ajar[$i] =$row1['replid']; 	
					$i++;
				}
			} else {
				$depart[0] = $departemen;
			}
			
		?>
		<tr height="25" onClick="pilih('<?=$row[0]?>','<?=$row[1]?>','<?=$depart[0]?>','<?=$ajar[0]?>')" style="cursor:pointer">
			<td align="center"><?=++$cnt ?></td>              
    		<td align="center"><?=$row[0] ?></td>
    		<td align="left"><?=$row[1] ?></td>
         <?php if ($sql_tambahdep == "") { 				
				
			?>
            <td align="center"><?=implode(", ",$depart) ?></td>			
            <?php } ?>
    		<td align="center">
    		<input type="button" name="pilih" class="but" id="pilih" value="Pilih" onclick="pilih('<?=$row[0]?>', '<?=$row[1]?>', '<?=$depart[0]?>', '<?=$ajar[0]?>')" />    	   	</td>
		</tr>
		<?php 	} ?> 
        </table>
     
	<?php } else { ?>    		
        <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table">
		<tr height="30" align="center">
        	<td>
        <br /><br />	
		<font size = "2" color ="red"><b>Tidak ditemukan adanya data. <br /><br />
        <?php 	if ($pelajaran == -1 || strlen((string) $pelajaran) == 0) { ?>
        
			Tambah data guru pada departemen <?=$departemen?> di menu Pendataan Guru pada bagian Guru & Pelajaran. </b></font>	
		
        <?php  } else {
			$sql="SELECT * FROM jbsakad.pelajaran WHERE replid = $pelajaran";
			
			$result=QueryDb($sql);
			$row = mysqli_fetch_array($result);
		?> 		
        Tambah data guru yang akan mengajar pelajaran <?=$row['nama']?> pada departemen <?=$departemen?> di menu Pendataan Guru pada bagian Guru & Pelajaran. </b></font>
        <?php } ?>
        <br /><br />        	</td>
        </tr>
        </table>
	<?php } ?>	</td>    
</tr>
<tr>
	<td colspan="2" align="center" >
	<input type="button" class="but" name="tutup" id="tutup" value="Tutup" onclick="window.close()" style="width:80px;"/>	</td>
</tr>
</table>