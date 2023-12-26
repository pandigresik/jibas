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
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<?php
class CPinjam
{
	function OnStart()
	{
		$this->state = $_REQUEST['state'];
		
		$this->jenisanggota = $_REQUEST['jenisanggota'];
		$jenis = 'pegawai';

		switch($this->jenisanggota)
		{
			case '0' : $jenis = 'pegawai'; break;
			case '1' : $jenis = 'siswa'; break;
			case '2' : $jenis = 'lain'; break;
		}
		$this->noanggota = $_REQUEST['noanggota'];
		$this->nama = $_REQUEST['nama'];
		$this->numcode = 0;
		$this->kodepustaka = $_REQUEST['kodepustaka'];
		
		$this->op = "";
		if (isset($_REQUEST['op']))
			$this->op = $_REQUEST['op'];
		
		if ($this->op == "newuser")
		{
			$sql = "DELETE FROM pinjam
					 WHERE idanggota = '".$_REQUEST['noanggota']."'
					   AND status = 0";
			QueryDb($sql);					
		}
		
		if ($this->op == "addnew")
		{
			$sql = "SELECT d.replid, d.kodepustaka, p.judul, d.status
					  FROM pustaka p, daftarpustaka d
					 WHERE p.replid = d.pustaka
					   AND (d.kodepustaka='$this->kodepustaka' OR d.info1 = '$this->kodepustaka')";
			$result=QueryDb($sql);
			$this->numcode=@mysqli_num_rows($result);
			$row=@mysqli_fetch_row($result);
			$this->replid = $row[0];
			$this->kodepustaka = $row[1];
			$this->judul = $row[2];
			$this->isavaiable = (0 == (int)$row[3]) ? -1 : 1;
		}
		
		if ($this->op == "addtochart")
		{
			$sql = "DELETE FROM pinjam
					 WHERE kodepustaka = '".$_REQUEST['kodepustaka']."'
					   AND idanggota = '".$_REQUEST['noanggota']."'
					   AND status = 0";
			QueryDb($sql);		   
					   
			$sql = "SELECT *
					  FROM pinjam
					 WHERE kodepustaka = '".$_REQUEST['kodepustaka']."'
					   AND idanggota = '".$_REQUEST['noanggota']."'
					   AND status = 1";
			$result = QueryDb($sql);
			$num = @mysqli_num_rows($result);
			if ($num == 0)
			{
				$idmember = "";
				if ($jenis == "siswa")
					$idmember = "nis = '".$_REQUEST['noanggota']."'";
				elseif ($jenis == "pegawai")
					$idmember = "nip = '".$_REQUEST['noanggota']."'";
				else
					$idmember = "idmember = '".$_REQUEST['noanggota']."'";
					
				$sql = "INSERT INTO pinjam
						   SET kodepustaka='".$_REQUEST['kodepustaka']."',
							   tglpinjam = '".MySqlDateFormat($_REQUEST['tglpinjam'])."',
							   tglkembali = '".MySqlDateFormat($_REQUEST['tglkembali'])."',
							   idanggota = '".$_REQUEST['noanggota']."',
							   keterangan = '".CQ($_REQUEST['keterangan'])."',
							   info1 = '$jenis',
							   $idmember";
				QueryDb($sql);
			}
			$this->replid = '';
			$this->kodepustaka = '';
			$this->judul = '';
		}
		
		if ($this->op == 'delqueue')
		{
			$sql = "DELETE FROM pinjam
					 WHERE replid = {$_REQUEST['replid']}";
			QueryDb($sql);
		}
		
		if ($this->op == 'DontSave')
		{
			$sql = "DELETE FROM pinjam
			         WHERE replid IN ({$_REQUEST['idstr']})";
			QueryDb($sql);
		}
		
		if ($this->op=='Save')
		{
			$sql = "UPDATE pinjam
					   SET status = 1
					 WHERE replid IN ({$_REQUEST['idstr']})";
			QueryDb($sql);
			
			$sql = "SELECT kodepustaka
					  FROM pinjam
					 WHERE replid IN ({$_REQUEST['idstr']})";
			$result = QueryDb($sql);
			while ($row = @mysqli_fetch_array($result))
			{
				$sql = "UPDATE daftarpustaka
						   SET status = 0
						 WHERE kodepustaka='{{$row['kodepustaka']}}'";
				QueryDb($sql);
			}
		}
		
		if (isset($_REQUEST['openuser']))
			$this->OpenUser();
	}
	
	function OpenUser()
	{	?>
		<script language='JavaScript'>
			cari();
		</script>
<?php  }

