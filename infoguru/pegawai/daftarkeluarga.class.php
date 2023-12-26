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
class DaftarKeluarga
{
    public $nip;
    public $nama;
    
    public function __construct()
    {
        $this->nip = $_REQUEST['nip'];

        $sql = "SELECT TRIM(CONCAT(IFNULL(gelarawal,''), ' ' , nama, ' ', IFNULL(gelarakhir,''))) AS nama FROM jbssdm.pegawai WHERE nip='$this->nip'";
        $result = QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $this->nama = $row[0];
        
        $id = $_REQUEST['id'];
        $op = $_REQUEST['op'];
        if ($op == "mnrmd2re2dj2mx2x2x3d2s33")
        {
            $success = true;
            BeginTrans();
            
            $sql = "DELETE FROM jbssdm.pegkeluarga WHERE replid=$id";	
            QueryDbTrans($sql, $success);
            
            if ($success)
                CommitTrans();
            else
                RollbackTrans();
        }
    }
}
?>