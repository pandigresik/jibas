clearContent = function()
{
	parent.footer.location.href = "../presensi/presensikeg.blank.php";
}

show = function()
{
	var bulan = $("#cbBulan").val();
	var tahun = $("#cbTahun").val();
	
	parent.footer.location.href = "../presensi/presensikeg.rekapsiswa.content.php?bulan="+bulan+"&tahun="+tahun;
}