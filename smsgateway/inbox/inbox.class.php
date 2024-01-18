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
new Inbox();
class Inbox{
	public function __construct(){
		$this->sms = [];
		$this->newSms = [];
		$cmd = $_REQUEST['cmd'] ?? '';
		$this->bulan = $_REQUEST['m'] ?? date('m');
		$this->tahun = $_REQUEST['y'] ?? date('Y');
		$this->cmd		= $cmd;
		$this->page		= $_REQUEST['page'] ?? 1;
		if ($cmd==""){
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			echo '<html xmlns="http://www.w3.org/1999/xhtml">';
			echo '<head>';
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo '<title>SMS</title>';
			echo '<link rel="stylesheet" type="text/css" href="../style/style.css" />';
			echo '<link rel="stylesheet" type="text/css" href="../script/ui/jquery.ui.all.css" />';
			echo '<script language="javascript" src="../script/ShowError.js"></script>';
			echo '<script language="javascript" src="../script/ajax.js"></script>';
			echo '<script language="javascript" src="../script/tables.js"></script>';
			echo '<script language="javascript" src="../script/jquery-1.4.2.js"></script>';
			echo '<script language="javascript" src="../script/jquery-ui.js"></script>';
			echo '<script language="javascript" src="../script/tools.js"></script>';
			echo '<script language="javascript" src="inbox.js"></script>';
			echo '</head>';
			echo '<body>';
			echo '<div id="SubTitle" align="right">';
			echo '<span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Pesan Masuk</span>';
			echo '</div>';
		}
		switch($cmd){
			case "showNewMsg":$this->showNewMsg();break;
			case "getRowHeader":echo $this->getRowHeader();break;
			case "getRowTemplate":echo $this->getRowTemplate();break;
			case "delete":echo $this->deleteMsg();break;
			case "viewMsg":echo $this->viewMsg();break;
			case "replyMsg":echo $this->replyMsg();break;
			case "getList":echo $this->getList();break;
			
			default:$this->showMsgList();break;
			
		}
		if ($cmd==""){
			echo '</body>';
			echo '</html>';
		}
	}

	public function showMsgList(){
		ob_start();
			global $G_START_YEAR,$LMonth;
			$arrYear = [];
			for ($y = $G_START_YEAR; $y <= date('Y'); $y++ )
				array_push($arrYear,$y);

			$arrMonth= [];
			for ($m = 1; $m <= 12; $m++ )
				array_push($arrMonth,[$m, $LMonth[($m-1)]]);
			
			
			?>
			<div align='left' style='border-bottom:1px dashed #919191; padding-bottom:5px; margin-bottom:5px'>
			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
				<td>Bulan :</td>
				<td>
					<select class="Cmb periode" id='month'>
						<?php
						foreach ($arrMonth as $m){
							echo "<option value='".$m[0]."' ";
							if ($m[0]==$this->bulan)
								echo "selected";
							echo ">".$m[1]."</option>";
						}
						?>
					</select>
					<select class="Cmb periode" id='year'>
						<?php
						foreach ($arrYear as $y){
							echo "<option value='$y' ";
							if ($y==$this->tahun)
								echo "selected";
							echo ">".$y."</option>";
						}
						?>
					</select>
				</td>
			  </tr>
			</table>
			</div>	
			<?php
			echo "<div id='tableContainer'>";
			$this->getList();
			echo "</div>";
		ob_flush();
	}

	public function getList(){
		ob_start();
			OpenDb();
			$this->filterAddr = "&m=$this->bulan&y=$this->tahun";
			$sql = "SELECT ID,SenderNumber,Text,DATE_FORMAT(ReceivingdateTime,'%e %b %Y %T'),`Status` FROM inbox WHERE YEAR(ReceivingdateTime)='$this->tahun' AND MONTH(ReceivingdateTime)='$this->bulan' ORDER BY ID DESC";
			$res = QueryDb($sql);
			$this->num = @mysqli_num_rows($res);
			if ($this->num>0){
				$res   = QueryDb($sql." LIMIT ".((($this->page)-1)*showList).",".showList);
				while ($row = @mysqli_fetch_row($res)){
					$nohp  = str_replace("+62","",(string) $row[1]);	
					$sqlph = "SELECT nama FROM phonebook WHERE nohp LIKE '%$nohp'";
					$resph = QueryDb($sqlph);
					$rowph = @mysqli_fetch_row($resph);
					$nama  = $rowph[0];
					array_push($this->sms,[$row[0], "($row[1]) $nama", $row[2], $row[3], $row[4]]);
				}
			}
			$sql = "SELECT max(ID) FROM inbox";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$_SESSION['maxID'] = $row[0];
			$this->showList();
		ob_flush();
	}

	public function showList(){
		ob_start();
			if (count($this->sms)==0){
				echo "<div class='ui-state-highlight' align='center' id='nodata' style='display:block'>Tidak ada pesan Masuk</div>";
			} else {
				echo "<div class='ui-state-highlight' align='center' id='nodata' style='display:none'>Tidak ada pesan Masuk</div>";
				?>
				<table cellspacing="0" cellpadding="0" border="1" width="100%" class="tab" id='tableInbox'>
				<?php
				$hdr = $this->getRowHeader();
				echo $hdr;
				?>
				<tbody>
				<?php
				foreach($this->sms as $data){
					$tmp = $this->getRowTemplate();
					if ($data[4]=='0')
						$tmp = str_replace("<tr","<tr class='bold'",(string) $tmp);
					$tmp = str_replace("_SENDER_",$data[1],(string) $tmp);
					$tmp = str_replace("_DATE_",$data[3],$tmp);
					$tmp = str_replace("_MSG_",$data[2],$tmp);
					$tmp = str_replace("_ID_",$data[0],$tmp);
					echo $tmp;
				}
				?>
				<tbody>
				
				</table>
				<?php pagination(showList,pageList,$this->num,"cmd=getList$this->filterAddr"); ?>
				<?php
			}
		ob_flush();
	}
	
