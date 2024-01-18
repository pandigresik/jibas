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
new UserList();
class UserList{
	public function __construct(){
		$cmd = $_REQUEST['cmd'] ?? '';
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<link rel="stylesheet" type="text/css" href="../style/style.css" />';
		echo '<link rel="stylesheet" type="text/css" href="../script/ui/jquery.ui.all.css" />';
		echo '<script language="javascript" src="../script/jquery-1.4.2.js"></script>';
		echo '<script language="javascript" src="../script/jquery-ui.js"></script>';
		echo '<script language="javascript" src="../script/tools.js"></script>';
		echo '<script language="javascript" src="user.js"></script>';
		echo '</head>';
		echo '<body>';
		match ($cmd) {
      "add" => $this->addUser(),
      "edit" => $this->editUser(),
      "selectpegawai" => $this->selectPegawai(),
      "del" => $this->deleteUser(),
      default => $this->showUserList(),
  };
		echo '</body>';
		echo '</html>';
	}
	
	public function deleteUser(){
		ob_start();
		if (isset($_REQUEST['id']) && $_REQUEST['id']!=""){
			global $db_name_user;
			$id  = $_REQUEST['id'];
			OpenDb();
			$sql = "SELECT login FROM $db_name_user.hakakses WHERE replid='$id'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$login = $row[0];
			
			$sql = "DELETE FROM $db_name_user.hakakses WHERE replid='$id'";
			$res = QueryDb($sql);
			
			$sql = "SELECT COUNT(replid) FROM $db_name_user.hakakses WHERE login='$login'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$num = $row[0];
			
			if ($num==0){
				$sql = "DELETE FROM $db_name_user.login WHERE login='$login'";
				$res = QueryDb($sql);
			}
		}
		$this->showUserList();
		ob_flush();
	}
	
	public function showUserList(){
		ob_start();
			?>
			<table id="MainTable" border="0" cellpadding="2" cellspacing="2" style="height:500px" width="100%">
			  <tr>
				<td height="50"  valign="top" align="right">
				<div id="SubTitle" align="right">
				<span style="color:#F90; background-color:#F90; font-size:20px">&nbsp;</span>&nbsp;<span style="color:#060; font-size:16px; font-weight:bold">Daftar Pengguna</span>
				</div>
				</td>
			  </tr>
			  <tr>
				<td valign="top">
					<div>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td align="right">
								<button type="button" class="Btn" id='btnAdd'>
									<table border="0" cellspacing="0" cellpadding="2">
									  <tr>
										<td><img src="../images/ico/tambah.png" /></td>
										<td>Tambah Pengguna</td>
									  </tr>
									</table>
								</button>
							</td>
						  </tr>
						</table>

					</div>
					<div id="phonebookList" style="margin-top:10px">
                    	<?php
						$this->showList();
						?>
					</div>
				</td>
			  </tr>
			</table>
			</body>
			</html>
			<?php
		ob_flush();
	}
	
	public function showList(){
		ob_start();
			global $db_name_sdm;
			global $db_name_user;
			OpenDb();
			$sql = "SELECT login,tingkat,keterangan,lastlogin,replid FROM $db_name_user.hakakses WHERE modul='SMSG'";
			$res = QueryDb($sql);
			$num = @mysqli_num_rows($res);
			if ($num>0){
				$cnt = 1;
				?>
				<table cellspacing="0" cellpadding="0" border="1" width="100%" class="tab">
				<tr class="Header">
					<td>No</td>
					<td>NIP</td>
					<td>Nama</td>
                    <td>Tingkat</td>
                    <td>Keterangan</td>
                    <td>Login Terakhir</td>
					<td>&nbsp;</td>
				</tr>
				<?php
				while ($row = @mysqli_fetch_row($res)){
				$sqlpeg = "SELECT nama FROM $db_name_sdm.pegawai WHERE nip='".$row[0]."'";
				$respeg = QueryDb($sqlpeg);
				$rowpeg = @mysqli_fetch_row($respeg);
				?>
				<tr height="20">
					<td align="center"><?php echo $cnt ?></td>
					<td style="padding: 2px;"><?php echo $row[0] ?></td>
					<td style="padding: 2px;"><?php echo $rowpeg[0] ?></td>
                    <td style="padding: 2px;" align="center"><?php echo ($row[1]=='1')?'Manager':'Staff'; ?></td>
                    <td style="padding: 2px;"><?php echo $row[2] ?></td>
                    <td style="padding: 2px;" align="center"><?php echo $row[3] ?></td>
					<td align="center">
						<table border='0' cellpadding='2'>
							<tr>
								<td><img src='../images/ico/ubah.png' alt='Ubah' style='cursor:pointer' class='btnEdit' id='<?php echo $row[4] ?>'></td>
								<td><img src='../images/ico/hapus.png' alt='Hapus' style='cursor:pointer' class='btnDel' id='<?php echo $row[4] ?>'></td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				$cnt++;
				}
				?>
				</table>
				<?php
			} else {
				echo "<div align='center' class='ui-state-highlight'>Tidak ditemukan data Pengguna</div>";
			}
		ob_flush();
	}
		
