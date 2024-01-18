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
function GenerateBarcode($length = 6)
{
    $dict = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $barcode = "";
    for($i = 0; $i < $length; $i++)
    {
        $pos = random_int(0, strlen($dict) - 1);
        $barcode .= substr($dict, $pos, 1);
    }
    
    return $barcode;
}

function GetNewBarcode()
{
    $barcode = "";
    do
    {
        $barcode = GenerateBarcode(6);
        
        $sql = "SELECT COUNT(replid)
                  FROM daftarpustaka
                 WHERE info1 = '".$barcode."'";
        $res = QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $ndata = (int)$row[0];		 
    }
    while($ndata != 0);
    
    return $barcode;
}

function GenKodePustaka($katalog, $penulis, $judul, $format, $counter)
{
    $sql = "SELECT kode FROM katalog WHERE replid='$katalog'";
    $result = QueryDb($sql);
    $ktlg = @mysqli_fetch_row($result);

    $sql = "SELECT kode FROM penulis WHERE replid='$penulis'";
    $result = QueryDb($sql);
    $pnls = @mysqli_fetch_row($result);
    
    $jdl = substr((string) $judul, 0, 1);

    $sql = "SELECT kode FROM format WHERE replid='$format'";
    $result = QueryDb($sql);
    $frmt = @mysqli_fetch_row($result);
    
    $cnt = str_pad((string) $counter, 5, "0", STR_PAD_LEFT);

    $unique = true;
    $addcnt = 0;
    do
    {
        $kode = $ktlg[0] . "/" . $pnls[0] . "/" . $jdl . "/" . $cnt . "/" . $frmt[0];
        
        $sql = "SELECT COUNT(replid) FROM daftarpustaka WHERE kodepustaka = '".$kode."'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        
        if ($row[0] > 0)
        {
            $addcnt++;
            $cnt = "$cnt.$addcnt";
            $unique = false;
        }
        else
        {
            $unique = true;
        }
    }
    while(!$unique);
    
    return $kode;
}

function Save()
{
    $npus = $_REQUEST['npus'];
    
    BeginTrans();
    $success = true;
    
    $idpustaka = $_REQUEST['idpustaka'];
    
    $sql = "SELECT judul, katalog, penulis, format
              FROM jbsperpus.pustaka
             WHERE replid = '".$idpustaka."'";
    //echo "$sql<br>";		          
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $judul = $row[0];
    $idkatalog = $row[1];
    $idpenulis = $row[2];
    $idformat = $row[3];
    
    $sql = "SELECT counter
              FROM katalog
             WHERE replid = '".$idkatalog."'";
    //echo "$sql<br>";		          
    $result = QueryDbTrans($sql, $success);
    $r = @mysqli_fetch_row($result);
    $counter = $r[0];
    
    for ($i = 1; $success && $i <= $npus; $i++)
    {
        $replid = $_REQUEST['replid'.$i];
        $parm = "jumlah$i";
        if ($_REQUEST[$parm] != "" && $_REQUEST[$parm] > 0)
        {
            for ($j = 1; $success && $j <= $_REQUEST[$parm]; $j++)
            {
                $counter++;
                $kodepustaka = GenKodePustaka($idkatalog, $idpenulis, $judul, $idformat, $counter);
                $barcode = GetNewBarcode();
                $sql = "INSERT INTO daftarpustaka
                           SET pustaka='$idpustaka', perpustakaan='$replid',
                               kodepustaka='$kodepustaka', info1='$barcode'";
                //echo "$sql<br>";			   
                QueryDbTrans($sql, $success);
            }
        }
    }
    
    if ($success)
    {
        $sql = "UPDATE katalog
                   SET counter = $counter
                 WHERE replid = '".$idkatalog."'";
        //echo "$sql<br>";		 
        QueryDbTrans($sql, $success);	
    }
    
    if ($success)
    {
        //echo "OK";
        //RollbackTrans();
        CommitTrans();
    }
    else
    {
        //echo "FAILED";
        RollbackTrans();
    }  ?>
    
    <script>
        opener.RefreshList();
        window.close();
    </script>
    
<?php  exit();
}
?>