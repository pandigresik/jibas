<?php
function ReadRequest()
{
    global $nip, $nama, $tahun30, $bulan30, $tanggal30, $tahun, $bulan, $tanggal, $maxDay30, $maxDay;
    
    $nip = $_REQUEST['nip'];
    
    $sql = "SELECT TRIM(CONCAT(IFNULL(gelarawal,''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS nama
              FROM jbssdm.pegawai
             WHERE nip='$nip'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $nama = $row[0];
    
    if (!isset($_REQUEST['tahun30']) || !isset($_REQUEST['tahun']))
    {
        $sql = "SELECT YEAR(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                       MONTH(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                       DAY(DATE_SUB(NOW(), INTERVAL 30 DAY)),
                       YEAR(NOW()), MONTH(NOW()), DAY(NOW())";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $tahun30 = $row[0];
        $bulan30 = $row[1];
        $tanggal30 = $row[2];
        $tahun = $row[3];
        $bulan = $row[4];
        $tanggal = $row[5];
    }
    else
    {
        $tahun30 = $_REQUEST['tahun30'];
        $bulan30 = $_REQUEST['bulan30'];
        $tanggal30 = $_REQUEST['tanggal30'];
        $tahun = $_REQUEST['tahun'];
        $bulan = $_REQUEST['bulan'];
        $tanggal = $_REQUEST['tanggal'];
    }
    
    $maxDay30 = DateArith::DaysInMonth($bulan30, $tahun30);
    $maxDay = DateArith::DaysInMonth($bulan, $tahun);
}

function ShowSelectDate()
{
    global $G_START_YEAR, $tahun30, $bulan30, $tanggal30, $tahun, $bulan, $tanggal, $maxDay30, $maxDay; ?>
    
    Tanggal:
	<select name="tahun30" id="tahun30" onchange="changeDate()">
	<?php
	for($i = $G_START_YEAR; $i <= date('Y'); $i++)
	{
		$sel = $i == $tahun30 ? "selected" : "";
		echo "<option value='$i' $sel>$i</option>";
	}
	?>
	</select>
	<select name="bulan30" id="bulan30" onchange="changeDate()">
	<?php
	for($i = 1; $i <= 12; $i++)
	{
		$sel = $i == $bulan30 ? "selected" : "";
		echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
	}
	?>
	</select>
	<select name="tanggal30" id="tanggal30" onchange="changeDate()">
	<?php
	for($i = 1; $i <= $maxDay30; $i++)
	{
		$sel = $i == $tanggal30 ? "selected" : "";
		echo "<option value='$i' $sel>" . $i . "</option>";
	}
	?>
	</select>
	&nbsp;s/d&nbsp;
	<select name="tahun" id="tahun" onchange="changeDate()">
	<?php
	for($i = $G_START_YEAR; $i <= date('Y'); $i++)
	{
		$sel = $i == $tahun ? "selected" : "";
		echo "<option value='$i' $sel>$i</option>";
	}
	?>
	</select>
	<select name="bulan" id="bulan" onchange="changeDate()">
	<?php
	for($i = 1; $i <= 12; $i++)
	{
		$sel = $i == $bulan ? "selected" : "";
		echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
	}
	?>
	</select>
	<select name="tanggal" id="tanggal" onchange="changeDate()">
	<?php
	for($i = 1; $i <= $maxDay; $i++)
	{
		$sel = $i == $tanggal ? "selected" : "";
		echo "<option value='$i' $sel>" . $i . "</option>";
	}
	?>
	</select>
<?php    
}


