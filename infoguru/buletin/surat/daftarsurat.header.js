cbDept_OnChange = function ()
{
    var departemen = $('#cbDept').val();
	
    $.ajax({
		type: 'POST',
		url: 'daftarsurat.header.ajax.php?op=getkategori&departemen='+departemen,
		success: function(html) {			
            $("#divCbKategori").html(html);
			parent.content.location.href = "daftarsurat.blank.php";
		},
		error: function(xhr, options, error) {
            alert(xhr.responseText);
		}
	});
}

showBlank = function()
{
	parent.content.location.href = "daftarsurat.blank.php";
}

doList = function()
{
	if (!$("#cbDept").val() || !$("#cbKategori").val())
		return;
	
	var departemen = $('#cbDept').val();
	var jenis = $('#cbJenis').val();
	var kategori = $('#cbKategori').val();
	var bulan1 = $('#cbBulan1').val();
	var tahun1 = $('#cbTahun1').val();
	var bulan2 = $('#cbBulan2').val();
	var tahun2 = $('#cbTahun2').val();
	
	parent.content.location.href = "daftarsurat.content.php?departemen="+departemen+"&jenis="+jenis+"&kategori="+kategori+"&bulan1="+bulan1+"&tahun1="+tahun1+"&bulan2="+bulan2+"&tahun2="+tahun2+"&searchby=0";
}

doSearch = function()
{
	if (!$("#cbDept").val() || !$("#cbKategori").val())
		return;
	
	var keyword = $.trim($('#txKeyword').val());
	if (keyword.length < 3)
	{
		alert('Panjang kata kunci pencarian minimal 3 karakter!')
		$('#txKeyword').focus();
		
		return;
	}
	
	var departemen = $('#cbDept').val();
	var jenis = $('#cbJenis').val();
	var kategori = $('#cbKategori').val();
	var bulan1 = $('#cbBulan1').val();
	var tahun1 = $('#cbTahun1').val();
	var bulan2 = $('#cbBulan2').val();
	var tahun2 = $('#cbTahun2').val();
	var searchby = $('#cbSearchBy').val();
	
	parent.content.location.href = "daftarsurat.content.php?departemen="+departemen+"&jenis="+jenis+"&kategori="+kategori+"&bulan1="+bulan1+"&tahun1="+tahun1+"&bulan2="+bulan2+"&tahun2="+tahun2+"&searchby="+searchby+"&keyword="+keyword;
}