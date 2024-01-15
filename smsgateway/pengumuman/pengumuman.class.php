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
class Pengumuman{
	function MainMessage(){
		?>
        <table border="0">
          <tr>
            <td>
            <fieldset style="border: 1px solid rgb(51, 102, 153); background-color: rgb(255, 255, 255);">
            <legend style="background-color: rgb(51, 102, 153); color: rgb(255, 255, 255); font-size: 10px; font-weight: bold; padding: 5px;">&nbsp;Pesan&nbsp;</legend>
            	<table width="245" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="94%" class="td"><strong>Pengirim&nbsp;:&nbsp;</strong><br /><input type="text" name="Sender" id="Sender" class="InputTxt" style="width:97%" /><div id="ErrSender" class="ErrMsg"></div></td>
                  </tr>
                  <tr>
                    <td class="td">
                    <strong>Pesan&nbsp;:&nbsp;</strong><br />
                    <textarea onkeyup="MaxChar(this.value,160,'CharLeft')" name="Message" rows="9" class="AreaTxt" id="Message"  style="width:99%"></textarea>
                    <div align="right">
                    <input type="text" name="CharLeft" id="CharLeft" value="160" readonly="readonly" style="border:none; width:30px; text-align:right" size="3" /> character left
                    </div>
                    <div id="ErrMessage" class="ErrMsg"></div>			</td>
                  </tr>
                  <tr>
                    <td class="td">
                    Ket&nbsp;:&nbsp;<br />
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><strong>[NOINDUK]</strong></td>
                        <td>:&nbsp;No&nbsp;Induk&nbsp;Pegawai/Siswa</td>
                      </tr>
					  <tr>
                        <td><strong>[PIN]</strong></td>
                        <td>:&nbsp;PIN&nbsp;Siswa&nbsp;atau&nbsp;Ortu&nbsp;atau&nbsp;Pegawai</td>
                      </tr>
                      <tr>
                        <td><strong>[NAMA]</strong></td>
                        <td>:&nbsp;Nama&nbsp;Pegawai/Siswa</td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
            </fieldset>
            
            <!--
            <div id="TabbedPanelsA" class="TabbedPanels">
              <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0"><strong>Pesan</strong></li>
              </ul>
              <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                
                </div>
              </div>
            </div>
            -->
            </td>
          </tr>
          <tr>
            <td>
            <div style="padding:5px">
                <span class="Link" style="padding-bottom:10px"><strong>Logs :</strong></span>
                <div style="max-height:200px; overflow:auto; overflow-x:hidden" id="DivLogs">
                    <table id="TableLogs" width="100%" border="0" cellspacing="2" cellpadding="2">
                    </table>
                </div>
            </div>
            </td>
          </tr>
        </table>
        <?php
	}
	
