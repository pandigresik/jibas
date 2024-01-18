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

OpenDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="home.css" />      	
<link type="text/css" rel="stylesheet" href="style/style.css" />      
<link type="text/css" rel="stylesheet" href="script/themes/base/jquery.ui.all.css" />      
<script src="script/jquery-1.9.1.js"></script>
<script src="script/jquery-ui-1.10.3.custom.min.js"></script>
<script src="script/tools.js"></script>
<script src="home.js"></script>
<script src="home.config.js"></script>	
</head>
<body marginheight='0' marginwidth='0' leftmargin='0' topmargin='0'>
	
<div id="pesanDialogBox"></div>
<div id="agendaDialogBox"></div>
<div id="bdayDialogBox"></div>
<div id="notesDialogBox"></div>
<div id="beritaSekolahDialogBox"></div>
<div id="beritaGuruDialogBox"></div>
<div id="beritaSiswaDialogBox"></div>

<table id='tabMain' border='0' cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td width='*' align='left' valign='top'>
	<table border='0' cellpadding='0'>
	<tr>
		<td width='5'>&nbsp;</td>
		<td align='left'>
<?php 		ShowImageUser() ?>				
		</td>
		<td align='left'>
			<font style='color: #444; font-size: 12px'>Selamat <?= GetTimeState() ?></font><br>
			<font style='color: black; font-size: 24px'><?= SI_USER_NAME() ?></font><br>
			<font style='color: #999; font-size: 10px'><?= SI_USER_ID() ?></font>
		</td>
	</tr>
	</table> 

	
	<table border='0' cellpadding='0' cellspacing='5' width='100%' style='height: 25px'>
	<tr>
		<td align='top' valign='top' width='33%'>
			
			<table border='0' cellpadding='5' width='100%' style='height: 210px'>
			<tr height='25'>
				<td align='left' valign='middle' style='background-color: #400080; color: #fff; font-weight: bold;'>
				BERITA SEKOLAH
				&nbsp;&nbsp;
				<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshListBeritaSekolah()'>muat ulang</a>]</font>
				</td>
			</tr>
			<tr style='background-color: #e6ccff; height: 195px'>
				<td align='left' valign='top'>
					<div id='divBeritaSekolah' style='overflow: auto; height: 195px;'>
						
						<input type='hidden' id='maxListBSekolahTs' value='0'>
						<table id='tabListBSekolah' width='100%' cellspacing='7' cellpadding='0'>
						<tbody>
							
						</tbody>
						<tfoot>
		
						</tfoot>
						</table>
						
					</div>
				</td>
			</tr>
			</table>
			
		</td>
		<td align='top' valign='top' width='33%'>
			
			<table border='0' cellpadding='5' width='100%' style='height: 210px'>
			<tr height='26'>
				<td align='left' valign='middle' style='background-color: #004000; color: #fff; font-weight: bold;'>
				BERITA GURU
				&nbsp;&nbsp;
				<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshListBeritaGuru()'>muat ulang</a>]</font>
				</td>
			</tr>
			<tr style='background-color: #e1ffe1; height: 195px;'>
				<td align='left' valign='top'>
					<div id='divBeritaGuru' style='overflow: auto; height: 195px;'>
						
						<input type='hidden' id='maxListBGuruTs' value='0'>
						<table id='tabListBGuru' width='100%' cellspacing='7' cellpadding='0'>
						
						<tbody>
							
						</tbody>
						<tfoot>
		
						</tfoot>
						</table>
						
					</div>
				</td>
			</tr>
			</table>
			
		</td>
		<td align='top' valign='top' width='33%'>
			
			<table border='0' cellpadding='5' width='100%' style='height: 210px'>
			<tr height='26'>
				<td align='left' valign='middle' style='background-color: #808000; color: #fff; font-weight: bold;'>
				BERITA SISWA
				&nbsp;&nbsp;
				<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshListBeritaSiswa()'>muat ulang</a>]</font>
				</td>
			</tr>
			<tr style='background-color: #ffffec; height: 195px'>
				<td align='left' valign='top'>
					<div id='divBeritaSiswa' style='overflow: auto; height: 195px;'>
						
						<input type='hidden' id='maxListBSiswaTs' value='0'>
						<table id='tabListBSiswa' width='100%' cellspacing='7' cellpadding='0'>
						<tbody>
							
						</tbody>
						<tfoot>
		
						</tfoot>
						</table>
						
					</div>
				</td>
			</tr>
			</table>
			
		</td>
	</tr>	
	</table>
		
	<table border='0' cellpadding='5' width='99%' align='center' style='height: 230px'>
	<tr height='26'>
		<td align='left' valign='middle' style='background-color: #400000; color: #fff; font-weight: bold;'>
		SURAT MASUK/KELUAR
		&nbsp;&nbsp;
		Jenis: <select id='jenissurat' onchange='changeJenisSurat()' onkeyup='changeJenisSurat()'>
			<option value='ALL'>(Semua)</option>
			<option value='IN'>Surat Masuk</option>
			<option value='OUT'>Surat Keluar</option>
		</select>
		&nbsp;&nbsp;
		<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshSurat()'>muat ulang</a>]</font>
		</td>
	</tr>
	<tr style='background-color: #efefef; height: 215px'>
		<td align='left' valign='top'>
			<div id='divSurat' style='overflow: auto; ; height: 215px'>
				
				<input type='hidden' id='minListSuratTs' value='0'>
				<table id='tabListSurat' border='1'
					   style='border-width: 1px; border-color: #bbb; border-collapse: collapse;'
					   width='100%' cellspacing='0' cellpadding='2'>
				<thead>
					<tr height='22'>
						<td align='center' valign='middle' class='header' width='15%'>Tanggal/Nomor</td>
						<td align='center' valign='middle' class='header' width='*'>Perihal/Kategori</td>
						<td align='center' valign='middle' class='header' width='7%'>Jumlah<br>Berkas</td>
						<td align='center' valign='middle' class='header' width='16%'>Sumber</td>
						<td align='center' valign='middle' class='header' width='16%'>Tujuan</td>
						<td align='center' valign='middle' class='header' width='10%'>Komentar</td>
						<td align='center' valign='middle' class='header' width='3%'>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
				<tfoot>

				</tfoot>
				</table>
						
			</div>
		</td>
	</tr>
	</table>
	
