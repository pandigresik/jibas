clearContent = function()
{
	parent.footer.location.href = "../pegawai/presensikeg.blank.php";
}

show = function()
{
	var bulan = $("#cbBulan").val();
	var tahun = $("#cbTahun").val();
	
	parent.footer.location.href = "../pegawai/presensikeg.rekapguru.content.php?bulan="+bulan+"&tahun="+tahun;
}