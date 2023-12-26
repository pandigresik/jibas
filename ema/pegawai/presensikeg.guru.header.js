changeCbAktif = function()
{
	parent.footer.location.href = "../pegawai/presensikeg.blank.php";
	
	var aktif = $("#cbAktif").val();
	$.ajax({
        url: "presensikeg.guru.ajax.php",
        data: "op=getcbactivity&aktif="+aktif,
        success: function(html) {
			$("#divCbKegiatan").html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

clearContent = function()
{
	parent.footer.location.href = "../pegawai/presensikeg.blank.php";
}

show = function()
{
	if ($('#cbKegiatan option').length == 0)
	{
		alert('Tidak ada data kegiatan!');
		return;
	}
	
	var idkegiatan = $("#cbKegiatan").val();
	var bulan = $("#cbBulan").val();
	var tahun = $("#cbTahun").val();
	
	parent.footer.location.href = "../pegawai/presensikeg.guru.content.php?idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun;
}