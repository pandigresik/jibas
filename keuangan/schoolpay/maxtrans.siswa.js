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
        url: "maxtrans.siswa.ajax.php",
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
                url: "maxtrans.siswa.ajax.php",
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
        url: "maxtrans.siswa.ajax.php",
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

showDaftarBatasan = function()
{
    if (!$("#departemen").val())
        return;

    if (!$("#tingkat").val())
        return;

    if (!$("#kelas").val())
        return;

    var departemen = $("#departemen").val();
    var idKelas = $("#kelas").val();

    var req = new RequestFactory();
    req.add("op", "showdaftar");
    req.add("departemen", departemen);
    req.add("idkelas", idKelas);

    $.ajax({
        url: "maxtrans.siswa.ajax.php",
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

changeMaxType = function (no)
{
    var sel = parseInt($("#maxtype" + no).val());
    var origval = $("#origval" + no).val();
    var origsel = parseInt($("#origsel" + no).val());
    var defaultmaxvalue = $("#defaultmaxvalue").val();

    console.log("sel = " + sel);
    console.log("origval = " + origval);
    console.log("origsel = " + origsel);
    console.log("defaultmaxvalue = " + defaultmaxvalue);

    if (sel === 1)
    {
        $("#val" + no).attr("readonly", false);
        $("#val" + no).css("background-color", "#fff");
        if (origsel === 0)
        {
            $("#val" + no).val("");
            console.log("A");
        }
        else
        {
            $("#val" + no).val(numberToRupiah(origval));
            console.log("B");
        }
    }
    else
    {
        $("#val" + no).attr("readonly", true);
        $("#val" + no).css("background-color", "#ccc");
        $("#val" + no).val(numberToRupiah(defaultmaxvalue));
    }
};

formatRp = function(no)
{
    var num = $("#val" + no).val();
    $("#val" + no).val(numberToRupiah(num));
};

unformatRp = function(no)
{
    var rp = $("#val" + no).val();
    $("#val" + no).val(rupiahToNumber(rp));
};

saveMaxValue = function(no)
{
    var sel = $("#maxtype" + no).val();
    var val = rupiahToNumber($("#val" + no).val());
    var nis = $("#nis" + no).val();

    if ($.trim(val).length === 0)
    {
        alert("Nilai belum diisikan!");
        $("#val" + no).focus();
        return;
    }

    if (!$.isNumeric(val))
    {
        alert("Nilai bukan angka!");
        $("#val" + no).focus();
        return;
    }

    var req = new RequestFactory();
    req.add("op", "savemaxvalue");
    req.add("sel", sel);
    req.add("val", val);
    req.add("nis", nis);

    $.ajax({
        url: "maxtrans.siswa.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {

        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};