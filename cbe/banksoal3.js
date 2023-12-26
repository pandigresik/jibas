bs_getPilihanDept = function()
{
    $.ajax({
        url: "banksoal.ajax.php",
        data: "op=getdepartemen",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "bs_getPilihanDept()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "bs_getPilihanDept()");
                return;
            }

            $("#bs_spCbDept").html(result.Data);

            bs_getPilihanPelajaran();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

bs_changeCbDept = function()
{
    $("#bs_divContent").html(".");
    $("#bs_spCbPelajaran").html("memuat ..");
    $("#bs_spCbTingkat").html("memuat ..");
    $("#bs_spCbSemester").html("memuat ..");

    bs_getPilihanPelajaran();
};

bs_getPilihanPelajaran = function ()
{
    if ($("#bs_cbDept").has('option').length === 0)
    {
        $("#bs_spCbPelajaran").html("");
        $("#bs_spCbTingkat").html("");
        $("#bs_spCbSemester").html("");
        return;
    }

    var dept = $("#bs_cbDept").val();

    $.ajax({
        url: "banksoal.ajax.php",
        data: "op=getpelajaran&dept=" + dept,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "bs_getPilihanPelajaran()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "bs_getPilihanPelajaran()");
                return;
            }

            $("#bs_spCbPelajaran").html(result.Data);

            bs_getPilihanTingkat();
            bs_getPilihanSemester();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

bs_changeCbPelajaran = function()
{
    $("#bs_divContent").html(".");
    $("#bs_spCbTingkat").html("memuat ..");
    $("#bs_spCbSemester").html("memuat ..");

    bs_getPilihanTingkat();
    bs_getPilihanSemester();
};

bs_getPilihanTingkat = function ()
{
    if ($("#bs_cbDept").has('option').length === 0)
    {
        $("#bs_spCbTingkat").html("");
        return;
    }

    if ($("#bs_cbPelajaran").has('option').length === 0)
    {
        $("#bs_spCbTingkat").html("");
        return;
    }

    var dept = $("#bs_cbDept").val();
    var idPelajaran = $("#bs_cbPelajaran").val();

    $.ajax({
        url: "banksoal.ajax.php",
        data: "op=gettingkat&dept=" + dept + "&idpelajaran=" + idPelajaran,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "bs_getPilihanTingkat()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "bs_getPilihanTingkat()");
                return;
            }

            $("#bs_spCbTingkat").html(result.Data);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

bs_changeCbTingkat = function()
{
    $("#bs_divContent").html(".");
};

bs_getPilihanSemester = function ()
{
    if ($("#bs_cbDept").has('option').length === 0)
    {
        $("#bs_spCbSemester").html("");
        return;
    }

    if ($("#bs_cbPelajaran").has('option').length === 0)
    {
        $("#bs_spCbSemester").html("");
        return;
    }

    var dept = $("#bs_cbDept").val();
    var idPelajaran = $("#bs_cbPelajaran").val();

    $.ajax({
        url: "banksoal.ajax.php",
        data: "op=getsemester&dept=" + dept + "&idpelajaran=" + idPelajaran,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "bs_getPilihanSemester()");
                return;
            }

            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "bs_getPilihanSemester()");
                return;
            }

            $("#bs_spCbSemester").html(result.Data);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

bs_changeCbSemester = function()
{
    $("#bs_divContent").html(".");
};

bs_showBankSoal = function()
{
    if ($("#bs_cbDept").has('option').length === 0)
        return;

    if ($("#bs_cbPelajaran").has('option').length === 0)
        return;

    if ($("#bs_cbTingkat").has('option').length === 0)
        return;

    if ($("#bs_cbSemester").has('option').length === 0)
        return;

    $("#bs_divContent").html("memuat ..");

    var dept = $("#bs_cbDept").val();
    var idPelajaran = $("#bs_cbPelajaran").val();
    var idTingkat = $("#bs_cbTingkat").val();
    var idSemester = $("#bs_cbSemester").val();

    var data = "op=getbanksoal&dept=" + dept;
    data += "&idpelajaran=" + idPelajaran;
    data += "&idtingkat=" + idTingkat;
    data += "&idsemester=" + idSemester;

    $.ajax({
        url: "banksoal.ajax.php",
        data: data,
        success: function (data)
        {
            $("#bs_divContent").html(data);

            $('.noRightClickList').bind('contextmenu', function(e) {
                return false;
            });
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

bs_showImageSoal = function(idSoal, idUjianSerta)
{
    var tagId = "#tag-" + idSoal + "-" + idUjianSerta;
    var soalTag = $(tagId).val();

    var json = stringutil_replaceAll("`", "\"", soalTag);

    var gnrt = jsonutil_tryParseJson(json);
    if (parseInt(gnrt.Code) < 0)
    {
        mainBox.showError(gnrt.Message, gnrt.Data, "bs_showImageSoal()");
        return;
    }

    var tag = gnrt.Data;

    if (parseInt(tag.ViewSoal) === 0)
    {
        alert("Tidak diijinkan melihat soal ujian!");
        return;
    }
    else if (parseInt(tag.ViewAfter) !== 0 && parseInt(tag.DateDiff) < parseInt(tag.ViewAfter))
    {
        alert("Soal dapat dilihat setelah " + tag.ViewAfter + " hari setelah tanggal ujian");
        return;
    }

    $.ajax({
        url: "banksoal.ajax.php",
        data: "op=getsoalpenjelasan&idsoal=" + idSoal + "&idujianserta=" + tag.IdUjianSerta + "&viewexp=" + tag.ViewExp,
        type: "post",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "bs_showImageSoal()");
                return false;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) < 0)
            {
                mainBox.showError(gnrt.Message, parse.Data, "bs_showImageSoal()");
                return false;
            }

            var jsonImageData = gnrt.Data;
            parse = jsonutil_tryParseJson(jsonImageData);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "bs_showImageSoal()");
                return false;
            }

            var imageData = parse.Data;

            var content = "<div style='width: 710px; height: 510px; overflow: auto>'>";
            //content += "<img class='noRightClick' src='data:image/jpeg;base64," + imageData.ImageSoal + "'>";
            content += "<img class='noRightClick' src='" + imageData.ImageSoal + "'>";

            if (parseInt(imageData.JenisJawaban) === 2)
                content += "<br><strong>Jawaban:</strong><br><img class='noRightClick' src='data:image/jpeg;base64," + imageData.Jawaban + "'>";
            else
                content += "<br><strong>Jawaban:</strong><br>" + imageData.Jawaban;

            if (parseInt(tag.ViewExp) === 1)
                //content += "<br><br><strong>Penjelasan:</strong><br><img class='noRightClick' src='data:image/jpeg;base64," + imageData.ImagePenjelasan + "'>";
                content += "<br><br><strong>Penjelasan:</strong><br><img class='noRightClick' src='" + imageData.ImagePenjelasan + "'>";
            else
                content += "<br><br></strong>Penjelasan:</strong><br><i>Tidak diperkenankan melihat penjelasan</i>";

            content += "<br><br><br></div>";

            boxSoal.show(content);

            $('.noRightClick').bind('contextmenu', function(e) {
                return false;
            });
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

