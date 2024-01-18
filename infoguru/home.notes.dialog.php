<?php
require_once("include/sessionchecker.php");
require_once("include/sessioninfo.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");
require_once("include/compatibility.php");

$replid = $_REQUEST['replid'];

OpenDb();

$sql = "SELECT c.nis, s.nama AS namasis, DATE_FORMAT(c.tanggal, '%d-%M-%Y') AS tanggal,
               c.judul, c.catatan, c.nip, p.nama AS namapeg, k.kelas, s.foto, s.foto IS NULL AS isnull
          FROM jbsvcr.catatansiswa c, jbsakad.siswa s, jbssdm.pegawai p, jbsakad.kelas k
         WHERE c.nis = s.nis
           AND c.nip = p.nip
           AND s.idkelas = k.replid
           AND c.replid = $replid";
           
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);
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
            <span style="color:#FF6600; font-family:Calibri; font-size:16px; font-weight:bold; ">Catatan siswa
            <?= $row['namasis'] . " (" . $row['nis'] . ")" . " kelas " . $row['kelas'] ?>
            </span>
        </div>
        
        <table width="95%" border="0" cellspacing="2" cellpadding="2" align="center">
		<tr>
			<td colspan='2' valign='top' align='left'>
				<font style='font-size: 9px; color: maroon;'><?=$row['tanggal']?></font><br>
				<font style='font-size: 14px; font-weight: bold;'><?=$row['judul']?></font><br>
				<font style='font-size: 10px;'> oleh <?= $row['namapeg'] . " (" . $row['nip'] .")" ?></font><br>
			</td>
		</tr>
		<tr>
			<td width='14%' valign='top' align='center'>
				<br>	
<?php
				if ($row['isnull'] == 0)
				{
					$pict = base64_encode((string) $row['foto']);    
				}
				else
				{
					$filename = "images/no_user.png";
					$contents = "";
					if ($handle = @fopen($filename, "r"))
					{
						$contents = @fread($handle, filesize($filename));
						@fclose($handle);
					}
				
					$pict = base64_encode($contents);
				}
				echo "<img src='data:image/jpeg;base64,$pict' height='90'>";
?>				
			</td>
			<td width='*' valign='top' align='left'>
				<font style="font-size: 11px; line-height: 18px">
                <?=$row['catatan']?>
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