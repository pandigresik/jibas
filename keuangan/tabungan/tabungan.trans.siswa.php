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
$idtahunbuku = $_REQUEST['idtahunbuku'];
$idtabungan = $_REQUEST['idtabungan'];
$departemen = $_REQUEST['departemen'];

$status = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link href="../script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
    <script src="../script/SpryTabbedPanels.js" type="text/javascript"></script>
    <script language = "javascript" type = "text/javascript" src="../script/tables.js"></script>
    <script src="../script/SpryValidationTextField.js" type="text/javascript"></script>
    <link href="../script/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
    <link href="../script/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <script src="../script/ajax.js" type="text/javascript"></script>
    <script src="../script/jquery-1.9.0.js" type="text/javascript"></script>
    <script language="javascript">
    function pilih(nis)
    {	
        parent.content.location.href = "tabungan.trans.input.php?nis="+nis+"&idtabungan=<?=$idtabungan?>&idtahunbuku=<?=$idtahunbuku?>&status=<?=$status?>";		
    }

    function scanBarcode(e)
    {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode != 13)
            return;

        var kode = $.trim($('#txBarcode').val());
        if (kode.length == 0)
            return;

        var data = "kode="+kode+"&departemen=<?=$departemen?>";

        $('#spScanInfo').html("");

        $.ajax({
            url: "tabungan.barcode.php",
            type: 'GET',
            data: data,
            success: function (response)
            {
                $('#txBarcode').val('');

                var data = $.parseJSON(response);
                if (data.status == "1")
                {
                    parent.content.location.href = "tabungan.trans.input.php?nis="+kode+"&idtabungan=<?=$idtabungan?>&idtahunbuku=<?=$idtahunbuku?>&status=<?=$status?>";
                }
                else
                {
                    $('#spScanInfo').html(data.message);
                    parent.content.location.href = "tabungan.trans.input.blank.php";
                }
            },
            error: function (xhr, response, error)
            {
                alert(xhr.responseText);
            }
        });
    }

    $( document ).ready(function() {
        setTimeout(function () {
            $('#txBarcode').focus();
        }, 300);

    });
    </script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF"
      onclick="document.getElementById('txBarcode').value = ''">
<input type="hidden" id="idtahunbuku" value="<?=$idtahunbuku ?>" />
<input type="hidden" id="idkategori" value="<?=$idkategori ?>" />
<input type="hidden" id="idpenerimaan" value="<?=$idpenerimaan ?>" />
<input type="hidden" id="status" value="<?=$status ?>" />
<input type="hidden" id="departemen" value="<?=$departemen ?>" />
<table border="0" width="100%" align="center" cellspacing="2" cellpadding="2">
<tr><td align="left">
 	<table border="0" cellpadding="2" bgcolor="#FFFFFF" cellspacing="0">
    <tr>
        <td align="left">
            <fieldset>
                <legend><strong>Scan Barcode:</strong></legend>
                <input name="txBarcode" id="txBarcode" type="text" style="width: 200px; font-size: 18px;"
                       onfocus="this.style.background = '#27d1e5'"
                       onblur="this.style.background = '#FFFFFF'"
                       onkeyup="return scanBarcode(event)">
                <br>
                <span id="spScanInfo" name="spScanInfo" style="color: red"></span>
                <br>
            </fieldset>
            <br>
        </td>
    </tr>
    <tr height="500">
    	<td valign="top" bgcolor="#FFFFFF">
        <div id="TabbedPanels1" class="TabbedPanels">
      		<ul class="TabbedPanelsTabGroup">
            	<li class="TabbedPanelsTab" tabindex="0"><font size="1">Pilih <?=$status?> Siswa</font></li>
            	<li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari <?=$status?> Siswa</font></li>
          	</ul>
      		<div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent" id="panel0"></div>
                <div class="TabbedPanelsContent" id="panel1"></div>
      		</div>
        </div>
		</td>
    </tr>
    </table>
     <!-- END OF CONTENT //--->
    </td>
</tr>
</table>
</body>
</html>
<script type="text/javascript" src="tabungan.trans.siswa.js"></script>