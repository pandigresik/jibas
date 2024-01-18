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
class Presensi{
	function OnStart(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
	}
	function Main(){
		global $db_name_akad;
		global $db_name_sdm;
		global $db_name_fina;
		global $db_name_user;
		global $SMonth;
	$sql = "SELECT * FROM format WHERE tipe=0";
	$res = QueryDb($sql);
	$hasformat = @mysqli_num_rows($res);
	?>
	<link href="../style/style.css" rel="stylesheet" type="text/css" />
    <table width="600" border="0"  align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td>
        <div id="TabbedPanelsA" class="TabbedPanels">
          <ul class="TabbedPanelsTabGroup">
            <li class="TabbedPanelsTab2" tabindex="0"><strong>Kirim Laporan Presensi</strong></li>
          </ul>
          <div class="TabbedPanelsContentGroup">
            <div class="TabbedPanelsContent" style="padding-top:5px; overflow:inherit">
                <table width="100%" border="0"  align="center" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td colspan="3" height="30" align="center">
                        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="tab">
                            <tr>
                    <td width="1%" align="right" valign="top" class="td" ><span style="color:#666; font-size:12px; font-weight:bold">Kirim Laporan Berdasarkan</span></td>
                    <td style="padding:5px">
                        <table  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td valign="top" class="tdTop">
                                <table  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><input type="radio" id="type0" checked="checked" name="type" onclick="SetActive('0','NISList','dep','kls')" /></td>
                                    <td>Siswa</td>
                                  </tr>
                                </table>
                            </td>
                            <td class="td">
                            <textarea class="AreaTxt" id="NISList" cols="50"></textarea><br /><span class="Ket">* dipisahkan koma</span>
                            <div id="ErrNISList" class="ErrMsg"></div>
                            </td>
                          </tr>
                          <tr>
                            <td class="tdTop">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><input type="radio" id="type1" name="type" onclick="SetActive('1','NISList','dep','kls')" /></td>
                                    <td>Kelas</td>
                                  </tr>
                                </table>
                            </td>
                            <td class="td">
                            <table cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="padding-right:5px">
                                <select id="dep" class="Cmb" disabled="disabled" onchange="ChgDep(this.value)">
                                <?php
                                $sql = "SELECT departemen FROM $db_name_akad.departemen WHERE aktif=1 ORDER BY urutan";
                                $res = QueryDb($sql);
                                while ($row = @mysqli_fetch_row($res)){
                                    if ($dep=='')
                                        $dep = $row[0];
                                    ?>
                                    <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$dep)?>><?=$row[0]?></option>
                                    <?php
                                }
                                if ($kls=='')
                                    $kls = $row[0];
                                ?>
                                </select>
                                </td>
                                <td>
                                <div id="klsInfo">
                                <select id="kls" class="Cmb" disabled="disabled">
                                <option value="-1" <?=StringIsSelected('-1',$kls)?>>- Semua -</option>
                                <?php
                                $sql = "SELECT k.replid, k.kelas FROM $db_name_akad.kelas k,$db_name_akad.tahunajaran ta,$db_name_akad.tingkat ti WHERE k.aktif=1 AND ta.aktif=1 AND ti.aktif=1 AND k.idtahunajaran=ta.replid AND k.idtingkat=ti.replid AND ta.departemen='$dep' AND ti.departemen='$dep' ORDER BY ti.urutan, k.kelas";
                                $res = QueryDb($sql);
                                while ($row = @mysqli_fetch_row($res)){
                                    ?>
                                    <option value="<?=$row[0]?>" <?=StringIsSelected($row[0],$kls)?>><?=$row[1]?></option>
                                    <?php
                                }
                                ?>
                                </select>
                                </div>
                                </td>      
                              </tr>
                            </table>
                            </td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" align="right" valign="top" class="td"><span style="color:#666; font-size:12px; font-weight:bold">Presensi</span></td>
                    <td class="td" style="padding:5px">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><input type="radio" id="harian" name="TypePres" checked="checked" /></td>
                            <td style="padding-right:10px">Harian</td>
                            <td><input type="radio" id="pelajaran" name="TypePres" /></td>
                            <td>Pelajaran</td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" align="right" valign="top" class="td"><span style="color:#666; font-size:12px; font-weight:bold">Tanggal Presensi</span></td>
                    <td class="td" style="padding:5px">
                        <?php
                        $sql = "SELECT DATE_FORMAT(now(),'%d-%m-%Y'), DATE_FORMAT(SUBDATE(now(), INTERVAL 1 MONTH),'%d-%m-%Y')";
                        $res = QueryDb($sql);
                        $row = @mysqli_fetch_row($res);
                        
