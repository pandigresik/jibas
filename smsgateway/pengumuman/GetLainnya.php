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
<table  border="1" cellspacing="0" cellpadding="0" class="tab">
  <tr style="background-color:#cfddd1">
	<td width="34%" valign="top" class="tdTop" style="font-weight:bold">Nama</td>
	<td width="66%" class="td"><input type="text" class="InputTxt" name="NewReceiptName" id="NewReceiptName" /><div id="ErrNewReceiptName" class="ErrMsg"></div></td>
  </tr>
  <tr>
	<td valign="top" class="tdTop" style="font-weight:bold">No Ponsel</td>
	<td class="td"><input type="text" class="InputTxt" name="NewReceiptNo" id="NewReceiptNo" /><div id="ErrNewReceiptNo" class="ErrMsg"></div></td>
  </tr>
  <tr style="background-color:#cfddd1">
	<td height="30" colspan="2" align="center"><div class="BtnSilver" onclick="InsertNewReceipt('','','','other')" align="center">Tambahkan</div></td>
  </tr>
</table>