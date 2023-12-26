psb_changeDepartemenEdit = function() {
	
	var dept = $("#psb_departemen").val();
	var no = $("#psb_nopendaftaran").val();
	$.ajax({
        url : 'psb/psb.edit.ajax.php?op=setProsesPsb&dept='+dept,
        type: 'get',
        success : function(html) {
            $('#psb_divProses').html(html);
			
			var proses = $("#psb_proses").val();
			$.ajax({
				url : 'psb/psb.edit.ajax.php?op=setKelompokPsb&proses='+proses,
				type: 'get',
				success : function(html) {
					$('#psb_divKelompok').html(html);
				}
			});
			
			$.ajax({
				url : 'psb/psb.edit.ajax.php?op=setSumbanganPsb&proses='+proses+'&no='+no,
				type: 'get',
				success : function(html) {
					$('#psb_divSumbangan').html(html);
				}
			});
			
			$.ajax({
				url : 'psb/psb.edit.ajax.php?op=setUjianPsb&proses='+proses+'&no='+no,
				type: 'get',
				success : function(html) {
					$('#psb_divNilaiUjian').html(html);
				}
			});
        }
    });
	
}

psb_changeProsesEdit = function() {
	
	var proses = $("#psb_proses").val();
	$.ajax({
		url : 'psb/psb.edit.ajax.php?op=setKelompokPsb&proses='+proses,
		type: 'get',
		success : function(html) {
			$('#psb_divKelompok').html(html);
		}
	})
}

psb_SimpanEdit = function() {
	
	if (!psb_ValidateInput())
		return;
	
	var data = "";
	var inputs = $('#psb_form :input:not([type=radio],[type=checkbox])');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#psb_form :input[type=radio]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	inputs = $('#psb_form :input[type=checkbox]:checked');
	inputs.each(function() {
		if (data != "")
			data += "&";
		
		data += this.name + "=" + $(this).val();
	});
	
	$.ajax({
		type: 'POST',
		url: 'psb/psb.edit.save.php',
		data: data,
		success: function(html) {
			$('#psb_content').html(html);
		},
		error: function(xhr, options, error) {
			$('#psb_content').html(xhr.responseText);
			//alert(xhr.responseText);
		}
	});
}