	function OnFinish()
	{	?>
		<script language='JavaScript'>
		//	Tables('table', 1, 0);
		</script>
<?php  }
	
	function GetMaxQueue($anggota)
	{
		$sql = "SELECT * FROM konfigurasi";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_array($result);
		$max_siswa_pjm = $row['siswa'];
		$max_pegawai_pjm = $row['pegawai'];
		$max_anggota_pjm = $row['other'];
		$denda = $row['denda'];
	
		if ($anggota == 1) 
			return $max_siswa_pjm;
		if ($anggota == 0) 
			return $max_pegawai_pjm;
		if ($anggota == 2) 
			return $max_anggota_pjm;
	}

	
    function Content()
	{
		global $WaktuPinjamSiswa, $WaktuPinjamPegawai, $WaktuPinjamLain;
		
		if ($this->state=='') 
			$this->state='1';
		
		$this->datenow = "";
		$this->oc1 = "";
		$this->oc2 = "";
		$this->dsp1 = "style=\"display:none\"";
		$this->dsp2 = "style=\"display:none\"";
		
		$addDay = 0;
		if ($this->state == 0)
			$addDay = $WaktuPinjamPegawai;
		elseif ($this->state == 1)
			$addDay = $WaktuPinjamSiswa;
		elseif ($this->state == 2)
			$addDay = $WaktuPinjamLain;
		
		if ($this->kodepustaka != '') 
		{
			$sql = "SELECT DATE_FORMAT(NOW(), '%d-%m-%Y'),
						   DATE_FORMAT(DATE_ADD(NOW(), INTERVAL $addDay DAY), '%d-%m-%Y')";
			//echo $this->state . " -- $sql";			   
			$result = QueryDb($sql);
			$row = @mysqli_fetch_row($result);
			$this->datenow = $row[0];
			$this->datereturn = $row[1];
			
			$this->oc1 = "onclick=\"TakeDate('tglpjm')\"";
			$this->oc2 = "onclick=\"TakeDate('tglkem')\"";
			$this->dsp1 = "";
			$this->dsp2 = "";
		}
			
		$sql = "SELECT DATE_FORMAT(now(),'%Y-%m-%d')";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		$now = $row[0];

		$sql = "SELECT * FROM pinjam WHERE idanggota='$this->noanggota' AND status=1 AND tglkembali<'".$now."' ORDER BY tglpinjam";
		$result = QueryDb($sql);
		$JumTelat = @mysqli_num_rows($result);

		$sql = "SELECT * FROM pinjam WHERE idanggota='$this->noanggota' AND status=1";
		$result = QueryDb($sql);
		$JumPinjam = @mysqli_num_rows($result);
		$max_queue = $this->GetMaxQueue($this->state); ?>
        <input type="hidden" name="max_queue" id="max_queue" value="<?=$max_queue?>" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
                <fieldset style="background-color: #d3f3e2; border-width: 1px; border-color: #ececec;" >
                    <legend class="welc" style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Pilih Anggota&nbsp;</legend>
                    <table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tr style="height: 24px">
                        <td width="9%" align="right"><span class="news_content1">Status&nbsp;Peminjam</span></td>
                        <td width="91%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="5" align="right">
                                    <input name="state" type="radio" value="0" onclick="fillstate('0')" <?=StringIsChecked($this->state,'0')?> />
                                </td>
                                <td><span class="news_content1">Pegawai</span></td>
                                <td width="5" align="right">
                                    <input name="state" type="radio" value="1" onclick="fillstate('1')" <?=StringIsChecked($this->state,'1')?>/>
                                </td>
                                <td><span class="news_content1">Siswa</span></td>
                                <td width="5" align="right">
                                    <input name="state" type="radio" value="2" onclick="fillstate('2')" <?=StringIsChecked($this->state,'2')?>/>
                                </td>
                                <td><span class="news_content1">Anggota Luar Sekolah</span></td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="height: 24px">
                        <td align="right">
                            <span class="news_content1">Peminjam</span>
                        </td>
                        <td>
                            &nbsp;
                            <input type="hidden" id="statuspeminjam" value="<?=$this->state?>" />
                            <input type="text" name="noanggota" id="noanggota" readonly="readonly" class="btnfrm" style="font-size: 16px;" onclick="cari()" value="<?=$this->noanggota?>" size="20" />
                            <input id="nama" name="nama" type="text" readonly="readonly"  style="font-size: 16px;" class="btnfrm" onclick="cari()" value="<?=$this->nama?>" size="40"/>
                            <a href="javascript:cari()"><img src="../img/ico/cari.png" width="16" height="16" border="0" /></a>
                        </td>
                    </tr>
                    <tr style="height: 24px">
                        <td align="right">
                            <span class="news_content1">Scan Barcode</span>
                        </td>
                        <td>
                            &nbsp;
                            <input type="text" id="txBarcode" name="txBarcode" style="font-size: 18px; width: 220px;"
                                   onfocus="this.style.background = '#27d1e5'"
                                   onblur="this.style.background = '#FFFFFF'"
                                   onkeyup="return scanBarcode(event)"><br>
                            <span id="spScanInfo" name="spScanInfo" style="color: red"></span>
                        </td>
                    </tr>
                    </table>
                </fieldset>
            </td>
            <td valign="top">
                <div id="title" align="right">
                    <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
                    <font style="font-size:18px; color:#999999">Peminjaman Pustaka Baru</font><br />
                    <a href="peminjaman.php" class="welc">Peminjaman</a><span class="welc"> > Peminjaman Pustaka Baru</span><br /><br /><br />
                </div>
            </td>
          </tr>
		  	<?php
			if (isset($_REQUEST['noanggota']) && $_REQUEST['noanggota']!=""){
			?>
          <tr>
            <td colspan="2">
              <br>
              <fieldset style="background-color: #e8fcff; border-width: 1px; border-color: #ececec;">
              <legend class="welc" style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Daftar Peminjaman&nbsp;</legend>
              <div style="height:200px; overflow-x:hidden; width:100%; overflow-y:scroll">
              <?php
              $sql = "SELECT * FROM pinjam WHERE idanggota='$this->noanggota' AND status=0 ORDER BY tglkembali";
			  $result = QueryDb($sql);
			  $num = @mysqli_num_rows($result);
              if ($num>0){
			  ?>
              <table width="98%" border="1"  cellspacing="0" cellpadding="2" class="tab">
                  <tr height="25" class="header">
                    <td width="19" height="25" align="center">No</td>
                    <td width="253" height="25" align="center">No Pustaka</td>
                    <td width="495" height="25" align="center">Judul</td>
                    <td width="99" align="center">Tgl Kembali</td>
                    <td width="99" align="center">&nbsp;</td>
                  </tr>
                  <tbody style="overflow:hidden;" >
                  <?php
				  $cnt=1;
				  while ($row=@mysqli_fetch_array($result)){
				  $judul = @mysqli_fetch_row(QueryDb("SELECT p.judul FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid AND d.kodepustaka='{$row['kodepustaka']}'"));
				  ?>
                  <tr height="25">
                    <td width="20" height="20" align="center">
						<input type="hidden" name="idpinjam<?=$cnt?>" id="idpinjam<?=$cnt?>" value="<?=$row['replid']?>" />
						<?=$cnt?>                    </td>
                    <td width="254" align="center"><?=$row['kodepustaka']?></td>
                    <td width="496" ><?=$judul[0]?></td>
                    <td width="100" align="center"><?=LongDateFormat($row['tglkembali'])?></td>
                    <td width="100" align="center"><a href="javascript:HapusPeminjaman('<?=$row['replid']?>')"><img src="../img/ico/hapus.png" width="16" height="16" border="0" /></a></td>
                  </tr>
                  <?php $cnt++; ?>
                  <?php } ?>
                  </tbody>
              </table>
              <?php } ?>
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr height="30">
                        <td class="news_content1">Jumlah yang akan dipinjam <?=$num?></td>
                        <td align="right">
                        	<?php if($num!=0){ ?>
                            <input name="simpan" type="button" class="cmbfrm3" value="  Simpan Peminjaman  "
                                   style="font-size: 14px; background-color: #0a931e; color: #fff; height: 28px;"
                                   onclick="ValidatePeminjaman()" />
          					&nbsp;<input name="batal" type="button" class="cmbfrm2" value="     Batal     "
                                    style="font-size: 14px; background-color: #6e2e16; color: #fff; height: 28px;"
                                    onclick="CancelPeminjaman()" />
                        	<?php } ?>
                        </td>
                      </tr>
              </table>
              <input type="hidden" name="num" id="num" value="<?=$num?>" /> 
                </fieldset>            </td>
          </tr>
          <tr>
          	<td colspan="2">
                <br>
                <fieldset style="background-color: #f8ed94; border-width: 1px; border-color: #ececec;">
                <legend class="welc" style="background-color: #fff; color: #888; font-size: 14px; font-family: 'Segoe UI'">&nbsp;Cari Pustaka&nbsp;</legend>

                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                    <tr>
                        <td width="9%" align="right" class="news_content1">Nomor Pustaka</td>
                        <td colspan="3">
							<input name="kodepustaka" id="kodepustaka" type="text"
                                   size="30" style="font-size: 18px;"
								   maxlength="45" value="<?=$this->kodepustaka?>"
                                   onkeyup="return OnEnterKodePustaka(event)"
                                   onfocus="this.style.background = '#27d1e5'"
                                   onblur="this.style.background = '#FFFFFF'" />&nbsp;
                                <a href="javascript:CariPustaka()">
                                    <img src="../img/ico/cari.png" border="0" />
                                    &nbsp;<font style='font-size: 11px; color: blue'>cari pustaka</font>
                                </a>&nbsp;
                            <font style='font-size: 11px; color: blue'>|</font>&nbsp;
							<img src="../img/barcode.png" height='16' border="0" />&nbsp;
							<font style='font-size: 11px; color: blue; font-weight: bold;'>scan barcode</font>&nbsp;
                            <font style='font-size: 11px; color: blue'>|</font>&nbsp;
							<a href="javascript:ClearData()">
								<img src="../img/broom.png" height='16' border="0" />&nbsp;
								<font style='font-size: 11px; color: blue; font-weight: bold;'>bersihkan data</font>
							</a>	
						</td>
                      </tr>
                      <tr height="30">
                            <td align="right" class="news_content1">Judul</td>
                            <td colspan="2">
							    <input type="textbox" id="judul" name="judul" class="btnfrm" style="height:30px; width:520px;" value="<?=$this->judul?>">
						    </td>
                            <td width="36%" rowspan="4" align='left' valign="top">
                                  <input type="hidden" name="borrowed" id="borrowed" value="<?=$JumPinjam?>" />
                                  <fieldset style="width:75%; background-color: #fff;">
                                      <legend class="welc" style="background-color: #771b8f; color: #fff;">Informasi Peminjaman Anggota</legend>
                                      <span class="news_content1">Jumlah Peminjaman <?=$JumPinjam?><br />
                                                Jumlah Peminjaman Yang Terlambat <?=$JumTelat?></span>
                                  </fieldset>
                            </td>
                      </tr>
                      <tr>
                            <td align="right" class="news_content1">Tanggal&nbsp;Pinjam</td>
                            <td colspan="2">
                            <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td><input name="tglpjm" type="text" class="btnfrm" id="tglpjm" value="<?=$this->datenow?>" size="20" maxlength="10" readonly="true" <?=$this->oc1?> /></td>
                            <td width="18" align="right"><a href="javascript:TakeDate('tglpjm')" <?=$this->dsp1?>><img src="../img/ico/calendar.png" width="16" height="16" border="0" /></a></td>
                            <td>&nbsp;&nbsp;&nbsp;<span class="news_content1">Tanggal&nbsp;Kembali</span>&nbsp;</td>
                            <td><input name="tglkem" type="text" class="btnfrm" id="tglkem" value="<?=$this->datereturn?>" size="20" maxlength="10" readonly="true" <?=$this->oc2?> /></td>
                            <td width="18" align="right"><a href="javascript:TakeDate('tglkem')" <?=$this->dsp2?> ><img src="../img/ico/calendar.png" border="0" /></a></td>
                          </tr>
                        </table>
                        </td>
                      </tr>
                      <tr>
                        <td align="right" class="news_content1">Keterangan</td>
                        <td><textarea name="keterangan" id="keterangan" cols="40" rows="1"></textarea></td>
                      </tr>
                      
                      <tr>
                          <td>&nbsp;</td>
                        <td align="left">
						<?php if ($this->numcode > 0 && $this->isavaiable == 1) { ?>
						<input name="button" type="button" class="cmbfrm2" id="button" value=" Tambah ke Daftar Peminjaman "
                               style="font-size: 14px; background-color: #0a931e; color: #fff; height: 28px;"
                               onclick="AddToChart()" />
						<?php } ?>
						<?php if ($this->isavaiable == -1) { ?>
						<font style='color: red; font-weight: bold;'>Status buku ini sedang dipinjam</font>
						<?php } ?>
						</td>
                      </tr>
                    </table>
              </fieldset>

            </td>
          </tr>
          <tr>
          	<td colspan="2" align="center"></td>
          </tr>
		  <?php
           } else {	
          ?>
          <input type="hidden" name="num" id="num" value="0" />
          <?php } ?>
        </table>
		
		<?php if ($this->op == "newuser" || $this->op == "addtochart") { ?>
		<script>
			setTimeout(function() { focusKodePustaka() }, 250);
		</script>
		<?php } ?>
        <?php
	}
}
?>