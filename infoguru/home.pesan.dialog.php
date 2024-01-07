<?php
require_once("include/sessionchecker.php");
require_once("include/sessioninfo.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");
require_once("include/compatibility.php");

$idpesan = $_REQUEST['replid'];

OpenDb();

$sql = "SELECT DATE_FORMAT(pg.tanggalpesan, '%d %M %Y %H:%i') as tgl, pg.judul as judul,
		       pg.pesan as pesan, p.nama as nama, pg.replid as replid
		  FROM jbsvcr.pesan pg, jbssdm.pegawai p
		 WHERE pg.idguru = p.nip
           AND pg.replid = '".$idpesan."'";
$result = QueryDb($sql);
if (@mysqli_num_rows($result) > 0)
{
	$row2 = @mysqli_fetch_array($result);
	$senderstate = "guru";
    $sendername = $row2['nama'];
}
else
{
	$sql = "SELECT DATE_FORMAT(pg.tanggalpesan, '%d %M %Y %H:%i') as tgl, pg.judul as judul,
				   pg.pesan as pesan, p.nama as nama, pg.replid as replid
			  FROM jbsvcr.pesan pg, jbsakad.siswa p
			 WHERE pg.nis = p.nis
               AND pg.replid = '".$idpesan."'";
	$result = QueryDb($sql);
	$row2 = @mysqli_fetch_array($result);
	$senderstate = "siswa";
    $sendername = $row2['nama'];
}            
?>
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="images/BGNews_01.png" width="12" height="11" alt=""></td>
	<td background="images/BGNews_02.png" width="*" height="11"></td>
    <td><img src="images/BGNews_03.png" width="18" height="11" alt=""></td>
</tr>
<tr>
	<td background="images/BGNews_04.png" width="12"></td>
	<td width="*" background="images/BGNews_05.png">
        <div align="left" style="padding-bottom:10px;" >
            <span style="color:#339900; font-size:20px; font-weight:bold">.:</span>
            <span style="color:#FF6600; font-family:Calibri; font-size:16px; font-weight:bold; ">Pesan dari
            <?=$sendername?>
            </span>
        </div>
        
        <table width="95%" border="0" cellspacing="2" cellpadding="2" align="center">
		<tr>
            <td valign='top' align='left'>
				<font style="font-size: 9px; color: maroon; font-style: italic;">
                <?=$row2['tgl']?>
                </font><br>
				<font style="font-size: 14px; font-weight: bold;">
                <?=$row2['judul']?>
                </font>
                <font style="font-size: 11px; line-height: 18px">
                <?=$row2['pesan']?>
                </font>
            </td>
        </tr>
        </table>
    </td>
	<td background="images/BGNews_06.png" width="18"></td>
</tr>
<tr>
	<td><img src="images/BGNews_07.png" width="12" height="20" alt=""></td>
	<td background="images/BGNews_08.png" width="*" height="17"></td>
	<td><img src="images/BGNews_09.png" width="18" height="20" alt=""></td>
</tr>
</table>
<?php
CloseDb();
?>