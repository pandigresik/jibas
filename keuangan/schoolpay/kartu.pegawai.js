$(document).ready(function() {
    setTimeout(function () {
        setTimeout(showKartuPegawai, 100);
    }, 300);
});

showKartuPegawai = function()
{
    var req = new RequestFactory();
    req.add("op", "showkartu");

    $.ajax({
        url: "kartu.pegawai.ajax.php",
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

clearReport = function () {
    $("#spReport").html("");
};

changeAktif = function(replid, newAktif)
{
    var req = new RequestFactory();
    req.add("op", "changeaktif");
    req.add("replid", replid);
    req.add("newaktif", newAktif);

    $.ajax({
        url: "kartu.pegawai.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spAktif" + replid).html(html);

            if (newAktif === 0)
                alert("Kartu telah di non-aktif kan. Kartu yang baru dapat dibuat di JIBAS Card Maker");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

cetakReport = function ()
{
    var addr = "kartu.pegawai.cetak.php";
    newWindow(addr, 'CetakKartuPegawai', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var addr = "kartu.pegawai.excel.php";
    newWindow(addr, 'ExcelKartuPegawai', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};