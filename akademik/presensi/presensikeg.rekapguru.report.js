function cetak()
{
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var nip = $('#nip').val();
	
	newWindow('presensikeg.rekapguru.cetak.php?bulan='+bulan+'&tahun='+tahun+'&nip='+nip,
			  'CetakRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var nip = $('#nip').val();
	
	newWindow('presensikeg.rekapguru.excel.php?bulan='+bulan+'&tahun='+tahun+'&nip='+nip,
			  'ExcelRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function detail(idkegiatan)
{
	var nip = $('#nip').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.rekapguru.detail.php?idkegiatan='+idkegiatan+'&nip='+nip+'&bulan='+bulan+'&tahun='+tahun,
			  'DetailRekapPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}