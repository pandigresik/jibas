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
class CKembali{
	function OnStart(){
		$this->kodepustaka = trim((string) $_REQUEST['kodepustaka']);
		
		$sql = "SELECT DATE_FORMAT(now(), '%Y-%m-%d')";
		$result = QueryDb($sql);
		$row = @mysqli_fetch_row($result);
		$this->datenow = $row[0];
			
		$this->op=$_REQUEST['op'];
		if ($this->op=="del"){
			$sql = "DELETE FROM format WHERE replid= '".$_REQUEST['id']."'";
			QueryDb($sql);
		}
		$this->num=0;
		if ($this->op=="ViewPeminjaman"){
			$sql = "SELECT * FROM pinjam WHERE kodepustaka='".$this->kodepustaka."' AND status=1";
			//echo $sql;
			$result = QueryDb($sql);
			if ($this->kodepustaka=="Tidak ada Kode Pustaka yang sedang dipinjam")
				$this->nothing=1;
			else
				$this->nothing=0;
			$this->num = @mysqli_num_rows($result);
			$row = @mysqli_fetch_array($result);
			$this->idpinjam = $row['replid'];
			$this->tglpinjam = $row['tglpinjam'];
			$this->tglkembali = $row['tglkembali'];
			$this->keterangan = $row['keterangan'];
			$this->idanggota = $row['idanggota'];
			$this->namaanggota = $this->GetMemberName();
			
			$sql = "SELECT DATEDIFF('".$this->datenow."','".$this->tglkembali."')";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_row($result);
			if ($row[0]>0){
				$this->denda = $row[0] * (int)get_denda();
				$this->telat = $row[0];
			} else {
				$this->denda = 0;
				$this->telat = 0;
			}
			//echo($sql);
			$sql = "SELECT judul FROM pustaka p, daftarpustaka d WHERE d.pustaka=p.replid AND d.kodepustaka='".$this->kodepustaka."'";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$this->judul = $row['judul'];
		}
		
		if ($this->op=="KembalikanPustaka"){
			$this->denda = $_REQUEST['denda'];
			$this->idpinjam = $_REQUEST['idpinjam'];
			$this->telat = $_REQUEST['telat'];
			
			$sql = "UPDATE pinjam SET status=2, tglditerima='".$this->datenow."' WHERE replid=".$this->idpinjam;
			QueryDb($sql);
			
			$sql = "UPDATE daftarpustaka SET status=1 WHERE kodepustaka='".$this->kodepustaka."'";
			QueryDb($sql);
			
			if($this->denda!=0){
				$sql = "INSERT INTO denda SET idpinjam='".$this->idpinjam."', denda='".$this->denda."', telat='".$this->telat."'";
				QueryDb($sql);
			}
			$this->ReloadPage();
		}
	}
	function ReloadPage()
	{
		?>
		<script language='JavaScript'>
			document.location.href="kembali.php";
		</script>
		<?php
	}
	function OnLoad()
	{
		$onload="onload=\"document.getElementById('kodepustaka').focus()\"";
		if (isset($_REQUEST['op']) && $this->op=="ViewPeminjaman") {
			if ($this->num!=0) {
				$onload="onload=\"document.getElementById('BtnKembali').focus()\"";	
			}
		}
		return ($onload);
	}
	function OnFinish(){
		?>
		<script language='JavaScript'>
			//Tables('table', 1, 0);
		</script>
		<?php
    }
    function Content(){
		?>
		<link href="../sty/style.css" rel="stylesheet" type="text/css" />
		<input type="hidden" id="source" value="<?=$this->source?>" />
        <table width="100%" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td>
            	<fieldset><legend class="welc">Kode Pustaka</legend>
   					<table width="100%" border="0" cellspacing="5" cellpadding="0">
                      <tr>
                        <td width="10%" align="right"><strong>Kode Pustaka</strong></td>
                        <td width="90%"><input name="kodepustaka" type="text" id="kodepustaka" value="<?=$this->kodepustaka?>" onkeypress="return KeyPress('kodepustaka',event)" size="55" />
                        &nbsp;<input type="button" class="cmbfrm2" value="Proses" onclick="ProsesKode()" /></td>
                      </tr>
                    </table>
                </fieldset>
            </td>
          </tr>
          <?php
		  if ($this->op=="ViewPeminjaman"){
		  if ($this->nothing==0){
			  //echo $this->nothing;
		  if ($this->num>0){
		  ?>
          <tr>
            <td>
            	<fieldset><legend class="welc">Informasi Peminjaman</legend>
                	<table width="100%" border="0" cellspacing="4" cellpadding="0">
                      <tr>
                        <td width="8%" align="right">Anggota</td>
                        <td width="92%"><input name="anggota" id="anggota" type="text" value="<?=$this->idanggota?> - <?=$this->namaanggota?>" size="50" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td align="right">Judul&nbsp;Pustaka</td>
                        <td><div id="title" class="btnfrm" style="height:30px">&nbsp;<?=$this->judul?></div></td>
                      </tr>
                      <tr>
                        <td align="right">Tanggal&nbsp;Pinjam</td>
                        <td><input name="tglpinjam" id="tglpinjam" type="text" value="<?=LongDateFormat($this->tglpinjam)?>" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td align="right">Tanggal&nbsp;Kembali</td>
                        <td><input name="tglkembali" id="tglkembali" type="text" value="<?=LongDateFormat($this->tglkembali)?>" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td align="right">Denda</td>
                        <td>
                        	<input name="dendanya" id="dendanya" type="text" value="<?=FormatRupiah($this->denda)?>" onfocus="unformatRupiah('dendanya')" onblur="formatRupiah('dendanya')" onkeyup="Copy('dendanya','denda')" />
                        	<input name="denda" id="denda" type="hidden" value="<?=$this->denda?>" />
                            <input name="idpinjam" id="idpinjam" type="hidden" value="<?=$this->idpinjam?>" />
                            <input name="telat" id="telat" type="hidden" value="<?=$this->telat?>" />                        </td>
                      </tr>
                    </table>
              </fieldset>
            </td>
          </tr>
          <tr>
          	<td>
            	<fieldset><legend class="welc">Pengembalian</legend>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center">
                        	<input id="BtnKembali" type="button" class="cmbfrm2" value="Kembalikan Sekarang" onclick="Kembalikan()"  />&nbsp;
                        	<input name="" type="button" class="cmbfrm2" value="Batal" onclick="BatalkanPengembalian()" />
                        </td>
                      </tr>
                    </table>
                </fieldset>
            </td>
          </tr>
          <?php
		  } else {
		  ?>
		  <tr>
          	<td height="30" align="center" class="err">
            	Pustaka dengan kode pustaka <?=$this->kodepustaka?>           	    sedang tidak dipinjam            </td>
          </tr>
		  <?php
          }
		  }
		  }
		  ?>
        </table>
		<?php
	}
	function GetMemberName(){
		$idanggota = $this->idanggota;
		$sql1 = "SELECT nama FROM ".get_db_name('akad').".siswa WHERE nis='$idanggota'";
		$result1 = QueryDb($sql1);
		if (@mysqli_num_rows($result1)>0){
			$row1 = @mysqli_fetch_array($result1);
			return $row1['nama'];
		} else {
			$sql2 = "SELECT nama FROM ".get_db_name('sdm').".pegawai WHERE nip='$idanggota'";
			$result2 = QueryDb($sql2);
			if (@mysqli_num_rows($result2)>0){
				$row2 = @mysqli_fetch_array($result2);
				return $row2['nama'];
			} else {
				$sql3 = "SELECT nama FROM anggota WHERE noregistrasi='$idanggota'";
				$result3 = QueryDb($sql3);
				if (@mysqli_num_rows($result3)>0){
					$row3 = @mysqli_fetch_array($result3);
					return $row3['nama'];
				} else {
					return "Tanpa Nama";
				}
			}
		}
	}
}
?>