<?php
require_once("../include/config.php");
require_once("../include/db_functions.php");
require_once("../include/common.php");

new Phonebook();

class Phonebook
{
	public function __construct()
	{
		$cmd = $_REQUEST['cmd'] ?? '';
		
		$this->cmd		= $cmd;
		$this->page		= $_REQUEST['page'] ?? 1;
		
		if ($cmd=='add' || $cmd=='edit')
		{
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			echo '<html xmlns="http://www.w3.org/1999/xhtml">';
			echo '<head>';
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo '<title>SMS</title>';
			echo '<link rel="stylesheet" type="text/css" href="../style/style.css" />';
			echo '<script language="javascript" src="../script/ShowError.js"></script>';
			echo '<script language="javascript" src="../script/ajax.js"></script>';
			echo '<script language="javascript" src="../script/tables.js"></script>';
			echo '<script language="javascript" src="../script/jquery-1.4.2.js"></script>';
			echo '<script language="javascript" src="../script/tools.js"></script>';
			echo '<script language="javascript" src="phonebook.js"></script>';
			echo '</head>';
			echo '<body>';
		}
		
		OpenDb();
		switch($cmd)
		{
			case "view":$this->view();break;
			case "search":$this->search();break;
			case "delete":$this->deletePH();break;
			case "add":$this->add();break;
			case "edit":$this->edit();break;
			case "import":$this->import();break;
		}
		CloseDb();
		
		if ($cmd=='add' || $cmd=='edit')
		{
			echo '</body>';
			echo '</html>';
		}
	}

	public function view()
	{
		ob_start();
		
		$jenis = $_REQUEST['jenis'];
		$alpha = $_REQUEST['alpha'];
		
		$this->filterAddr = "&jenis=$jenis&alpha=$alpha";
		$filter1= ($jenis!='-1')?" AND `status`='$jenis'":"";
		$filter2= ($alpha!='-1')?" AND nama LIKE '$alpha%'":"";
		$sql   = "SELECT replid,nohp,nama,`status`,keterangan FROM phonebook WHERE 1 $filter1 $filter2";
		$res   = QueryDb($sql); 
		$this->num = @mysqli_num_rows($res);
		$res   = QueryDb($sql." LIMIT ".((($this->page)-1)*showList).",".showList);
		$this->data  = [];
		while ($row = @mysqli_fetch_row($res))
			array_push($this->data,$row);
		
		$this->showData();
		ob_flush();
	}

	public function search()
	{
		ob_start();
		
		$field   = $_REQUEST['field'];
		$keyword = $_REQUEST['keyword'];
		
		$this->filterAddr = "&field=$field&keyword=$keyword";
		$filter= ($alpha!='-1')?" AND `$field` LIKE '%$keyword%'":"";
		$sql   = "SELECT replid,nohp,nama,`status`,keterangan FROM phonebook WHERE 1 $filter";
		$res   = QueryDb($sql); 
		$this->num = @mysqli_num_rows($res);
		$res   = QueryDb($sql." LIMIT ".($this->page*showList).",".showList);
		$this->data  = [];
		while ($row = @mysqli_fetch_row($res))
			array_push($this->data,$row);

		$this->showData();
		
		ob_flush();
	}

	public function showData()
	{
		ob_start();
		
		if (count($this->data)>0)
		{
			$cnt = ($this->page==1)?1:((($this->page)-1)*showList)+1;
			$arrNama = ['Siswa', 'Orang Tua', 'Pegawai', 'Lainnya', 'Alumni'];
			?>
			<table cellspacing="0" cellpadding="0" border="1" width="100%" class="tab">
			<tr class="Header">
				<td>No</td>
				<td>HP</td>
				<td>Nama</td>
				<td>Status</td>
				<td>Keterangan</td>
				<td>&nbsp;</td>
			</tr>
<?php			foreach($this->data as $data)
				{	?>
				<tr height='20'>
					<td align='center'><?php echo $cnt ?></td>
					<td style='padding:2px'><?php echo $data[1] ?></td>
					<td style='padding:2px'><?php echo $data[2] ?></td>
					<td style='padding:2px'><?php echo $arrNama[$data[3]] ?></td>
					<td style='padding:2px'><?php echo $data[4] ?></td>
					<td align='center'>
						<table border='0' cellpadding='2'>
						<tr>
								<td><img src='../images/ico/ubah.png' alt='Ubah' style='cursor:pointer' class='btnEdit' id='<?php echo $data[0] ?>'></td>
								<td><img src='../images/ico/hapus.png' alt='Hapus' style='cursor:pointer' class='btnDel' id='<?php echo $data[0] ?>'></td>
						</tr>
						</table>
					</td>
				</tr>
<?php			$cnt++;
				} // end for ?>
			</table>
<?php 		pagination(showList,pageList,$this->num,"cmd=$this->cmd$this->filterAddr"); ?>
<?php	}
		else
		{
			echo "<div align='center' style='border:1px solid #d88900; background-color:#dec496;padding:4px;font-weight:bold;color:#4b4b4b'>Tidak ada data</div>";
		}
		
		ob_flush();
	}

