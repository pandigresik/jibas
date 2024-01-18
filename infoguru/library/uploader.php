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
require_once('../include/common.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/fileinfo.php');
require_once('../include/imageresizer.php');

if (isset($_REQUEST['simpan']) && isset($_FILES['foto']))
{
		$nama = $_REQUEST['nama'];
		$keterangan = $_REQUEST['keterangan'];
		$pengguna = SI_USER_ID();
		$bln = date('m');
		$thn = date('Y');
		
		// create filename
		$salt = RandomString(7);
		$foto = $_FILES["foto"];
		$origfile = $foto['name'];
		$ext = GetFileExt($origfile);
		$fn = GetFileName($origfile);
		$fn = str_replace(" ", "", (string) $fn);
		$fn = date('ymdHis') . "-" . $pengguna . "-" . $salt . "-" . $fn;
		$fn = md5($fn) . $ext;
		
		// Check and create directory if it doesnt exists
		$updir = $FILESHARE_UPLOAD_DIR . "/media/" . $thn . $bln . "/";
		if (!is_dir($updir))
		{
			mkdir($updir, 0750, true);
			
			$fhtaccess = "$updir/.htaccess";
			$fhtaccess = str_replace("//", "/", $fhtaccess);
			if ($fp = @fopen($fhtaccess, "w"))
			{
				@fwrite($fp, "Options -Indexes\r\n");
				@fclose($fp);
			}
		}
		$output = $updir . $fn;
		
		$w = 320; $h = 240;
		ResizeImage($foto, $w, $h, 70, $output);
		$foto_data = addslashes(fread(fopen($output,"r"), filesize($output)));
		if ($foto_data != "")
			$gantifoto = ", foto='$foto_data'";
		else
			$gantifoto = "";
		$relpath = $thn . $bln . "/";
		
		OpenDb();
		$sql = "INSERT INTO jbsvcr.gambartiny
					  SET info1='$fn', idguru='$pengguna', namagambar='$nama',bulan='$bln',tahun='$thn', keterangan='$keterangan' $gantifoto";
		$result = QueryDb($sql);
		CloseDb();
		
		if ($result)
		{
		?>
				<script language="javascript">
					alert ('Berhasil upload gambar !\nSilakan pilih gambar dengan menekan tombol Pilih Gambar');
					document.location.href="blank_uploader.php";
				</script>
<?php 	}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <link rel="Stylesheet" href="../style/style.css" />
    <title></title>
    <script language="javascript" src="../script/validasi.js"></script>
    <script language="javascript">
    	function chg(){
		var x=document.getElementById("foto").value;
			if (x.length>0){
				//document.getElementById("keterangan").style.visibility="visible";
				//document.getElementById("jumlah").value="1";
				var result = '';
				var i=0;
				var xxx='';
				while (i <= x.length - 1){
					if (x.charAt(i)=='.'){
						xxx=x.charAt(i);
					}
					if (xxx.length>0){
						result = result + x.charAt(i);
					}
					i++;
				}
			document.getElementById("ext").value = result;
			document.getElementById("tr").style.background = "#99CCCC" ;
			document.getElementById("nama").focus();
			}
		}
		function validate(){
			var x=document.getElementById("foto").value;
			var n=document.getElementById("nama").value;
			var y=1;
			if (x.length==0){
				alert ('File tidak boleh kosong!');
				return false;
			} else {
				while (y<=3){
				var ext=document.getElementById("ext").value;
				var file=document.getElementById("foto").value;
				if (file.length>0){
					if (ext!='.JPG' && ext!='.jpg' && ext!='.Jpg' && ext!='.JPg' && ext!='.JPEG' && ext!='.jpeg'){
						alert ('Format Gambar harus ber-extensi jpg atau JPG !');
						document.getElementById("foto").value='';
						document.getElementById("tr").style.background = "#FF8080" ;
						return false;
					} 
				}
				y++;
				}
			}
			if (n.length==0){
				alert ('Nama gambar harus diisi!');
				document.getElementById("nama").focus();
				return false;
			}
		return true;
		}

    </script>
</head>
<body>
    <form action="uploader.php" id="form1" runat="server" enctype="multipart/form-data" onSubmit="return validate()" method="post">
    <div>
    <table border="1" cellpadding="2" cellspacing="0" width="100%">
    <tr id="tr" style="background:#99CCCC">
        <td width="16%" align="left" >File Gambar :</td>
        <td width="84%" ><input size="60" name="foto" id="foto" type="file" onChange="chg()"><input type="hidden" name="ext" id="ext" size="30"></td>
    </tr>
    <tr>
        <td align="left">Nama Gambar :</td>
        <td align="left"><input type="text" name="nama" id="nama" size="30"></td>
    </tr>
    <tr>
        <td align="left" bgcolor="#99CCCC">Keterangan :</td>
        <td align="left" bgcolor="#99CCCC"><input type="text" name="keterangan" id="keterangan" size="50"></td>
    </tr>
    <tr>
        <td colspan="2">
            <div align="center">
              <input size="60" class="but" name="simpan" id="simpan" type="submit" value="Upload">        
            </div></td>
      </tr>
    </table>
    
    </div>
    </form>
</body>
</html>