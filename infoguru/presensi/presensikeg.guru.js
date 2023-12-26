changeOption = function()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	var idkegiatan = $('#cbKegiatan').val();
	
	document.location.href = 'presensikeg.guru.php?bulan='+bulan+'&tahun='+tahun+'&idkegiatan='+idkegiatan;
}

function cetak()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	var idkegiatan = $('#cbKegiatan').val();
	
	newWindow('presensikeg.guru.cetak.php?bulan='+bulan+'&tahun='+tahun+'&idkegiatan='+idkegiatan,
			  'CetakLaporanPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	var idkegiatan = $('#cbKegiatan').val();
	
	newWindow('presensikeg.guru.excel.php?bulan='+bulan+'&tahun='+tahun+'&idkegiatan='+idkegiatan,
			  'ExcelLaporanPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}
