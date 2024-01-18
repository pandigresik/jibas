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
class Inbox{
	function Main(){
		global $LMonth;
		?>
		<link href="../style/style.css" rel="stylesheet" type="text/css" />
        <div align="left" style="padding-bottom:10px">
        	<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="padding-right:4px">Bulan</td>
                <td style="padding-right:4px">
                <select id="Month" class="Cmb" onchange="ChgCmb()">
                    <?php
                    for ($i=1; $i<=12; $i++){
						if ($Month=='')
							$Month = date('m');
                        ?>
                        <option value="<?=$i?>" <?=StringIsSelected($i,$Month)?>><?=$LMonth[$i-1]?></option>
                        <?php
                    }
                    ?>
                </select>
                </td>
                <td style="padding-right:2px">
                <select id="Year" class="Cmb" onchange="ChgCmb()">
                    <?php
                    for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                        if ($Year=='')
							$Year = date('Y');
						?>
                        <option value="<?=$i?>" <?=StringIsSelected($i,$Year)?>><?=$i?></option>
                        <?php
                    }
                    ?>
                </select>
                </td>
              </tr>
            </table>
        </div>
        <div id="Inbox">
        <table width="100%" border="1" id="InboxTable" class="tab" cellspacing="0" cellpadding="0">
          <tr class="Header">
            <td>No</td>
            <td>No HP</td>
            <td>Tanggal</td>
            <td>Pesan</td>
            <td>&nbsp;</td>
          </tr>
          <?php
		  $ID  = "";
		  $sql = "SELECT * FROM inbox WHERE YEAR(ReceivingDateTime)='$Year' AND MONTH(ReceivingDateTime)='$Month' ORDER BY ID DESC";
		  $res = QueryDb($sql);
		  $num = @mysqli_num_rows($res);
		  if ($num>0){
		  $cnt=1;
		  while ($row = @mysqli_fetch_array($res)){
		  if ($ID=="")
		  	  $ID = $row['ID'];
		  else		
		  	  $ID .= ','.$row['ID'];
		  $style = "";
		  if ($row['Status']=='0')
		  		$style = "font-weight:bold;";
				
		  $bg = "";
		  //if ($cnt%2==0)
		  	//	$bg = "background-color:#cfddd1;";		
		  $nohp  = str_replace("+62","",(string) $row['SenderNumber']);	
          $sqlph = "SELECT nama FROM phonebook WHERE nohp LIKE '%$nohp'";
		  $resph = QueryDb($sqlph);
		  $rowph = @mysqli_fetch_row($resph);
		  $nama  = $rowph[0];
		  ?>
          <tr style="cursor:pointer;<?=$bg?><?=$style?>" id="<?=$row['ID']?>" >
            <td class="td" align="center" style="font-weight:normal" onclick="ReadMessage('<?=$row['ID']?>');"><?=$cnt?></td>
            <td class="td Link" onclick="ReadMessage('<?=$row['ID']?>');">(<?=$row['SenderNumber']?>) <?php echo $nama ?></td>
            <td class="td" onclick="ReadMessage('<?=$row['ID']?>');"><?=FullDateFormat($row['ReceivingDateTime'])?></td>
            <td class="td" onclick="ReadMessage('<?=$row['ID']?>');">
			<?php
			if (strlen((string) $row['Text'])>50)
				echo substr((string) $row['Text'],0,50)."...";
			else
				echo $row['Text'];
			?>
            </td>
            <td class="td" align="center"><img onclick="DeleteRow(this,'<?=$row['ID']?>');" src="../images/ico/hapus.png" width="16" height="16" /></td>
          </tr>
          <?php
		  $cnt++;
		  }
		  }
		  ?>
          </table>
            <script language='JavaScript'>
				Tables('InboxTable', 1, 0);
			</script>
          <input type="hidden" id="CurrentInboxIdList" style="width:100%" value="<?=$ID?>" />
          </div>
        <?php
	}
	
}
?>