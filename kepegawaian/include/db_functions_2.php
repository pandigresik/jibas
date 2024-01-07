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
    $mysqlconnection = @mysqli_init();
	
	//Buka koneksi ke Database
    function OpenDb() {
        global $db_host, $db_user, $db_pass, $db_name, $mysqlconnection;
	
        $mysqlconnection = @mysqli_connect($db_host, $db_user, $db_pass) or trigger_error("Can not connect to database server", E_USER_ERROR);
        $select = @mysqli_select_db($mysqlconnection, $db_name) or trigger_error("Can not open the database", E_USER_ERROR);
		
		return $mysqlconnection;
    }	
	
	function OpenDbi() {
        global $db_host, $db_user, $db_pass, $db_name, $conni;

        $conni = @mysqli_connect($db_host, $db_user, $db_pass) or trigger_error("Can not connect to database server", E_USER_ERROR);
        $select = @mysqli_select_db($conni, $db_name) or trigger_error("Can not open the database", E_USER_ERROR);
		
		return $conni;
    }
	
	 //Buat query
    function QueryDbi($sql) {
        $result = mysqli_query($mysqlconnection, $sql) or trigger_error("Failed to execute query: $sql", E_USER_ERROR);
        return $result;
    }

    //Tutup koneksi
    function CloseDb() {
        global $mysqlconnection;
		
        @mysqli_close($mysqlconnection);
    }

    //Buat query
    function QueryDb($sql) {
		global $mysqlconnection;
		
        $result = mysqli_query($sql, $mysqlconnection) or trigger_error("<br>&nbsp;&nbsp;Failed to execute query: <br>&nbsp;&nbsp;$sql", E_USER_ERROR);
        return $result;
    }
	
	    //Buat query
    function QueryDbIgnore($sql) {
		global $mysqlconnection;
		
        $result = mysqli_query($sql, $mysqlconnection);
        return $result;
    }

	
    function QueryDbTrans($sql, &$success) {
		global $mysqlconnection;
		
        $result = @mysqli_query($mysqlconnection, $sql);
		$success = ($result && 1); //&& (mysqli_affected_rows($conn)($mysqlconnection) > 0));
		//$success = ($result && (mysqli_affected_rows($conn)($mysqlconnection) > 0));
		
        return $result;
    }
	
	function BeginTrans() {
		global $mysqlconnection;
		
		@mysqli_query($mysqlconnection, "SET AUTOCOMMIT=0");
		@mysqli_query($mysqlconnection, "BEGIN");
	}
	
	function CommitTrans() {
		global $mysqlconnection;
		
		@mysqli_query($mysqlconnection, "COMMIT");
		@mysqli_query($mysqlconnection, "SET AUTOCOMMIT=1");
	}
	
	function RollbackTrans() {
		global $mysqlconnection;
		
		@mysqli_query($mysqlconnection, "ROLLBACK", $mysqlconnection);
		@mysqli_query($mysqlconnection, "SET AUTOCOMMIT=1");
	}
	
	function GetValue($tablename, $column, $where) {
		$sql = "SELECT $column FROM $tablename WHERE $where";
		$result_get_value = QueryDb($sql);
		$row_get_value = mysqli_fetch_row($result_get_value);
		return $row_get_value[0];
	}
?>