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
include_once '../../vendor/autoload.php';
require_once("config.php");
require_once("db_functions.php");

class ChartFactory {
	public $xdata;
	public $ydata;
	public $title;
	public $xtitle;
	public $ytitle;
	
	public $color;
	
	function __construct() {
		$this->color = ['red', 'black', 'green', 'blue', 'gray', 'darkblue', 'gold', 'yellow', 'navy', 'orange', 'darkred', 'darkgreen', 'pink'];
	}
	
	function ArrayData($xda, $yda, $tit, $xti, $yti) {
		$this->xdata = $xda;
		$this->ydata = $yda;
		$this->title = $tit;
		$this->xtitle = $xti;
		$this->ytitle = $yti;
	}
	
	function SqlData($sql, $tit, $xti, $yti) {
		OpenDb();
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result)) {
			$this->xdata[] = $row[0];
			$this->ydata[] = $row[1];
		}
		CloseDb();
		
		$this->title = $tit;
		$this->xtitle = $xti;
		$this->ytitle = $yti;
	}

	function DrawBarChart() {
		if ( (count($this->xdata ?? []) == 0) || (count($this->ydata ?? []) == 0) ) return;
		
		//Buat grafik
		mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(450,300,"auto");
		$graph->SetScale("textlin");
		
		//setting kanvas
		$graph->SetShadow();
		$graph->img->SetMargin(50,40,50,40);
		$graph->xaxis->SetTickLabels($this->xdata);
		$graph->xaxis->SetTickSide(SIDE_LEFT);
		
		//Create bar plots
		$plot = new BarPlot($this->ydata);
		$plot->SetFillColor($this->color);
		$plot->SetShadow('darkgray@0.5');
		
		$plot->value->Show();
		//$plot->value->SetFont(FF_FONT1,FS_BOLD);
		
		$plot->value->SetFormat('%d');
		//$plot->value->SetAlign('center','center');
		
		//memasukkan kedalam grafik
		$graph->Add($plot);
		
		$graph->title->Set($this->title);
		$graph->xaxis->title->Set($this->xtitle);
		$graph->yaxis->title->Set($this->ytitle);
		
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		
		//Pengaturan sumbu x dan sumbu y
		$graph->yaxis->HideZeroLabel();
		$graph->ygrid->SetFill(true,'#dedede','#FFFFFF');
		
		//Menamplikan ke browser
		$graph->Stroke();
	}
	
	function DrawPieChart() {
		if ( (count($this->xdata ?? []) == 0) || (count($this->ydata ?? []) == 0) ) return;
		
		//Buat grafik
		mitoteam\jpgraph\MtJpGraph::load(['pie','pie3d']);
		$graph = new PieGraph(500,300,"auto");
		$graph->img->SetAntiAliasing();
		$graph->SetShadow();

		$graph->title->Set($this->title);
		$graph->title->SetFont(FF_FONT1,FS_BOLD);

		$plot = new PiePlot3D($this->ydata);
		$plot->ExplodeAll();
		$plot->SetTheme("pastel");
		$plot->SetShadow('darkgray@0.5');
		$plot->SetLegends($this->xdata);
		$plot->SetSize(0.4);
		$plot->SetCenter(0.45);
		//memasukkan kedalam grafik
		$graph->Add($plot);
		//Menamplikan ke browser
		$graph->Stroke();
		
		/*
		//Buat grafik
		mitoteam\jpgraph\MtJpGraph::load(['pie','pie3d']);
		$graph = new PieGraph(500,300,"auto");
		$graph->img->SetAntiAliasing();
		$graph->SetShadow();
	
		$graph->title->Set($this->title);
	
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
	
		$plot = new PiePlot3D($this->ydata);
		$plot->ExplodeAll();
		$plot->SetShadow('darkgray@0.5');
		$plot->SetTheme("earth");
		$plot->SetLegends($this->xdata);
		$plot->SetCenter(0.4);
	
		// Enable and set policy for guide-lines. Make labels line up vertically
		$plot->SetGuideLines(true,false);
		$plot->SetGuideLinesAdjust(1.1);
	
		//memasukkan kedalam grafik
		$graph->Add($plot);
		//Menamplikan ke browser
		$graph->Stroke();*/
	}
}
?>