psb_StatusPsbChangeDepartemen = function() {

	var dept = $("#psb_departemen").val();
	$.ajax({
        url : 'psb/psb.status.ajax.php?op=setProsesPsb&dept='+dept,
        type: 'get',
        success : function(html) {
            $('#psb_divProses').html(html);
			
			var proses = $("#psb_proses").val();
			$.ajax({
				url : 'psb/psb.status.ajax.php?op=setKelompokPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					$('#psb_divKelompok').html(html);
				}
			})
			
			psb_StatusPsbBlank();
        }
    })	
}

psb_StatusPsbChangeProses = function() {
	
	psb_StatusPsbBlank();
	
}

psb_StatusPsbBlank = function() {
	
	$('#psb_divStatus').html("&nbsp;");
}

psb_ShowStatusPsb = function() {
	
	psb_ResizeStatusDiv();
	
	var idproses = parseInt($("#psb_proses").val());
	if (idproses == -1)
		return;
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.status.ajax.php',
		data: "op=getStatusPsb&idproses="+idproses+"&page=1",
		success: function(html) {
			$('#psb_divStatus').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divStatus').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});

}

psb_StatusPsbGotoPage = function(idproses, page, nPage) {
	
	if (page <= 0 || page > nPage)
		return;
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.status.ajax.php',
		data: "op=getStatusPsb&idproses="+idproses+"&page="+page,
		success: function(html) {
			$('#psb_divStatus').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divStatus').html(xhr.responseText);
		}
	});
	
}

psb_StatusPsbChangePage = function(idproses) {
	
	var page = $("#psb_StatusPsbPage").val();
	$.ajax({
		type: 'POST',
		url: 'psb/psb.status.ajax.php',
		data: "op=getStatusPsb&idproses="+idproses+"&page="+page,
		success: function(html) {
			$('#psb_divStatus').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_divStatus').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
	
}
