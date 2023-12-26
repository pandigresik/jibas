clearReport = function () {
    $("#spReport").html("");
};

showStatTrans = function()
{
    if (!$("#vendor").val())
        return;

    var vendorId = $("#vendor").val();
    var tahun1 = $("#tahun1").val();
    var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var dtStart = tahun1 + "-" + bulan1 + "-" + tanggal1;

    var tahun2 = $("#tahun2").val();
    var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
    var dtEnd = tahun2 + "-" + bulan2 + "-" + tanggal2;

    var req = new RequestFactory();
    req.add("op", "9834732984783924");
    req.add("vendorid", vendorId);
    req.add("dtstart", dtStart);
    req.add("dtend", dtEnd);

    $.ajax({
        url: "stat.trans.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spReport").html(html);
            if ($("#table").length !== 0)
                Tables('table', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

cetakReport = function ()
{
    var vendorId = $("#vendor").val();
    var tahun1 = $("#tahun1").val();
    var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var dtStart = tahun1 + "-" + bulan1 + "-" + tanggal1;

    var tahun2 = $("#tahun2").val();
    var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
    var dtEnd = tahun2 + "-" + bulan2 + "-" + tanggal2;

    var req = new RequestFactory();
    req.add("vendorid", vendorId);
    req.add("dtstart", dtStart);
    req.add("dtend", dtEnd);

    var addr = "stat.trans.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakStatTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var vendorId = $("#vendor").val();
    var tahun1 = $("#tahun1").val();
    var bulan1 = $("#bulan1").val();
    var tanggal1 = $("#tanggal1").val();
    var dtStart = tahun1 + "-" + bulan1 + "-" + tanggal1;

    var tahun2 = $("#tahun2").val();
    var bulan2 = $("#bulan2").val();
    var tanggal2 = $("#tanggal2").val();
    var dtEnd = tahun2 + "-" + bulan2 + "-" + tanggal2;

    var req = new RequestFactory();
    req.add("vendorid", vendorId);
    req.add("dtstart", dtStart);
    req.add("dtend", dtEnd);

    var addr = "stat.trans.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelStatTrans', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};
