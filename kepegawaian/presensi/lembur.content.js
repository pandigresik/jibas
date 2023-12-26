InputLembur = function()
{
	var tahun1 = $("#tahun1").val();
	var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var tahun2 = $("#tahun2").val();
	var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
	
	var addr = "lembur.input.php?tahun1="+tahun1+"&bulan1="+bulan1+"&tanggal1="+tanggal1+"&tahun2="+tahun2+"&bulan2="+bulan2+"&tanggal2="+tanggal2;
	newWindow(addr, 'InputLembur','900','600','resizable=1,scrollbars=1,status=0,toolbar=0')
}

Cetak = function()
{
	var tahun1 = $("#tahun1").val();
	var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var tahun2 = $("#tahun2").val();
	var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
	
	var addr = "lembur.cetak.php?tahun1="+tahun1+"&bulan1="+bulan1+"&tanggal1="+tanggal1+"&tahun2="+tahun2+"&bulan2="+bulan2+"&tanggal2="+tanggal2;
	newWindow(addr, 'CetakLembur','900','600','resizable=1,scrollbars=1,status=0,toolbar=0')
}

Excel = function()
{
	var tahun1 = $("#tahun1").val();
	var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var tahun2 = $("#tahun2").val();
	var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
	
	var addr = "lembur.excel.php?tahun1="+tahun1+"&bulan1="+bulan1+"&tanggal1="+tanggal1+"&tahun2="+tahun2+"&bulan2="+bulan2+"&tanggal2="+tanggal2;
	newWindow(addr, 'ExcelLembur','90','60','resizable=1,scrollbars=1,status=0,toolbar=0')
}

refreshPage = function()
{
	document.location.reload();
}

ValidateEdit = function(no)
{
	return Validator.CheckHour($("#jammasuk" + no), "Jam Masuk") &&
		   Validator.CheckMinute($("#menitmasuk" + no), "Menit Masuk") &&
		   Validator.CheckHour($("#jampulang" + no), "Jam Pulang") &&
		   Validator.CheckMinute($("#menitpulang" + no), "Menit Pulang") &&
		   confirm("Data sudah benar?");
}

SaveEdit = function(no)
{	
	if (!ValidateEdit(no))
		return;
	
	var replid = $("#replid" + no).val();
	var nip = $("#nip" + no).val();
	var jammasuk = $("#jammasuk" + no).val();
	var menitmasuk = $("#menitmasuk" + no).val();
	var jampulang = $("#jampulang" + no).val();
	var menitpulang = $("#menitpulang" + no).val();
	var keterangan = escape($("#ket" + no).val());
	var tglpresensi = $("#tglpresensi" + no).val();
	
	var data = "replid="+replid+"&nip="+nip+"&jammasuk="+jammasuk+
			   "&menitmasuk="+menitmasuk+"&jampulang="+jampulang+
			   "&menitpulang="+menitpulang+"&keterangan="+keterangan+
			   "&tglpresensi="+tglpresensi;
	$.ajax({
        type: "POST",
        url: "lembur.content.save.edit.php",
        data: data,
        success: function(response) {
			$("#info" + no).html("OK");
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#info" + no).html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });
}

Delete = function(no)
{
	if (!confirm("Apakah anda yakin akan menghapus data presensi lembur pegawai ini?"))
		return;
	
	var replid = $("#replid" + no).val();
	$.ajax({
        type: "POST",
        url: "lembur.content.delete.php",
        data: "replid="+replid,
        success: function(response) {
			document.location.reload();
        },
        error: function(xhr, ajaxOptions, thrownError) {
			$("#info" + no).html("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });

}