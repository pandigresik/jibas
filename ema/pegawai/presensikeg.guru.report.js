function cetak()
{
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var idkegiatan = $('#idkegiatan').val();
	var nip = $('#nip').val();
	
	newWindow('presensikeg.guru.cetak.php?bulan='+bulan+'&tahun='+tahun+'&idkegiatan='+idkegiatan+'&nip='+nip,
			  'CetakLaporanPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var idkegiatan = $('#idkegiatan').val();
	var nip = $('#nip').val();
	
	newWindow('presensikeg.guru.excel.php?bulan='+bulan+'&tahun='+tahun+'&idkegiatan='+idkegiatan+'&nip='+nip,
			  'ExcelLaporanPresensiKegiatanGuru',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}
