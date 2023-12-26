changeDate = function()
{
	var nip = document.getElementById("nip").value;
    var tahun30 = document.getElementById("tahun30").value;
    var bulan30 = document.getElementById("bulan30").value;
    var tanggal30 = document.getElementById("tanggal30").value;
    var tahun = document.getElementById("tahun").value;
    var bulan = document.getElementById("bulan").value;
    var tanggal = document.getElementById("tanggal").value;
	
    document.location.href = "daftarpresensi.php?nip="+nip+"&tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
}

refresh = function()
{
	changeDate();
}

cetak = function()
{
	var nip = document.getElementById("nip").value;
    var tahun30 = document.getElementById("tahun30").value;
    var bulan30 = document.getElementById("bulan30").value;
    var tanggal30 = document.getElementById("tanggal30").value;
    var tahun = document.getElementById("tahun").value;
    var bulan = document.getElementById("bulan").value;
    var tanggal = document.getElementById("tanggal").value;
	
	var addr = "daftarpresensi.cetak.php?nip="+nip+"&tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
	newWindow(addr, 'CetakPresensiPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}