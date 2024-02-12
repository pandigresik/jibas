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
require_once("../inc/config.php");
require_once("../inc/rupiah.php");
require_once("../inc/db_functions.php");

class ChartFactory {
	public $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nop', 'Des'];
	public $xdata;
	public $ydata;
	public $title;
	public $xtitle;
	public $ytitle;
	
	public $color;
	
	function __construct() {
		$this->color = ['#cd9b9b', '#7d26cd', '#8b1c62', '#b03060', '#faf0e6', '#ff69b4', '#d2d2d2', '#7fff00', '#00bfff', '#ff1493', '#6e8b3d', '#b8860b', '#00ffff', '#dcdcdc', '#00c5cd', '#a52a2a'];
		//$this->color = array(136,34,40,45,46,62,63,134,74,10,120,136,141,168,180,77,209,218,346,395,89,430);
	}
	
	function ArrayData($xda, $yda, $tit, $xti, $yti) {
		$this->xdata = $xda;
		$this->ydata = $yda;
		$this->title = $tit;
		$this->xtitle = $xti;
		$this->ytitle = $yti;
	}
	
	function SqlData($sql, $btit, $ptit, $xti, $yti) {
		OpenDb();
		$result = QueryDb($sql);
		while ($row = mysqli_fetch_row($result)) {
			$this->xdata[] = $row[0];
			$this->ydata[] = $row[1];
		}
		CloseDb();
		
		$this->btitle = $btit;
		$this->ptitle = $ptit;
		$this->xtitle = $xti;
		$this->ytitle = $yti;
	}

	function DrawBarChart() {
		if ( (count($this->xdata ?? []) == 0) || (count($this->ydata ?? []) == 0) ) return;
		
		// Some "random" data
		$ydata  = $this->ydata;//array(10,120,80,190,260,170,60,40,20,230);
		//$ydata2 = array(10,70,40,120,200,60,80,40,20,5);

		// Get a list of month using the current locale
		//$months = $gDateLocale->GetShortMonth();

		// Create the graph. 
		mitoteam\jpgraph\MtJpGraph::load(['bar']);
$graph = new Graph(400,300);	
		$graph->SetScale("textlin");
		$graph->SetMarginColor('white');

		// Adjust the margin slightly so that we use the 
		// entire area (since we don't use a frame)
		$graph->SetMargin(25,1,20,100);//Left Right Top Bottom

		// Box around plotarea
		$graph->SetBox(); 

		// No frame around the image
		$graph->SetFrame(false);

		// Setup the tab title
		$graph->title->Set($this->btitle);
		$graph->title->SetFont(FF_FONT1,FS_BOLD,10);

		// Setup the X and Y grid
		$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
		$graph->ygrid->SetLineStyle('dashed');
		$graph->ygrid->SetColor('gray');
		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle('dashed');
		$graph->xgrid->SetColor('gray');

		// Setup month as labels on the X-axis
		$monthyear = $this->xdata;
		//$monthyear = explode('-',$monthyear);
		//$xlab = $this->bulan[$monthyear[0]-1]." ".$monthyear[1];
		$graph->xaxis->SetTickLabels($monthyear);
		$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(90);

		// Create a bar pot
		$bplot = new BarPlot($this->ydata);
		$bplot->SetWidth(0.4);
		$fcol='#440000';
		$tcol='#FF9090';

		$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

		// Set line weigth to 0 so that there are no border
		// around each bar
		$bplot->SetWeight(0);

		$graph->Add($bplot);

		$graph->Stroke();

	}
	
	function DrawPieChart() {
		if ( (count($this->xdata ?? []) == 0) || (count($this->ydata ?? []) == 0) ) return;
		
		$data = $this->ydata;//array(40,60,21,33);

		// Setup graph
		mitoteam\jpgraph\MtJpGraph::load(['pie','pie3d']);
		$graph = new PieGraph(400,200,"auto");
		$graph->SetShadow();

		// Setup graph title
		$graph->title->Set($this->btitle);
		$graph->title->SetFont(FF_FONT1,FS_BOLD,10);

		// Create pie plot
		$p1 = new PiePlot($data);
		$p1->value->SetFont(FF_VERDANA,FS_BOLD);
		$p1->value->SetColor("darkred");
		$p1->SetSize(0.3);
		$p1->SetCenter(0.4);
		$p1->SetLegends($this->xdata);
		//$p1->SetStartAngle(M_PI/8);
		//$p1->ExplodeSlice(3);

		$graph->Add($p1);

		$graph->Stroke();

	}
}
?>