	public function deletePH()
	{
		ob_start();
		
		$id    = $_REQUEST['id'];
		$sql   = "DELETE FROM phonebook WHERE replid='$id'";
		$res   = QueryDb($sql);
		
		ob_flush();
	}

	public function add()
	{
		ob_start();
		
		if (isset($_REQUEST['op']) && $_REQUEST['op']=='save')
		{
			//&op=save&nama=Ellyf%20Tatok%20Juga&hp=085624084062&jenis=0&ket=
			$nama	= $_REQUEST['nama'];
			$hp		= $_REQUEST['hp'];
			$jenis	= 3;
			$ket	= $_REQUEST['ket'];
			
			$sql = "SELECT COUNT(replid) FROM phonebook WHERE nohp='$hp'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$num = $row[0];
			if ($num>0)
				echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>No HP $hp sudah terdaftar sebelumnya.</div>";	
			else
			{
				$sql = "INSERT INTO phonebook SET nama='$nama',nohp='$hp',`status`='$jenis',keterangan='$ket'";
				$res = QueryDb($sql);
				if ($res)
					echo "<script>parent.opener.afterSave('".$hp."');window.close();</script>";
				else 
					echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>Gagal menyimpan Data</div>";	
			}
		}	?>
			
		<table border="0" cellspacing="0" cellpadding="2" width='350'>
		<tr height="25">
			<td class="Header" colspan="3" align="center">Tambah Phonebook</td>
		</tr>
		<tr>
			<td>No HP</td>
			<td>:</td>
			<td><input type="text" id="nohp" class='InputTxt' style='width:98%' onkeypress="return numbersonly(this, event)" value="<?php echo stripslashes((string) $hp) ?>" /></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>:</td>
			<td><input type="text" id="nama" class='InputTxt' style='width:98%' value="<?php echo stripslashes((string) $nama) ?>" /></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td>:</td>
			<td><textarea class="AreaTxt" id="ket" rows='3'  style='width:99%'><?php echo stripslashes((string) $ket) ?></textarea></td>
		</tr>
		<tr>
			<td colspan='3' align='center'>
				<input type='button' value='Simpan' class="BtnSilver90" id='btnSave'>&nbsp;<input onclick='window.close()' type='button' value='Tutup' class="BtnSilver90">	
			</td>
		</tr>
		</table>
<?php
		ob_flush();
	}

	public function edit()
	{
		ob_start();
		
		$id		= $_REQUEST['id'];
		
		if (isset($_REQUEST['op']) && $_REQUEST['op']=='update')
		{
			$nama	= $_REQUEST['nama'];
			$hp		= $_REQUEST['hp'];
			$ket	= $_REQUEST['ket'];
			
			$sql = "SELECT COUNT(replid) FROM phonebook WHERE nohp='$hp' AND replid<>'$id'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$num = $row[0];
			if ($num>0)
				echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>No HP $hp sudah terdaftar sebelumnya.</div>";	
			else
			{
				$sql = "UPDATE phonebook SET nama='$nama',nohp='$hp',keterangan='$ket' WHERE replid='$id'";
				$res = QueryDb($sql);
				if ($res)
					echo "<script>parent.opener.showData();window.close();</script>";
				else 
					echo "<div align='center' style='border:1px solid #ce0000; background-color:#fbd9d9;padding:4px;font-weight:bold;color:#4b4b4b;margin-bottom:5px'>Gagal memperbaharui Data</div>";	
			}
		}
		else
		{
			$sql = "SELECT nama,nohp,`status`,keterangan FROM phonebook WHERE replid='$id'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			$nama = $row[0];
			$hp = $row[1];
			$ket = $row[3];
		}
		?>
		<input type='hidden' id='id' value='<?php echo $id ?>'>
		<table border="0" cellspacing="0" cellpadding="2" width='350'>
		<tr height="25">
			<td class="Header" colspan="3" align="center">Ubah Phonebook</td>
		</tr>
		<tr>
			<td>No HP</td>
			<td>:</td>
			<td><input type="text" id="nohp" class='InputTxt' style='width:98%' onkeypress="return numbersonly(this, event)" value="<?php echo stripslashes((string) $hp) ?>" /></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>:</td>
			<td><input type="text" id="nama" class='InputTxt' style='width:98%' value="<?php echo stripslashes((string) $nama) ?>" /></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td>:</td>
			<td><textarea class="AreaTxt" id="ket" rows='3'  style='width:99%'><?php echo stripslashes((string) $ket) ?></textarea></td>
		</tr>
		<tr>
			<td colspan='3' align='center'>
				<input type='button' value='Update' class="BtnSilver90" id='btnUpdate'>&nbsp;<input onclick='window.close()' type='button' value='Tutup' class="BtnSilver90">	
			</td>
		</tr>
		</table>
<?php	ob_flush();
	}
	
