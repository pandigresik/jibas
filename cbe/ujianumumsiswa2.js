var ums_preventViewDaftar = false;

ums_getPilihanUjian = function()
{
    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: "op=getpilihanujian",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ums_getPilihanUjian()");
                return;
            }

            ums_getPilihanDept();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ums_getPilihanDept = function ()
{
    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: "op=getpilihandept",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ums_getPilihanDept()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "ums_getPilihanDept()");
                return;
            }

            $("#ums_spCbDept").html(result.Data);

            ums_getPilihanPelajaran();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ums_changeCbDept = function()
{
    ums_getPilihanPelajaran();

    $("#ums_divDaftarUjian").html(".");
};

ums_getPilihanPelajaran = function()
{
    if ($("#ums_cbDept").has('option').length === 0)
    {
        $("#ums_spCbPelajaran").html("");
        return;
    }

    var dept = $("#ums_cbDept").val();
    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: "op=getpilihanpelajaran&dept=" + dept,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ums_getPilihanPelajaran()");
                return;
            }

            var result = parse.Data;
            //var result = $.parseJSON(json);
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "ums_getPilihanPelajaran()");
                return;
            }

            $("#ums_spCbPelajaran").html(result.Data);

            ums_getPilihanTingkat();
            ums_getPilihanSemester();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ums_changeCbPelajaran = function()
{
    $("#ums_divDaftarUjian").html(".");
};

ums_getPilihanTingkat = function ()
{
    if ($("#ums_cbDept").has('option').length === 0)
    {
        $("#ums_spCbTingkat").html("");
        return;
    }

    if ($("#ums_cbPelajaran").has('option').length === 0)
    {
        $("#ums_spCbTingkat").html("");
        return;
    }

    var dept = $("#ums_cbDept").val();
    var idPelajaran = $("#ums_cbPelajaran").val();

    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: "op=getpilihantingkat&dept=" + dept + "&idpelajaran=" + idPelajaran,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ums_getPilihanTingkat()");
                return;
            }

            var result = parse.Data;

            //var result = $.parseJSON(json);
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "ums_getPilihanTingkat()");
                return;
            }

            $("#ums_spCbTingkat").html(result.Data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ums_changeCbTingkat = function()
{
    $("#ums_divDaftarUjian").html(".");
};

ums_getPilihanSemester = function ()
{
    if ($("#ums_cbDept").has('option').length === 0)
    {
        $("#ums_spCbSemester").html("");
        return;
    }

    if ($("#ums_cbPelajaran").has('option').length === 0)
    {
        $("#ums_spCbSemester").html("");
        return;
    }

    var dept = $("#ums_cbDept").val();
    var idPelajaran = $("#ums_cbPelajaran").val();

    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: "op=getpilihansemester&dept=" + dept + "&idpelajaran=" + idPelajaran,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ums_getPilihanSemester()");
                return;
            }

            var result = parse.Data;
            //var result = $.parseJSON(json);
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "ums_getPilihanSemester()");
                return;
            }

            $("#ums_spCbSemester").html(result.Data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ums_changeCbSemester = function()
{
    $("#ums_divDaftarUjian").html(".");
};

ums_showDaftarUjian = function()
{
    if ($("#ums_cbDept").has('option').length === 0)
        return;

    if ($("#ums_cbPelajaran").has('option').length === 0)
        return;

    if ($("#ums_cbTingkat").has('option').length === 0)
        return;

    if ($("#ums_cbSemester").has('option').length === 0)
        return;

    if (ums_preventViewDaftar)
        return;

    ums_preventViewDaftar = true;
    $("#ums_divDaftarUjian").html("memulai ..");

    var dept = $("#ums_cbDept").val();
    var viewDaftarUjian = $("#ums_cbViewDaftarUjian").val();
    var idPelajaran = $("#ums_cbPelajaran").val();
    var idTingkat = $("#ums_cbTingkat").val();
    var idSemester = $("#ums_cbSemester").val();

    var data = "op=getdaftarujian&dept="+dept+"&viewdaftarujian="+viewDaftarUjian+"&idpelajaran="+idPelajaran,
    data = data + "&idtingkat=" + idTingkat + "&idsemester=" + idSemester;

    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: data,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                ums_preventViewDaftar = false;
                $("#ums_divDaftarUjian").html("");

                mainBox.showError(parse.Message, "", "ums_showDaftarUjian()");
                return;
            }

            var result = parse.Data;

            //var result = $.parseJSON(json);
            if (parseInt(result.Code) < 0)
            {
                ums_preventViewDaftar = false;
                $("#ums_divDaftarUjian").html("");

                mainBox.showError(result.Message, "", "ums_showDaftarUjian()");
                return;
            }

            ums_preventViewDaftar = false;
            $("#ums_divDaftarUjian").html(result.Data);
        },
        error: function(xhr)
        {
            ums_preventViewDaftar = false;
            $("#ums_divDaftarUjian").html("");

            alert(xhr.responseText);
        }
    });
};

ums_startUjian = function(no)
{
    var jsonTag = $("#tag-" + no).val();
    jsonTag = stringutil_replaceAll("`", "\"", jsonTag);

    var parse = jsonutil_tryParseJson(jsonTag);
    if (parseInt(parse.Code) < 0)
    {
        mainBox.showError(parse.Message, jsonTag, "um_startUjian()");
        return;
    }

    var tag = parse.Data;

    var question = "";
    if (parseInt(tag.StatusUjian) === 0)
        question = "Lanjutkan ujian " + tag.Judul + "?";
    else if (parseInt(tag.StatusUjian) === 1 || parseInt(tag.StatusUjian) === 2)
        question = "Ulangi ujian " + tag.Judul + "?";
    else
        question = "Mulai ujian " + tag.Judul + "?";

    if (!confirm(question))
        return;

    ums_preventViewDaftar = true;
    $("#lbInfo-" + no).html("memulai ujian ..");
    $("input[name='btMulai']").attr("disabled", true);

    var idSerta = tag.IdUjianSerta;
    if (parseInt(tag.StatusUjian) === 1 || parseInt(tag.StatusUjian) === 2)
        idSerta = 0;

    var data = "op=startujian&idujian=" + tag.IdUjian;
    data += "&idremedujian=" + tag.IdRemedUjian;
    data += "&idujianserta=" + idSerta;
    data += "&idjadwalujian=" + tag.IdJadwalUjian;

    $.ajax({
        url: "ujianumumsiswa.ajax.php",
        data: data,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                ums_preventViewDaftar = false;
                $("#lbInfo-" + no).html("");
                $("input[name='btMulai']").attr("disabled", false);

                mainBox.showError(parse.Message, parse.Data, "ums_startUjian()");
                return;
            }

            document.location.href = "ujian.php";
        },
        error: function (xhr)
        {
            ums_preventViewDaftar = false;
            $("#lbInfo-" + no).html("");
            $("input[name='btMulai']").attr("disabled", false);

            alert(xhr.responseText);
        }
    })
};