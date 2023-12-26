showRiwayatLogin = function()
{
    if (!$("#vendor").val())
        return;

    var req = new RequestFactory();
    req.add("op", "23894723984789324");
    req.add("vendorid", $("#vendor").val());
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());
    req.add("tanggal", $("#tanggal").val());

    $.ajax({
        url: "riwayat.login.ajax.php",
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
    });
};

changeTanggal = function ()
{
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();

    $.ajax({
        url: "riwayat.login.ajax.php",
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

clearReport = function ()
{
    $("#spReport").html("");
};

cetakReport = function ()
{
    var req = new RequestFactory();
    req.add("vendorid", $("#vendor").val());
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());
    req.add("tanggal", $("#tanggal").val());

    var addr = "riwayat.login.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakRiwayatTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var req = new RequestFactory();
    req.add("vendorid", $("#vendor").val());
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());
    req.add("tanggal", $("#tanggal").val());

    var addr = "riwayat.login.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelRiwayatTrans', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};