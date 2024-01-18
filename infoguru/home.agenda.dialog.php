<?php
require_once("include/sessionchecker.php");
require_once("include/sessioninfo.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");
require_once("include/compatibility.php");

$replid = $_REQUEST['replid'];

OpenDb();
$sql = "SELECT *
          FROM jbsvcr.agenda
         WHERE replid = '".$replid."'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
?>
<table width="350"  border="0" cellpadding="0" cellspacing="0">
<tr>
    <td width="40" height="69" style="background-image:url(images/agenda_01.jpg); background-repeat:no-repeat;">&nbsp;</td>
    <td width="236" height="69" valign="bottom" style="background-image:url(images/agenda_03.jpg); background-repeat:repeat-x;">
        <div align="right"><span class="style1" style='color: maroon'><?=ShortDateFormat($row['tanggal'])?></span><br></div>
    </td>
    <td width="42" height="69" style="background-image:url(images/agenda_04.jpg); background-repeat:no-repeat">&nbsp;</td>
    <td width="32" height="69" style="background-image:url(images/agenda_05.jpg); background-repeat:no-repeat">&nbsp;</td>
</tr>
<tr>
    <td width="40" height="13" style="background-image:url(images/agenda_06.jpg); background-repeat:repeat-y">&nbsp;</td>
    <td width="236" height="13" style="background-image:url(images/agenda_09.jpg);">
        <span class="style2" style='font-weight: bold; font-size: 12px;'><?=$row['judul']?></span><br /><br />
<?php $komentar = $row['komentar'];
	$komentar = str_replace("#sq;", "'", (string) $komentar);
	echo $komentar;	?>
	</td>
    <td width="42" height="13" style="background-image:url(images/agenda_09.jpg);">&nbsp;</td>
    <td width="32" height="13" style="background-image:url(images/agenda_10.jpg); background-repeat:repeat-y">&nbsp;</td>
</tr>
<tr>
    <td width="40" height="39" style="background-image:url(images/agenda_16.jpg); background-repeat:no-repeat">&nbsp;</td>
    <td width="236" height="39" style="background-image:url(images/agenda_18.jpg); background-repeat:repeat-x">&nbsp;</td>
    <td width="42" height="39" style="background-image:url(images/agenda_18.jpg); background-repeat:repeat-x">&nbsp;</td>
    <td width="32" height="39" style="background-image:url(images/agenda_20.jpg); background-repeat:no-repeat">&nbsp;</td>
</tr>
</table>
<?php
CloseDb();
?>