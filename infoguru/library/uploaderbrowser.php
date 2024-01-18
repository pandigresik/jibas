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
require_once('deleteimage.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];
$tahun = "";
if (isset($_REQUEST['tahun']))
	$tahun = $_REQUEST['tahun'];
$bulan = "";
if (isset($_REQUEST['bulan']))
	$bulan = $_REQUEST['bulan'];
	
$namabulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];

OpenDb();

$sql = "SELECT bulan FROM jbsvcr.gambartiny WHERE idguru='".SI_USER_ID()."' GROUP BY bulan";
$result = QueryDb($sql);
$num = @mysqli_num_rows($result);
$b = 0;
while ($row = @mysqli_fetch_array($result))
{
	$bln[$b]=$row['bulan'];
	$b++;
}

$sql = "SELECT tahun FROM jbsvcr.gambartiny WHERE idguru='".SI_USER_ID()."' GROUP BY tahun";
$result = QueryDb($sql);
$t = 0;
while ($row = @mysqli_fetch_array($result))
{
	$thn[$t]=$row['tahun'];
	$t++;
}

if ($op == "09vn4230984cn2048723n98423")
{
	$sql_del = "DELETE FROM jbsvcr.gambartiny WHERE replid='".$_REQUEST['replid']."'";
	$result_del = QueryDb($sql_del);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <link href="../style/style.css" rel="Stylesheet" />
    <title>Untitled Page</title>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript">
    function OpenGambar(addr) {
        //var addr = "gambar.php.php?replid=" + replid + "&table=" + jbsvcr.gambartiny;
	    newWindow(addr, 'Gambar','720','630','resizable=1,scrollbars=1,status=0,toolbar=0');
    }
    
    function DelGambar(replid) {
        if (confirm("Apakah anda yakin akan menghapus gambar ini?")) {
            document.location.href = "uploaderbrowser.php?op=09vn4230984cn2048723n98423&replid="+replid;
        }
    }
        
    function PilihGambar(replid) {
        parent.opener.Accept(replid);
        parent.close();
    }
	function chg() {
        var tahun=document.getElementById('tahun').value;
		var bulan=document.getElementById('bulan').value;
		document.location.href = "uploaderbrowser.php?tahun="+tahun+"&bulan="+bulan;
    }
    </script>
</head>
<body>
    <form id="form1" runat="server">
    <div>
    Bulan: 
    <select name="bulan" id="bulan" onchange="chg()">
    	<?php
		for ($bi=0;$bi<=$b-1;$bi++){
		if ($bulan=="")
			$bulan=$bln[$bi];
		?>
        <option value="<?=$bln[$bi]?>" <?=StringIsSelected($bulan,$bln[$bi])?>><?=$namabulan[(int)$bln[$bi]-1]?></option>
        <?php
		}
		?>
    </select>&nbsp;<select name="tahun" id="tahun" onchange="chg()">
    	<?php
		for ($ti=0;$ti<=$t-1;$ti++){
		if ($tahun=="")
			$tahun=$thn[$ti];
		?>
        <option value="<?=$thn[$ti]?>" <?=StringIsSelected($tahun,$thn[$ti])?>><?=$thn[$ti]?></option>
        <?php
		}
		?>
    </select>
    <br /><br />
<?php
	if ($num>0)
	{
		$sql = "SELECT * FROM jbsvcr.gambartiny WHERE idguru='".SI_USER_ID()."' AND tahun=".$tahun." AND bulan=".$bulan;
		$result = QueryDb($sql); ?>
		<table width="75%" border="0" cellspacing="0">
<?php 	while ($row = @mysqli_fetch_array($result))
		{
			$bln = $row['bulan'];
			$bln = strlen((string) $bln) == 1 ? "0$bln" : $bln;
			$thn = $row['tahun'];
			$fn = $row['info1'];
			
			$imgAddr = "$FILESHARE_ADDR/media/$thn$bln/$fn";
			?>
			<tr>
				<td align="center">
					<img onclick="PilihGambar('<?=$imgAddr?>')"
						  src="<?=$imgAddr?>" width="80" height="80"/><br /><br />
					<input class="but" type="button" name="pilih" id="pilih" value="Pilih"
							 onclick="PilihGambar('<?=$imgAddr?>')" />
					<img style="cursor:pointer;" src="../images/ico/lihat.png"
						  onclick="OpenGambar('<?=$imgAddr?>')" title="Lihat Gambar Ini" />&nbsp;
					<img style="cursor:pointer;" src="../images/ico/hapus.png"
						  onclick="DelGambar('<?=$row['replid']?>')" title="Hapus Gambar Ini" />
				</td>  
			</tr>
			<tr>
				<td>Nama Gambar : <?=$row['namagambar']?><br />Keterangan Gambar : <?=$row['keterangan']?></td>  
			</tr>
			<tr>
				<td><hr /></td>  
			</tr>
<?php 	} ?>
</table>
<?php }
	CloseDb();
?>

	</div>
    </form>
</body>
</html>