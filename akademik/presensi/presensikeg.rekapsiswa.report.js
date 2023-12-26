function cetak()
{
	var nis = $('#nis').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.rekapsiswa.cetak.php?nis='+nis+'&bulan='+bulan+'&tahun='+tahun,
			  'CetakRekapPresensiKegiatanSiswa',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var nis = $('#nis').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.rekapsiswa.excel.php?nis='+nis+'&bulan='+bulan+'&tahun='+tahun,
			  'ExcelRekapPresensiKegiatanSiswa',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function detail(idkegiatan)
{
	var nis = $('#nis').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.rekapsiswa.detail.php?idkegiatan='+idkegiatan+'&nis='+nis+'&bulan='+bulan+'&tahun='+tahun,
			  'DetailRekapPresensiKegiatanSiswa',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}
