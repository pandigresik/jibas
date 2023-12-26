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
session_name("jbsperpus");
session_start();

if (!isset($_SESSION['login']))
{ 
	include('login.php');
	exit;
}  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="img/jibas2015.ico" rel="shortcut icon" />
    <title>JIBAS Perpustakaan</title>
    <script language="javascript">
    function backToMenu(i) {
        var addr;
        switch (i) {
            case '0' :
                addr = "../../referensi/referensi.php";
                break;
            case '1' :
                addr = "../../transaksi/transaksi.php";
                break;
        }
        content.location.href = addr;
    }
    </script>
</head>

<frameset rows="60,*,32" frameborder="no" border="0" framespacing="0">
	<frame name="frameatas" src="frameatas.php" scrolling="no" noresize="noresize" style="overflow:hidden" />
    <frameset cols="30,*,30" frameborder="0" border="0" framespacing="0">
    	<frame name="framekiri" src="framekiri.php" scrolling="no" noresize="noresize" style="overflow:hidden" />
        <frame name="content" src="ref/referensi.php"/>
        <frame name="framekanan" src="framekanan.php" scrolling="no" noresize="noresize" style="overflow:hidden" />
    </frameset>
	<frame name="framebawah" src="framebawah.php" scrolling="no" noresize="noresize" style="overflow:hidden" />
</frameset><noframes></noframes>

</html>