$(function() {
    $( "#tabs" ).tabs();
	GetReportList('tabHadir', 1);
	GetReportList('tabIzin', 2);
	GetReportList('tabSakit', 3);
	GetReportList('tabCuti', 4);
	GetReportList('tabAlpa', 5);
	GetReportList('tabBebas', 6);
});

function cetak()
{
    var tahun30 = $("#tahun30").val();
    var bulan30 = $("#bulan30").val();
    var tanggal30 = $("#tanggal30").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tanggal = $("#tanggal").val();
    var addr = "rekapall.cetak.php?tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
    
    newWindow(addr, 'CetakPresensiSemua','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function excel()
{
    var tahun30 = $("#tahun30").val();
    var bulan30 = $("#bulan30").val();
    var tanggal30 = $("#tanggal30").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tanggal = $("#tanggal").val();
    var addr = "rekapall.excel.php?tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal;
    
    newWindow(addr, 'ExcelPresensiSemua','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function GetReportList(tabName, status)
{
	var tahun30 = $("#tahun30").val();
    var bulan30 = $("#bulan30").val();
    var tanggal30 = $("#tanggal30").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tanggal = $("#tanggal").val();
	
	$.ajax({
        type: "POST",
        url: "rekapall.reportlist.php",
        data: "status="+status+"&tahun30="+tahun30+"&bulan30="+bulan30+"&tanggal30="+tanggal30+"&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal,
        success: function(response) {
			$('#' + tabName).html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
			alert("ERROR: " + xhr.responseText);
            Logger.HandleError(xhr.responseText);
        }
    });
}