	public function import()
	{
		global $db_name_sdm;
		global $db_name_akad;
		
		ob_start();
			
		$sql = "SELECT nama, IF(handphone IS NULL, '', TRIM(handphone)), replid
				  FROM $db_name_sdm.pegawai
				 WHERE aktif = 1";
		$res = QueryDb($sql);
		$data = [];
		while ($row = @mysqli_fetch_row($res))
		{
			$temp = $row[1];
			$temp = str_replace("#", "", (string) $temp);
			
			if (strlen($temp) >= 7)
				array_push($data, [$row[0], $row[1], 2, '', $row[2]]);
		}
		
		$sql = "SELECT nama,
					   IF(hpsiswa IS NULL, '', TRIM(hpsiswa)),
					   IF(LENGTH(IF(namaayah IS NULL, '', TRIM(namaayah))) = 0, CONCAT('Orangtua ', nama), TRIM(namaayah)), 
					   IF(hportu IS NULL, '', hportu),
					   replid,
					   alumni,
					   IF(info1 IS NULL, '', TRIM(info1)),
					   IF(info2 IS NULL, '', TRIM(info2))
				  FROM $db_name_akad.siswa
				 WHERE aktif = 1";
		$res = QueryDb($sql);
		while ($row = @mysqli_fetch_row($res))
		{
			// hpsiswa
			$temp = $row[1];
			$temp = str_replace("#", "", (string) $temp);
			if (strlen($temp) >= 7)
			{
				if ($row[5] == '1')
					array_push($data, [$row[0], $temp, 4, '', $row[4]]);
				else
					array_push($data, [$row[0], $temp, 0, '', $row[4]]);
			}
			
			// hportu
			$temp = $row[3];
			$temp = str_replace("#", "", (string) $temp);
			if (strlen($temp) >= 7)
				array_push($data, [$row[2], $temp, 1, "Orangtua $row[0]", $row[4]]);
			
			// hp from info1
			$temp = $row[6];
			$temp = str_replace("#", "", (string) $temp);
			if (strlen($temp) >= 7)
				array_push($data, [$row[2], $temp, 1, "Orangtua $row[0]", $row[4]]);
			
			// hp from info2
			$temp = $row[7];
			$temp = str_replace("#", "", (string) $temp);
			if (strlen($temp) >= 7)
				array_push($data, [$row[2], $temp, 1, "Orangtua $row[0]", $row[4]]);	
		}

		foreach($data as $pb)
		{
			$sql = "SELECT COUNT(replid)
					  FROM phonebook
					 WHERE idreplid ='".$pb[4]."'
					   AND `status` = '$pb[2]'
					   AND nohp = '$pb[1]'";
			$res = QueryDb($sql);
			$row = @mysqli_fetch_row($res);
			if ($row[0] == 0)
			{
				$sql = "INSERT INTO phonebook
						   SET nama='".$pb[0]."', nohp='$pb[1]', `status`='".$pb[2]."',keterangan='".$pb[3]."', idreplid='".$pb[4]."'";
				QueryDb($sql);
			}
			else
			{
				$sql = "UPDATE phonebook
						   SET nama='".$pb[0]."'
						 WHERE `status`='".$pb[2]."'
						   AND idreplid='".$pb[4]."'
						   AND nohp='".$pb[1]."'";
				QueryDb($sql);
			}
		}
		
		ob_flush();
	}
}
?>