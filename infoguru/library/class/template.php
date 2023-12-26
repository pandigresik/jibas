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

if(ereg("template.php", $PHP_SELF)) {
	header("location : index.php");
	die;
}

class Template {
	VAR $TAGS = array();
	VAR $THEME;
	VAR $CONTENT;

	public function DefineTag($tag_name,$var_name) {
		$this->TAGS[$tag_name] = $var_name;
	}

	public function DefineTheme($theme_name) {
		$this->THEME = $theme_name;
	}

	public function Parse() {
		$this->CONTENT = file($this->THEME);
		$this->CONTENT = implode("", $this->CONTENT);
		while(list($key,$val) = each ($this->TAGS)) {
			$this->CONTENT = ereg_replace($key,$val,$this->CONTENT);
		}
	}

	public function PrintProcess() {
		echo $this->CONTENT;
	}
}
?>