</td>
<td width='300' align='left' valign='top'
	style='background-color: #e6f2ff;'>
	
	<table id='secInfo1' border='0' cellpadding='0' width='100%'>
	<tr height='16'>
		<td align='left' valign='top' style='background-color: #004080; color: #fff; font-weight: bold;'>
		PESAN
		&nbsp;&nbsp;
		<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshListPesan()'>muat ulang</a>]</font>
		</td>
	</tr>
	<tr>
		<td align='left' valign='top'>
			<div id='divSecInfo1' style='overflow: auto;'>
				<input type='hidden' id='minListPesanId' value='0'>
				<table id='tabListPesan' width='100%' cellspacing='7' cellpadding='0'>
				<tbody>
					
				</tbody>
				<tfoot>

				</tfoot>
				</table>
			</div>
		</td>
	</tr>
	</table>
	
	<table id='secInfo2' border='0' cellpadding='0' width='100%'>
	<tr height='16'>
		<td align='left' valign='top' style='background-color: #004080; color: #fff; font-weight: bold;'>
		AGENDA
		&nbsp;&nbsp;
		<font style='color: #80ff00;'>[<a href='#' style='color: #fff; font-weight: normal;' onclick='refreshListAgenda()'>muat ulang</a>]</font>
		</td>
	</tr>
	<tr>
		<td align='left' valign='top'>
			<div id='divSecInfo2' style='overflow: auto;'>
				<input type='hidden' id='maxListAgendaTs' value='0'>
				<table id='tabListAgenda' width='100%' cellspacing='7' cellpadding='0'>
				<tbody>
					
				</tbody>
				<tfoot>

				</tfoot>
				</table>
			</div>
		</td>
	</tr>
	</table>
	
	<table id='secInfo3' border='0' cellpadding='0' width='100%'>
	<tr height='16'>
		<td align='left' valign='top' style='background-color: #004080; color: #fff; font-weight: bold;'>
		CATATAN SISWA
		</td>
	</tr>
	<tr>
		<td align='left' valign='top'>
			<div id='divSecInfo3' style='overflow: auto;'>
				<input type='hidden' id='minListNotesId' value='0'>
<?php 			echo "Departemen: ";
				ShowCbDepartemen(); ?>					
				<table id='tabListNotes' width='100%' cellspacing='7' cellpadding='0'>
				<tbody>
					
				</tbody>
				<tfoot>

				</tfoot>
				</table>
			</div>
		</td>
	</tr>
	</table>
	
	<table id='secInfo4' border='0' cellpadding='0' width='100%'>
	<tr height='16'>
		<td align='left' valign='top' style='background-color: #004080; color: #fff; font-weight: bold;'>
		ULANG TAHUN
		</td>
	</tr>
	<tr>
		<td align='left' valign='top'>
			<div id='divSecInfo4' style='overflow: auto;'>
				<table id='tabListBirthday' width='100%' cellspacing='7' cellpadding='0'>
				<thead>
					Tanggal:
					<span id='divCbTahun'><?php ShowCbTahun(); ?></span>
					<span id='divCbBulan'><?php ShowCbBulan(); ?></span>
					<span id='divCbTanggal'><?php ShowCbTanggal(); ?></span><br>
					<span id='debug'></span>
				</thead>	
				<tbody>
					
				</tbody>
				<tfoot>

				</tfoot>
				</table>
			</div>
		</td>
	</tr>
	</table>
</td>	
</tr>	
</table>	
</body>
</html>
<?php
CloseDb();
?>