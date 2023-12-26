var uks_preventViewDaftar = false;

uks_initUjianKhusus = function()
{
    uks_getDaftarPelajaran(0);
};

uks_getDaftarPelajaran = function (viewDaftarUjian)
{
    $.ajax({
        url: "ujiankhusus.ajax.php",
        data: "op=getdaftarpelajaran&viewdaftarujian=" + viewDaftarUjian,
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "uks_getDaftarPelajaran()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "uks_getDaftarPelajaran()");
                return;
            }

            var select = urldecode(result.Data);
            $("#uks_spCbPelajaran").html(select);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

uks_changeCbView = function()
{
    $("#uks_divDaftarUjian").html(".");

    uks_showDaftarPelajaran();
};

uks_showDaftarPelajaran = function()
{
    var viewDaftarUjian = $("#uks_cbViewDaftarUjian").val();

    $("#uks_spCbPelajaran").html("memuat ..");

    uks_getDaftarPelajaran(viewDaftarUjian);
};

uks_changeCbPelajaran = function()
{
    $("#uks_divDaftarUjian").html(".");
};

uks_showDaftarUjian = function()
{
    if ($("#uks_cbPelajaran").has('option').length === 0)
        return;

    var viewDaftarUjian = $("#uks_cbViewDaftarUjian").val();
    var idPelajaran = $("#uks_cbPelajaran").val();
    if (parseInt(idPelajaran) === 0)
        return;

    if (uks_preventViewDaftar)
        return;

    uks_preventViewDaftar = true;
    $("#uks_divDaftarUjian").html("memuat ..");

    uks_getDaftarUjian(viewDaftarUjian, idPelajaran);
};

uks_getDaftarUjian = function(viewDaftarUjian, idPelajaran)
{
    $.ajax({
        url: "ujiankhusus.ajax.php",
        data: "op=getdaftarujian&viewdaftarujian="+viewDaftarUjian+"&idpelajaran=" + idPelajaran,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                uks_preventViewDaftar = false;
                $("#uks_divDaftarUjian").html("");

                mainBox.showError(parse.Message, "", "uks_getDaftarUjian()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                uks_preventViewDaftar = false;
                $("#uks_divDaftarUjian").html("");

                mainBox.showError(result.Message, "", "uks_getDaftarUjian()");
                return;
            }

            uks_preventViewDaftar = false;
            $("#uks_divDaftarUjian").html(result.Data);
        },
        error: function (xhr, response, error)
        {
            uks_preventViewDaftar = false;
            $("#uks_divDaftarUjian").html("");

            alert(xhr.responseText);
        }
    })

};

uks_startUjian = function(no)
{
    var jsonTag = $("#tag-" + no).val();
    jsonTag = stringutil_replaceAll("`", "\"", jsonTag);

    var parse = jsonutil_tryParseJson(jsonTag);
    if (parseInt(parse.Code) < 0)
    {
        mainBox.showError(parse.Message, jsonTag, "uks_startUjian");
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

    uks_preventViewDaftar = true;
    $("#lbInfo-" + no).html("memulai ujian ..");
    $("input[name='btMulai']").attr("disabled", true);

    var data = "op=startujian&idujian=" + tag.IdUjian;
    data += "&idremedujian=" + tag.IdRemedUjian;
    data += "&idujianserta=" + tag.IdUjianSerta;
    data += "&idjadwalujian=" + tag.IdJadwalUjian;

    $.ajax({
        url: "ujiankhusus.ajax.php",
        data: data,
        success: function (json)
        {
            console.log(json);

            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                uks_preventViewDaftar = false;
                $("#lbInfo-" + no).html("");
                $("input[name='btMulai']").attr("disabled", false);

                mainBox.showError(parse.Message, parse.Data, "uks_startUjian()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                uks_preventViewDaftar = false;
                $("#lbInfo-" + no).html("");
                $("input[name='btMulai']").attr("disabled", false);

                mainBox.show(result.Message, "", "uks_startUjian()");
                return;
            }

            document.location.href = "ujian.php";
        },
        error: function (xhr)
        {
            uks_preventViewDaftar = false;
            $("#lbInfo-" + no).html("");
            $("input[name='btMulai']").attr("disabled", false);

            alert(xhr.responseText);
        }
    })
};