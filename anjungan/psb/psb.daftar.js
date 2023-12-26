psb_ShowDaftarPsb = function() {
	
	psb_ResizeDaftarDiv();
	
	var idkelompok = parseInt($("#psb_kelompok").val());
	if (idkelompok == -1)
		return;
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.daftar.ajax.php',
		data: "op=getDaftarPsb&idkelompok="+idkelompok+"&page=1",
		success: function(html) {
			$('#psb_divDaftar').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divDaftar').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});

}

psb_DaftarPsbGotoPage = function(idkelompok, page, nPage) {
	
	if (page <= 0 || page > nPage)
		return;
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.daftar.ajax.php',
		data: "op=getDaftarPsb&idkelompok="+idkelompok+"&page="+page,
		success: function(html) {
			$('#psb_divDaftar').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divDaftar').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
	
}

psb_DaftarPsbChangePage = function(idkelompok) {
	
	var page = $("#psb_DaftarPsbPage").val();
	$.ajax({
		type: 'POST',
		url: 'psb/psb.daftar.ajax.php',
		data: "op=getDaftarPsb&idkelompok="+idkelompok+"&page="+page,
		success: function(html) {
			$('#psb_divDaftar').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divDaftar').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
	
}

psb_DaftarPsbUbah = function(nocalon, namacalon, idkelompok, page, npage) {
	
	nocalon = escape(nocalon);
	namacalon = escape(namacalon);
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.daftar.ajax.php',
		data: "op=doChangeData&nocalon="+nocalon+"&namacalon="+namacalon+"&idkelompok="+idkelompok+"&page="+page+"&npage="+npage,
		success: function(html) {
			$('#psb_divDaftar').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divDaftar').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
}

psb_CheckPinCalon = function() {
	
	var nocalon = escape($("#psb_UbahDataNoCalon").val());
	var namacalon = escape($("#psb_UbahDataNamaCalon").val());
	var page = $("#psb_UbahDataPage").val();
	var npage = $("#psb_UbahDataNPage").val();
	var idkelompok = $("#psb_UbahDataIdKelompok").val();
	var pincalon = $.trim($("#psb_PinCalon").val());
	
	if (pincalon.length == 0)
		return;
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.daftar.ajax.php',
		data: "op=doCheckPin&nocalon="+nocalon+"&namacalon="+namacalon+"&idkelompok="+idkelompok+"&page="+page+"&npage="+npage+"&pincalon="+pincalon,
		success: function(html) {
			$('#psb_content').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_CheckPinInfo').html(xhr.responseText);
		}
	});
}

psb_BatalUbahDataCalon = function() {
	
	var page = $("#psb_UbahDataPage").val();
	var npage = $("#psb_UbahDataNPage").val();
	var idkelompok = $("#psb_UbahDataIdKelompok").val();
	
	psb_DaftarPsbGotoPage(idkelompok, page, npage);
}

/*
psb_CheckPinCalon = function() {
	
	var page = $("#psb_UbahDataPage").val();
	var npage = $("#psb_UbahDataNPage").val();
	var idkelompok = ("#psb_UbahDataIdKelompok").val();
	
	psb_DaftarPsbGotoPage(idkelompok, page, npage);
}
*/

psb_DaftarPsbChangeDepartemen = function() {

	var dept = $("#psb_departemen").val();
	$.ajax({
        url : 'psb/psb.daftar.ajax.php?op=setProsesPsb&dept='+dept,
        type: 'get',
        success : function(html) {
            $('#psb_divProses').html(html);
			
			var proses = $("#psb_proses").val();
			$.ajax({
				url : 'psb/psb.input.ajax.php?op=setKelompokPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					$('#psb_divKelompok').html(html);
				}
			})
			
			psb_DaftarPsbBlank();
        }
    })	
}

psb_DaftarPsbChangeProses = function() {
	
	var proses = $("#psb_proses").val();
	$.ajax({
		url : 'psb/psb.daftar.ajax.php?op=setKelompokPsb&proses='+proses,
		type: 'get',
		success : function(html) {
			$('#psb_divKelompok').html(html);
			psb_DaftarPsbBlank();
		}
	})
	
}

psb_DaftarPsbChangeKelompok = function() {
	
	psb_DaftarPsbBlank();
	
}

psb_DaftarPsbBlank = function() {
	
	$('#psb_divDaftar').html("&nbsp;");
}

psb_EditDonePsbClicked = function(idkelompok, page, npage) {

	//psb_DaftarPsbGotoPage(idkelompok, page, npage);
	psb_DaftarPsbClicked();
	
}