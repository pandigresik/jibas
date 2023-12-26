ptkacari_perpus_change = function()
{
    $('#ptkacari_content').html('');
}

ptkacari_pilih_change = function()
{
    $('#ptkacari_content').html('');
}

ptkacari_search = function()
{
    var perpus = $('#ptkacari_perpus').val();
	var pilih = $('#ptkacari_pilih').val();
	var keyword = $('#ptkacari_keyword').val();
    
    if ($.trim(keyword).length < 5)
    {
        alert('Kata kunci pencarian minimal 5 karakter!')
        return;
    }

	$.ajax({
        url: 'pustaka/pustaka.cari.ajax.php',
		data: 'op=search&perpus='+perpus+'&pilih='+pilih+'&keyword='+keyword,
        type: 'get',
        success : function(html) {
            $('#ptkacari_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })

}

ptkacari_showdetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.cari.ajax.php',
		data: 'op=getdetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkacari_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkacari_hidedetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.cari.ajax.php',
		data: 'op=hidedetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkacari_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkacari_goto = function(perpus, pilih, keyword, halaman)
{
	$.ajax({
        url: 'pustaka/pustaka.cari.ajax.php',
		data: 'op=search&perpus='+perpus+'&pilih='+pilih+'&keyword='+keyword+'&halaman='+halaman,
        type: 'get',
        success : function(html) {
            $('#ptkacari_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkacari_pageno_change = function(perpus, pilih, keyword)
{
	var halaman = $('#ptkacari_pageno').val();
	
	$.ajax({
        url: 'pustaka/pustaka.cari.ajax.php',
		data: 'op=search&perpus='+perpus+'&pilih='+pilih+'&keyword='+keyword+'&halaman='+halaman,
        type: 'get',
        success : function(html) {
            $('#ptkacari_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}