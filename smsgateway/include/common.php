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
require_once("sessionchecker.php");

$SMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
$LMonth = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$Alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
function RandStr($length) 
{
	$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, random_int(0, 61), 1);
	return $s;		
}

function RandCode($length) 
{
	$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, random_int(0, 61), 1);
	return $s;		
}

function RandNumber($length) 
{
	$charset = "1234567890";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, random_int(0, 61), 1);
	return $s;		
}

function StringIsChecked($String,$Comparer)
{
	if ($String==$Comparer)
		echo "Checked";
}

function StringIsSelected($String,$Comparer)
{
	if ($String==$Comparer)
		echo "Selected";
}

function IntIsSelected($String,$Comparer)
{
	if ((int)$String==(int)$Comparer)
		echo "Selected";
}

function SDateFormat($string)
{
	global $LMonth;
	$x = explode(' ',(string) $string);
	$y = explode('-',$x[0]);
	//echo $y[2].' '.$LMonth[(int)$y[1]-1].' '.$y[0];
	$m = ($y[1]-1);
	echo $y[2].'-'.$y[1].'-'.$y[0];
}

function DateFormat($string)
{
	global $LMonth;
	$x = explode(' ',(string) $string);
	$y = explode('-',$x[0]);
	//echo $y[2].' '.$LMonth[(int)$y[1]-1].' '.$y[0];
	$m = ($y[1]-1);
	echo $y[2].' '.$LMonth[$m].' '.$y[0];
}

function DateFormat2($string)
{
	global $LMonth;
	$x = explode(' ',(string) $string);
	$y = explode('-',$x[0]);
	$m = ($y[1]-1);

	if ($y[2]=='1')
		$ext = "st";
	elseif ($y[2]=='2')
		$ext = "nd";
	elseif ($y[2]=='3')
		$ext = "rd";
	else
		$ext = "th";
	$d = $y[2]; 
	if ($y[2]<10)
		$d = substr($y[2],1,1);
	echo $LMonth[$m].' '.$d.'<sup>'.$ext.'</sup>, '.$y[0];
}

function FullDateFormat($string)
{
	global $LMonth;
	
	$x = explode(' ',(string) $string);
	$y = explode('-',$x[0]);
	$m = ($y[1]-1);
	
	echo $y[2].' '.$LMonth[$m].' '.$y[0].' '.$x[1];
}

function FullDateFormat2($string)
{
	global $LMonth;
	global $SMonth;
	
	$x = explode(' ',(string) $string);
	$y = explode('-',$x[0]);

	$m = ($y[1]-1);
	if ($y[2]=='1')
		$ext = "st";
	elseif ($y[2]=='2')
		$ext = "nd";
	elseif ($y[2]=='3')
		$ext = "rd";
	else
		$ext = "th";
	$d = $y[2]; 
	if ($y[2]<10)
		$d = substr($y[2],1,1);
	echo $SMonth[$m].' '.$d.'<sup>'.$ext.'</sup>, '.$y[0].' on '.$x[1];
}

function MysqlDateFormat($string)
{
	$y = explode('-',(string) $string);
	return $y[2].'-'.$y[1].'-'.$y[0];
}

function GetLastId($field,$table)
{
	$sql = "SELECT MAX($field) FROM $table";
	$res = QueryDb($sql);
	$num = @mysqli_num_rows($res);
	$row = @mysqli_fetch_row($res);
	if ($num==0)
		return '1';
	else
		return $row[0]+1;
}

function ReplaceText($input,$output)
{
	return $output;
}

function CQ($string)
{
	return $string;
}

function pagination($showList,$pageList,$num,$url){
	$page		= $_GET['page'] ?? 1;
	$pagestart 	= $_REQUEST['pagestart'] ?? 1;
	if (ceil($num/$showList)>1){
	?>
	<div class='pagination'>
	<!--<div>-->
	<table border="0" cellspacing="1" cellpadding="1" align="center">
	  <tr>
		<td>
			<?php
			if($page > 1){
				$prev = $page-1;
				$hal = ($prev<$pagestart)?$pagestart-pageList:$pagestart;
				//echo "<td>";
				echo "<div class='prevnext'>";
				echo "<a class='paginationjs' href='$url&page=$prev&pagestart=$hal'><</a>";
				echo "</div>";
				//echo "</td>";
			}
			for ($i=$pagestart;$i<($pagestart+pageList);$i++){//20 jumlah tampilan link halaman
				if ($i != $page){
					if ($i<=ceil(($num/showList))){
						//echo "<td>";
						echo "<div class='page'>";
						echo "<a class='paging paginationjs' href='$url&page=$i&pagestart=$pagestart'>$i</a>";
						echo "</div>";
						//echo "</td>";
					}
				}else{
					//echo "<td>";
					echo "<div class='selected'>";
					echo "<span class='current'>$i</span>";	
					echo "</div>";
					//echo "</td>";
				}
			}
			
			if($page < ceil($num/showList)){
				//echo "<td>";
				$next=$page+1;
				if ($next==$pagestart+pageList)
					$pagestart = $pagestart+pageList;
				echo "<div class='prevnext'>";
				echo "<a class='paginationjs' href='$url&page=$next&pagestart=$pagestart'>></a>";
				echo "</div>";
				//echo "</td>";
			}
			?>
		</td>
	  </tr>
	</table>
	<!--</div>-->
	</div>
	<?php
	}
}
?>