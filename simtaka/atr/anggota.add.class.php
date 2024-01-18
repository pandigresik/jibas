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
class CAnggotaAdd{
	function OnStart(){
		if (isset($_REQUEST['simpan'])){
			$sql = "SELECT noregistrasi FROM anggota WHERE noregistrasi='".$_REQUEST['noreg']."' ";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num>0){
				$this->exist();
			} else {
				$nama = trim(addslashes((string) $_REQUEST['nama']));
				$alamat = trim(addslashes((string) $_REQUEST['alamat']));
				$kodepos = trim(addslashes((string) $_REQUEST['kodepos']));
				$email = trim(addslashes((string) $_REQUEST['email']));
				$telpon = trim(addslashes((string) $_REQUEST['telpon']));
				$hp = trim(addslashes((string) $_REQUEST['hp']));
				$kerja = trim(addslashes((string) $_REQUEST['kerja']));
				$institusi = trim(addslashes((string) $_REQUEST['institusi']));
				$keterangan = trim(addslashes((string) $_REQUEST['keterangan']));
				$foto = $_FILES['foto'];
				$uploadedfile = $foto['tmp_name'];
				$uploadedfile_name = $foto['name'];
				//echo $uploadedfile; exit;
				if (strlen((string) $uploadedfile)!=0){
					$filename=str_replace(GetFileName($uploadedfile_name),'temp',(string) GetFileName($uploadedfile_name)).GetFileExt($uploadedfile_name);
					ResizeImage($foto, 100, 80, 100, $filename);
					$handle = fopen($filename, "r");
					$foto_binary = addslashes(fread(fopen($filename,"r"),filesize($filename)));
					$fill_foto = ", foto='$foto_binary'"; 
				}
				$date = @mysqli_fetch_row(QueryDb("SELECT now()"));
				$sql = "INSERT INTO anggota SET noregistrasi='".$_REQUEST['noreg']."', nama='$nama', alamat='$alamat', kodepos='$kodepos', email='$email', telpon='$telpon', hp='$hp', pekerjaan='$kerja', institusi='$institusi', keterangan='$keterangan', tgldaftar='".$date[0]."' $fill_foto";
				$result = QueryDb($sql);
				if ($result)
					$this->success();
			}
		}
		
	}
	function exist(){
		?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="format.add.php";
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
	function add(){
		?>
        <form enctype="multipart/form-data" name="addanggota" action="anggota.add.php" onSubmit="return validate()" method="post">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="2" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Tambah Anggota</font></td>
  		  </tr>
          <tr>
            <td width="6%">&nbsp;<strong>No.Registrasi</strong></td>
            <td width="94%"><input name="noreg" type="text" class="cmbfrm2" id="noreg" readonly="readonly" value="<?=$this->get_noreg()?>" ></td>
          </tr>
          <tr>
            <td>&nbsp;<strong>Nama</strong></td>
            <td><input name="nama" type="text" class="inputtxt" id="nama" size="35"></td>
          </tr>
          <tr>
            <td>&nbsp;<strong>Alamat</strong></td>
            <td><textarea name="alamat" cols="45" rows="3" class="areatxt" id="alamat"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;Kodepos</td>
            <td><input name="kodepos" type="text" class="inputtxt" id="kodepos" /></td>
          </tr>
          <tr>
            <td>&nbsp;Email</td>
            <td><input name="email" type="text" class="inputtxt" id="email" size="40" /></td>
          </tr>
          <tr>
            <td>&nbsp;Telepon</td>
            <td><input name="telpon" type="text" class="inputtxt" id="telpon" /></td>
          </tr>
          <tr>
            <td>&nbsp;HP</td>
            <td><input name="hp" type="text" class="inputtxt" id="hp" /></td>
          </tr>
          <tr>
            <td>&nbsp;Pekerjaan</td>
            <td><input name="kerja" type="text" class="inputtxt" id="kerja" size="40" /></td>
          </tr>
          <tr>
            <td>&nbsp;Institusi</td>
            <td><input name="institusi" type="text" class="inputtxt" id="institusi" size="50" /></td>
          </tr>
          <tr>
            <td>&nbsp;Foto</td>
            <td><input type="file" name="foto" id="foto"  class="cmbfrm2"/></td>
          </tr>
          <tr>
            <td>&nbsp;Keterangan</td>
            <td><textarea name="keterangan" cols="45" rows="5" class="areatxt" id="keterangan"></textarea></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="window.close()" ></td>
          </tr>
        </table>
		</form>
		<?php
	}
	function get_noreg(){
		return "ANG".date('YmdHis');
	}
}
?>