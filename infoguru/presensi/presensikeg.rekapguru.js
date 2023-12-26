changeOption = function()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	
	document.location.href = 'presensikeg.rekapguru.php?bulan='+bulan+'&tahun='+tahun;
}

function cetak()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	
	newWindow('presensikeg.rekapguru.cetak.php?bulan='+bulan+'&tahun='+tahun,
			  'CetakRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	
	newWindow('presensikeg.rekapguru.excel.php?bulan='+bulan+'&tahun='+tahun,
			  'ExcelRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function detail(idkegiatan)
{
	var nip = $('#nip').val();
	var bulan = $('#cbBulan').val();
	var tahun = $('#cbTahun').val();
	
	newWindow('presensikeg.rekapguru.detail.php?idkegiatan='+idkegiatan+'&nip='+nip+'&bulan='+bulan+'&tahun='+tahun,
			  'DetailRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}