	function SendingTime(){
	global $LMonth;
	OpenDb();
	$sql = "SELECT DATE_FORMAT(now(),'%H'),DATE_FORMAT(now(),'%i')";
	$res = QueryDb($sql);
	$row = @mysqli_fetch_row($res);
	$hour = $row[0];
	$min = $row[1];
	?>
    <fieldset style="border: 1px solid rgb(51, 102, 153); background-color: rgb(255, 255, 255);">
            <legend style="background-color: rgb(51, 102, 153); color: rgb(255, 255, 255); font-size: 10px; font-weight: bold; padding: 5px;">&nbsp;Jadwal&nbsp;Pengiriman&nbsp;</legend>
    <!--
    <div id="TabbedPanelsB" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0"><strong>Jadwal Pengiriman</strong></li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
        	-->
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="padding-right:2px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>Tanggal</td>
                        <td>
                            <table border="0" cellspacing="1" cellpadding="1">
                              <tr>
                                <td style="padding-right:2px">
                                <select name="SendDate" id="SendDate" class="Cmb">
                                    <?php
                                    for ($i=1; $i<=31; $i++){
                                        ?>
                                        <option value="<?=$i?>" <?=StringIsSelected($i,date('d'))?>><?=$i?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </td>
                                <td style="padding-right:2px">
                                <select name="SendMonth" id="SendMonth" class="Cmb">
                                    <?php
                                    for ($i=1; $i<=12; $i++){
                                        ?>
                                        <option value="<?=$i?>" <?=StringIsSelected($i,date('m'))?>><?=$LMonth[$i-1]?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </td>
                                <td style="padding-right:2px">
                                <select name="SendYear" id="SendYear" class="Cmb">
                                    <?php
                                    for ($i=G_START_YEAR; $i<=date('Y'); $i++){
                                        ?>
                                        <option value="<?=$i?>" <?=StringIsSelected($i,date('Y'))?>><?=$i?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </td>                      
                                </tr>
                            </table>
                        </td>
                        <td>Waktu</td>
                        <td align="left">
                        <table border="0" cellspacing="1" cellpadding="1">
                          <tr>
                                <td><input name="SendHour" type="text" class="InputTxt" id="SendHour" value="<?=$hour?>" size="2" maxlength="2" style="text-align:center" onblur="this.value = this.value.length==0 ? '<?=$hour?>' : parseInt(this.value)>23 ? '23' : this.value;" /></td>
                                <td>:</td>
                                <td><input name="SendMinute" type="text" class="InputTxt" id="SendMinute" value="<?=$min?>" size="2" maxlength="2" style="text-align:center" onblur="this.value = this.value.length==0 ? '<?=$min?>' : parseInt(this.value)>59 ? '59' : this.value;"/></td>
                          </tr>
                        </table>
        
                        </td>
                      </tr>
                    </table>
                </td>
                <td  align="center" height="30">
                <table align="center" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div align="center" class="BtnSilver90" onclick="Send();">Kirim</div></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
        <!--
        </div>
      </div>
    </div>
    -->
    </fieldset>
	<?php
	}
	
	function SelectReceiver(){
	?>
    <fieldset style="border: 1px solid rgb(51, 102, 153); background-color: rgb(255, 255, 255);">
            <legend style="background-color: rgb(51, 102, 153); color: rgb(255, 255, 255); font-size: 10px; font-weight: bold; padding: 5px;">&nbsp;Pilih&nbsp;Penerima&nbsp;</legend>
            
    <!--<div id="TabbedPanelsC" class="TabbedPanels" style="z-index:0">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0"><strong>Pilih Penerima</strong></li>
      </ul>
      <div class="TabbedPanelsContentGroup" >
        <div class="TabbedPanelsContent">
            <div style="width:100%">
            -->
                <div id="TabbedPanels1" class="TabbedPanels">
                  <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Pegawai</li>
                    <li class="TabbedPanelsTab" tabindex="0">Siswa</li>
					<li class="TabbedPanelsTab" tabindex="0">Ortu Siswa</li>
					<li class="TabbedPanelsTab" tabindex="0">Calon Siswa</li>
					<li class="TabbedPanelsTab" tabindex="0">Ortu Calon Siswa</li>
                    <li class="TabbedPanelsTab" tabindex="0">Lainnya</li>
                  </ul>
                  <div class="TabbedPanelsContentGroup" style="height:203px; overflow:auto; width:100%; overflow-x:hidden">
                    <div class="TabbedPanelsContent" id="TabPegawai"></div>
                    <div class="TabbedPanelsContent" id="TabSiswa"></div>
                    <div class="TabbedPanelsContent" id="TabOrtu"></div>
					<div class="TabbedPanelsContent" id="TabCalonSiswa"></div>
					<div class="TabbedPanelsContent" id="TabOrtuCS"></div>
                    <div class="TabbedPanelsContent" id="TabLainnya" style="padding:10px" align='center'></div>
                  </div>
                </div>
                <!--
            </div>
         </div>
      </div>
    </div>
	-->
    </fieldset>
    <?php
	}
	
	function ReceiverList(){
	?>
    <fieldset style="border: 1px solid rgb(51, 102, 153); background-color: rgb(255, 255, 255);">
            <legend style="background-color: rgb(51, 102, 153); color: rgb(255, 255, 255); font-size: 10px; font-weight: bold; padding: 5px;">&nbsp;Daftar&nbsp;Penerima&nbsp;</legend>
    <!--
    <div id="TabbedPanelsC" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0"><strong>Penerima</strong></li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
            -->
            <div id="DIVReceiptTable" style="overflow:auto; padding-left:2px">
            <table width="100%" border="1" class="tab" cellspacing="0" cellpadding="0" id="ReceiptTable">
              <tr class="Header">
                <td width="15">No</td>
                <td><input type="checkbox" id="ChkCheckAll" onclick="CheckAllReceipt(this.checked)" /></td>
                <td>No Ponsel</td>
                <td>Nama</td>
              </tr>
            </table>
            </div>
            <table align="center" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="ErrNewReceiptList" class="ErrMsg"></div></td>
              </tr>
            </table>
            <!--
            </div>
      </div>
    </div>
    -->
    
    </fieldset>
    <?php
	}
	
	function OnFinish()
	{	?>
    <script type="text/javascript">
	<!--
		var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
		
		ShowWait('TabPegawai');
		sendRequestText('GetPegawai.php',ShowPegawai,'');
		
		ShowWait('TabSiswa');
		sendRequestText('GetSiswa.php',ShowSiswa,'');
		
		ShowWait('TabCalonSiswa');
		sendRequestText('GetCalonSiswa.php',ShowCalonSiswa,'');
		
		ShowWait('TabLainnya');
		sendRequestText('GetLainnya.php',ShowLainnya,'');
		
		ShowWait('TabOrtu');
		sendRequestText('GetOrtu.php',ShowOrtu,'');
		
		ShowWait('TabOrtuCS');
		sendRequestText('GetOrtuCS.php',ShowOrtuCS,'');
		
		function ShowPegawai(x)
		{
			document.getElementById('TabPegawai').innerHTML = x;
			Tables('TablePeg', 1, 0);
		}
		
		function ShowSiswa(x)
		{
			document.getElementById('TabSiswa').innerHTML = x;
			Tables('TableSis', 1, 0);
		}
		
		function ShowCalonSiswa(x)
		{
			document.getElementById('TabCalonSiswa').innerHTML = x;
			Tables('TabCalonSiswa', 1, 0);
		}
		
		function ShowLainnya(x)
		{
			document.getElementById('TabLainnya').innerHTML = x;
		}
		
		function ShowOrtu(x)
		{
			document.getElementById('TabOrtu').innerHTML = x;
			Tables('TableOr', 1, 0);
		}
		
		function ShowOrtuCS(x)
		{
			document.getElementById('TabOrtuCS').innerHTML = x;
			Tables('TableOrCS', 1, 0);
		}
	//-->
	</script>
    <?php
	}
}
?>