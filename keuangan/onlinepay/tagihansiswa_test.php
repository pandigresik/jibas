<?php
require_once('../include/sessionchecker.php');
require_once('../include/common.php');
require_once('../include/compatibility.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('tagihansiswa.func.php');

OpenDb();
GetInvoiceList();
CloseDb();
?>