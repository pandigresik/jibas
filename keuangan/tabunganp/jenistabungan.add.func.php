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
function SimpanData()
{
    global $mysqli_ERROR_MSG;
    
    OpenDb();
    
	$sql = "SELECT replid
              FROM datatabunganp
             WHERE nama = '".$_REQUEST['nama']."'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0)
	{
		$mysqli_ERROR_MSG = "Nama {$_REQUEST['nama']} sudah digunakan";
	}
	else
	{
		$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;
		$sql = "INSERT INTO datatabunganp
				   SET nama='".CQ($_REQUEST['nama'])."', rekkas='".$_REQUEST['norekkas']."',
				       departemen='".$_REQUEST['departemen']."', rekutang='".$_REQUEST['norekutang']."',
					   keterangan='".CQ($_REQUEST['keterangan'])."', aktif=1, info2='$smsinfo'";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result)
		{ ?>
			<script language="javascript">
				opener.refreshAll();
				window.close();
			</script> 
<?php          exit();          
        }
        
	}
}
?>