	public function getRowHeader(){
		return "<tr height='20' class='Header'>
			<td width='250'>Pengirim</td>
					<td width='150'>Tanggal</td>
                    <td width='*'>Pesan</td>
                    <td width='50'>&nbsp;</td>
			</tr>";

	}
	
	public function getRowTemplate(){
		$tmp = "<tr height='20'>
				<td style='padding: 2px;'>_SENDER_</td>
				<td style='padding: 2px;'>_DATE_</td>
				<td style='padding: 2px;'>_MSG_</td>
				<td align='center'>
				<img src='../images/ico/lihat.png' alt='Lihat' style='cursor:pointer' class='btnView' id='_ID_'>";
		if ($_SESSION['tingkat']!='2')
		$tmp .= "<img src='../images/ico/hapus.png' alt='Hapus' style='cursor:pointer' class='btnDel' id='_ID_'>";

		$tmp .= "</td>
				</tr>";
		return $tmp;
	}

	public function showNewMsg(){
		ob_start();
			OpenDb();
			$sql = "SELECT ID,SenderNumber,Text,DATE_FORMAT(ReceivingdateTime,'%e %b %Y %T') FROM inbox WHERE ID>'$_SESSION[maxID]' ORDER BY ID DESC";
			$res = QueryDb($sql);
			$num = @mysqli_num_rows($res);
			if ($num>0){
				while ($row = @mysqli_fetch_row($res)){
					$nohp  = str_replace("+62","",(string) $row[1]);	
					$sqlph = "SELECT nama FROM phonebook WHERE nohp LIKE '%$nohp'";
					$resph = QueryDb($sqlph);
					$rowph = @mysqli_fetch_row($resph);
					$nama  = $rowph[0];
					array_push($this->newSms,["($row[1]) $nama", $row[3], $row[2], $row[0]]);
				}
			}
			$sql = "SELECT max(ID) FROM inbox";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$_SESSION['maxID'] = $row[0];
			echo json_encode(['num'=>$num, 'data'=>$this->newSms], JSON_THROW_ON_ERROR);
		ob_flush();
	}

	public function deleteMsg(){
		ob_start();
			OpenDb();
			$id = $_REQUEST['id'];
			$sql = "DELETE FROM inbox WHERE ID='$id'";
			$res = QueryDb($sql);
		ob_flush();
	}

	public function viewMsg(){
		ob_start();
			OpenDb();
			$id = $_REQUEST['id'];
			$sql = "UPDATE inbox SET `Status`='1' WHERE ID='$id'";
			$res = QueryDb($sql);
			$sql = "SELECT ID,SenderNumber,Text,DATE_FORMAT(ReceivingdateTime,'%e %b %Y %T') FROM inbox WHERE ID='$id'";
			$res = QueryDb($sql);
			$data = @mysqli_fetch_row($res);
			$nohp  = str_replace("+62","",(string) $data[1]);	
			$sqlph = "SELECT nama FROM phonebook WHERE nohp LIKE '%$nohp'";
			$resph = QueryDb($sqlph);
			$rowph = @mysqli_fetch_row($resph);
			$nama  = $rowph[0];
			?>
			<div style="font-family:Arial; color:#666666; font-weight:bold">
				Tanggal : 	
			</div>
			<div style="padding-left:10px">
				<?php echo $data[3] ?>
			</div>
			<div style="font-family:Arial; color:#666666; font-weight:bold; border-top:1px dashed #999999; margin-top:5px">
				Pengirim : 	
			</div>
			<div style="padding-left:10px">
				<?php echo $data[1] ?> &lt;<?php echo $nama ?>&gt;
			</div>
			<div style="font-family:Arial; color:#666666; font-weight:bold; border-top:1px dashed #999999; margin-top:5px">
				Pesan : 	
			</div>
			<div style="padding-left:10px">
				<?php echo $data[2] ?>
			</div>
			<div style="font-family:Arial; color:#666666; font-weight:bold; border-top:1px dashed #999999; margin-top:5px">
				Balas : 	
			</div>
			<div style="padding-left:10px">
				<textarea id='replytext' class="AreaTxt" style='width:98%'></textarea>
			</div>
			<div align='right' style='padding-right:2px'><span id='charLeft'>160</span></div>
			<?php
		ob_flush();
	}

	public function replyMsg(){
		ob_start();
			OpenDb();
			$id   = $_REQUEST['id'];
			$text = $_REQUEST['text'];
			
			
			$sql = "INSERT INTO smsgeninfo SET replid='$idsmsgeninfo',tanggal=now(),tipe='3'";
			$res = QueryDb($sql);

			$sql  = "SELECT last_insert_id(replid) FROM smsgeninfo WHERE tipe='3' ORDER BY replid DESC LIMIT 1";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$idsmsgeninfo = (int)$row[0];
			$idsmsgeninfo = ($idsmsgeninfo+1);

			$sql = "SELECT SenderNumber FROM inbox WHERE ID = '".$id."'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$sender = $row[0];

			$sql = "INSERT INTO outbox SET InsertIntoDB=now(), SendingDateTime=now(), Text='$text', DestinationNumber='$sender', SenderID='$sender', CreatorID='$sender', idsmsgeninfo=$idsmsgeninfo";
			$res = QueryDb($sql);
			$output = ($res)?1:0;
			echo json_encode(['status'=>$output], JSON_THROW_ON_ERROR);
		ob_flush();
	}

}
?>