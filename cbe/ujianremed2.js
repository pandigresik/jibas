var ur_previewViewDaftar = false;

ur_getPilihanPelajaran = function()
{
    var viewDaftarUjian = $("#ur_cbViewDaftarUjian").val();

    $.ajax({
        url: "ujianremed.ajax.php",
        data: "op=getpilihanpelajaran&viewdaftarujian=" + viewDaftarUjian,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "ur_getPilihanPelajaran()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "ur_getPilihanPelajaran()");
                return;
            }

            $("#ur_spCbPelajaran").html(result.Data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ur_changeCbView = function()
{
    ur_getPilihanPelajaran();

    $("#ur_spCbPelajaran").html("memuat ..");
    $("#ur_divDaftarUjian").html(".");
};

ur_changeCbPelajaran = function()
{
    $("#ur_divDaftarUjian").html(".");
};

ur_showDaftarUjian = function()
{
    if ($("#ur_cbPelajaran").has('option').length === 0)
        return;

    if (ur_previewViewDaftar)
        return;

    ur_previewViewDaftar = true;
    $("#ur_divDaftarUjian").html("memuat ..");

    var viewDaftarUjian = $("#ur_cbViewDaftarUjian").val();
    var idPelajaran = $("#ur_cbPelajaran").val();
    $.ajax({
        url: "ujianremed.ajax.php",
        data: "op=getdaftarujian&viewdaftarujian="+viewDaftarUjian+"&idpelajaran="+idPelajaran,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                ur_previewViewDaftar = false;
                $("#ur_divDaftarUjian").html("");

                mainBox.showError(parse.Message, "", "ur_showDaftarUjian()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                ur_previewViewDaftar = false;
                $("#ur_divDaftarUjian").html("");

                mainBox.showError(result.Message, "", "ur_showDaftarUjian()");
                return;
            }

            ur_previewViewDaftar = false;
            $("#ur_divDaftarUjian").html(result.Data);
        },
        error: function(xhr)
        {
            ur_previewViewDaftar = false;
            $("#ur_divDaftarUjian").html("");

            alert(xhr.responseText);
        }
    });
};

ur_startUjian = function(no)
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

    if (parseInt(tag.JumlahSoal) === 0)
    {
        mainBox.show("Belum tersedia soal untuk ujian ini. Hubungi Administrator JIBAS atau guru pelajaran yang bersangkutan");
        return;
    }

    if (parseInt(tag.StatusUjian) === 1 || parseInt(tag.StatusUjian) === 2)
    {
        mainBox.show("Ujian tidak dapat dimulai karena sudah dikerjakan sebelumnya!");
        return;
    }

    if (tag.IdJadwalUjian < 0)
    {
        mainBox.show("Ujian tidak dapat dimulai karena jadwal ujian belum dimulai atau jadwal ujian sudah berakhir!");
        return;
    }

    if (!confirm("Mulai ujian " +  tag.Judul + "?"))
        return;

    ur_previewViewDaftar = true;
    $("#lbInfo-" + no).html("memulai ujian ..");
    $("input[name='btMulai']").attr("disabled", true);

    var data = "op=startujian&idujian=" + tag.IdUjian;
    data += "&idremedujian=" + tag.IdRemedUjian;
    data += "&idujianserta=" + tag.IdUjianSerta;
    data += "&idjadwalujian=" + tag.IdJadwalUjian;

    $.ajax({
        url: "ujianremed.ajax.php",
        data: data,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                ur_previewViewDaftar = false;
                $("#lbInfo-" + no).html("");
                $("input[name='btMulai']").attr("disabled", false);

                mainBox.showError(parse.Message, parse.Data, "ur_startUjian()");
                return;
            }

            document.location.href = "ujian.php";
        },
        error: function (xhr)
        {
            ur_previewViewDaftar = false;
            $("#lbInfo-" + no).html("");
            $("input[name='btMulai']").attr("disabled", false);

            alert(xhr.responseText);
        }
    })
};