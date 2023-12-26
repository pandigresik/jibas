ptkastat_perpus_change = function()
{
	$('#ptkastat_content').html('');
}

ptkastat_showstat = function()
{
	var perpus = $('#ptkastat_perpus').val();
	var bln1 = $('#ptkastat_bulan1').val();
	var thn1 = $('#ptkastat_tahun1').val();
	var bln2 = $('#ptkastat_bulan2').val();
	var thn2 = $('#ptkastat_tahun2').val();
	var jum = $('#ptkastat_jumlah').val();
	
	$.ajax({
        url: 'pustaka/pustaka.stat.ajax.php',
		data: 'op=showstat&perpus='+perpus+'&bln1='+bln1+'&thn1='+thn1+'&bln2='+bln2+'&thn2='+thn2+'&jum='+jum,
        type: 'get',
        success : function(html) {
            $('#ptkastat_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkastat_showdetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.stat.ajax.php',
		data: 'op=getdetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkastat_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

ptkastat_hidedetail = function(cnt, idpustaka)
{
	$.ajax({
        url: 'pustaka/pustaka.stat.ajax.php',
		data: 'op=hidedetail&cnt='+cnt+'&idpustaka='+idpustaka,
        type: 'get',
        success : function(html) {
            $('#ptkastat_divdetail_' + cnt).html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}