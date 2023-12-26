openSearchClient = function ()
{
    var addr = "searchclient.php";
    newWindow(addr, 'SearchClient', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

acceptSearch = function(data, noid, nama, kelas)
{
    $("#noid").val(noid);
    $("#nama").val(nama);
    $("#kelompok").val(data);

    clearReport();
};

clearReport = function () {
    $("#spReport").html("");
};

showClientTrans = function ()
{
    var noId = $.trim($("#noid").val());
    var kelompok = $.trim($("#kelompok").val());

    if (noId.length === 0)
        return;

    var req = new RequestFactory();
    req.add("op", "2987429834783294");
    req.add("clientid", noId);
    req.add("clientgroup", kelompok);
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());

    $.ajax({
        url: "client.trans.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html) {
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
    var noId = $.trim($("#noid").val());
    var kelompok = $.trim($("#kelompok").val());

    var req = new RequestFactory();
    req.add("clientid", noId);
    req.add("clientgroup", kelompok);
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());

    var addr = "client.trans.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakClientTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var noId = $.trim($("#noid").val());
    var kelompok = $.trim($("#kelompok").val());

    var req = new RequestFactory();
    req.add("clientid", noId);
    req.add("clientgroup", kelompok);
    req.add("bulan", $("#bulan").val());
    req.add("tahun", $("#tahun").val());

    var addr = "client.trans.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelClientTrans', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakKuitansi = function(transid)
{
    newWindow('trans.kuitansi.php?transid='+transid, 'CetakKuitansiSchoolPay','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
};
