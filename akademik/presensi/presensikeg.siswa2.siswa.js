$(function() {
	$( "#tabs" ).tabs();
});
 
changeCbDepartemen = function()
{
	parent.report.location.href = "presensikeg.blank.php";
	
	$(function() {
		var departemen = $('#cbDepartemen').val();
		var idtingkat = 0;
		var idkelas = 0;
	
		// Get CbTingkat
		$.ajax({
			url: "presensikeg.siswa2.ajax.php",
			data: "op=getcbtingkat&departemen="+departemen,
			async: false,
			success: function(html) {
				var json = $.parseJSON(html);
				$("#divCbTingkat").html(json.selection);
				idtingkat = json.value;
			},
			error: function(xhr, options, error) {
				alert(xhr.responseText);
			}
		});
		
		// No Data In CbTingkat!
		if ($('#cbTingkat option').length == 0)
		{
			$('#divCbKelas').html("");
			$('#divSiswa').html("");
			
			return;
		}
		
		// Get CbKelas
		$.ajax({
			url: "presensikeg.siswa2.ajax.php",
			data: "op=getcbkelas&idtingkat="+idtingkat,
			async: false,
			success: function(html) {
				var json = $.parseJSON(html);
				$("#divCbKelas").html(json.selection);
				idkelas = json.value;
			},
			error: function(xhr, options, error) {
				alert(xhr.responseText);
			}
		})
		
		// No Data In CbKelas!
		if ($('#cbKelas option').length == 0)
		{
			$('#divSiswa').html("");
			
			return;
		}
		
		showDataSiswa();
	});	
}

changeCbTingkat = function()
{
	parent.report.location.href = "presensikeg.blank.php";
	
	$(function() {
		var idtingkat = $('#cbTingkat').val();
		var idkelas = 0;
	
		// Get CbKelas
		$.ajax({
			url: "presensikeg.siswa2.ajax.php",
			data: "op=getcbkelas&idtingkat="+idtingkat,
			async: false,
			success: function(html) {
				var json = $.parseJSON(html);
				$("#divCbKelas").html(json.selection);
				idkelas = json.value;
			},
			error: function(xhr, options, error) {
				alert(xhr.responseText);
			}
		})
		
		// No Data In CbKelas!
		if ($('#cbKelas option').length == 0)
		{
			$('#divSiswa').html("");
			
			return;
		}
		
		showDataSiswa();
	});	
}

changeCbKelas = function()
{
	parent.report.location.href = "presensikeg.blank.php";
	
	// No Data In CbKelas!
	if ($('#cbKelas option').length == 0)
	{
		$('#divSiswa').html("");
		
		return;
	}
	
	showDataSiswa();
}

showDataSiswa = function()
{
	var idkegiatan = $('#idkegiatan').val();
	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();
	var idkelas = $('#cbKelas').val();
	
	$.ajax({
		url: "presensikeg.siswa2.ajax.php",
		data: "op=getsiswa&idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&idkelas="+idkelas,
		success: function(html) {
			$("#divSiswa").html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	})
}

showReport = function(idkegiatan, bulan, tahun, nis)
{
	parent.report.location.href = "presensikeg.siswa2.report.php?idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&nis="+nis;
}

cbFilterChange = function()
{
	$('#txKeyword').val('');
	$('#lbError').html('');
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
		url: "presensikeg.siswa2.ajax.php",
		data: "op=searchsiswa&idkegiatan="+idkegiatan+"&bulan="+bulan+"&tahun="+tahun+"&filter="+filter+"&keyword="+keyword,
		success: function(html) {
			$("#divSiswa2").html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	})
	
	$('#txKeyword').prop('disabled', false);
}
