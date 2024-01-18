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
class NewInbox{
	function OnStart(){
		$op = $_REQUEST['op'];
	
		//Source InboxID
		$CurrentInboxIdList = $_REQUEST['CurrentInboxIdList'];
		$this->CurrentInboxIdList = $CurrentInboxIdList;

		$InboxID = $_REQUEST['InboxID'];
		$this->InboxID = $InboxID;

		$TxtToReply = CQ($_REQUEST['TxtToReply']);
		$this->TxtToReply = trim((string) $TxtToReply);

		$ID = $_REQUEST['ID'];
		$this->ID = $ID;

		$Year = $_REQUEST['Year'];
		$this->Year = $Year;

		$Month = $_REQUEST['Month'];
		$this->Month = $Month;
		
		if ($op=='GetNewID')
			$this->GetNewID();
		if ($op=='GetInboxList')
			$this->GetInboxList();
		if ($op=='GetMessage')
			$this->GetMessage();
		if ($op=='GetMessageReaden')
			$this->GetMessageReaden();
		if ($op=='ReplyMessage')
			$this->ReplyMessage();
		if ($op=='DeleteSMS')
			$this->DeleteSMS();
	}
	function DeleteSMS(){
		$sql = "DELETE FROM inbox WHERE  ID='$this->InboxID'";
		$res = QueryDb($sql);
	}
	function GetNewID(){
		$sql = "SELECT * FROM inbox WHERE YEAR(ReceivingDateTime)='$this->Year' AND MONTH(ReceivingDateTime)='$this->Month' AND ID NOT IN (".$this->CurrentInboxIdList.") ORDER BY ID DESC";
		$res = QueryDb($sql);
		$num = @mysqli_num_rows($res);
		$IDList = "";
		if ($num>0){
			$cnt = 1;
			while ($row = @mysqli_fetch_array($res)){
				  if ($IDList=="")
					  $IDList = $row['ID'];
				  else		
					  $IDList .= ','.$row['ID'];
				?>
				<input type="text" id="Content<?=$row['ID']?>" style="width:100%" value="<span class='Link'><?=$row['SenderNumber']?></span>#><?=FullDateFormat($row['ReceivingDateTime'])?>#>
				<?php
				if (strlen((string) $row['Text'])>50)
					echo substr((string) $row['Text'],0,50)."...";
				else
					echo $row['Text'];
				?>
				#><?=$row['ID']?>" />
				<?php
			}
		}
		?>
		<input type="text" id="NewInboxIdList" style="width:100%" value="<?=$IDList?>" />
		<input type="text" id="NumInboxIdList" style="width:100%" value="<?=$num?>" />
		<?php
	}
	function GetMessage(){
		$sql = "SELECT * FROM inbox WHERE ID = '$this->InboxID'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		?>
        <table width="100%" border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td colspan="2" align="right" valign="top" style="padding-bottom:10px"><img title="Close" onclick="HidePopup()" src="../images/ico/hapus.png" width="16" height="16" /></td>
          </tr>
          <tr>
            <td width="16%" align="left" class="Link"><?=$row['SenderNumber']?></td>
            <td width="84%" align="right" class="Ket"><?=FullDateFormat($row['ReceivingDateTime'])?></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:justify; padding-top:10px"><?=$row['Text']?></td>
          </tr>
          <tr>
            <td colspan="2" style="padding-top:5px" align="center">
                <div align="left" id="ReplyLabel" style="display:none; font-weight:bold">
                Balas :
                </div>
                <textarea id="TxtReply" class="AreaTxt" rows="7" style="width:98%;display:none"></textarea>
                <div class="BtnSilver" align="center" id="BtnReply" style="display:block" onclick="BalasSMS('1')">Balas</div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px">
                  <tr>
                    <td align="right" width="50%" style="padding-right:3px"><div class="BtnSilver" align="center" id="BtnSend" style="display:none; " onclick="KirimSMS('<?=$row['ID']?>')">Kirim</div></td>
                    <td align="left" style="padding-left:3px"><div class="BtnSilver" align="center" id="BtnCancel" style="display:none" onclick="BalasSMS('0')">Batal</div></td>
                  </tr>
                </table>
            </td>
          </tr>
        </table>
        <?php
	}
	function GetMessageReaden(){
		$sql = "UPDATE inbox SET Status=1 WHERE ID = '$this->InboxID'";
		QueryDb($sql);
	}
	function ReplyMessage(){
		$idsmsgeninfo = GetLastId('replid','smsgeninfo');	
		$sql = "INSERT INTO smsgeninfo SET replid='$idsmsgeninfo',tanggal=now(),tipe='3'";
		$res = QueryDb($sql);

		$sql = "SELECT SenderNumber FROM inbox WHERE ID = '$this->ID'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		$sender = $row[0];

		$sql = "INSERT INTO outbox SET InsertIntoDB=now(), SendingDateTime=now(), Text='$this->TxtToReply', DestinationNumber='$sender', SenderID='$sender', CreatorID='$sender', idsmsgeninfo=$idsmsgeninfo";
		//echo $sql; exit;
		QueryDb($sql);

	}
	function GetInboxList(){
		?>
		<table width="100%" border="1" id="InboxTable" class="tab" cellspacing="0" cellpadding="0">
          <tr class="Header">
            <td>No</td>
            <td>Pengirim</td>
            <td>Tanggal</td>
            <td>Pesan</td>
            <td>&nbsp;</td>
          </tr>
          <?php
		  $ID  = "";
		  $sql = "SELECT * FROM inbox WHERE YEAR(ReceivingDateTime)='$this->Year' AND MONTH(ReceivingDateTime)='$this->Month' ORDER BY ID DESC";
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
          <tr style="cursor:pointer;<?=$bg?><?=$style?>" id="<?=$row['ID']?>">
            <td class="td" align="center" style="font-weight:normal" onclick="ReadMessage('<?=$row['ID']?>');"><?=$cnt?></td>
            <td class="td Link" onclick="ReadMessage('<?=$row['ID']?>');">(<?=$row['SenderNumber']?>) <?php echo $nama ?></td>
            <td class="td" onclick="ReadMessage('<?=$row['ID']?>');" ><?=FullDateFormat($row['ReceivingDateTime'])?></td>
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
		<?php
    }
}
?>