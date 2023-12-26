$(document).ready(function() {
    setTimeout(function () {
        setTimeout(showTagihanReport, 100);
    }, 300);
});

showTagihanReport = function()
{
    $.ajax({
        url: "tagihan.vendor.ajax.php",
        method: "POST",
        data: "op=9873498732984324",
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
    var addr = "tagihan.vendor.cetak.php";
    newWindow(addr, 'CetakTagihanVendor', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var addr = "tagihan.vendor.excel.php";
    newWindow(addr, 'ExcelTagihanVendor', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showDetail = function(dept, vendorId, jenis)
{
    var addr = "tagihan.vendor.detail.php?dept=" + dept + "&vendorid=" + vendorId + "&jenis=" + jenis;
    newWindow(addr, 'DetailTagihanVendor', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};