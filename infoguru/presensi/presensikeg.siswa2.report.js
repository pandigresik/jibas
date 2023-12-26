function cetak()
{
	var nis = $('#nis').val();
	var idkegiatan = $('#idkegiatan').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.siswa2.cetak.php?nis='+nis+'&idkegiatan='+idkegiatan+'&bulan='+bulan+'&tahun='+tahun,
			  'CetakLaporanPresensiKegiatanSiswa',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel()
{
	var nis = $('#nis').val();
	var idkegiatan = $('#idkegiatan').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	
	newWindow('presensikeg.siswa2.excel.php?nis='+nis+'&idkegiatan='+idkegiatan+'&bulan='+bulan+'&tahun='+tahun,
			  'ExcelLaporanPresensiKegiatanSiswa',
			  '790','650',
			  'resizable=1,scrollbars=1,status=0,toolbar=0');
}