function ShowStatistics()
{
    global $nip, $tahun30, $bulan30, $tanggal30, $tahun, $bulan, $tanggal;
    
    ?>
    
    <table border="0" cellpadding="2" cellspacing="0" width="870">
    <tr>
        <td align="center" width="50%">
        <img height="250" src="<?= "daftarpresensi.image.php?type=bar&nip=$nip&tahun30=$tahun30&bulan30=$bulan30&tanggal30=$tanggal30&tahun=$tahun&bulan=$bulan&tanggal=$tanggal" ?>" />    
        </td>
        <td align="center" width="50%">
        <img height="250" src="<?= "daftarpresensi.image.php?type=pie&nip=$nip&tahun30=$tahun30&bulan30=$bulan30&tanggal30=$tanggal30&tahun=$tahun&bulan=$bulan&tanggal=$tanggal" ?>" />    
        </td>
    </tr>    
    </table>
    
<?php    
}

function ShowDetail()
{
    global $nip, $tahun30, $bulan30, $tanggal30, $tahun, $bulan, $tanggal;
    ?>

    <table border="1" cellpadding="2" cellspacing="0" width="870" style="border-width: 1px; border-collapse: collapse" class="tab" id="table">
    <tr height="25">
        <td width="30" align="center" class="header">No</td>
        <td width="120" align="center" class="header">Tanggal</td>
        <td width="100" align="center" class="header">Status</td>
        <td width="100" align="center" class="header">Jam Masuk</td>
        <td width="100" align="center" class="header">Jam Pulang</td>
        <td width="120" align="center" class="header">Waktu Kerja</td>
        <td width="200" align="center" class="header">Keterangan</td>
        <td width="100" align="center" class="header">Sumber</td>
    </tr>  
    <?php
    $sql = "SELECT tanggal, DATE_FORMAT(tanggal, '%d %M %Y') AS tanggalview, jammasuk, jampulang,
                   jamwaktukerja, menitwaktukerja, status, keterangan, source
              FROM jbssdm.presensi
             WHERE tanggal BETWEEN '$tahun30-$bulan30-$tanggal30' AND '$tahun-$bulan-$tanggal'
               AND nip = '$nip'
             ORDER BY tanggal";
    $res = QueryDb($sql);
    $no = 0;
    $totjkerja = 0;
    $totmkerja = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $status = $row["status"];
        
        if ($status == 1)
        {
            $bgcolor = "#b3de81";
            $statusname = "Hadir";
        }
        elseif ($status == 2)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Izin";
        }
        elseif ($status == 3)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Sakit";
        }
        elseif ($status == 4)
        {
            $bgcolor = "#eccbfb";
            $statusname = "Cuti";
        }
        elseif ($status == 5)
        {
            $bgcolor = "#fbcbcb";
            $statusname = "Alpa";    
        }
		elseif ($status == 6)
        {
            $bgcolor = "#979797";
            $statusname = "Bebas";    
        }
        
        $totjkerja += $row["jamwaktukerja"];
        $totmkerja += $row["menitwaktukerja"];
    ?>
    <tr height="22">
        <td align="center"><?=$no?></td>
        <td align="center"><?=$row["tanggalview"]?></td>
        <td align="center" bgcolor="<?=$bgcolor?>"><strong><?=$statusname?></strong></td>
        <td align="center"><?=$row["jammasuk"]?></td>
        <td align="center"><?=$row["jampulang"]?></td>
        <td align="left"><?=$row["jamwaktukerja"] . " jam " . $row["menitwaktukerja"] . " menit"?></td>
        <td align="left"><?=$row["keterangan"]?></td>
        <td align="left"><?=$row["source"]?></td>
    </tr>
    <?php
    }
    ?>
    <tr height="30">
        <td style="background-color: #DDD" colspan="5">&nbsp;</td>
        <td style="background-color: #DDD; font-weight: bold;" align="left">
    <?php
        if ($totmkerja >= 60)
        {
            $totjkerja += floor($totmkerja / 60);
            $totmkerja %= 60;
        }    
        echo $totjkerja . " jam " . $totmkerja . " menit";
    ?>
        </td>
        <td style="background-color: #DDD" colspan="2">&nbsp;</td>
    </tr>
    </table>
<?php
}
?>