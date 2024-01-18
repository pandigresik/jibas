<?php
require_once("include/sessionchecker.php");
require_once("include/sessioninfo.php");
require_once("include/config.php");
require_once("include/db_functions.php");
require_once("include/common.php");
require_once("include/compatibility.php");
require_once("library/datearith.php");
require_once("home.config.php");
require_once("home.func.php");
require_once("home.letter.func.php");

$op = $_REQUEST['op'];
if ($op == "getlastpesan")
{
    try
    {
        $minListPesanId = $_REQUEST['minListPesanId'];
        $offsetListPesan = $_REQUEST['offsetListPesan'];
        
        OpenDb();
        ShowLastPesan($minListPesanId, $offsetListPesan);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlastagenda")
{
    try
    {
        $maxListAgendaTs = $_REQUEST['maxListAgendaTs'];
        $offsetListAgenda = $_REQUEST['offsetListAgenda'];
        
        OpenDb();
        ShowLastAgenda($maxListAgendaTs, $offsetListAgenda);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlinknextlistpesan")
{
    try
    {
        $minListPesanId = $_REQUEST['minListPesanId'];
        $offsetListPesan = $_REQUEST['offsetListPesan'];
        
        OpenDb();
        ShowLinkNextListPesan($minListPesanId);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlinknextlistagenda")
{
    try
    {
        $maxListAgendaTs = $_REQUEST['maxListAgendaTs'];
        $offsetListAgenda = $_REQUEST['offsetListAgenda'];
        
        OpenDb();
        ShowLinkNextListAgenda($maxListAgendaTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "showbirthdaylist")
{
    try
    {
        $yy = $_REQUEST['tahun'] ?? date('Y');
        $mm = $_REQUEST['bulan'] ?? date('n');
        $dd = $_REQUEST['tanggal'] ?? date('j');
        
        OpenDb();
        ShowBirthdayList($dd, $mm, $yy);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "changecbtanggal")
{
    try
    {
        $tahun = $_REQUEST['tahun'];
        $bulan = $_REQUEST['bulan'];
        
        ShowCbTanggal();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlastnotes")
{
    try
    {
        $minListNotesId = $_REQUEST['minListNotesId'];
        $offsetListNotes = $_REQUEST['offsetListNotes'];
        $departemen = $_REQUEST['departemen'];
        
        OpenDb();
        ShowLastNotes($minListNotesId, $offsetListNotes, $departemen);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlinknextlistnotes")
{
    try
    {
        $minListNotesId = $_REQUEST['minListNotesId'];
        $departemen = $_REQUEST['departemen'];
        
        OpenDb();
        ShowLinkNextListNotes($minListNotesId, $departemen);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlastberitasekolah")
{
    try
    {
        $maxListBSekolahTs = $_REQUEST['maxListBSekolahTs'];
        
        OpenDb();
        ShowLastBeritaSekolah($maxListBSekolahTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlinknextlistberitasekolah")
{
    try
    {
        $maxListBSekolahTs = $_REQUEST['maxListBSekolahTs'];
        
        OpenDb();
        ShowLinkNextListBeritaSekolah($maxListBSekolahTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }  
}
elseif ($op == "getlastberitaguru")
{
    try
    {
        $maxListBGuruTs = $_REQUEST['maxListBGuruTs'];
        
        OpenDb();
        ShowLastBeritaGuru($maxListBGuruTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif($op == "getlinknextlistberitaguru")
{
    try
    {
        $maxListBGuruTs = $_REQUEST['maxListBGuruTs'];
        
        OpenDb();
        ShowLinkNextListBeritaGuru($maxListBGuruTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }  
}
elseif ($op == "getlastberitasiswa")
{
    try
    {
        $maxListBSiswaTs = $_REQUEST['maxListBSiswaTs'];
        
        OpenDb();
        ShowLastBeritaSiswa($maxListBSiswaTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }
}
elseif ($op == "getlinknextlistberitasiswa")
{
    try
    {
        $maxListBSiswaTs = $_REQUEST['maxListBSiswaTs'];
        
        OpenDb();
        ShowLinkNextListBeritaSiswa($maxListBSiswaTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }  
}
elseif ($op == "getlastsurat")
{
    try
    {
        $jenis = $_REQUEST['jenis'];
        $minListSuratTs = $_REQUEST['minListSuratTs'];
        //$minListSuratTs = "14125987710000011";
        
        OpenDb();
        ShowLastLetter($jenis, $minListSuratTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }  
}
elseif ($op == "getlinknextlistsurat")
{
    try
    {
        $jenis = $_REQUEST['jenis'];
        $minListSuratTs = $_REQUEST['minListSuratTs'];
        
        OpenDb();
        ShowLinkNextListSurat($jenis, $minListSuratTs);
        CloseDb();
        
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        http_response_code(500);
        echo $ex->getMessage();
    }  
}
?>