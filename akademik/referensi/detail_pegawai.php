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
 require_once("../jibitheme/include/theme.php");

require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/common.php');


if(isset($_POST["detail_pegawai"])){
	$lihat_pegawai = $_POST["detail_pegawai"];
}elseif(isset($_GET["detail_pegawai"])){
	$lihat_pegawai = $_GET["detail_pegawai"];
}
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
<script language = "javascript" type = "text/javascript">
    var win = null;
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}
</script></head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" >
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="26">
	<td width="18" background="../jibitheme/<?=GetThemeDir() ?>index_15.jpg">&nbsp;</td>
    <td width="*" background="../jibitheme/<?=GetThemeDir() ?>index_16.jpg">&nbsp;</td>
    <td width="15" background="../jibitheme/<?=GetThemeDir() ?>index_19.jpg">&nbsp;</td>
</tr>
<tr height="300">
	<td width="18" background="../jibitheme/<?=GetThemeDir() ?>index_27.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
   <table border="0" width="100%" height="100%" valign="middle">
<tr>
    <td align="center" valign="center">

<?php
OpenDb();
if (!isset($_POST['simpan'])) {
$query = "select replid,nip,noid,nama,bagian, tmplahir,day(tgllahir) as tanggal,year(tgllahir) as tahun,month(tgllahir) as bulan,suku,dokter from jibiklinik.pendataanpegawai  where replid = '$lihat_pegawai'";
$result_query= Querydb($query) or die(\MYSQLI_ERROR);
$row=mysqli_fetch_array($result_query);
$tmplahir=$row['tmplahir'];
$tgllahir=$row['tanggal'];
$bulanlahir=$row['bulan'];
$tahunlahir=$row['tahun'];
if($row['dokter']== 1 )
{
 $dokter = "Dokter";
} else {
 $dokter = "Pegawai";
}

?>
    <table width="505" height="261" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="header"><div align="center">Detail pegawai</div></td>
      </tr>
      <tr>
        <td> <br>
            <fieldset>
            <legend></legend>
            <table id="table" cellpadding="0" cellspacing="0" border="0" width="104%">
              <tr>
                <td width="22%">Nip</td>
                <td width="2%">:</td>
                <td width="44%"><?=$row['nip'] ?></td>
                <td rowspan="7" align="center"><img src="../library/gambar.php?table=pendataanpegawai&replid=<?=$row['replid'] ?>" width="90" height="121"></td>
              </tr>
              <tr>
                <td>nama</td>
                <td>:</td>
                <td><b><?=$row['nama'] ?></b></td>
              </tr>
              <tr>
                <td>no Identitas</td>
                <td valign="top">:</td>
                <td valign="top"><?=$row['noid']  ?> </td>
              </tr>
              <tr>
                <td>Tmpt,tgl lahir</td>
                <td valign="top">:</td>
                <td valign="top"><?=" $tmplahir, $tgllahir-$bulanlahir-$tahunlahir" ?> </td>
              </tr>
              <tr>
                <td>Bagian</td>
                <td>:</td>
                <td><?=$row['bagian'] ?></td>
              </tr>
              <tr>
                <td>suku</td>
                <td>:</td>
                <td><?=$row['suku'] ?></td>
              </tr>
              <tr>
                <td>status</td>
                <td>:</td>
                <td><?=$dokter ?></td>
              </tr>
            </table>
            </fieldset></td>
      </tr>
      <tr>
        <td>
          <div align="center">
              <input type="button" value="TUTUP" name="batal" class="but" onClick="window.close();">
          </div></td>
        </tr>
    </table>
    <?php

}else {
    $query_cek = "SELECT * FROM pegawai.suku WHERE suku  = '".$_POST['suku']."'";
    $result_cek = QueryDb($query_cek);
    $num_cek = @mysqli_num_rows($result_cek);

    if($num_cek == 0) {
        $query = "INSERT INTO pegawai.suku(suku) ".
                 "VALUES ('".CQ($_POST['suku'])."')";
        $result = QueryDb($query) or die (mysqli_error($mysqlconnection));

    	$query_get_id = "SELECT last_insert_id() FROM suku";
    	$result_get_id = QueryDb($query_get_id);
    	$row_id = @mysqli_fetch_array($result_get_id);

        if(mysqli_affected_rows($conn) > 0) {
                ?>
<script language = "javascript" type = "text/javascript">
                    opener.document.location.href="pendataan_suku.php?sukuid=<?=$row_id[0] ?>";
    				window.close();
                </script>
                <?php
        }else{
               ?>
               <script language = "javascript" type = "text/javascript">
                   alert("Gagal menambah data");
                   opener.document.location.href="pendataan_suku.php?sukuid=<?=$row_id[0] ?>";
                   window.close();
               </script>
               <?php
        }
    }else {
        ?>
        <script language = "javascript" type = "text/javascript">
            alert("Gagal menambah data. Masukkan Jenis Pemantauan yang berbeda");
            opener.document.location.href="pendataan_suku.php?sukuid=<?=$_POST['sukuid'] ?>";
            window.close();
        </script>
    <?php
    }
}
CloseDb();
?></td></tr>
</table>
 <script language='JavaScript'>
               //  Tables('table', 1, 0);
              </script>
    <!-- END OF CONTENT //--->
    </td>
    <td width="15" background="../jibitheme/<?=GetThemeDir() ?>index_30.jpg">&nbsp;</td>
</tr>
<tr height="26">
	<td width="18" background="../jibitheme/<?=GetThemeDir() ?>index_35.jpg">&nbsp;</td>
    <td width="*" background="../jibitheme/<?=GetThemeDir() ?>index_37.jpg">&nbsp;</td>
    <td width="15" background="../jibitheme/<?=GetThemeDir() ?>index_39.jpg">&nbsp;</td>
</tr>
</table>


</body>
</html>