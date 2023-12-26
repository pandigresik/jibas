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
require_once('../inc/config.php');
require_once('../inc/common.php');
require_once('../inc/db_functions.php');
require_once('pinjam.class.php');
require_once('pinjam.config.php');

OpenDb();
$P = new CPinjam();
$P->OnStart();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../scr/jquery/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<link href="../sty/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scr/tables.js"></script>
<script type="text/javascript" src="../scr/tools.js"></script>
<script language="javascript" src="../scr/jquery-1.9.0.js"></script>
<script language="javascript" src="../scr/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="pinjam3.js"></script>
<script type="text/javascript">
function scanBarcode(e)
{
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode != 13)
        return;

    var kode = $.trim($('#txBarcode').val());
    if (kode.length == 0)
        return;

    var data = "kode="+kode;

    $('#spScanInfo').html("");

    $.ajax({
        url: "../pjm/scanbarcode.php",
        type: 'GET',
        data: data,
        success: function (response)
        {
            var data = $.parseJSON(response);

            if (data.status == "1")
            {
                var status = data.usertype;
                var noanggota = kode;
                var nama = data.username;

                document.location.href = "../pjm/pinjam.php?op=newuser&state="+status+"&noanggota="+noanggota+"&nama="+nama;
            }
            else
            {
                $('#spScanInfo').html(data.message);
            }
        },
        error: function (xhr, response, error)
        {
            alert(xhr.responseText);
        }
    });
}
</script>
</head>

<body>
    <div id="content">
      <?=$P->Content()?>
    </div>
</body>
<?=$P->OnFinish()?>
</html>
<?php CloseDb(); ?>