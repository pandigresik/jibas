clearReport = function ()
{
    $("#spReport").html("");
};

changeDepartemen = function ()
{
    clearReport();

    var departemen = $("#departemen").val();

    var req = new RequestFactory();
    req.add("op", "gettingkat");
    req.add("departemen", departemen);

    $.ajax({
        url: "kartu.siswa.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spCbTingkat").html(html);

            var idTingkat = $("#tingkat").val();

            var req = new RequestFactory();
            req.add("op", "getkelas");
            req.add("departemen", departemen);
            req.add("idtingkat", idTingkat);

            $.ajax({
                url: "kartu.siswa.ajax.php",
                method: "POST",
                data: req.createQs(),
                success: function (html)
                {
                    $("#spCbKelas").html(html);
                },
                error: function (xhr) {
                    alert(xhr.responseText);
                }
            });

        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

changeTingkat = function ()
{
    clearReport();

    var departemen = $("#departemen").val();
    var idTingkat = $("#tingkat").val();

    var req = new RequestFactory();
    req.add("op", "getkelas");
    req.add("departemen", departemen);
    req.add("idtingkat", idTingkat);

    $.ajax({
        url: "kartu.siswa.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spCbKelas").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

showKartuSiswa = function()
{
    if (!$("#departemen").val())
        return;

    if (!$("#tingkat").val())
        return;

    if (!$("#kelas").val())
        return;

    var idKelas = $("#kelas").val();

    var req = new RequestFactory();
    req.add("op", "showkartu");
    req.add("idkelas", idKelas);

    $.ajax({
        url: "kartu.siswa.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spReport").html(html);
            if ($("#table").length !== 0)
                Tables("table", 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

changeAktif = function(replid, newAktif)
{
    var req = new RequestFactory();
    req.add("op", "changeaktif");
    req.add("replid", replid);
    req.add("newaktif", newAktif);

    $.ajax({
        url: "kartu.siswa.ajax.php",
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
    var departemen = $("#departemen").val();
    var idKelas = $("#kelas").val();

    var req = new RequestFactory();
    req.add("op", "getkelas");
    req.add("departemen", departemen);
    req.add("idkelas", idKelas);

    var addr = "kartu.siswa.cetak.php?" + req.createQs();
    newWindow(addr, 'CetakKartuSiswa', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelReport = function ()
{
    var departemen = $("#departemen").val();
    var idKelas = $("#kelas").val();

    var req = new RequestFactory();
    req.add("op", "getkelas");
    req.add("departemen", departemen);
    req.add("idkelas", idKelas);

    var addr = "kartu.siswa.excel.php?" + req.createQs();
    newWindow(addr, 'ExcelKartuSiswa', '250', '250', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};
