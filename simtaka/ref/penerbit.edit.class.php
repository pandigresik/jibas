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
class CPenerbitEdit{
	public $kode, $nama, $replid, $keterangan;
	function OnStart(){
		if (isset($_REQUEST['simpan'])){
			$sql = "SELECT kode FROM penerbit WHERE kode='".CQ($_REQUEST['kode'])."' AND replid <> '".$_REQUEST['replid']."'";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num>0){
				$this->exist();
			} else {
				$sql = "UPDATE penerbit SET kode='".CQ($_REQUEST['kode'])."', nama='".CQ($_REQUEST['nama'])."', alamat='".CQ($_REQUEST['alamat'])."', telpon='".CQ($_REQUEST['telpon'])."', email='".CQ($_REQUEST['email'])."', fax='".CQ($_REQUEST['fax'])."', website='".CQ($_REQUEST['website'])."', kontak='".CQ($_REQUEST['kontak'])."', keterangan='".CQ($_REQUEST['keterangan'])."' WHERE replid='".$_REQUEST['replid']."'";
				$result = QueryDb($sql);
				if ($result)
					$this->success();
			}
		} else {
			$sql = "SELECT * FROM penerbit WHERE replid='".$_REQUEST['id']."'";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->replid = $_REQUEST['id'];
			$this->kode = $row['kode'];
			$this->nama = $row['nama'];
			$this->alamat = $row['alamat'];
			$this->telpon = $row['telpon'];
			$this->fax = $row['fax'];
			$this->email = $row['email'];
			$this->website = $row['website'];
			$this->kontak = $row['kontak'];
			$this->keterangan = $row['keterangan'];
		}
	}
	function exist(){
		?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="penerbit.edit.php?id=<?=$_REQUEST['replid']?>";
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
        <form name="editpenerbit" onSubmit="return validate()">
		<input name="replid" type="hidden" id="replid" value="<?=$this->replid?>">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Ubah Penerbit</font>            </td>
  		  </tr>
		  <tr>
            <td width="6%">&nbsp;<strong>Kode</strong></td>
            <td width="94%"><input name="kode" type="text" class="inputtxt" id="kode" maxlength="3" value="<?=$this->kode?>"></td>
          </tr>
          <tr>
            <td>&nbsp;<strong>Nama</strong></td>
            <td><input name="nama" type="text" class="inputtxt" id="nama" size="48" maxlength="100" value="<?=$this->nama?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Alamat</td>
            <td><textarea name="alamat" cols="45" rows="5" class="areatxt" id="alamat"><?=$this->alamat?></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;Telepon</td>
            <td><input name="telpon" type="text" class="inputtxt" id="telpon" size="48" maxlength="100" value="<?=$this->telpon?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Email</td>
            <td><input name="email" type="text" class="inputtxt" id="email" size="48" maxlength="100" value="<?=$this->email?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Fax</td>
            <td><input name="fax" type="text" class="inputtxt" id="fax" size="48" maxlength="100" value="<?=$this->fax?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Website</td>
            <td><input name="website" type="text" class="inputtxt" id="website" size="48" maxlength="100" value="<?=$this->website?>"></td>
          </tr>
          <tr>
            <td>&nbsp;Kontak</td>
            <td><input name="kontak" type="text" class="inputtxt" id="kontak" size="48" maxlength="100" value="<?=$this->kontak?>"></td>
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