                        $x 	 = explode('-',(string) $row[0]);
                        $Date1 = $x[0];
                        $Month1 = $x[1];
                        $Year1 = $x[2];
                        
                        $y 	 = explode('-',(string) $row[1]);
                        $Date2 = $y[0];
                        $Month2 = $y[1];
                        $Year2 = $y[2];
                        //print_r($y);
                        ?>
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td style="padding-right:2px">
                            <select id="Date2" class="Cmb">
                                <?php
                                for ($i=1; $i<=31; $i++){
                                    
									?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Date2)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            <select id="Month2" class="Cmb">
                                <?php
                                for ($i=1; $i<=12; $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Month2)?>><?=$SMonth[$i-1]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            <select id="Year2" class="Cmb">
                                <?php
                                for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Year2)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            s/d
                            </td>
                            <td style="padding-right:2px">
                            <select id="Date1" class="Cmb">
                                <?php
                                for ($i=1; $i<=31; $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Date1)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            <select id="Month1" class="Cmb">
                                <?php
                                for ($i=1; $i<=12; $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Month1)?>><?=$SMonth[$i-1]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            <select id="Year1" class="Cmb">
                                <?php
                                for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Year1)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                          </tr>
                        </table>
                        <div id="ErrPresDate" class="ErrMsg"></div>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" align="right" valign="top" class="td"><span style="color:#666; font-size:12px; font-weight:bold">Pengirim</span></td>
                    <td class="td" style="padding:5px">
                        <input type="text" id="Sender" class="InputTxt" />
                        <div id="ErrSenderName" class="ErrMsg"></div>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" align="right" valign="top" class="td"><span style="color:#666; font-size:12px; font-weight:bold">Dikirim kepada</span></td>
                    <td class="td" style="padding:5px">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><input type="checkbox" id="kesiswa" /></td>
                            <td style="padding-right:10px">Siswa</td>
                            <td><input type="checkbox" id="keortu" /></td>
                            <td>Orang Tua</td>
                          </tr>
                        </table>
                        <div id="ErrDestination" class="ErrMsg"></div>
                    </td>
                  </tr>
                  <tr>
                    <td width="1%" align="right" valign="top" class="td"><span style="color:#666; font-size:12px; font-weight:bold">Tanggal Pengiriman</span></td>
                    <td class="td" style="padding:5px">
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td style="padding-right:2px">
                            <select id="SendDate" class="Cmb">
                                <?php
                                for ($i=1; $i<=31; $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Date1)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:2px">
                            <select id="SendMonth" class="Cmb">
                                <?php
                                for ($i=1; $i<=12; $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Month1)?>><?=$SMonth[$i-1]?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td style="padding-right:7px">
                            <select id="SendYear" class="Cmb">
                                <?php
                                for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                                    ?>
                                    <option value="<?=$i?>" <?=StringIsSelected($i,$Year1)?>><?=$i?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </td>
                            <td>
							<?php
							$sql = "SELECT DATE_FORMAT(now(),'%H'),DATE_FORMAT(now(),'%i')";
							$res = QueryDb($sql);
							$row = @mysqli_fetch_row($res);
							$hour = $row[0];
							$min = $row[1];
							?>
                            <input name="SendHour" type="text" class="InputTxt" id="SendHour" value="<?=$hour?>" size="2" maxlength="2" style="text-align:center" onblur="this.value = this.value.length==0 ? '<?=$hour?>' : parseInt(this.value)>23 ? '23' : this.value;" />
                            </td>
                            <td>:</td>
                            <td>
                            <input name="SendMinute" type="text" class="InputTxt" id="SendMinute" value="<?=$min?>" size="2" maxlength="2" style="text-align:center" onblur="this.value = this.value.length==0 ? '<?=$min?>' : parseInt(this.value)>59 ? '59' : this.value;"/>
                            </td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" height="30" align="center">
                    <div id="ErrAll" class="ErrMsg"></div>
                    <?php if ($hasformat==0) { ?>
                    <div class="ErrMsg" style="height:30px"><img src="../images/ico/error.gif" width="14" height="14" />&nbsp;Belum ada format SMS untuk laporan Presensi, <br />Silakan tambah format di bagian Format Pesan SMS Presensi</div>
                    <?php } ?>
                    <div class="BtnSilver" align="center" onclick="Send()">Kirim</div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center">
                    <div id="SuccessMsg" style="padding:5px">
                    </div>
                    </td>
                  </tr>
                </table>
             </div>
          </div>
        </div>
        </td>
      </tr>
    </table>
<?php
    }
}
?>