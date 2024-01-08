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
class CStat
{
	function OnStart()
	{
		$this->Limit=10;
		if (isset($_REQUEST['Limit']))
			$this->Limit = $_REQUEST['Limit'];
			
		$this->perpustakaan = $_REQUEST['perpustakaan'];
		
		$this->BlnAwal = date('m');
		if (isset($_REQUEST['BlnAwal']))
			$this->BlnAwal = $_REQUEST['BlnAwal'];
			
		$this->ThnAwal = date('Y');
		if (isset($_REQUEST['ThnAwal']))
			$this->ThnAwal = $_REQUEST['ThnAwal'];
			
		$this->BlnAkhir = date('m');
		if (isset($_REQUEST['BlnAkhir']))
			$this->BlnAkhir = $_REQUEST['BlnAkhir'];
			
		$this->ThnAkhir = date('Y');
		if (isset($_REQUEST['ThnAkhir']))
			$this->ThnAkhir = $_REQUEST['ThnAkhir'];
	}
	
	function reload_page()
	{
		?>
		<script language='JavaScript'>
			document.location.href="pustaka.baru.php";
        </script>
		<?php
	}
	
	function OnFinish()
	{
		?>
		<script language='JavaScript'>
			Tables('table', 1, 0);
		</script>
		<?php
    }
	
	function GetPerpus()
	{
		global $db_name_perpus;
		
		$sql = "SELECT replid,nama FROM $db_name_perpus.perpustakaan ORDER BY nama";
		
		$result = QueryDb($sql);
		?>
		<select name="perpustakaan" id="perpustakaan" class="cmbfrm"  onchange="chg()">
		<?php
			echo "<option value='-1' ".IntIsSelected('-1',$this->perpustakaan).">(Semua)</option>";
		
			while ($row = @mysqli_fetch_row($result))
			{
				if ($this->perpustakaan=="")
					$this->perpustakaan = $row[0];	
				?>
				<option value="<?=$row[0]?>" <?=IntIsSelected($row[0],$this->perpustakaan)?>><?=$row[1]?></option>
<?php 		}	?>
		</select>
		<?php
	}
	
    function Content()
	{
		global $G_START_YEAR;
		?>
		
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top">
           	<div align="left">
                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr>
                    <td width="20">Perpustakaan </td>
                    <td width="200"><?=$this->GetPerpus()?></td>
                    <td width="66%" rowspan="3">
                        <a href="javascript:show()"><img src="../../img/view.png" width="48" height="48" border="0" /></a>
					</td>
                </tr>
                <tr>
                    <td>Bulan</td>
                    <td width="*">
<?php 					$yearnow = date('Y');	?>
                        <table width="100%" border="0" cellspacing="0" cellpadding="1">
                        <tr>
                            <td>
<?php 							echo "<select name='BlnAwal' id='BlnAwal' class='cmbfrm' onchange='chg()'>";
                                for ($i=1;$i<=12;$i++)
								{
                                    if ($this->BlnAwal=="")
                                        $this->BlnAwal = $i;
										
                                    echo "<option value='".$i."' ".IntIsSelected($i,$this->BlnAwal).">".NamaBulan($i)."</option>";
                                }
                                echo "</select>"; ?>
							</td>
                            <td>
<?php 							echo "<select name='ThnAwal' id='ThnAwal' class='cmbfrm' onchange='chg()'>";
                                for ($i=$G_START_YEAR;$i<=$yearnow;$i++)
								{
                                    if ($this->ThnAwal=="")
                                        $this->ThnAwal = $i;
                                    echo "<option value='".$i."' ".IntIsSelected($i,$this->ThnAwal).">".$i."</option>";
                                }
                                echo "</select>";	?>
							</td>
                            <td>
<?php 							echo "&nbsp;s.d.&nbsp;";	?>
							</td>
                            <td>
<?php 							echo "<select name='BlnAkhir' id='BlnAkhir' class='cmbfrm' onchange='chg()'>";
                                for ($i=1;$i<=12;$i++)
								{
                                    if ($this->BlnAkhir=="")
                                        $this->BlnAkhir = $i;
                                    echo "<option value='".$i."' ".IntIsSelected($i,$this->BlnAkhir).">".NamaBulan($i)."</option>";
                                }
                                echo "</select>";	?>
							</td>
                            <td>
<?php 							echo "<select name='ThnAkhir' id='ThnAkhir' class='cmbfrm' onchange='chg()'>";
                                for ($i=$G_START_YEAR;$i<=$yearnow;$i++)
								{
                                    if ($this->ThnAkhir=="")
                                        $this->ThnAkhir = $i;
                                    echo "<option value='".$i."' ".IntIsSelected($i,$this->ThnAkhir).">".$i."</option>";
                                }
                                echo "</select>"; ?>
							</td>
                        </tr>
                        </table>
					</td>
                </tr>
                <tr>
                    <td>Jumlah&nbsp;data&nbsp;yang&nbsp;ditampilkan</td>
                    <td>
<?php 					echo "<select name='Limit' id='Limit' class='cmbfrm' onchange='chg()'>";
						for ($i=5;$i<=20;$i+=5)
						{
							if ($this->Limit=="")
								$this->Limit = $i;
							echo "<option value='".$i."' ".IntIsSelected($i,$this->Limit).">".$i."</option>";
						}
						echo "</select>"; ?>
					</td>
                </tr>
                </table>
			</div>
			</td>
            <td valign="top">
            <div id="title" align="right">
                <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
                <font style="font-size:18px; color:#999999">Statistik Peminjam Terbanyak</font><br />
                <a href="pustaka.php" class="welc">Pustaka</a><span class="welc"> > Statistik Peminjam</span><br /><br /><br />
            </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top">
<?php 				if (isset($_REQUEST['ShowState']))
				{
					echo $this->ShowStatistik();
				}
				else
				{
					echo "&nbsp;";	
				} ?>
            </td>
        </tr>
        </table>
        <br />
<?php }

