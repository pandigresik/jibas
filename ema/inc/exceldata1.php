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
require_once("../include/config.php");
require_once("../include/db_functions.php");

class ExcelHeader {
	function __construct(public $title, public $width, public $isno)
 {
 }
}

class ExcelData {
	public $sql = "";
	public $eheader;
	public $data; 
	
	function __construct(public $title) {
		$this->eheader[] = new ExcelHeader("No", "", 100, true);
	}
	
	function AddHeader($tit, $wid) {
		$this->eheader[] = new ExcelHeader($tit, $wid, false);
	}
	
	function SqlData($sql) {
		$this->sql = $sql;
	}
	
	function ArrayData($data) {
		$this->data = $data;
	}
	
	function ExportFromSql() {
		$nheader = count($this->eheader);
		$html = "<html><head>";
		$html .= "<style> 
					body {
					  font-family  : Verdana;
				   	  font-size    : 11px;
				  	  color                     : #000000;
	                  background-color          : #FFFFFF;
				    }
				    tr, td {
			          font-family  : Verdana;
				  	  font-size    : 11px;
				  	  border-collapse    : collapse;
					}
					.header {
					  border:outset 1px #ccc;
				      background: #000000;
			          color:#FFFFFF;
				      font-weight:bold;
				      padding: 1px 2px;
					  border-collapse    : collapse;
				     font-size        : 12px;
				   }
				  </style></head>";
		

		$html .= "<body>";
		$html .= "<font size='3'><strong>" . $this->title . "<strong></font><br><br>";
		$html .= "<table border='1' style='border-color: Gray; border-collapse: collapse'; cellpadding='2' cellspacing='0'>";
		$html .= "<tr height='30'>";
		for ($i = 0; $i < $nheader; $i++) {
			$h = $this->eheader[$i];
			$html .= "<td class='header' align='center' valign='middle' width='" . $h->width . "'>" . $h->title . "</td>";
		}
        $html .= "</tr>";
		
		OpenDb();
		$result = QueryDb($this->sql);
		$cnt = 0;
		while ($row = mysqli_fetch_row($result)) {
			$cnt++;
			$html .= "<tr height='25'>";
			$html .= "<td align='center' valign='middle'>$cnt</td>";
			$nrow = count($row);
			for ($i = 0; $i < $nrow; $i++) {
				$html .= "<td align='left' valign='middle'>" . $row[$i] . "</td>";
			}
			$html .= "</tr>";
		}
		CloseDb();

		$html .= "</table></body></html>";
		
		$file = str_replace(" ", "_", (string) $this->title);
		
		header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
	    header('Content-Type: application/x-msexcel'); // Other browsers  
		header('Content-Disposition: attachment; filename="' . $file .'".xls');
	    header('Expires: 0');  
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');  
		echo " ";
		echo $html;
	
	}
	
		function ExportFromData() {
		$nheader = count($this->eheader);
		$html = "<html><head>";
		$html .= "<style> 
					body {
					  font-family  : Verdana;
				   	  font-size    : 11px;
				  	  color                     : #000000;
	                  background-color          : #FFFFFF;
				    }
				    tr, td {
			          font-family  : Verdana;
				  	  font-size    : 11px;
				  	  border-collapse    : collapse;
					}
					.header {
					  border:outset 1px #ccc;
				      background: #000000;
			          color:#FFFFFF;
				      font-weight:bold;
				      padding: 1px 2px;
					  border-collapse    : collapse;
				     font-size        : 12px;
				   }
				  </style></head>";
		

		$html .= "<body>";
		$html .= "<font size='3'><strong>" . $this->title . "<strong></font><br><br>";
		$html .= "<table border='1' style='border-color: Gray; border-collapse: collapse'; cellpadding='2' cellspacing='0'>";
		$html .= "<tr height='30'>";
		for ($i = 0; $i < $nheader; $i++) {
			$h = $this->eheader[$i];
			$html .= "<td class='header' align='center' valign='middle' width='" . $h->width . "'>" . $h->title . "</td>";
		}
        $html .= "</tr>";
		
		OpenDb();
		$result = QueryDb($this->data);
		$cnt = 0;
		while ($row = mysqli_fetch_row($result)) {
			
			$html .= "<tr height='25'>";
			$html .= "<td align='center' valign='middle'>$cnt</td>";
			$nrow = count($row);
            $ncol = count($row);
            for($j = 0; $j < $ncol; $j++) {
   	           $data[$i][] = $row[$j];
               }
              $i++;
		 }
		CloseDb();

		$html .= "</table></body></html>";
		
		$file = str_replace(" ", "_", (string) $this->title);
		
		header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
	    header('Content-Type: application/x-msexcel'); // Other browsers  
		header('Content-Disposition: attachment; filename="' . $file .'".xls');
	    header('Expires: 0');  
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');  
		echo " ";
		echo $html;
	
	}
	
	
	function Export() {
	
		if ($this->sql == "") 
			$this->ExportFromSql();
		else
			$this->ExportFromData();
		
	}
}

?>