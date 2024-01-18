<?php
require_once("include/sessionchecker.php");
require_once("include/sessioninfo.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");
require_once("include/compatibility.php");

$id = $_REQUEST['id'];
$jenis = $_REQUEST['jenis'];

OpenDb();

if ($jenis == "S")
{
    $sql = "SELECT s.foto, s.foto IS NULL AS isnull, k.kelas AS info, s.nama
              FROM jbsakad.siswa s, jbsakad.kelas k
             WHERE s.nis = '$id'
               AND s.idkelas = k.replid";
}
else
{
    $sql = "SELECT p.foto, p.foto IS NULL AS isnull, p.bagian AS info, p.nama
              FROM jbssdm.pegawai p
             WHERE nip = '$id' ";
}

$res = QueryDb($sql);
$row = @mysqli_fetch_array($res);

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
?>
<table width="350"  border="0" cellpadding="0" cellspacing="0">
<tr>
    <td align='center' valign='top'>
<?php      echo "<img src='data:image/jpeg;base64,$pict' height='120'>";             ?><br><br>
<font style='font-weight: bold; font-size: 16px'><?=$row['nama']?></font><br>
<font style='font-weight: bold; font-size: 12px'><?=$row['info']?></font>        
    </td>
</tr>
</table>
<?php
CloseDb();
?>