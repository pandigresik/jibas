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
require_once('../include/sessionchecker.php');
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');

OpenDb();

$sisformatsms = $_REQUEST['sisformatsms'];
$sisformatsms = str_replace("'", "`", (string) $sisformatsms);

$csisformatsms = $_REQUEST['csisformatsms'];
$csisformatsms = str_replace("'", "`", (string) $csisformatsms);

$tabunganformatsms = $_REQUEST['tabunganformatsms'];
$tabunganformatsms = str_replace("'", "`", (string) $tabunganformatsms);

$tungformatsms = $_REQUEST['tungformatsms'];
$tungformatsms = str_replace("'", "`", (string) $tungformatsms);

$paymentformatsms = $_REQUEST['paymentformatsms'];
$paymentformatsms = str_replace("'", "`", (string) $paymentformatsms);

$departemen = $_REQUEST['departemen'];

$sql = "SELECT COUNT(*)
		  FROM jbsfina.formatsms
		 WHERE jenis = 'SISPAY'
           AND departemen = '".$departemen."'";
$ndata = FetchSingle($sql);

if ($ndata > 0)
{
	$sql = "UPDATE jbsfina.formatsms
	           SET format = '$sisformatsms'
	         WHERE jenis = 'SISPAY'
	           AND departemen = '".$departemen."'";
}
else
{
	$sql = "INSERT INTO jbsfina.formatsms
	           SET format = '$sisformatsms', jenis = 'SISPAY', departemen = '".$departemen."'";	
}
QueryDb($sql);

$sql = "SELECT COUNT(*)
		  FROM jbsfina.formatsms
		 WHERE jenis = 'CSISPAY'
           AND departemen = '".$departemen."'";
$ndata = FetchSingle($sql);

if ($ndata > 0)
{
	$sql = "UPDATE jbsfina.formatsms
	           SET format = '$csisformatsms'
	         WHERE jenis = 'CSISPAY'
	           AND departemen = '".$departemen."'";
}
else
{
	$sql = "INSERT INTO jbsfina.formatsms
	           SET format = '$csisformatsms', jenis = 'CSISPAY', departemen = '".$departemen."'";	
}
QueryDb($sql);

$sql = "SELECT COUNT(*)
		  FROM jbsfina.formatsms
		 WHERE jenis = 'SISTUNG'
           AND departemen = '".$departemen."'";
$ndata = FetchSingle($sql);
if ($ndata > 0)
{
    $sql = "UPDATE jbsfina.formatsms
	           SET format = '$tungformatsms'
	         WHERE jenis = 'SISTUNG'
	           AND departemen = '".$departemen."'";
}
else
{
    $sql = "INSERT INTO jbsfina.formatsms
	           SET format = '$tungformatsms', jenis = 'SISTUNG', departemen = '".$departemen."'";
}
QueryDb($sql);

$sql = "SELECT COUNT(*)
		  FROM jbsfina.formatsms
		 WHERE jenis = 'SISTAB'
           AND departemen = '".$departemen."'";
$ndata = FetchSingle($sql);
if ($ndata > 0)
{
    $sql = "UPDATE jbsfina.formatsms
	           SET format = '$tabunganformatsms'
	         WHERE jenis = 'SISTAB'
	           AND departemen = '".$departemen."'";
}
else
{
    $sql = "INSERT INTO jbsfina.formatsms
	           SET format = '$tabunganformatsms', jenis = 'SISTAB', departemen = '".$departemen."'";
}
QueryDb($sql);

$sql = "SELECT COUNT(*)
		  FROM jbsfina.formatsms
		 WHERE jenis = 'SCHOOLPAY'
           AND departemen = '".$departemen."'";
$ndata = FetchSingle($sql);
if ($ndata > 0)
{
    $sql = "UPDATE jbsfina.formatsms
	           SET format = '$paymentformatsms'
	         WHERE jenis = 'SCHOOLPAY'
	           AND departemen = '".$departemen."'";
}
else
{
    $sql = "INSERT INTO jbsfina.formatsms
	           SET format = '$paymentformatsms', jenis = 'SCHOOLPAY', departemen = '".$departemen."'";
}
QueryDb($sql);

CloseDb();
?>
<script>
    document.location.href = "formatsms.php?departemen=<?=$departemen?>";
</script>