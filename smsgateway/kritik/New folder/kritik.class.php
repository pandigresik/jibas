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
class Kritik{
	function Main(){
		global $LMonth;
		?>
		<link href="../style/style.css" rel="stylesheet" type="text/css" />
        <div align="left" style="padding-bottom:10px">
        	<table border="0" cellspacing="3" cellpadding="3">
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
                </select>                </td>
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
                </select>                </td>
              </tr>
              <tr>
                <td style="padding-right:4px">Jenis</td>
                <td colspan="2" style="padding-right:4px"><span style="padding-right:2px">
                  <select name="Type" class="Cmb" id="Type" onchange="ChgCmb()">
                    <?php
				if ($Type=="")
					$Type="kritik";
				?>
                    <option value="kritik" <?=StringIsSelected($Type,'kritik')?>>Kritik</option>
                    <option value="saran" <?=StringIsSelected($Type,'kritik')?>>Saran</option>
                    <option value="pesan" <?=StringIsSelected($Type,'kritik')?>>Pesan</option>
                  </select>
                </span></td>
              </tr>
            </table>
        </div>
        <div id="Kritik">
        <table width="100%" border="1" id="KritikTable" class="tab" cellspacing="0" cellpadding="0">
          <tr class="Header">
            <td>No</td>
            <td>No HP</td>
            <td>Tanggal</td>
            <td><?=ucfirst($Type) ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php
		  $ID  = "";
		  $sql = "SELECT replid,senddate,sender,`from`,`type`,message FROM kritiksaran WHERE YEAR(senddate)='$Year' AND MONTH(senddate)='$Month' AND `type`='$type' ORDER BY replid DESC";
		  $res = QueryDb($sql);
		  $num = @mysqli_num_rows($res);
		  if ($num>0){
		  $cnt=1;
		  while ($row = @mysqli_fetch_row($res)){
		  if ($ID=="")
		  	  $ID = $row[0];
		  else		
		  	  $ID .= ','.$row[0];
		  $style = "";
		  //if ($row['Status']=='0')
		  	//	$style = "font-weight:bold;";
				
		  $bg = "";
		  //if ($cnt%2==0)
		  	//	$bg = "background-color:#cfddd1;";		
		  ?>
          <tr style="cursor:pointer;<?=$bg?><?=$style?>" id="<?=$row[0]?>" >
            <td class="td" align="center" style="font-weight:normal" onclick="ReadMessage('<?=$row[0]?>');"><?=$cnt?></td>
            <td class="td Link" onclick="ReadMessage('<?=$row[0]?>');"><?=$row[2]?></td>
            <td class="td" onclick="ReadMessage('<?=$row[0]?>');"><?=FullDateFormat($row[1])?></td>
            <td class="td" onclick="ReadMessage('<?=$row[0]?>');">
			<?php
			//echo ucfirst($row[4])." : <br>";
			if (strlen((string) $row[5])>50)
				echo substr((string) $row[5],0,50)."...";
			else
				echo $row[5];
			?>
            </td>
            <td class="td" align="center"><img onclick="DeleteRow(this,'<?=$row[0]?>');" src="../images/ico/hapus.png" width="16" height="16" /></td>
          </tr>
          <?php
		  $cnt++;
		  }
		  }
		  ?>
          </table>
            <script language='JavaScript'>
				Tables('KritikTable', 1, 0);
			</script>
          <input type="hidden" id="CurrentKritikIdList" style="width:100%" value="<?=$ID?>" />
          </div>
        <?php
	}
	
}
?>