ptkadaftar_pilih_change = function()
{
	var choice = $('#ptkadaftar_pilih').val();
	
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=getchoice&choice='+choice,
        type: 'get',
        success : function(html) {
			$('#ptkadaftar_content').html('');
            $('#ptkadaftar_divkriteria').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkadaftar_kriteria_change = function()
{
	$('#ptkadaftar_content').html('');
}

ptkadaftar_perpus_change = function()
{
	$('#ptkadaftar_content').html('');
}

ptkadaftar_showlist = function()
{
	var perpus = $('#ptkadaftar_perpus').val();
	var pilih = $('#ptkadaftar_pilih').val();
	var kriteria = $('#ptkadaftar_kriteria').val();
	
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=showlist&perpus='+perpus+'&pilih='+pilih+'&kriteria='+kriteria,
        type: 'get',
        success : function(html) {
            $('#ptkadaftar_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkadaftar_showdetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=getdetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkadaftar_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkadaftar_hidedetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=hidedetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkadaftar_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkadaftar_goto = function(perpus, pilih, kriteria, halaman)
{
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=showlist&perpus='+perpus+'&pilih='+pilih+'&kriteria='+kriteria+'&halaman='+halaman,
        type: 'get',
        success : function(html) {
            $('#ptkadaftar_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkadaftar_pageno_change = function(perpus, pilih, kriteria)
{
	var halaman = $('#ptkadaftar_pageno').val();
	
	$.ajax({
        url: 'pustaka/pustaka.daftar.ajax.php',
		data: 'op=showlist&perpus='+perpus+'&pilih='+pilih+'&kriteria='+kriteria+'&halaman='+halaman,
        type: 'get',
        success : function(html) {
            $('#ptkadaftar_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}