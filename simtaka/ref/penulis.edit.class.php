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
class CPenulisEdit{
	public $kode, $nama, $replid, $keterangan;
	function OnStart(){
		if (isset($_REQUEST['simpan'])){
			$sql = "SELECT kode FROM penulis WHERE kode='".CQ($_REQUEST['kode'])."' AND replid <> '".$_REQUEST['replid']."'";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num>0){
				$this->exist();
			} else {
				$sql = "UPDATE penulis SET kode='".CQ($_REQUEST['kode'])."', nama='".CQ($_REQUEST['nama'])."', kontak='".CQ($_REQUEST['kontak'])."', biografi='".CQ($_REQUEST['biografi'])."', keterangan='".CQ($_REQUEST['keterangan'])."', gelardepan='".CQ($_REQUEST['gelardepan'])."', gelarbelakang='".CQ($_REQUEST['gelarbelakang'])."' WHERE replid= ".$_REQUEST['replid'];
				$result = QueryDb($sql);
				if ($result)
					$this->success();
			}
		} else {
			$sql = "SELECT * FROM penulis WHERE replid='".$_REQUEST['id']."'";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->replid = $_REQUEST['id'];
			$this->kode = $row['kode'];
			$this->nama = $row['nama'];
			$this->kontak = $row['kontak'];
			$this->biografi = $row['biografi'];
			$this->keterangan = $row['keterangan'];
			$this->gelardepan = $row['gelardepan'];
			$this->gelarbelakang = $row['gelarbelakang'];
		}
	}
	function exist(){
		?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="penulis.edit.php?id=<?=$_REQUEST['replid']?>";
		</script>
        <?php
	}
	function success(){
		?>
        <script language="javascript">
			parent.opener.getfresh();
			window.close();
        </script>
        <?php
	}
	function edit(){
		?>
        <form name="editpenulis" onSubmit="return validate()">
		<input name="replid" type="hidden" id="replid" value="<?=$this->replid?>">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Ubah Penulis</font>            </td>
  		  </tr>
		  <tr>
            <td width="6%">&nbsp;<strong>Kode</strong></td>
            <td width="94%"><input name="kode" type="text" class="inputtxt" id="kode" maxlength="3" value="<?=$this->kode?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Gelar&nbsp;Depan</td>
            <td><input name="gelardepan" type="text" class="inputtxt" id="gelardepan" size="30" maxlength="45" value="<?=$this->gelardepan?>"></td>
          </tr>
		  <tr>
            <td>&nbsp;<strong>Nama</strong></td>
            <td><input name="nama" type="text" class="inputtxt" id="nama" size="48" maxlength="100" value="<?=$this->nama?>"></td>
          </tr>
		  <tr>
            <td>&nbsp;Gelar&nbsp;Belakang</td>
            <td><input name="gelarbelakang" type="text" class="inputtxt" id="gelarbelakang" size="30" maxlength="45" value="<?=$this->gelarbelakang?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Kontak</td>
            <td><input name="kontak" type="text" class="inputtxt" id="kontak" size="48" maxlength="100" value="<?=$this->kontak?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Biografi</td>
            <td><textarea name="biografi" cols="45" rows="8" class="areatxt" id="biografi"><?=$this->biografi?></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;Keterangan</td>
            <td><textarea name="keterangan" cols="45" rows="5" class="areatxt" id="keterangan"><?=$this->keterangan?></textarea></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="window.close()" ></td>
          </tr>
        </table>
		</form>
		<?php
	}
}
?>