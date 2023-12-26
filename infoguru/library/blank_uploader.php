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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #666666}
-->
</style>
 <script language="javascript">
        function upload() {
            document.location.href = "uploader.php";
        }
        
        function browse() {
            document.location.href = "uploaderbrowser.php";
        }
    </script>
</head>

<body>
<table width="100%" border="0" cellspacing="0">
  <tr height="250">
    <th scope="row"><span class="style1">Tekan tombol <input type="button" onclick="upload()" value="Upload Gambar" class="but" />
     untuk meng-upload gambar baru<br /><br />
    Tekan tombol <input type="button" value="Lihat Gambar" class="but" onclick="browse()" /> untuk memilih gambar yang sudah di-upload sebelumnya</span><br /></th>
  </tr>
</table>

</body>
</html>