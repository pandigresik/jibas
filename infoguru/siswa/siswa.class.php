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

class CSiswa
{
	public $dep;
    public $tkt;
	public $kls;
	public $ta;
	
	function OnStart()
	{
		
	}
	
	function ShowStudentList()
	{
		?>
		<link href="../style/style.css" rel="stylesheet" type="text/css" />
		<div id="TabbedPanels1" class="TabbedPanels">
          <ul class="TabbedPanelsTabGroup">
            <li class="TabbedPanelsTab" tabindex="0">Pilih Siswa</li>
            <li class="TabbedPanelsTab" tabindex="0">Cari Siswa</li>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <div class="TabbedPanelsContent" id="pilihsiswa"></div>
            <div class="TabbedPanelsContent" id="carisiswa"></div>
          </div>
        </div>
        <script type="text/javascript">
        <!--
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        getTabContent();
		//-->
        </script>
        <?php
	}
	
	function GetDep()
	{
		echo "<select name='dep' id='dep' onChange='chg_dep()' class='cmbfrm' style='width:125px'>";
		$sql = "SELECT * FROM departemen WHERE aktif=1 ORDER BY urutan";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		$i=0;
		if ($num == 0)
		{
			echo "<option value=''>Tidak ada Data</option>";
		}
		else
		{
			while ($row = @mysqli_fetch_array($result))
			{
				if($i==0)
					$this->dep = $row['departemen'];
				++$i;
				if ($dep == "")
					$dep = $row['departemen'];
				echo "<option value='".$row['departemen']."' ".StringIsSelected($dep,$row['departemen']).">".$row['departemen']."</option>";
			}
		}
		echo "</select>";
	}
	
	function GetTkt()
	{
		$dep = $this->dep ;
		echo "<select name='tkt' id='tkt' onChange='chg_tkt()' class='cmbfrm' style='width:125px'>";
		$sql = "SELECT * FROM tingkat WHERE departemen = '$dep' AND aktif=1 ORDER BY tingkat";
		$result = QueryDb($sql);
		$i=0;
		$num = @mysqli_num_rows($result);
		if ($num==0)
		{
			echo "<option value=''>Tidak ada Data</option>";
		}
		else
		{
			while ($row = @mysqli_fetch_array($result))
			{
				if($i==0)
					$this->tkt = $row['replid'];
				++$i;
				if ($tkt == "")
					$tkt = $row['replid'];
				echo "<option value='".$row['replid']."' ".StringIsSelected($tkt,$row['replid']).">".$row['tingkat']."</option>";
			}
		}
		echo "</select>";
		$sql = "SELECT * FROM tahunajaran WHERE departemen='$dep' AND aktif=1 ";
		//echo $sql;
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		$ta  = $row['replid'];
		$this->ta = $row['replid'];
		echo "<input type='hidden' name='ta' id='ta' value='$ta' />";
	}
	
	function GetKls()
	{
		$tkt = $this->tkt ;
		$ta = $this->ta ;
				
		echo "<select name='kls' id='kls' onChange='chg_kls()' class='cmbfrm' style='width:125px'>";
		if ($tkt=="")
		{
			echo "<option value=''>Tidak ada Data</option>";	
		}
		else
		{
			$i=0;
			$sql = "SELECT * FROM kelas WHERE idtingkat='$tkt' AND idtahunajaran='$ta' AND aktif = 1 ORDER BY kelas";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num==0)
			{
				echo "<option value=''>Tidak ada Data</option>";	
			}
			else
			{
				while ($row = @mysqli_fetch_array($result))
				{
					if($i==0)
						$this->kls = $row['replid'];
					++$i;
					if ($kls == "")
						$kls = $row['replid'];
						
					echo "<option value='".$row['replid']."' ".StringIsSelected($kls,$row['replid']).">".$row['kelas']."</option>";
				}
			}
		}
		echo "</select>";	
	}
	
	function GetSis()
	{
		$kls = $this->kls ;
		echo "<table width='100%' border='1' class='tab'>
		  <tr>
			<td width='10' height='25' align='center' class='header'>No.</td>
			<td width='100' height='25' align='center' class='header'>NIS</td>
			<td width='*' height='25' align='center' class='header'>Nama</td>
			<td width='30' height='25' align='center' class='header'>&nbsp;</td>
		  </tr>";
		$sql = "SELECT * FROM siswa WHERE idkelas='$kls' AND aktif = 1 ORDER BY nama";
		//echo $sql;
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		if ($num==0)
		{
			echo "<tr><td height='20' colspan='4' align='center' class='nodata'>Tidak ada Data</td></tr>";	
		}
		else
		{
			$cnt=1;
			while ($row = @mysqli_fetch_array($result))
			{
				if ($tkt == "")
					$tkt = $row['replid'];
				echo "<tr>
					<td height='20' align='center'>$cnt</td>
					<td height='20' align='center'>".$row['nis']."</td>
					<td height='20'>".$row['nama']."</td>
					<td height='20' align='center'><input type='button' class='cmbfrm2' value='>' onclick=\"pilihsiswa('".$row['nis']."')\"></td>
				</tr>";
				$cnt++;
			}
		}
		echo "</table>";
	}
	
	function GetSisCari()
	{
		$nis = $this->nis ;
		$nama = $this->nama ;
		$nisn = $this->nisn ;
		$filter = "";
		if ($nis!="")
			$filter = $filter." nis LIKE '%$nis%' AND ";
		if ($nama!="")
			$filter = $filter." nama LIKE '%$nama%' AND ";
		if ($nisn!="")
			$filter = $filter." nisn LIKE '%$nisn%' AND ";
		echo "<table width='100%' border='1' class='tab'>
		  <tr>
			<td width='10' height='25' align='center' class='header'>No.</td>
			<td width='100' height='25' align='center' class='header'>NIS</td>
			<td width='100' height='25' align='center' class='header'>NISN</td>
			<td width='*' height='25' align='center' class='header'>Nama</td>
			<td width='30' height='25' align='center' class='header'>&nbsp;</td>
		  </tr>";
		$sql = "SELECT * FROM siswa WHERE $filter aktif = 1 ORDER BY nama";
		$result = QueryDb($sql);
		$num = @mysqli_num_rows($result);
		if ($num==0)
		{
			echo "<tr>
    			<td height='20' colspan='4' align='center' class='nodata'>Tidak ada Data</td>
  			</tr>";	
		}
		else
		{
			$cnt=1;
			while ($row = @mysqli_fetch_array($result))
			{
				echo "<tr>
					<td height='20' align='center'>$cnt</td>
					<td height='20' align='center'>".$row['nis']."</td>
					<td height='20' align='center'>".$row['nisn']."</td>
					<td height='20'>".$row['nama']."</td>
					<td height='20' align='center'><input type='button' class='cmbfrm2' value='>' onclick=\"pilihsiswa('".$row['nis']."')\"></td>
				</tr>";
				$cnt++;
			}
		}
		echo "</table>";
	}
}
?>