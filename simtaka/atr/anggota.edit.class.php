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
class CAnggotaEdit{
	public $replid;
	function OnStart(){
		if (isset($_REQUEST['simpan'])){
			$this->save();		
		} else {
			$this->replid = $_REQUEST['replid'];
			$sql = "SELECT * FROM anggota WHERE replid='".$_REQUEST['replid']."'";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->noreg = $row['noregistrasi'];
			$this->nama = $row['nama'];
			$this->alamat = $row['alamat'];
			$this->telpon = $row['telpon'];
			$this->email = $row['email'];
			$this->hp = $row['HP'];
			$this->kodepos = $row['kodepos'];
			$this->pekerjaan = $row['pekerjaan'];
			$this->institusi = $row['institusi'];
			$this->keterangan = $row['keterangan'];
		}
	}
	function exist(){
		?>
        <script language="javascript">
			alert('Kode sudah digunakan!');
			document.location.href="format.edit.php?id=<?=$_REQUEST['replid']?>";
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
        <form name="editanggota" action="anggota.edit.php" method="post" onSubmit="return validate()" enctype="multipart/form-data">
        <input name="replid" type="hidden" id="replid" value="<?=$this->replid?>">
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="3" align="left">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        		<font style="font-size:18px; color:#999999">Ubah Anggota</font></td>
  		  </tr>
          <tr>
            <td width="77">&nbsp;<strong>Nama</strong></td>
            <td colspan="2"><input name="nama" type="text" class="inputtxt" id="nama" size="35" value="<?=stripslashes((string) $this->nama)?>"></td>
          </tr>
          <tr>
            <td width="77">&nbsp;<strong>Alamat</strong></td>
            <td colspan="2"><textarea name="alamat" cols="45" rows="3" class="areatxt" id="alamat"><?=stripslashes((string) $this->alamat)?></textarea></td>
          </tr>
          <tr>
            <td width="77">&nbsp;Kodepos</td>
            <td width="64%"><input name="kodepos" type="text" class="inputtxt" id="kodepos"  value="<?=stripslashes((string) $this->kodepos)?>"/></td>
            <td width="29%" rowspan="4">
            	<table width="120" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="120" bgcolor="#CCCCCC" align="center" valign="middle">
                        	<img src="../lib/gambar.php?replid=<?=$this->replid?>&field=foto&table=anggota" />
                        </td>
                    </tr>
                </table>            
            </td>
          </tr>
          <tr>
            <td width="77">&nbsp;Telepon</td>
            <td><input name="telpon" type="text" class="inputtxt" id="telpon" value="<?=stripslashes((string) $this->telpon)?>" /></td>
          </tr>
          <tr>
            <td width="77">&nbsp;HP</td>
            <td><input name="hp" type="text" class="inputtxt" id="hp" value="<?=stripslashes((string) $this->hp)?>" /></td>
          </tr>
          <tr>
            <td width="77">&nbsp;Foto</td>
            <td><input type="file" name="foto" id="foto"  class="file"/>
                <br /><span class="posted">* Diisi untuk mengganti foto</span></td>
          </tr>
          <tr>
            <td width="77">&nbsp;Email</td>
            <td colspan="2"><input name="email" type="text" class="inputtxt" id="email" size="40" value="<?=stripslashes((string) $this->email)?>" /></td>
          </tr>
          <tr>
            <td width="77">&nbsp;Pekerjaan</td>
            <td colspan="2"><input name="kerja" type="text" class="inputtxt" id="kerja" size="40" value="<?=stripslashes((string) $this->pekerjaan)?>" /></td>
          </tr>
          <tr>
            <td width="77">&nbsp;Institusi</td>
            <td colspan="2"><input name="institusi" type="text" class="inputtxt" id="institusi" size="50" value="<?=stripslashes((string) $this->institusi)?>" /></td>
          </tr>
          
          <tr>
            <td width="77">&nbsp;Keterangan</td>
            <td colspan="2"><textarea name="keterangan" cols="45" rows="5" class="areatxt" id="keterangan"><?=stripslashes((string) $this->keterangan)?></textarea></td>
          </tr>
          <tr>
            <td colspan="3" align="center"><input type="submit" class="cmbfrm2" name="simpan" value="Simpan" >&nbsp;<input type="button" class="cmbfrm2" name="batal" value="Batal" onClick="window.close()" ></td>
          </tr>
        </table>
		</form>
		<?php
	}
	function save(){
		$replid = $_REQUEST['replid'];
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
		$sql = "UPDATE anggota SET nama='$nama', alamat='$alamat', kodepos='$kodepos', email='$email', telpon='$telpon', hp='$hp', pekerjaan='$kerja', institusi='$institusi', keterangan='$keterangan', tgldaftar='".$date[0]."' $fill_foto WHERE replid='$replid'";
		$result = QueryDb($sql);
		if ($result)
			$this->success();
	}
}
?>