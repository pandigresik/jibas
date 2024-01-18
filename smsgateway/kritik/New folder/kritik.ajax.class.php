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
class NewKritik{
	function OnStart(){
		$op = $_REQUEST['op'];
	
		//Source InboxID
		$CurrentKritikIdList = $_REQUEST['CurrentKritikIdList'];
		$this->CurrentKritikIdList = $CurrentKritikIdList;

		$KritikID = $_REQUEST['KritikID'];
		$this->KritikID = $KritikID;

		$TxtToReply = $_REQUEST['TxtToReply'];
		$this->TxtToReply = trim((string) $TxtToReply);

		$ID = $_REQUEST['ID'];
		$this->ID = $ID;

		$Year = $_REQUEST['Year'];
		$this->Year = $Year;

		$Month = $_REQUEST['Month'];
		$this->Month = $Month;
		
		$Type = $_REQUEST['Type'];
		$this->Type = $Type;
		
		if ($op=='GetNewID')
			$this->GetNewID();
		if ($op=='GetKritikList')
			$this->GetKritikList();
		if ($op=='GetMessage')
			$this->GetMessage();
		if ($op=='GetMessageReaden')
			$this->GetMessageReaden();

		if ($op=='DeleteSMS')
			$this->DeleteSMS();
	}
	function DeleteSMS(){
		$sql = "DELETE FROM kritiksaran WHERE  replid='$this->KritikID'";
		$res = QueryDb($sql);
	}
	function GetNewID(){
		$sql = "SELECT * FROM kritiksaran WHERE YEAR(senddate)='$this->Year' AND MONTH(senddate)='$this->Month' AND replid NOT IN (".$this->CurrentKritikIdList.") ORDER BY replid DESC";
		$res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		$IDList = "";
		if ($num>0){
			$cnt = 1;
			while ($row = @mysqli_fetch_array($res)){
				  $IDList = ($IDList=="")?$row['replid']:$IDList.','.$row['replid'];
				  
				  $msg 	  = (strlen((string) $row['message'])>50)?substr((string) $row['message'],0,50)."...":$row['message'];
				?>
				<input type="text" id="Content<?=$row['replid']?>" style="width:100%" value="<span class='Link'><?=$row['sender']?></span>#><?=FullDateFormat($row['senddate'])?>#><?=$msg	?>#><?=$row['replid']?>#><?=$row['from']?>" />
				<?php
			}
		}
		?>
		<input type="text" id="NewKritikIdList" style="width:100%" value="<?=$IDList?>" />
		<input type="text" id="NumKritikIdList" style="width:100%" value="<?=$num?>" />
		<?php
	}
	function GetMessage(){
		$sql = "SELECT * FROM kritiksaran WHERE replid = '$this->KritikID'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		?>
        <table width="100%" border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td colspan="2" align="right" valign="top" style="padding-bottom:10px"><img title="Close" onclick="HidePopup()" src="../images/ico/hapus.png" width="16" height="16" /></td>
          </tr>
          <tr>
            <td width="16%" align="left" class="Link"><?=$row['sender']?></td>
            <td width="84%" align="right" class="Ket"><?=FullDateFormat($row['senddate'])?></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:justify; padding-top:10px"><?=$row['message']?></td>
          </tr>
          
        </table>
        <?php
	}
	function GetMessageReaden(){
		$sql = "UPDATE kritiksaran SET Status=1 WHERE replid = '$this->KritikID'";
		QueryDb($sql);
	}
	
	function GetKritikList(){
		?>
		<table width="100%" border="1" id="KritikTable" class="tab" cellspacing="0" cellpadding="0">
          <tr class="Header">
            <td>No</td>
            <td>Pengirim</td>
            <td>Tanggal</td>
            <td><?=ucfirst((string) $this->Type) ?></td>
            <td>&nbsp;</td>
          </tr>
          <?php
		  $ID  = "";
		  $sql = "SELECT replid,senddate,sender,`from`,`type`,message FROM kritiksaran WHERE YEAR(senddate)='$this->Year' AND MONTH(senddate)='$this->Month' AND `type`='$this->Type' ORDER BY replid DESC";
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
		  
		  $nohp  = str_replace("+62","",(string) $row[2]);	
          $sqlph = "SELECT nama FROM phonebook WHERE nohp LIKE '%$nohp'";
		  $resph = QueryDb($sqlph);
		  $rowph = @mysqli_fetch_row($resph);
		  $nama  = $rowph[0];
		  ?>
          <tr style="cursor:pointer;<?=$bg?><?=$style?>" id="<?=$row[0]?>" >
            <td class="td" align="center" style="font-weight:normal" onclick="ReadMessage('<?=$row[0]?>');"><?=$cnt?></td>
            <td class="td Link" onclick="ReadMessage('<?=$row[0]?>');"><?=$row[2]?> &lt;<?=$nama?>&gt;</td>
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
		<?php
    }
}
?>