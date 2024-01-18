<?php
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');
new Pegawai();
class Pegawai{
	public function __construct(){
		$op = $_REQUEST['op'] ?? '';
		$this->bag = $_REQUEST['bagian'] ?? "";
		$this->nip = $_REQUEST['nip'] ?? "";
		$this->nama = $_REQUEST['nama'] ?? "";
		$this->filter = ""; 
		switch($op){
			case 'pilih':$this->headerPilih();break;
			case 'cari':$this->headerCari();break;
		}
	}

	public function headerPilih(){
		ob_start();
			global $db_name_sdm;
			OpenDb();
			$sql = "SELECT replid,bagian FROM $db_name_sdm.bagianpegawai ORDER BY urutan ASC";
			$res = QueryDb($sql);
			$bag = [];
			while($row = @mysqli_fetch_row($res))
				array_push($bag,$row[1]);
			?>
			<table border='0'>
				<tr>
					<td>Bagian : </td>
					<td>
						<select id='bag' class='InputTxt'>
							<?php
							foreach($bag as $bagian){
								$this->bag = ($this->bag=="")?$bagian:$this->bag;								
								echo "<option value='$bagian' ";
								if ($bagian==$this->bag)
									echo "selected";
								echo ">".$bagian."</option>";
							}
							?>
						</select>
					</td>
				</tr>
			</table>	
			<?php
			$this->filter = " AND bagian='$this->bag'";
			$this->showPegawai();
		ob_flush();
	}

	public function headerCari(){
		ob_start();
			?>
			<table border='0'>
				<tr>
					<td>NIP:</td>
					<td>
						<input type='text' class='InputTxt' id='nip' value="<?php echo $this->nip ?>">
					</td>
					<td>&nbsp;&nbsp;Nama:</td>
					<td>
						<input type='text' class='InputTxt' id='nama' value="<?php echo $this->nama ?>">
					</td>
					<td><input type='button' value="Cari" id='btnCari' class="Btn"></td>
				</tr>
			</table>
			<?php
			if ($this->nip!="")
				$this->filter .= " AND nip LIKE '%$this->nip%'";
			if ($this->nama!="")
				$this->filter .= " AND nama LIKE '%$this->nama%'";
			if ($this->nip!="" || $this->nama!="")
			$this->showPegawai();
		ob_flush();
	}

	public function showPegawai(){
		global $db_name_sdm;
		global $db_name_user;
		ob_start();
			OpenDb();
			$sql = "SELECT nip,nama FROM $db_name_sdm.pegawai WHERE 1 $this->filter";
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
					<td>&nbsp;</td>
				</tr>
				<?php
				while ($row = @mysqli_fetch_row($res)){
				$sqlpass = "SELECT count(replid) FROM $db_name_user.login WHERE login='".$row[0]."'";
				$respass = QueryDb($sqlpass);
				$rowpass = @mysqli_fetch_row($respass);
				$hp		 = ($rowpass[0]==0)?'false':'true';
				?>
				<tr height="20">
					<td align="center"><?php echo $cnt ?></td>
					<td style="padding: 2px;"><?php echo $row[0] ?></td>
					<td style="padding: 2px;"><?php echo $row[1] ?></td>
					<td align="center">
						<img class='btnSelectPeg' nip='<?php echo $row[0] ?>' nama='<?php echo $row[1] ?>' hp='<?php echo $hp ?>' style="cursor: pointer;" alt="Pilih" src="../images/ico/down.png">
					</td>
				</tr>
				<?php
				$cnt++;
				}
				?>
				</table>
				<?php
			} else {
				echo "<div align='center' class='ui-state-highlight'>Tidak ditemukan data Pegawai</div>";
			}
		ob_flush();
	}
}
?>