	function ShowStatistik()
	{
		$filter="";
		if ($this->perpustakaan!='-1')
			$filter=" AND d.perpustakaan=".$this->perpustakaan;
			
		$sql = "SELECT COUNT(x.replid) AS num, x.idanggota, x.jenis
				  FROM
					   (SELECT p.replid, p.info1 AS jenis, IF(p.nis IS NOT NULL, p.nis, IF(p.nip IS NOT NULL, p.nip, p.idmember)) AS idanggota
						  FROM jbsperpus.pinjam p, jbsperpus.daftarpustaka d
						 WHERE p.tglpinjam BETWEEN '".$this->ThnAwal."-".$this->BlnAwal."-01'
						   AND '".$this->ThnAkhir."-".$this->BlnAkhir."-31'
						   AND d.kodepustaka=p.kodepustaka $filter) AS x
				 GROUP BY x.idanggota, x.jenis
				 ORDER BY num DESC
				 LIMIT $this->Limit";
		
		$result = QueryDb($sql);
		$cnt=1;
		$key = $this->ThnAwal."-".$this->BlnAwal."-01,".$this->ThnAkhir."-".$this->BlnAkhir."-31"; ?>
		
		<table width="100%" border="0" cellspacing="2" cellpadding="2">
<?php 		if (@mysqli_num_rows($result)>0)
		{ ?>
        <tr>
		    <td colspan="2" align="center" valign="top"><a href="javascript:Cetak()"><img src="../img/ico/print1.png" width="16" height="16" border="0" />&nbsp;Cetak</a></td>
	    </tr>
		<tr>
			<td width="50%" align="center" valign="top">
            	<img src="<?="statimage.php?type=bar&key=$key&Limit=$this->Limit&krit=1&perpustakaan=$this->perpustakaan" ?>" />
			</td>
			<td align="center" valign="top">
            	<img src="<?="statimage.php?type=pie&key=$key&Limit=$this->Limit&krit=1&perpustakaan=$this->perpustakaan" ?>" />
			</td>
		</tr>
<?php 		} ?>
		<tr>
		<td colspan="2" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="350" valign="top">
					<table width="100%" border="1" cellspacing="0" cellpadding="5" class="tab">
					<tr height="25">
						<td width='10%' align="center" class="header">No</td>
						<td width='*' align="center" class="header">Anggota</td>
						<td width='15%' align="center" class="header">Jumlah</td>
						<td width='15%' align="center" class="header">&nbsp;</td>
					</tr>
<?php 					if (@mysqli_num_rows($result)>0)
					{
						while ($row = @mysqli_fetch_row($result))
						{ 
							$this->idanggota = $row[1];
							$this->jenisanggota = $row[2];
							$NamaAnggota = $this->GetMemberName();  ?>
							<tr height="20">
								<td align="center"><?=$cnt?></td>
								<td align="left">
									<font style='font-size: 9px'><?=$this->idanggota?></font><br>
									<font style='font-size: 11px; font-weight: bold;'><?=$NamaAnggota?></font>
								</td>	
								<td align="center"><?=$row[0]?></td>
								<td align="center">
									<a href="javascript:ViewList('<?=$this->jenisanggota?>','<?=$this->idanggota?>')"><img src="../../img/ico/lihat.png" width="16" height="16" border="0" /></a>
								</td>
							</tr>
<?php 							$cnt++;
						}
					}
					else
					{ ?>
						<tr>
							<td height="20" align="center" colspan="4" class="nodata">Tidak ada data</td>
						</tr>	
<?php 					} ?>
				    </table>
				</td>
				<td valign="top">
                   	<div id="ListInfo" style="padding-left:15px"></div>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
<?php }
	
	function GetMemberName()
	{
		if ($this->jenisanggota == "siswa")
		{
			$sql = "SELECT nama
					  FROM jbsakad.siswa
					 WHERE nis = '$this->idanggota'";
		}
		elseif ($this->jenisanggota == "pegawai")
		{
			$sql = "SELECT nama
					  FROM jbssdm.pegawai
					 WHERE nip = '$this->idanggota'";
		}
		else
		{
			$sql = "SELECT nama
					  FROM jbsperpus.anggota
					 WHERE noregistrasi = '$this->idanggota'";
		}
		$res = QueryDb($sql);
		$row = mysqli_fetch_row($res);
		$namaanggota = $row[0];
		
		return $namaanggota;
	}
}
?>