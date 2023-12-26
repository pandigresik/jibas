searchTrans = function ()
{
    $("#spReport").html("");

    var noid1 = $.trim($("#noid1").val());
    if (noid1.length !== 4)
    {
        alert("Isikan nomor transaksi!")
        $("#noid1").focus();
        return;
    }

    var noid2 = $.trim($("#noid2").val());
    if (noid2.length !== 4)
    {
        alert("Isikan nomor transaksi!")
        $("#noid2").focus();
        return;
    }

    var noid3 = $.trim($("#noid3").val());
    if (noid3.length !== 4)
    {
        alert("Isikan nomor transaksi!")
        $("#noid3").focus();
        return;
    }

    var transId = noid1 + "." + noid2 + "." + noid3;
    var req = new RequestFactory();
    req.add("op", "search");
    req.add("transid", transId);

    $.ajax({
        url: "cari.trans.ajax.php",
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

clearReport = function () {
    $("#noid1").val("");
    $("#noid2").val("");
    $("#noid3").val("");
    $("#spReport").html("");
};

cetakReport = function ()
{
    var req = new RequestFactory();
    req.add("transid", $("#transid").val());

    var addr = "cari.trans.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakCariTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var req = new RequestFactory();
    req.add("transid", $("#transid").val());

    var addr = "cari.trans.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelCariTrans', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakKuitansi = function(transid)
{
    newWindow('trans.kuitansi.php?transid='+transid, 'CetakKuitansiSchoolPay','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
};