	public function addUser()
	{
		ob_start();
		
		if (isset($_REQUEST['op']) && $_REQUEST['op']=='Simpan')
		{
			global $db_name_user;
			
			$nip	= $_REQUEST['nip'];
			$pass	= md5((string) $_REQUEST['password1']);
			$ket	= addslashes((string) $_REQUEST['ket']);
			$tingkat= $_REQUEST['tingkat'];
				
			OpenDb();
			
			$sql = "SELECT COUNT(replid)
					  FROM $db_name_user.hakakses
					 WHERE login = '$nip'
					   AND modul='SMSG'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$num = $row[0];
			if ($num > 0)
			{
				echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>Pengguna sudah terdaftar di Jibas SMS Gateway</div>";
			}
			else
			{
				$sql = "SELECT COUNT(replid)
					      FROM $db_name_user.login
						 WHERE login='$nip'";
				$res = QueryDb($sql);
				$row = @mysqli_fetch_row($res);
				
				if ($row[0] < 1)
				{
					$sql = "INSERT INTO $db_name_user.login
							   SET login='$nip',
								   password='$pass'";
					QueryDb($sql);
				}
				
				$sql = "INSERT INTO $db_name_user.hakakses
						   SET login = '$nip',
							   tingkat='$tingkat',
							   keterangan='$ket',
							   modul='SMSG'";
				$res = QueryDb($sql);
				if ($res)
				{
					echo "<script>parent.opener.location.href='userlist.php';window.close();</script>";
				}
				else
				{
					echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>Gagal menyimpan Data Pengguna</div>";	
				}
			}
		}
			?>
			<title>Tambah Pengguna</title>
            <form action="userlist.php?cmd=add" method="post" onsubmit="return saveUser()">
            <input type="hidden" id="hp" name="hp" value="0" />
            <table border="0" cellspacing="0" cellpadding="2">
				<tr height="25">
					<td class="Header" colspan="3" align="center">Tambah Pengguna</td>
				</tr>
			  <tr>
				<td>Pengguna</td>
				<td>:</td>
				<td>
                	<table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><input type="text" id="nip" name="nip" readonly="readonly" size="15" class='InputTxt nip' value="<?php echo stripslashes((string) $nip) ?>" style="margin-right:4px"/></td>
                        <td><input type="text" id="nama" name="nama" readonly="readonly"  size="35" class='InputTxt nama' value="<?php echo stripslashes((string) $nama) ?>"  style="margin-right:4px"></td>
                        <td><input type="button" value="..."  id="caripegawai" class="Btn" /></td>
                      </tr>
                    </table>
				</td>
			  </tr>
			  <tr class='passfield'>
				<td>Password</td>
				<td>:</td>
				<td><input type="password" id="password1" class='InputTxt' name="password1" style='width:98%' value="" /></td>
			  </tr>
			  <tr class='passfield'>
			    <td>Password (ulangi)</td>
			    <td>:</td>
			    <td><input type="password" id="password2" class='InputTxt' style='width:98%' value="" /></td>
		      </tr>
              <tr class='hasspassword' style="display:none">
			    <td></td>
			    <td></td>
			    <td><div class="ui-state-highlight">Pegawai <span class="nip"></span> - <span class="nama"></span> sudah memiliki password</div></td>
		      </tr>
			  <tr>
				<td>Level</td>
				<td>:</td>
				<td>
					<select id="tingkat" name="tingkat" class="Cmb">
						<option value="1" <?php echo StringIsSelected('1',$tingkat) ?>>Manager</option>
						<option value="2" <?php echo StringIsSelected('2',$tingkat) ?>>Staff</option>
					</select>				</td>
			  </tr>
			  <tr>
				<td>Keterangan</td>
				<td>:</td>
				<td><textarea class="AreaTxt" name="ket" id="ket" rows='3'  style='width:99%'><?php echo stripslashes($ket) ?></textarea></td>
			  </tr>
			  <tr>
				<td colspan='3' align='center'>
					<input type='submit' value='Simpan' class="BtnSilver90" id='btnSave' name="op">&nbsp;<input onclick='window.close()' type='button' value='Tutup' class="BtnSilver90">				
                </td>
			  </tr>
			</table>
            </form>
		<?php
		ob_flush();
	}
	
