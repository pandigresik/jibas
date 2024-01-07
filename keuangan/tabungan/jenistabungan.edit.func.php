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
    global $id, $mysqli_ERROR_MSG;
    
    $sql = "SELECT replid
              FROM datatabungan
             WHERE nama = '".$_REQUEST['nama']."'
               AND replid <> '$id'";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0)
	{
		$mysqli_ERROR_MSG = "Nama {$_REQUEST['nama']} sudah digunakan!";
	}
	else
	{
		$smsinfo = isset($_REQUEST['smsinfo']) ? 1 : 0;
		$sql = "UPDATE datatabungan
				   SET nama='".CQ($_REQUEST['nama'])."',
					   rekkas='".$_REQUEST['norekkas']."',
					   rekutang='".$_REQUEST['norekutang']."',
					   keterangan='".CQ($_REQUEST['keterangan'])."',
					   info2='$smsinfo'
				 WHERE replid=$id";
		$result = QueryDb($sql);
		CloseDb();
	
		if ($result)
		{ ?>
			<script language="javascript">
				opener.refresh();
				window.close();
			</script> 
<?php 	}
        exit();
    }
}


function LoadData()
{
    global $id;
    global $nama, $rekkas, $rekutang, $keterangan, $smsinfo;
    global $namarekkas, $namarekutang;
    
    $sql = "SELECT * FROM datatabungan WHERE replid = '".$id."'";
    $result = QueryDb($sql);
    
    $row = mysqli_fetch_array($result);
    $nama = $row['nama'];
    $rekkas = $row['rekkas'];
    $rekutang = $row['rekutang'];
    $keterangan = $row['keterangan'];
    $smsinfo = (int)$row['info2'];
    
    if (isset($_REQUEST['nama']))
        $nama = $_REQUEST['nama'];
        
    if (isset($_REQUEST['keterangan']))
        $keterangan = $_REQUEST['keterangan'];	
    
    $sql = "SELECT nama FROM rekakun WHERE kode = '".$rekkas."'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $namarekkas = $row[0];
        
    $sql = "SELECT nama FROM rekakun WHERE kode = '".$rekutang."'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $namarekutang = $row[0];
}

function CheckIdIsUsed($id)
{
    global $idIsUsed;
    
    $idIsUsed = false;

    $sql = "SELECT EXISTS(SELECT replid FROM jbsfina.tabungan WHERE idtabungan = '$id' LIMIT 1)";
    $idIsUsed = (1 == (int)FetchSingle($sql));
}
?>