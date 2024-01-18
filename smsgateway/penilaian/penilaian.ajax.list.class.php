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
class PenilaianListAjax{
	function OnStart(){
		$op = $_REQUEST['op'];
	
		//Source
		$Bln = $_REQUEST['Bln'];
		$this->Bln = $Bln;
		$Thn = $_REQUEST['Thn'];
		$this->Thn = $Thn;
		$replid = $_REQUEST['replid'];
		$this->IdInfoGen = $replid;

		$OutboxID = $_REQUEST['OutboxID'];
		$this->OutboxID = $OutboxID;

		$DestNumb = $_REQUEST['DestNumb'];
		$this->DestNumb = str_replace(' 62','0',(string) $DestNumb);
		
		$Txt = CQ($_REQUEST['Txt']);
		$this->Txt = $Txt;
		
		if ($op=='GetInfoGenList')
			$this->GetInfoGenList();
		if ($op=='GetDetailInfoGenList')
			$this->GetDetailInfoGenList($this->IdInfoGen);
		if ($op=='DeleteInfoGenList')
			$this->DeleteInfoGenList($this->IdInfoGen);

		if ($op=='DeleteDetailInfoGenList')
			$this->DeleteDetailInfoGenList();
		
		if ($op=='ResendDetailInfoGenList')
			$this->ResendDetailInfoGenList();
		
		if ($op=='ResendDetailInfoGenList2')
			$this->ResendDetailInfoGenList2();
	}
	function ResendDetailInfoGenList2(){
		$sql = "SELECT * FROM outboxhistory WHERE ID='".$this->OutboxID."'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		$IdInfoGen = $row['idsmsgeninfo'];
		$SenderID = $row['SenderID'];
		$CreatorID = $row['SenderID'];
		
		$sql = "INSERT INTO outbox SET InsertIntoDB=now(), SendingDateTime=now(), Text='$this->Txt', DestinationNumber='$this->DestNumb', SenderID='$SenderID',CreatorID='$CreatorID', idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);

		$sql = "INSERT INTO outboxhistory SET InsertIntoDB=now(), SendingDateTime=now(), Text='$this->Txt', DestinationNumber='$this->DestNumb', SenderID='$SenderID', idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);
		
		$this->GetDetailInfoGenList($IdInfoGen);
	}
	function ResendDetailInfoGenList(){
		$sql = "SELECT * FROM outboxhistory WHERE ID='".$this->OutboxID."'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		$IdInfoGen = $row['idsmsgeninfo'];
		$SendingDateTime = $row['SendingDateTime'];
		$Text = $row['Text'];
		$DestinationNumber = $row['DestinationNumber'];
		$SenderID = $row['SenderID'];
		$CreatorID = $row['SenderID'];
		
		$sql = "INSERT INTO outbox SET InsertIntoDB=now(), SendingDateTime='$SendingDateTime', Text='$Text', DestinationNumber='$DestinationNumber', SenderID='$SenderID',CreatorID='$CreatorID', idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);

		$sql = "INSERT INTO outboxhistory SET InsertIntoDB=now(), SendingDateTime='$SendingDateTime', Text='$Text', DestinationNumber='$DestinationNumber', SenderID='$SenderID', idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);
		
		$this->GetDetailInfoGenList($IdInfoGen);
	}
	function DeleteDetailInfoGenList(){
		$sql = "SELECT idsmsgeninfo FROM outboxhistory WHERE ID='".$this->OutboxID."'";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_row($res);
		$this->IdInfoGen = $row[0];

		$sql = "DELETE FROM outboxhistory WHERE ID='$this->OutboxID'";
		QueryDb($sql);
		$sql = "DELETE FROM outbox WHERE ID='$this->OutboxID'";
		QueryDb($sql);
		$this->GetDetailInfoGenList($this->IdInfoGen);
	}
	function DeleteInfoGenList($IdInfoGen){
		$sql = "DELETE FROM outbox WHERE idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);
		$sql = "DELETE FROM outboxhistory WHERE idsmsgeninfo='$IdInfoGen'";
		QueryDb($sql);
		$sql = "DELETE FROM smsgeninfo WHERE replid='$IdInfoGen'";
		QueryDb($sql);
		$this->GetInfoGenList();
	}
	function GetInfoGenList(){
		?>
		<table width="300" class="tab" border="1" cellspacing="0" cellpadding="0" id="Table1">
		  <tr class="Header">
			<td>No</td>
			<td>Tanggal</td>
			<td>&nbsp;</td>
		  </tr>
		  <?php
		  $sql = "SELECT replid,info FROM smsgeninfo WHERE tipe=1 AND YEAR(tanggal)='".$this->Thn."' AND MONTH(tanggal)='".$this->Bln."' ORDER BY replid DESC";
		  //echo $sql;
		  $res = QueryDb($sql);
		  $num = @mysqli_num_rows($res);
		  if ($num>0){
		  $cnt = 1;
		  while ($row = @mysqli_fetch_row($res)){
			  ?>
			  <tr class="td">
				<td align="center" valign="top" class="td"><?=$cnt?></td>
				<td class="td" style="cursor:pointer" onclick="SelectInfoGenList('<?=$row[0]?>')"><?=$row[1]?></td>
				<td class="td" align="center"><img onclick="DeleteInfoGenList('<?=$row[0]?>')" src="../images/ico/hapus.png" width="16" height="16" border="0" style="cursor:pointer" /></td>
			  </tr>
			  <?php
			  $cnt++;
		      }  
		  } else {
		  ?>
		  <tr>
			<td class="td Ket" align="center" colspan='3' >Tidak ada data</td>
		  </tr>
		  <?php
		  }
		  ?>
		</table>
		<?php
	}
	function GetDetailInfoGenList($id){
		?>
        <table width="97%" border="1" cellspacing="0" cellpadding="0" class="tab" id="Table2">
          <tr class="Header">
            <td>No</td>
            <td>HP</td>
            <td>Pesan</td>
            <td>&nbsp;</td>
          </tr>
          <?php
		  $sql = "SELECT * FROM outboxhistory WHERE idsmsgeninfo='$id'";
		  $res = QueryDb($sql);
		  $num = @mysqli_num_rows($res);
		  if ($num>0){
		  $cnt = 1;
		  while ($row = @mysqli_fetch_array($res)){
			  ?>
              <tr>
                <td align="center" class="tdTop" valign="top"><?=$cnt?></td>
                <td class="tdTop" valign="top">
				<span id='SpanNumber<?=$row['ID']?>'>
				<?=$row['DestinationNumber']?>
				</span>
				<input type="text" id="Input<?=$row['ID']?>" class="InputTxt" value="<?=$row['DestinationNumber']?>" style="width:95%; display:none">
				</td>
                <td class="td">
				<span id='SpanTxt<?=$row['ID']?>'>
				<?=$row['Text']?>
				</span>
				<textarea id="TxtArea<?=$row['ID']?>" style="width:99%; display:none" class="AreaTxt"><?=$row['Text']?></textarea>
				</td>
                <td class="td" align='center'>
					<table border='0' cellpadding='0' cellspacing='0' id="Utility1<?=$row['ID']?>">
						<tr>
							<td style='padding-right:2px'>
								<img title='Ubah lalu kirim ulang' onclick="EditDetailInfoGenList('<?=$row['ID']?>','1')" src="../images/ico/ubah.png" width="16" height="16" border="0" style="cursor:pointer" />
							</td>
							<td style='padding-right:2px'>
								<img title='Kirim Ulang' onclick="ResendDetailInfoGenList('<?=$row['ID']?>')" src="../images/ico/refresh.png" width="16" height="16" border="0" style="cursor:pointer" />
							</td>
							<td style='padding-right:2px'>
								<img title='Hapus' onclick="DeleteDetailInfoGenList('<?=$row['ID']?>')" src="../images/ico/hapus.png" width="16" height="16" border="0" style="cursor:pointer" />
							</td>
						</tr>
					</table>
                    <table border='0' cellpadding='0' cellspacing='0' id="Utility2<?=$row['ID']?>" style="display:none">
						<tr>
							<td style='padding-right:2px'>
								<img title='Batalkan perubahan' onclick="EditDetailInfoGenList('<?=$row['ID']?>','0')" src="../images/ico/hapusBW.png" width="12" height="12" border="0" style="cursor:pointer" />
							</td>
							<td style='padding-right:2px'>
							  <img title='Kirim' onclick="ResendDetailInfoGenList2('<?=$row['ID']?>')" src="../images/ico/refresh.png" width="16" height="16" border="0" style="cursor:pointer" />
							</td>
						</tr>
					</table>
				</td>
              </tr>
              <?php
			  $cnt++;
		      }  
		  } else {
		  ?>
          <tr>
            <td colspan="4" align="center" class="td Ket">Tidak ada data</td>
          </tr>
          <?php
		  }
		  ?>
        </table>
        <?php
	}
}
?>