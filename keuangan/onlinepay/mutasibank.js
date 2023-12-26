var blankMessage = "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Pilih salah satu bank di panel kiri untuk menampilkan riwayat mutasi </span>";
var G_MAX_FILE_SIZE = 100 * 1024;
var LS_IMAGE_EXT = ["jpg", "jpeg", "png", "gif"];

$(document).ready(function ()
{
    if ($("#tabBankSaldo").length)
        Tables('tabBankSaldo', 1, 0);

    $("#dvContent").html(blankMessage);
});

changeDept = function ()
{
    showBankSaldo();
};

showBankSaldo = function ()
{
    var qs = "op=32678764872342";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());

    $("#dvBankSaldo").html("memuat .. ");
    clearContent();

    $.ajax({
        url: "mutasibank.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvBankSaldo").html(result);
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

showMutasiBank = function (bank, bankNo)
{
    var qs = "departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent(bank);
    qs += "&bankno=" + encodeURIComponent(bankNo);

    var dvContent = $("#dvContent");
    dvContent.html("memuat .. ");

    $.ajax({
        url: "mutasibank.content.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result);

            showRiwayatMutasi();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};
