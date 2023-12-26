showRiwayatMutasi = function ()
{
    var dvSubContent = $("#dvSubContent");
    dvSubContent.html("memuat ..");

    var qs = "departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    $.ajax({
        url: "mutasibank.riwayat.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvSubContent.html(result);

            if ($("#tabRiwayatMutasi").length)
                Tables('tabRiwayatMutasi', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

showDatePicker1 = function ()
{
    var selDate = $("#dttanggal1").val();
    $("#tanggal1").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal1").val(date);
            $("#tanggal1").val(dateutil_formatInaDate(date));
            $("#dvRiwayatMutasi").html("");
        }
    }).focus();
};

showDatePicker2 = function ()
{
    var selDate = $("#dttanggal2").val();
    $("#tanggal2").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal2").val(date);
            $("#tanggal2").val(dateutil_formatInaDate(date));
            $("#dvRiwayatMutasi").html("");
        }
    }).focus();
};

showRincianMutasi = function (idMutasi)
{
    var addr = "mutasibank.riwayat.rincian.php?idmutasi=" + idMutasi;
    newWindow(addr, 'RincianMutasi', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showLaporanMutasi = function ()
{
    var dvRiwayatMutasi = $("#dvRiwayatMutasi");
    dvRiwayatMutasi.html("memuat ..");

    var qs = "op=8947982347892347932";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());
    qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
    qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());

    $.ajax({
        url: "mutasibank.riwayat.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvRiwayatMutasi.html(result);

            if ($("#tabRiwayatMutasi").length)
                Tables('tabRiwayatMutasi', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

cetakMutasi = function()
{
    var departemen = $("#departemen").val();

    var addr = "mutasibank.riwayat.cetak.php?departemen=" + encodeURIComponent(departemen);
    newWindow(addr, 'RiwayatMutasiCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelMutasi = function()
{
    var qs ="departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());
    qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
    qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());

    var addr = "mutasibank.riwayat.excel.php?" + qs;
    newWindow(addr, 'RiwayatMutasiExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

getRiwayatMutasiContent = function ()
{
    return $("#dvRiwayatMutasi").html();
};

getRiwayatMutasiDepartemen = function ()
{
    return $("#departemen").val();
};

getRiwayatMutasiBank = function ()
{
    return $("#bank").val();
};

getRiwayatMutasiBankNo = function ()
{
    return $("#bankno").val();
};

getRiwayatMutasiTanggal1 = function ()
{
    return $("#tanggal1").val();
};

getRiwayatMutasiTanggal2 = function ()
{
    return $("#tanggal2").val();
};