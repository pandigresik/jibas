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
class FormatPenilaian{
	function Main(){
		$sql = "SELECT * FROM format WHERE tipe=1";
		$res = QueryDb($sql);
		$row = @mysqli_fetch_array($res);
		?>
		<link href="../style/style.css" rel="stylesheet" type="text/css" />
		
        <table width="574" border="0" align="center" cellpadding="1" cellspacing="1">
          <tr>
            <td width="319" valign="top">
                <div id="TabbedPanelsA" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0"><strong>Format Pesan</strong></li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent" style="padding-top:5px; overflow:inherit">
                        <table border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td class="td" style="padding:10px">
                            <textarea id="Format" cols="40" rows="10" class="AreaTxt" disabled="disabled" ><?=$row['format']?></textarea>
                            </td>
                          </tr>
                          <tr>
                            <td class="td" align="center">
                            <?php
							if ($_SESSION['tingkat']!='2'){
							?>
							<div class="BtnSilver" align="center" id="BtnEdit" onclick="Ubah()">Ubah</div>
                            <div class="BtnSilver" align="center" id="BtnSave" style="display:none" onclick="Simpan()">Simpan</div>
                            <?php 
							}
							?>
							</td>
                          </tr>
                        </table>
                	</div>
                  </div>
                </div>    	
            </td>
            <td width="248" valign="top">
                <div id="TabbedPanelsA" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0"><strong>Ket.</strong></li>
                  </ul>
                  <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent" style="padding-top:5px; overflow:inherit">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="td">['SISWA']</td>
                            <td class="td">Nama Siswa</td>
                          </tr>
                          <tr>
                            <td class="td">[TANGGAL1]</td>
                            <td class="td">Tanggal Awal</td>
                          </tr>
                          <tr>
                            <td class="td">[BULAN1]</td>
                            <td class="td">Bulan Awal</td>
                          </tr>
                          <tr>
                            <td class="td">[TANGGAL2]</td>
                            <td class="td">Tanggal Akhir</td>
                          </tr>
                          <tr>
                            <td class="td">[BULAN2]</td>
                            <td class="td">Bulan Akhir</td>
                          </tr>
                          <tr>
                            <td class="td">['PENGIRIM']</td>
                            <td class="td">Pengirim</td>
                          </tr>
                      </table>
                   </div>
                 </div>
               </div>        
            </td>
          </tr>
          <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
            <td colspan="2">
        		<b>KETERANGAN FORMAT PESAN LAPORAN PENILAIAN</b><br /><br/>
                <span class="Ket">
                    jika mengirimkan informasi dengan format di bawah ini: <br />
                    <b>Informasi penilaian ['SISWA'] antara tanggal [TANGGAL1]/[BULAN1] s/d [TANGGAL2]/[BULAN2]. Pengirim ['PENGIRIM']</b><br /><br />
                    maka informasi yang diterima oleh siswa: <br />
                    <b>Informasi penilaian Jafar Ashiddiq antara tanggal 1/2 s/d 28/2, UTS FIS 80, PRAK FIS 77. Pengirim Bag.Akademik</b>
                </span>
          </td>
          </tr>
        </table>
		<?php
		//Kami informasikan presensi ['SISWA'] tanggal [TANGGAL1]/[BULAN1] s/d [TANGGAL2]/[BULAN2] hadir ['HADIR'] absen ['ABSEN']. ['PENGIRIM']
	}
}
?>