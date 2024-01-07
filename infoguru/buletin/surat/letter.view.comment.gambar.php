<?php
require_once("../../include/sessionchecker.php");
require_once("../../include/sessioninfo.php");
require_once("../../include/config.php");
require_once("../../include/common.php");
require_once("../../include/compatibility.php");
require_once("../../include/db_functions.php");

OpenDb();

$ownerid = $_REQUEST['ownerid'];

$sql = "SELECT foto, foto IS NULL AS isnull FROM jbssdm.pegawai WHERE nip = '".$ownerid."'";
$res = QueryDb($sql);

if (mysqli_num_rows($res) > 0)
{
    $row = mysqli_fetch_array($res);
    if ($row['isnull'] == 0)
    {
        echo $row['foto'];    
    }
    else
    {
        ShowNoUserImage();
    }
    
}
else
{
    ShowNoUserImage();
}

CloseDb();

function ShowNoUserImage()
{
    $filename = "../../images/no_user.png";
    $contents = "";
    
    if ($handle = @fopen($filename, "r"))
    {
        $contents = @fread($handle, filesize($filename));
        @fclose($handle);
    }

    echo $contents;
}
?>