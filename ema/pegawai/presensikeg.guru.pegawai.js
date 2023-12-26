$(function() {
	$( "#tabs" ).tabs();
});

showDataPegawai = function()
{
	var idkegiatan = $('#idkegiatan').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var bagian = $('#cbBagian').val();
	
	$.ajax({
		url: "presensikeg.guru.ajax.php",
		data: "op=getpegawai&idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&bagian="+bagian,
		success: function(html) {
			$("#divPegawai").html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	})
}

showReport = function(idkegiatan, bulan, tahun, nip)
{
	parent.report.location.href = "presensikeg.guru.report.php?idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&nip="+nip;
}

txKeywordKeyUp = function(event)
{
	if (event.keyCode != 13)
		return;
	
	if ($.trim($('#txKeyword').val()).length < 3)
	{
		$('#lbError').html('Panjang kata kunci minimal 3 karakter');
		$('#txKeyword').focus();
		return;
	}
	
	doSearch();
}

btCariClick = function()
{
	if ($.trim($('#txKeyword').val()).length < 3)
	{
		$('#lbError').html('Panjang kata kunci minimal 3 karakter');
		$('#txKeyword').focus();
		return;
	}
	
	doSearch();
}

doSearch = function()
{
	$('#lbError').html('');
	$('#txKeyword').prop('disabled', true);
	
	var idkegiatan = $('#idkegiatan').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var filter = $('#cbFilter').val();
	var keyword = $.trim($('#txKeyword').val());
	
	$.ajax({
		url: "presensikeg.guru.ajax.php",
		data: "op=searchpegawai&idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&filter="+filter+"&keyword="+keyword,
		success: function(html) {
			$("#divPegawai2").html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	})
	
	$('#txKeyword').prop('disabled', false);
}