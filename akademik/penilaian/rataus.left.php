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
require_once('../include/config.php');
require_once('../include/common.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
OpenDb();
$oldnis = $_REQUEST['oldnis'];
$sql = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '$oldnis'";
$result = QueryDb($sql);
$row = @mysqli_fetch_array($result);
$dep[0] = array($row['departemen'], $oldnis);
if ($row['nislama'] <> "") {
	$sql1 = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '".$row['nislama']."'";
	$result1 = QueryDb($sql1);
	$row1 = @mysqli_fetch_array($result1);	
	$dep[1] = array($row1['departemen'], $row['nislama']);
	if ($row1['nislama'] <> "") {				
		$sql2 = "SELECT replid, departemen, nislama FROM riwayatdeptsiswa WHERE nis = '".$row1[nislama]'";
		$result2 = QueryDb($sql2);
		$row2 = @mysqli_fetch_array($result2);					
		$dep[2] = array($row2['departemen'],$row1['nislama']) ;
	}	
}	
$allnis = "";
for ($i=0;$i<2;$i++){
	if ($dep[$i][1]!=""){
		if ($allnis=="")
			$allnis = "'".$dep[$i][1]."'";
		else
			$allnis .= ",'".$dep[$i][1]."'";
	}
}

$alldept = "";
for ($i=0;$i<2;$i++){
	if ($dep[$i][0]!=""){
		if ($alldept=="")
			$alldept = "'".$dep[$i][0]."'";
		else
			$alldept .= ",'".$dep[$i][0]."'";
	}
}

$tkt = "";
if (isset($_REQUEST['tkt']))
	$tkt = $_REQUEST['tkt'];
$kls = "";
if (isset($_REQUEST['kls']))
	$kls = $_REQUEST['kls'];	
$nis = "";
if (isset($_REQUEST['nis']))
	$nis = $_REQUEST['nis'];
?>
<script language="javascript">
	function ChgTkt(Val){
		var x = Val.split(",");
		document.location.href="rataus.left.php?kls="+x[0]+"&tkt="+x[1]+"&oldnis=<?=$oldnis?>&nis="+x[2];
	}
</script>
<script language="javascript" src="../script/tables.js"></script>
<link href="../style/style.css" rel="stylesheet" type="text/css" />
<div>
<table>
<tr><td style="padding-right:5px; padding-bottom:15px"><strong>Tingkat</strong></td><td style="padding-bottom:15px">
<select name="tingkat" onchange="ChgTkt(this.value)">
	<?php
	$sql = "SELECT DISTINCT(r.idkelas), k.kelas, t.tingkat, r.nis, t.replid FROM riwayatkelassiswa r, kelas k, tingkat t WHERE r.nis IN ($allnis) AND r.idkelas = k.replid AND k.idtingkat = t.replid";
	$res = QueryDb($sql);
	while ($row = @mysqli_fetch_row($res)){
		if ($tkt=="")
			$tkt = $row[4];
		if ($kls=="")
			$kls = $row[0];
		if ($nis=="")
			$nis = $row[3];	
		?>
        <option value="<?=$row[0].','.$row[4].','.$row[3]?>" <?=StringIsSelected($row[0].','.$row[4].','.$row[3],$kls.','.$tkt.','.$nis)?>><?=$row[2]?></option>
        <?php
	}	
	?>
</select>
</td></tr>
</table>
</div>
<div>
<strong>Pelajaran:</strong>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab" id="table" style="border-collapse: collapse; border-width: 1px; border-color: #f5f5f5;">
  <tr style="height: 2px;">
    <td></td>
  </tr>
<?php
$sql = "SELECT DISTINCT p.replid, n.nis, u.idkelas, p.nama FROM ujian u, pelajaran p, nilaiujian n WHERE u.idpelajaran = p.replid AND u.idkelas = '$kls' AND u.replid = n.idujian AND n.nis='$nis' ORDER BY p.nama";
$res = QueryDb($sql);
while ($row = @mysqli_fetch_row($res)){
	?>
    <tr>
    	<td height="20" class="td" style="cursor:pointer" onclick="parent.right.location.href='rataus.right.php?pel=<?=$row[0]?>&nis=<?=$nis?>&kls=<?=$kls?>&tkt=<?=$tkt?>'"><a target="right" href="rataus.right.php?pel=<?=$row[0]?>&nis=<?=$nis?>&kls=<?=$kls?>&tkt=<?=$tkt?>"><?=$row[3]?></a></td>
    </tr>
    <?php
}

?>
  
</table>
<script language='JavaScript'>
	Tables('table', 1, 0);
</script>
</div>