	public function editUser()
	{
		ob_start();
		
		global $db_name_sdm;
		global $db_name_user;
		
		$id	= $_REQUEST['id'];
		OpenDb();
		if (isset($_REQUEST['op']) && $_REQUEST['op']=='Simpan')
		{
			$nip	= $_REQUEST['nip'];
			$ket	= $_REQUEST['ket'];
			$tingkat= $_REQUEST['tingkat'];
				
			$sql = "UPDATE $db_name_user.hakakses
					   SET tingkat='$tingkat',keterangan='$ket'
					 WHERE replid='$id'";
			$res = QueryDb($sql);
			if ($res)
			{
				echo "<script>parent.opener.location.href='userlist.php';window.close();</script>";
			}
			else 
				echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>Gagal menyimpan Data Pengguna</div>";	
			}
			
			$sql = "SELECT login,tingkat,keterangan FROM $db_name_user.hakakses WHERE replid='$id'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$nip = $row[0];
			$tingkat = $row[1];
			$ket = $row[2];
			
			$sql = "SELECT nama FROM $db_name_sdm.pegawai WHERE nip='$nip'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$nama= $row[0];
			?>
			<title>Ubah Pengguna</title>
            <form action="userlist.php?cmd=edit&id=<?php echo $id ?>" method="post" onsubmit="return saveUser()">
            <input type="hidden" id="hp" name="hp" value="0" />
            <table border="0" cellspacing="0" cellpadding="2">
				<tr height="25">
					<td class="Header" colspan="3" align="center">Ubah Pengguna</td>
				</tr>
			  <tr>
				<td>Pengguna</td>
				<td>:</td>
				<td>
                	<table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><input type="text" name="nip" readonly="readonly" size="15" class='InputTxt' style="margin-right:4px" value="<?php echo $nip ?>"/></td>
                        <td><input type="text" name="nama" readonly="readonly"  size="35" class='InputTxt'/  style="margin-right:4px" value="<?php echo $nama ?>"></td>
                      </tr>
                    </table>
				</td>
			  </tr>
			  <tr>
				<td>Level</td>
				<td>:</td>
				<td>
					<select id="tingkat" name="tingkat" class="Cmb">
						<option value="1" <?php echo StringIsSelected('1',$tingkat) ?>>Manager</option>
						<option value="2" <?php echo StringIsSelected('2',$tingkat) ?>>Staff</option>
					</select>				</td>
			  </tr>
			  <tr>
				<td>Keterangan</td>
				<td>:</td>
				<td><textarea class="AreaTxt" name="ket" id="ket" rows='3'  style='width:99%'><?php echo stripslashes((string) $ket) ?></textarea></td>
			  </tr>
			  <tr>
				<td colspan='3' align='center'>
					<input type='submit' value='Simpan' class="BtnSilver90" id='btnSave' name="op">&nbsp;<input onclick='window.close()' type='button' value='Tutup' class="BtnSilver90">				
                </td>
			  </tr>
			</table>
            </form>
		<?php
		ob_flush();
	}
	
}
?>