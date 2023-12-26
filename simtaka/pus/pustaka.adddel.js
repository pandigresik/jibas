function SetAktif(iddp, newaktif)
{
	if (!confirm("Apakah anda akan mengubah status aktif pustaka ini?"))
		return;
	
	$.ajax({
		url: "pustaka.adddel.ajax.php",
		data: "op=setnewstatus&iddp="+iddp+"&newaktif="+newaktif,
		success: function(html) {
			$('#divStatus' + iddp).html(html);
		},
		error: function(xhr, response, error) {
			alert(xhr.responseText);
		}
	});
}

function ClearCheckBarcode()
{
	var nlist = $('#nlist').val();
	for(var i = 1; i <= nlist; i++)
	{
		$('#ck' + i).attr("checked", false);
	}
}

function PrintBarcode()
{
	var iddplist = "";
	var nlist = $('#nlist').val();
	for(var i = 1; i <= nlist; i++)
	{
		if ($('#ck' + i).is(':checked'))
		{
			var iddp = $('#iddp' + i).val();
			
			if (iddplist != "")
				iddplist += ",";
			iddplist += iddp;
		}
	}
	
	if (iddplist == "")
	{
		alert('Lebih dahulu pilih pustaka yang akan dicetak label & barcode nya');
		return;
	}
	
	var idpustaka = $('#idpustaka').val();
	var idperpustakaan = $('#idperpustakaan').val();
	
	newWindow('pustaka.adddel.printlabel.php?idperpustakaan='+idperpustakaan+'&idpustaka='+idpustaka+'&iddplist='+iddplist,
			  'CetakNomorPustaka2','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
	
}

function DelPustaka(iddp, rowno)
{
	if (!confirm('Apakah anda yakin akan menghapus pustaka ini?'))
		return;
	
	$.ajax({
		url: "pustaka.adddel.ajax.php",
		data: "op=delpustaka&iddp="+iddp,
		success: function(html) {
			$('#row' + rowno).remove();
		},
		error: function(xhr, response, error) {
			alert(xhr.responseText);
		}
	});
}

function RefreshList()
{
	location.reload();
}

function TambahPustaka()
{
	var idpustaka = $('#idpustaka').val();
	var idperpustakaan = $('#idperpustakaan').val();
	
	newWindow('pustaka.adddel.tambahpustaka.php?idperpustakaan='+idperpustakaan+'&idpustaka='+idpustaka,
			  'TambahPustaka','450','450','resizable=1,scrollbars=1,status=0,toolbar=0')
}