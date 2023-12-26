var blankMessage = "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Pilih salah satu bank di panel kiri untuk menampilkan rincian saldo </span>";

$(document).ready(function ()
{
    if ($("#tabBankSaldo").length)
        Tables('tabBankSaldo', 1, 0);

    $("#dvContent").html(blankMessage);
});

changeDept = function ()
{
    refreshSaldo();
};

refreshSaldo = function ()
{
    var departemen = $("#departemen").val();

    var dvBankSaldo = $("#dvBankSaldo");
    dvBankSaldo.html("memuat ..");

    $.ajax({
        url: "saldobank.ajax.php",
        method: "POST",
        data: "op=8473623874632784&departemen=" + encodeURIComponent(departemen),
        success: function (result)
        {
            dvBankSaldo.html(result);

            if ($("#tabBankSaldo").length)
                Tables('tabBankSaldo', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

clearContent = function ()
{
    $("#dvContent").html(blankMessage);
};

showRincianSaldo = function (bank, bankNo)
{
    var departemen = $("#departemen").val();

    var qs = "op=67435724732";
    qs += "&departemen=" + encodeURIComponent(departemen);
    qs += "&bank=" + encodeURIComponent(bank);
    qs += "&bankno=" + encodeURIComponent(bankNo);

    var dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    $.ajax({
        url: "saldobank.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result);

            if ($("#tabRincianSaldo").length)
                Tables('tabRincianSaldo', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

getBankSaldoContent = function ()
{
    return $("#dvBankSaldoContent").html();
};

getDepartemenText = function ()
{
    return $("#departemen  option:selected").text();
};

getRincianSaldoContent = function ()
{
    return $("#dvRincianSaldoContent").html();
};

getBank = function ()
{
    return $("#bank").val();
};

getBankNo = function ()
{
    return $("#bankno").val();
};

cetakSaldo = function ()
{
    var departemen = $("#departemen").val();

    var addr = "saldobank.cetak.php?departemen=" + departemen;
    newWindow(addr, 'SaldoBankCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelSaldo = function ()
{
    var departemen = $("#departemen").val();

    var addr = "saldobank.excel.php?departemen=" + departemen;
    newWindow(addr, 'SaldoBankExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakRincianSaldo = function ()
{
    var departemen = $("#departemen").val();

    var addr = "saldobank.rincian.cetak.php?departemen=" + departemen;
    newWindow(addr, 'RincianSaldoCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};


excelRincianSaldo  = function ()
{
    var departemen = $("#departemen").val();
    var bankNo = $("#bankno").val();

    var addr = "saldobank.rincian.excel.php?departemen=" + departemen + "&bankno=" + bankNo;
    newWindow(addr, 'RincianSaldoExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};