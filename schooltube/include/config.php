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

// ------------------------------------------------------------
// PATCH MANAGEMENT FRAMEWORK                                  
// ------------------------------------------------------------

if (file_exists('../include/global.patch.manager.php'))
{
	require_once('../include/global.patch.manager.php');
	ApplyGlobalPatch("..");	
}
elseif (file_exists('../../include/global.patch.manager.php'))
{
	require_once('../../include/global.patch.manager.php');
	ApplyGlobalPatch("../..");
}
elseif (file_exists('../../../include/global.patch.manager.php'))
{
	require_once('../../../include/global.patch.manager.php');
	ApplyGlobalPatch("../../..");
}

require_once('module.patch.manager.php');
ApplyModulePatch();

// ------------------------------------------------------------
// MAIN CONFIGURATION                                          
// ------------------------------------------------------------
	
if (file_exists('../include/mainconfig.php'))
{
	require_once('../include/mainconfig.php');
}
elseif (file_exists('../../include/mainconfig.php'))
{
	require_once('../../include/mainconfig.php');
}
elseif (file_exists('../../../include/mainconfig.php'))
{
	require_once('../../../include/mainconfig.php');
}

// ------------------------------------------------------------
// DEFAULT CONSTANTS                                           
// ------------------------------------------------------------

$G_ENABLE_QUERY_ERROR_LOG = true;
$G_FILE_LOG_QUERY_ERROR = "el-error.log";

$G_ENABLE_QUERY_LOG = false;
$G_FILE_LOG_QUERY = "el-query.log";

// ------------------------------------------------------------
// FORMAT ALL INCOMING REQUEST
// ------------------------------------------------------------

function FmtReq_PreventInjection($value)
{
    $result = $value;
    $loValue = strtolower((string) $result);

    $arrKeyFound = [];
    $arrKey = ["union ", "union*", "select ", "select*", "-- "];
    for($i = 0; $i < count($arrKey); $i++)
    {
        $key = $arrKey[$i];
        $keyLen = strlen($key);

        $pos = strpos($loValue, $key);
        if ($pos === false)
            continue;

        $search = substr((string) $result, $pos, $keyLen);
        $arrKeyFound[] = $search;
    }

    for($i = 0; $i < count($arrKeyFound); $i++)
    {
        $search = $arrKeyFound[$i];
        $replace = substr($search, 0, 1) . " " . substr($search, 1);
        $result = str_replace($search, $replace, (string) $result);
    }

    return $result;
}

function FmtReq_FormatValue($value)
{
    $value = str_replace("'", "`", (string) $value);  //&#39;
    $value = str_replace('"', "`", $value);  //&#34;
    $value = addslashes($value);
    $value = FmtReq_PreventInjection($value);

    return $value;
}

function FmtReq_TraverseRequestArray(&$arr)
{
    foreach($arr as $key => $value)
    {
        if (is_array($arr[$key]))
            FmtReq_TraverseRequestArray($arr[$key]);
        else
            $arr[$key] = FmtReq_FormatValue($value);
    }
}

foreach($_REQUEST as $key => $value)
{
    if (is_array($_REQUEST[$key]))
        FmtReq_TraverseRequestArray($_REQUEST[$key]);
    else
        $_REQUEST[$key] = FmtReq_FormatValue($value);
}
?>