var um_preventViewDaftar = false;

um_getPilihanUjian = function()
{
    $.ajax({
        url: "ujianumum.ajax.php",
        data: "op=getpilihanujian",
        success: function(json)
        {
            um_getPilihanDept();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

um_getPilihanDept = function ()
{
    $.ajax({
        url: "ujianumum.ajax.php",
        data: "op=getpilihandept",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "um_getPilihanDept()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "um_getPilihanDept()");
                return;
            }

            $("#um_spCbDept").html(result.Data);

            um_getPilihanPelajaran();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

um_getPilihanPelajaran = function()
{
    if ($("#um_cbDept").has('option').length === 0)
    {
        $("#um_spCbPelajaran").html("");
        return;
    }

    var dept = $("#um_cbDept").val();
    $.ajax({
        url: "ujianumum.ajax.php",
        data: "op=getpilihanpelajaran&dept=" + dept,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "um_getPilihanPelajaran()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "um_getPilihanPelajaran()");
                return;
            }

            $("#um_spCbPelajaran").html(result.Data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

um_changeCbDept = function()
{
    um_getPilihanPelajaran();

    $("#um_divDaftarUjian").html(".");
};

um_changeCbPelajaran = function()
{
    $("#um_divDaftarUjian").html(".");
};

um_changeCbView = function()
{
    $("#um_divDaftarUjian").html(".");
};

um_showDaftarUjian = function()
{
    if ($("#um_cbDept").has('option').length === 0)
        return;

    if ($("#um_cbPelajaran").has('option').length === 0)
        return;

    if (um_preventViewDaftar)
        return;

    um_preventViewDaftar = true;
    $("#um_divDaftarUjian").html("memulai ..");

    var dept = $("#um_cbDept").val();
    var viewDaftarUjian = $("#um_cbViewDaftarUjian").val();
    var idPelajaran = $("#um_cbPelajaran").val();
    $.ajax({
        url: "ujianumum.ajax.php",
        data: "op=getdaftarujian&dept="+dept+"&viewdaftarujian="+viewDaftarUjian+"&idpelajaran="+idPelajaran,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                um_preventViewDaftar = false;
                $("#um_divDaftarUjian").html("");

                mainBox.showError(parse.Message, "", "um_showDaftarUjian()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                um_preventViewDaftar = false;
                $("#um_divDaftarUjian").html("");

                mainBox.showError(result.Message, "", "um_showDaftarUjian()");
                return;
            }

            um_preventViewDaftar = false;
            $("#um_divDaftarUjian").html(result.Data);
        },
        error: function(xhr)
        {
            um_preventViewDaftar = false;
            $("#um_divDaftarUjian").html("");

            alert(xhr.responseText);
        }
    });

};

um_startUjian = function(no)
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

    um_preventViewDaftar = true;
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
        url: "ujianumum.ajax.php",
        data: data,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                um_preventViewDaftar = false;
                $("#lbInfo-" + no).html("");
                $("input[name='btMulai']").attr("disabled", false);

                mainBox.showError(parse.Message, parse.Data, "um_startUjian()");
                return;
            }

            document.location.href = "ujian.php";
        },
        error: function (xhr)
        {
            um_preventViewDaftar = false;
            $("#lbInfo-" + no).html("");
            $("input[name='btMulai']").attr("disabled", false);

            alert(xhr.responseText);
        }
    })
};