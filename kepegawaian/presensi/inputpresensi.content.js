ValidateEdit = function(no)
{
	var status = $("#status" + no).val();
	if (status == 1)
	{
		return Validator.CheckHour($("#jammasuk" + no), "Jam Masuk") &&
			   Validator.CheckMinute($("#menitmasuk" + no), "Menit Masuk") &&
			   Validator.CheckHour($("#jampulang" + no), "Jam Pulang") &&
			   Validator.CheckMinute($("#menitpulang" + no), "Menit Pulang") &&
			   confirm("Data sudah benar?");
	}
	
	return confirm("Data sudah benar?");
}

ValidateInput = function(no)
{
	var status = $("#status" + no).val();
	if (status == 1)
	{
		return Validator.CheckHour($("#jammasuk" + no), "Jam Masuk") &&
			   Validator.CheckMinute($("#menitmasuk" + no), "Menit Masuk") &&
			   Validator.CheckHour($("#jampulang" + no), "Jam Pulang") &&
			   Validator.CheckMinute($("#menitpulang" + no), "Menit Pulang");
	}
	
	return true;
}

RefreshPage = function()
{
	
}

Delete = function()
{
	$("#btHapus").attr("disabled", true);
	if (!confirm("Apakah anda yakin akan menghapus data presensi ini?"))
	{
		$("#btHapus").attr("disabled", false);
		return;
	}
	
	var tglpresensi = $("#tglpresensi").val();
	$.ajax({
        type: "POST",
        url: "inputpresensi.remove.php",
        data: "tglpresensi="+tglpresensi,
        success: function(response) {
			document.location.href = "blank.php";
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#infosave" + no).html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });

}

SaveNew = function()
{
	var ndata = $("#ndata").val();
	for (no = 1; no <= ndata; no++)
	{
		if (!ValidateInput(no))
			return;
	}

	$("#btSimpan").attr("disabled", true);	
	if (!confirm("Data sudah benar?"))
	{
		$("#btSimpan").attr("disabled", false);
		return;
	}	

	var tglpresensi = $("#tglpresensi").val();
	
	data = "ndata="+ndata+"&tglpresensi="+tglpresensi;	
	for (no = 1; no <= ndata; no++)
	{
		var status = $("#status" + no).val();
		var nip = $("#nip" + no).val();
		var jammasuk = $("#jammasuk" + no).val();
		var menitmasuk = $("#menitmasuk" + no).val();
		var jampulang = $("#jampulang" + no).val();
		var menitpulang = $("#menitpulang" + no).val();
		var keterangan = escape($("#ket" + no).val());
		
		data += "&nip"+no+"="+nip+"&jammasuk"+no+"="+jammasuk+
			    "&menitmasuk"+no+"="+menitmasuk+"&jampulang"+no+"="+jampulang+
			    "&menitpulang"+no+"="+menitpulang+"&keterangan"+no+"="+keterangan+
			    "&status"+no+"="+status;
	}
	
	$.ajax({
        type: "POST",
        url: "inputpresensi.content.save.new.php",
        data: data,
        success: function(response) {
			alert('Data telah disimpan!');
			
			var tahun = $("#tahun").val();
			var bulan = $("#bulan").val();
			var tanggal = $("#tanggal").val();
			document.location.href = "inputpresensi.content.php?tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#infosave" + no).html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });
}

SaveEdit = function(no)
{
	var status = $("#status" + no).val();
	if (status == -1) 
		return;
	
	if (!ValidateEdit(no))
		return;
	
	var replid = $("#replid" + no).val();
	var nip = $("#nip" + no).val();
	var jammasuk = $("#jammasuk" + no).val();
	var menitmasuk = $("#menitmasuk" + no).val();
	var jampulang = $("#jampulang" + no).val();
	var menitpulang = $("#menitpulang" + no).val();
	var keterangan = escape($("#ket" + no).val());
	var tglpresensi = $("#tglpresensi").val();
	
	$.ajax({
        type: "POST",
        url: "inputpresensi.content.save.edit.php",
        data: "replid="+replid+"&nip="+nip+"&jammasuk="+jammasuk+
			  "&menitmasuk="+menitmasuk+"&jampulang="+jampulang+
			  "&menitpulang="+menitpulang+"&keterangan="+keterangan+
			  "&status="+status+"&tglpresensi="+tglpresensi,
        success: function(response) {
			$("#replid" + no).val(response);
			$("#info" + no).html("OK");
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#info" + no).html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });
}

CopyBelowIn = function()
{
	var ndata = $("#ndata").val();
	var jam1 = $.trim($("#jammasuk1").val());
	var menit1 = $.trim($("#menitmasuk1").val());
	
	if (jam1.length == 0 || menit1.length == 0)
		return;
	
	for (no = 2; no <= ndata; no++)
	{
		$('#jammasuk' + no).val(jam1);
		$('#menitmasuk' + no).val(menit1);
	}
}

CopyBelowOut = function()
{
	var ndata = $("#ndata").val();
	var jam1 = $.trim($("#jampulang1").val());
	var menit1 = $.trim($("#menitpulang1").val());
	
	if (jam1.length == 0 || menit1.length == 0)
		return;
	
	for (no = 2; no <= ndata; no++)
	{
		$('#jampulang' + no).val(jam1);
		$('#menitpulang' + no).val(menit1);
	}
}