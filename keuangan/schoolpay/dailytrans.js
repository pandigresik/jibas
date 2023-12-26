changeTanggal = function ()
{
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();

    $.ajax({
        url: "dailytrans.ajax.php",
        method: "POST",
        data: "op=getdate&tahun=" + tahun + "&bulan=" + bulan,
        success: function (html) {
            $("#spCbTanggal").html(html);
            clearReport();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

clearReport = function () {
    $("#spReport").html("");
};

changePetugas = function ()
{
    var petugas = $("#petugas").val();

    $.ajax({
        url: "dailytrans.ajax.php",
        method: "POST",
        data: "op=getvendor&petugas=" + petugas,
        success: function (html) {
            $("#spCbVendor").html(html);
            clearReport();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

showDailyTrans = function () {

    var req = new RequestFactory();
    req.add("op", "8374687234678324");
    req.add("tahun", $("#tahun").val());
    req.add("bulan", $("#bulan").val());
    req.add("tanggal", $("#tanggal").val());
    req.add("petugas", $("#petugas").val());
    req.add("vendor", $("#vendor").val());

    $.ajax({
        url: "dailytrans.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spReport").html(html);
            if ($("#table").length !== 0)
                Tables('table', 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

cetakReport = function ()
{
    var req = new RequestFactory();
    req.add("tahun", $("#tahun").val());
    req.add("bulan", $("#bulan").val());
    req.add("tanggal", $("#tanggal").val());
    req.add("petugas", $("#petugas").val());
    req.add("vendor", $("#vendor").val());

    var addr = "dailytrans.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakDailyTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var req = new RequestFactory();
    req.add("tahun", $("#tahun").val());
    req.add("bulan", $("#bulan").val());
    req.add("tanggal", $("#tanggal").val());
    req.add("petugas", $("#petugas").val());
    req.add("vendor", $("#vendor").val());

    var addr = "dailytrans.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelDailyTrans', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakKuitansi = function(transid)
{
    newWindow('trans.kuitansi.php?transid='+transid, 'CetakKuitansiSchoolPay','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
};