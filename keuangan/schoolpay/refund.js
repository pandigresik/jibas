showRefundHistory = function ()
{
    if (!$("#departemen").val()) return;
    if (!$("#vendor").val()) return;

    var req = new RequestFactory();
    req.add("op", "showrefundhistory");
    req.add("vendorid", $("#vendor").val());
    req.add("departemen", $("#departemen").val());
    req.add("idtahunbuku", $("#idtahunbuku").val());

    $.ajax({
        url: "refund.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spReport").html(html);
            if ($("#table").length !== 0)
                Tables('table', 1, 0);
        },
        error: function (html) {
            alert(xhr.responseText);
        }
    });
};

makeRefund = function ()
{
    if (!$("#departemen").val()) return;
    if (!$("#vendor").val()) return;

    var tagihan = $("#tagihan").val();
    if (parseInt(tagihan) === 0)
    {
        alert("Belum ada tagihan vendor ke sekolah!")
        return;
    }

    var req = new RequestFactory();
    req.add("vendorid", $("#vendor").val());
    req.add("departemen", $("#departemen").val());
    req.add("idtahunbuku", $("#idtahunbuku").val());
    req.add("tagihan", tagihan);

    var addr = "refund.trans.php?" + req.createQs();
    newWindow(addr, 'RefundTrans', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

changeVendor = function ()
{
    if (!$("#departemen").val()) return;

    var vendorId = $("#vendor").val();
    var departemen = $("#departemen").val();
    var idTahunBuku = $("#idtahunbuku").val();

    showLastRefundDate(vendorId, idTahunBuku);
    showTagihanVendor(vendorId, departemen);
};

refreshReport = function (idRefund)
{
    var vendorId = $("#vendor").val();
    var departemen = $("#departemen").val();
    var idTahunBuku = $("#idtahunbuku").val();

    showLastRefundDate(vendorId, idTahunBuku);
    showTagihanVendor(vendorId, departemen);
    showRefundHistory();
    //cetakKuitansi(idRefund);
};

changeDepartemen = function ()
{
    if (!$("#vendor").val()) return;

    var departemen = $("#departemen").val();

    var req = new RequestFactory();
    req.add("op", "gettahunbuku");
    req.add("departemen", departemen);

    $.ajax({
        url: "refund.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spTahunBuku").html(html);

            var idTahunBuku = $("#idtahunbuku").val();
            var vendorId = $("#vendor").val();

            showLastRefundDate(vendorId, idTahunBuku);
            showTagihanVendor(vendorId, departemen);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

showTagihanVendor = function (vendorId, departemen)
{
    var req = new RequestFactory();
    req.add("op", "gettagihanvendor");
    req.add("vendorid", vendorId);
    req.add("departemen", departemen);

    $.ajax({
        url: "refund.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spTagihanVendor").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

showLastRefundDate = function(vendorId, idTahunBuku)
{
    var req = new RequestFactory();
    req.add("op", "getlastrefunddate");
    req.add("vendorid", vendorId);
    req.add("idtahunbuku", idTahunBuku);

    $.ajax({
        url: "refund.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spLastRefundDate").html(html);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

cetakReport = function()
{
    var req = new RequestFactory();
    req.add("op", "showrefundhistory");
    req.add("vendorid", $("#vendor").val());
    req.add("departemen", $("#departemen").val());
    req.add("idtahunbuku", $("#idtahunbuku").val());

    var addr = "refund.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakRefundTrans', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function()
{
    var req = new RequestFactory();
    req.add("op", "showrefundhistory");
    req.add("vendorid", $("#vendor").val());
    req.add("departemen", $("#departemen").val());
    req.add("idtahunbuku", $("#idtahunbuku").val());

    var addr = "refund.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelRefundTrans', '50', '50', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakKuitansi = function(idRefund)
{
    newWindow('refund.kuitansi.php?idrefund='+idRefund, 'CetakKuitansi','360','650','resizable=1,scrollbars=1,status=0,toolbar=0');
};