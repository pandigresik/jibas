var ujianEnd = false;
var noCurrSoal = 0;
var preventShowSoal = false;
var allowNextSoal = false;

ujian_showSoal = function(idSoal, noSoal, saveHistory)
{
    if (ujianEnd)
    {
        mainBox.show("Ujian telah berakhir!");
        return;
    }

    if (preventShowSoal)
        return;

    preventShowSoal = true;

    $("#divSoal").html("memuat..");
    $("#divJawaban").html("memuat..");

    $.ajax({
        url: "ujian.ajax.php",
        data: "op=getsoal&idsoal=" + idSoal,
        type: "post",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                preventShowSoal = false;

                mainBox.showError(parse.Message, parse.Data, "ujian_showSoal");
                return;
            }

            var gnrt = parse.Data; // $.parseJSON(json);
            if (parseInt(gnrt.Code) < 0)
            {
                preventShowSoal = false;

                mainBox.showError(gnrt.Message, parse.Data, "ujian_showSoal");
                return;
            }

            parse = jsonutil_tryParseJson(gnrt.Data);
            if (parseInt(parse.Code) < 0)
            {
                preventShowSoal = false;

                mainBox.showError(parse.Message, parse.Data, "ujian_showSoal");
                return;
            }

            currSoalInfo = parse.Data; //$.parseJSON(gnrt.Data);
            zoomFactor = 1.0;

            noCurrSoal = noSoal;

            ujian_displaySoal(noSoal);
            ujian_displayJawaban();

            if (saveHistory)
                history.pushState({idSoal: idSoal, noSoal: noSoal}, "", "ujian.php");

            preventShowSoal = false;
        },
        error: function(xhr)
        {
            preventShowSoal = false;

            alert(xhr.responseText);
        }
    });
};

ujian_displaySoal = function(noSoal)
{
    var divGambar = "<div id='divGambarSoal' style='position: relative'>";
    //divGambar += "<img id='imSoal' class='noRightClick' src='data:image/jpeg;base64," + currSoalInfo.Soal + "' style='position: absolute; top: 0; left: 0;'>";
    divGambar += "<img id='imSoal' class='noRightClick' src='" + currSoalInfo.Soal + "' style='position: absolute; top: 0; left: 0;'>";
    divGambar += "<span class='spNoSoal'>" + noSoal + "</span>";
    divGambar += "<span class='spZoomBox'>";
    divGambar += "<span class='spButtonZoomBox' style='font-size: 28px' onclick='ujian_soalZoom(0.1)'>+</span>&nbsp;"
    divGambar += "<span class='spButtonZoomBox' style='font-size: 16px' onclick='ujian_soalZoom(1)'>100</span>&nbsp;"
    divGambar += "<span class='spButtonZoomBox' style='font-size: 36px' onclick='ujian_soalZoom(-0.1)'>-</span>"
    divGambar += "</span>";
    divGambar += "</div>";

    $("#divSoal").html(divGambar);

    $('.noRightClick').bind('contextmenu', function(e) {
        return false;
    });

    origImageWidth = $("#imSoal").width();
};

ujian_displayJawaban = function()
{
    if (parseInt(currSoalInfo.Jenis) === 0) // Pilihan Ganda
        ujian_displayJawabanPilihanGanda();
    else if (parseInt(currSoalInfo.Jenis) === 1) // Pilihan Kompleks
        ujian_displayJawabanPilihanKompleks();
    else if (parseInt(currSoalInfo.Jenis) === 2) // Sebab Akibat
        ujian_displayJawabanSebabAkibat();
    else if (parseInt(currSoalInfo.Jenis) === 3) // Essay
        ujian_displayJawabanEssay();
    else if (parseInt(currSoalInfo.Jenis) === 4) // Pilihan Ganda Kompleks 2022-04-04
        ujian_displayJawabanPilihanMultiGanda();
};

ujian_displayJawabanEssay = function()
{
    if (parseInt(currSoalInfo.JenisEssay) === 1)
        ujian_displayJawabanEssayGambar();
    else
        ujian_displayJawabanEssayTeks();
};

ujian_displayJawabanEssayTeks = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);
    var nJawaban = parseInt(currSoalInfo.NJawaban);
    if (nJawaban === 0) nJawaban = 1;

    var jawaban = [];
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 1 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            jawaban = ujianData.DcJawaban[idSoal].Jawaban;
    }


    var div = "<div class='divJawaban' id='divJawabanEssayTeks'>";
    div += "<table border='0' cellpadding='2' cellspacing='0' width='100%'>";
    for(var i = 0; i < nJawaban; i++)
    {
        var answer = "";
        if (jawaban.length > i)
            answer = jawaban[i];

        var no = i + 1;
        div += "<tr>";
        div += "<td width='14' align='right' valign='top'>#" + no + "</td>";
        div += "<td width='*' align='left' valign='top'>";
        div += "<textarea class='inputText' id='txJawaban" + i + "'>" + answer + "</textarea>";
        div += "</td>";
        div += "</tr>";
    }
    div += "</table>";
    div += "</div>";

    $("#divJawaban").html(div);
};

ujian_displayJawabanEssayGambar = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);
    var nJawaban = parseInt(currSoalInfo.NJawaban);
    if (nJawaban === 0) nJawaban = 1;

    var image64 = "";
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 2 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            image64 = ujianData.DcJawaban[idSoal].Jawaban[0];
    }

    var div = "<div class='divJawaban' style='top: -10px;' id='divJawabanEssayGambar'>";
    div += "<a href='#' onclick='ujian_clearCanvas()'>bersihkan canvas ..</a><br>";
    div += "<canvas width='600' height='400' id='cvs' style='display: block; border: 2px solid #888;'></canvas>";
    div += "</div>";

    $("#divJawaban").html(div);

    ujian_initCanvas();
    ujian_loadImage(image64);
};

ujian_displayJawabanPilihanMultiGanda = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);
    var nJawaban = parseInt(currSoalInfo.NJawaban);
    if (parseInt(currSoalInfo.SoalGabungJawaban) === 1) nJawaban = 5;
    if (nJawaban === 0) nJawaban = 5;

    var jawaban = "";
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 0 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            jawaban = ujianData.DcJawaban[idSoal].Jawaban[0].toUpperCase();
    }

    var jsonJawaban = "[" + jawaban.replaceAll('`', '"') + "]";
    var lsJawaban = jsonutil_tryParseJson2(jsonJawaban, "[]");

    var div = "<div class='divJawaban' id='divJawabanMultiGanda'>";

    div += "<table border='0'><tr><td width='80' style='line-height: 35px' valign='top'>";

    var checked = lsJawaban.includes('A') ? "checked" : "";
    div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chA' value='A' " + checked + ">&nbsp;A</span><br>";

    checked = lsJawaban.includes('B') ? "checked" : "";
    div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chB' value='B' " + checked + ">&nbsp;B</span><br>";

    if (nJawaban >= 3)
    {
        checked = lsJawaban.includes('C') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chC' value='C' " + checked + ">&nbsp;C</span><br>";
    }

    if (nJawaban >= 4)
    {
        checked = lsJawaban.includes('D') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chD' value='D' " + checked + ">&nbsp;D</span><br>";
    }

    if (nJawaban >= 5)
    {
        checked = lsJawaban.includes('E') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chE' value='E' " + checked + ">&nbsp;E</span><br>";
    }

    div += "</td><td width='80' style='line-height: 35px' valign='top'>";

    if (nJawaban >= 6)
    {
        checked = lsJawaban.includes('F') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chF' value='F' " + checked + ">&nbsp;F</span><br>";
    }

    if (nJawaban >= 7)
    {
        checked = lsJawaban.includes('G') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chG' value='G' " + checked + ">&nbsp;G</span><br>";
    }

    if (nJawaban >= 8)
    {
        checked = lsJawaban.includes('H') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chH' value='H' " + checked + ">&nbsp;H</span><br>";
    }

    if (nJawaban >= 9)
    {
        checked = lsJawaban.includes('I') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chI' value='I' " + checked + ">&nbsp;I</span><br>";
    }

    if (nJawaban >= 10)
    {
        checked = lsJawaban.includes('J') ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chJ' value='J' " + checked + ">&nbsp;J</span><br>";
    }

    div += "</td></tr></table>";

    div += "</div>";

    $("#divJawaban").html(div);
};

ujian_displayJawabanPilihanGanda = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);
    var nJawaban = parseInt(currSoalInfo.NJawaban);
    if (parseInt(currSoalInfo.SoalGabungJawaban) === 1) nJawaban = 5;
    if (nJawaban === 0) nJawaban = 5;

    var jawaban = "";
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 0 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            jawaban = ujianData.DcJawaban[idSoal].Jawaban[0].toUpperCase();
    }

    var div = "<div class='divJawaban' id='divJawabanGanda'>";
    var checked = jawaban === "A" ? "checked" : "";
    div += "<span style='font-size: 20px'><input type='radio' class='radioButton' name='rbGanda' id='rbA' value='A' " + checked + ">&nbsp;A</span><br>";

    checked = jawaban === "B" ? "checked" : "";
    div += "<span style='font-size: 20px'><input type='radio' class='radioButton' name='rbGanda' id='rbB' value='B' " + checked + ">&nbsp;B</span><br>";

    if (nJawaban >= 3)
    {
        checked = jawaban === "C" ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='radio' class='radioButton' name='rbGanda' id='rbC' value='C' " + checked + ">&nbsp;C</span><br>";
    }

    if (nJawaban >= 4)
    {
        checked = jawaban === "D" ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='radio' class='radioButton' name='rbGanda' id='rbD' value='D' " + checked + ">&nbsp;D</span><br>";
    }

    if (nJawaban >= 5)
    {
        checked = jawaban === "E" ? "checked" : "";
        div += "<span style='font-size: 20px'><input type='radio' class='radioButton' name='rbGanda' id='rbE' value='E' " + checked + ">&nbsp;E</span><br>";
    }

    div += "</div>";

    $("#divJawaban").html(div);
};

ujian_displayJawabanPilihanKompleks = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);

    var jawaban = "";
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 0 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            jawaban = ujianData.DcJawaban[idSoal].Jawaban[0].toUpperCase();
    }

    var div = "<div class='divJawaban'  id='divJawabanKompleks'>";
    var checked = jawaban === "A" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbKompleks' id='rbA' value='A' " + checked + ">&nbsp;A. Jika hanya (1), (2) dan (3) benar</span><br>";

    checked = jawaban === "B" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbKompleks' id='rbB' value='B' " + checked + ">&nbsp;B. Jika hanya (1) dan (3) benar</span><br>";

    checked = jawaban === "C" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbKompleks' id='rbC' value='C' " + checked + ">&nbsp;C. Jika hanya (2) dan (4) benar</span><br>";

    checked = jawaban === "D" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbKompleks' id='rbD' value='D' " + checked + ">&nbsp;D. Jika hanya (4) benar</span><br>";

    checked = jawaban === "E" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbKompleks' id='rbE' value='E' " + checked + ">&nbsp;E. Jika semuanya benar</span><br>";

    div += "</div>";

    $("#divJawaban").html(div);
};

ujian_displayJawabanSebabAkibat = function()
{
    var idSoal = parseInt(currSoalInfo.IdSoal);

    var jawaban = "";
    if (idSoal in ujianData.DcJawaban)
    {
        if (ujianData.DcJawaban[idSoal].TipeData === 0 &&
            ujianData.DcJawaban[idSoal].Jawaban.length > 0)
            jawaban = ujianData.DcJawaban[idSoal].Jawaban[0].toUpperCase();
    }

    var div = "<div class='divJawaban' id='divJawabanSebabAkibat'>";
    var checked = jawaban === "A" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbSebabAkibat' id='rbA' value='A' " + checked + ">&nbsp;A. Jika pernyataan benar, alasan benar, keduanya menunjukkan hubungan sebab akibat</span><br>";

    checked = jawaban === "B" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbSebabAkibat' id='rbB' value='B' " + checked + ">&nbsp;B. Jika pernyataan benar, alasan benar, tetapi keduanya tidak menunjukkan hubungan sebab akibat</span><br>";

    checked = jawaban === "C" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbSebabAkibat' id='rbC' value='C' " + checked + ">&nbsp;C. Jika pernyataan benar, alasan salah</span><br>";

    checked = jawaban === "D" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbSebabAkibat' id='rbD' value='D' " + checked + ">&nbsp;D. Jika pernyataan salah, alasan benar</span><br>";

    checked = jawaban === "E" ? "checked" : "";
    div += "<span style='font-size: 16px'><input type='radio' class='radioButton' name='rbSebabAkibat' id='rbE' value='E' " + checked + ">&nbsp;E. Jika pernyataan dan alasan, keduanya salah</span><br>";

    div += "</div>";

    $("#divJawaban").html(div);
};

ujian_soalZoom = function(scale)
{
    if (origImageWidth === 0)
        origImageWidth = $("#imSoal").width();

    //logToConsole("origImageWidth", origImageWidth);
    //logToConsole("ZoomScale", scale);
    if (scale === 1) {
        zoomFactor = 1.0;
    }
    else if (scale < 0) {
        if (zoomFactor + scale >= 0.5) zoomFactor = zoomFactor + scale;
    }
    else {
        if (zoomFactor + scale <= 2.0) zoomFactor = zoomFactor + scale;
    }

    var zoomWidth = origImageWidth * zoomFactor;
    //logToConsole("ZoomFactor", zoomFactor);
    //logToConsole("ZoomWidth", zoomWidth);

    if (zoomWidth !== 0) $("#imSoal").css("width", zoomWidth + "px");
};

ujian_createBoxJawaban = function(idSoal, top, left, noSoal, jawaban)
{
    var box = "<div id='dvBox-" + idSoal + "' class='divBox' style='top: " + top + "px; left: " + left + "px;'>";
    box += "<span id='spBoxJwb-" + idSoal + "' class='spBoxJwb' onclick='ujian_showSoal(" + idSoal + "," + noSoal + ", true)'>";
    box += jawaban;
    box += "</span>";
    box += "<span id='spBoxNo-" + idSoal + "' class='spBoxNo' onclick='ujian_showSoal(" + idSoal + "," + noSoal + ", true)'>";
    box += noSoal;
    box += "</span>";
    box += "</div>";

    return box;
};

ujian_simpanJawaban = function(jenisJawaban) // jenis 0 Fix 1 Ragu2
{
    if (ujianEnd)
    {
        mainBox.show("Ujian telah berakhir!");
        return;
    }

    if (currSoalInfo === null)
        return;

    allowNextSoal = true;

    var jenisSoal = parseInt(currSoalInfo.Jenis);

    if (jenisSoal === 0)
        ujian_simpanJawabanPilihan("rbGanda", 0, jenisJawaban);
    else if (jenisSoal === 1)
        ujian_simpanJawabanPilihan("rbKompleks", 1, jenisJawaban);
    else if (jenisSoal === 2)
        ujian_simpanJawabanPilihan("rbSebabAkibat", 2, jenisJawaban);
    else if (jenisSoal === 3)
        ujian_simpanJawabanEssay(3, jenisJawaban);
    else if (jenisSoal === 4)
        ujian_simpanJawabanPilihan("chGanda", 4, jenisJawaban);

    if (allowNextSoal)
        ujian_nextSoal();
};

ujian_nextSoal = function()
{
    var noAvailSoal = 1;
    var idAvailSoal = 0;
    var found = false;
    for(var no = noCurrSoal + 1; no <= ujianData.LsSoalUjian.length; no++)
    {
        var ixSoal = no - 1;
        var idSoal = ujianData.LsSoalUjian[ixSoal];

        if (idSoal in ujianData.DcJawaban)
        {
            found = ujianData.DcJawaban[idSoal].Jawaban.length === 0;
        }
        else
        {
            found = true;
        }

        if (found)
        {
            noAvailSoal = no;
            idAvailSoal = idSoal;
            break;
        }
    }

    if (!found)
    {
        for(no = 1; no <= noCurrSoal - 1; no++)
        {
            ixSoal = no - 1;
            idSoal = ujianData.LsSoalUjian[ixSoal];

            if (idSoal in ujianData.DcJawaban)
            {
                found = ujianData.DcJawaban[idSoal].Jawaban.length === 0;
            }
            else
            {
                found = true;
            }

            if (found)
            {
                noAvailSoal = no;
                idAvailSoal = idSoal;
                break;
            }
        }
    }

    if (found)
        ujian_showSoal(idAvailSoal, noAvailSoal, true);
};

ujian_simpanJawabanEssay = function (jenisSoal, jenisJawaban)
{
    if (ujianEnd)
    {
        mainBox.show("Ujian telah berakhir!");
        return;
    }

    if (parseInt(currSoalInfo.JenisEssay) === 1)
        ujian_simpanJawabanEssayGambar(jenisSoal, jenisJawaban);
    else
        ujian_simpanJawabanEssayTeks(jenisSoal, jenisJawaban);
};

ujian_simpanJawabanEssayTeks = function (jenisSoal, jenisJawaban)
{
    var idSoal = parseInt(currSoalInfo.IdSoal);
    var nJawaban = parseInt(currSoalInfo.NJawaban);
    if (nJawaban === 0) nJawaban = 1;

    var answerArr = [];
    var allEmpty = true;
    for(var i = 0; i < nJawaban; i++)
    {
        answerArr[i] = "";

        var answer = $("#txJawaban" + i).val();
        if (answer === undefined)
            continue;

        answer = answer.trim();
        if (answer.length === 0)
            continue;

        answer = answer.replace(new RegExp("\"", "g"), "`");
        answer = answer.replace(new RegExp("'", "g"), "`");
        //logToConsole("Essay Format", answer);

        answerArr[i] = answer;

        allEmpty = false;
    }

    if (allEmpty)
    {
        allowNextSoal = false;
        alert("Jawaban belum diisikan!");
        return;
    }

    var idSoal = parseInt(currSoalInfo.IdSoal);
    if (idSoal in ujianData.DcJawaban)
    {
        ujianData.DcJawaban[idSoal].TipeData = 1;
        ujianData.DcJawaban[idSoal].Jawaban = answerArr;
    }
    else
    {
        ujianData.DcJawaban[idSoal] = {TipeData : 1, Jawaban : answerArr};
    }

    var color = parseInt(jenisJawaban) === 0 ? colorSure : colorDoubt;
    var box = "#spBoxJwb-" + idSoal;
    $(box).html("{..}");
    $(box).css("background-color", color);

    var json = ujian_ujianDataToJson();
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=updateintent&json=" + encodeURIComponent(json),
        type: "post",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                $(box).html("*");
                $(box).css("background-color", "#DDDDDD");

                mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanEssayTeks()");
                return;
            }

            var idUjianSerta = ujianData.Info.IdUjianSerta;
            var jawabanInfo = {IdSoal: idSoal, IdUjianSerta: idUjianSerta, JenisSoal: jenisSoal, TipeDataJawaban: 1, Jawaban: answerArr};
            var jawabanInfoJson = JSON.stringify(jawabanInfo);

            $.ajax({
                url: "ujian.ajax.php",
                data: "op=simpanjawaban&jwbjson=" + jawabanInfoJson,
                type: "post",
                success: function(data)
                {
                    var parse = jsonutil_tryParseJson(json);
                    if (parseInt(parse.Code) < 0)
                    {
                        $(box).html("*");
                        $(box).css("background-color", "#DDDDDD");

                        mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanEssayTeks()");
                    }
                },
                error: function(xhr)
                {
                    $(box).html("*");
                    $(box).css("background-color", "#DDDDDD");

                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr)
        {
            $(box).html("*");
            $(box).css("background-color", "#DDDDDD");

            alert(xhr.responseText);
        }
    });
};

ujian_simpanJawabanEssayGambar = function (jenisSoal, jenisJawaban)
{
    var image64 = ujian_getDrawingImage();
    if (image64.length === 0)
    {
        allowNextSoal = false;
        return;
    }

    var pos = image64.indexOf(",");
    if (pos === -1)
    {
        allowNextSoal = false;
        return;
    }

    image64 = image64.substr(pos + 1);

    var idSoal = parseInt(currSoalInfo.IdSoal);
    if (idSoal in ujianData.DcJawaban)
    {
        ujianData.DcJawaban[idSoal].TipeData = 2;
        ujianData.DcJawaban[idSoal].Jawaban = [ image64 ];
    }
    else
    {
        ujianData.DcJawaban[idSoal] = {TipeData : 2, Jawaban : [ image64 ]};
    }

    var color = parseInt(jenisJawaban) === 0 ? colorSure : colorDoubt;
    var box = "#spBoxJwb-" + idSoal;
    $(box).html("{..}");
    $(box).css("background-color", color);

    var json = ujian_ujianDataToJson();
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=updateintent&json=" + encodeURIComponent(json),
        type: "post",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                $(box).html("*");
                $(box).css("background-color", "#DDDDDD");

                mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanEssayGambar()");
                return;
            }

            var idUjianSerta = ujianData.Info.IdUjianSerta;
            var jawabanInfo = {IdSoal: idSoal, IdUjianSerta: idUjianSerta, JenisSoal: jenisSoal, TipeDataJawaban: 2, Jawaban: [ image64 ]};
            var jawabanInfoJson = JSON.stringify(jawabanInfo);

            $.ajax({
                url: "ujian.ajax.php",
                data: "op=simpanjawaban&jwbjson=" + encodeURIComponent(jawabanInfoJson),
                type: "post",
                success: function(json)
                {
                    var parse = jsonutil_tryParseJson(json);
                    if (parseInt(parse.Code) < 0)
                    {
                        $(box).html("*");
                        $(box).css("background-color", "#DDDDDD");

                        mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanEssayGambar()");
                    }
                },
                error: function(xhr)
                {
                    $(box).html("*");
                    $(box).css("background-color", "#DDDDDD");

                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr)
        {
            $(box).html("*");
            $(box).css("background-color", "#DDDDDD");

            alert(xhr.responseText);
        }
    });
};

ujian_simpanJawabanPilihan = function(rbGroup, jenisSoal, jenisJawaban)
{
    var jawaban = "";
    var displayJawaban = "";
    if (jenisSoal === 4)
    {
        // NOTE: untuk jenis soal Pilihan Ganda Kompleks, setiap jawabannya diberi backticks
        // ini karena di CBE Server, tipe jawaban yg diterima berupa string, bukan list of string
        // di cbe server, backtick akan dikonversi menjadi list of string

        var stJawaban = "";
        var chGroup = "input[name=" + rbGroup + "]";
        $(chGroup).each(function ()
        {
            if (this.checked)
            {
                if (stJawaban !== "") stJawaban += ",";
                stJawaban += "`" + $(this).val() + "`";
            }
        });

        jawaban = stJawaban;
        displayJawaban = "{..}";
    }
    else
    {
        var group = "input[name=" + rbGroup + "]";
        jawaban = $(group).filter(":checked").val();
        displayJawaban = jawaban;
        if (jawaban === undefined)
        {
            allowNextSoal = false;
            return;
        }
    }

    var idSoal = parseInt(currSoalInfo.IdSoal);
    if (idSoal in ujianData.DcJawaban)
    {
        ujianData.DcJawaban[idSoal].TipeData = 0;
        ujianData.DcJawaban[idSoal].Jawaban = [ jawaban ];
    }
    else
    {
        ujianData.DcJawaban[idSoal] = {TipeData : 0, Jawaban : [ jawaban ]};
    }

    var color = parseInt(jenisJawaban) === 0 ? colorSure : colorDoubt;
    var box = "#spBoxJwb-" + idSoal;
    $(box).html(displayJawaban);
    $(box).css("background-color", color);

    var json = ujian_ujianDataToJson();
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=updateintent&json=" + encodeURIComponent(json),
        type: "post",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                $(box).html("*");
                $(box).css("background-color", "#DDDDDD");

                mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanPilihan()");
                return;
            }

            var idUjianSerta = ujianData.Info.IdUjianSerta;
            var jawabanInfo = {IdSoal: idSoal, IdUjianSerta: idUjianSerta, JenisSoal: jenisSoal, TipeDataJawaban: 0, Jawaban: [ jawaban ]};
            var jawabanInfoJson = JSON.stringify(jawabanInfo);

            $.ajax({
                url: "ujian.ajax.php",
                data: "op=simpanjawaban&jwbjson=" + encodeURIComponent(jawabanInfoJson),
                type: "post",
                success: function(json)
                {
                    var parse = jsonutil_tryParseJson(json);
                    if (parseInt(parse.Code) < 0)
                    {
                        $(box).html("*");
                        $(box).css("background-color", "#DDDDDD");

                        mainBox.showError(parse.Message, parse.Data, "ujian_simpanJawabanPilihan()");
                    }
                },
                error: function(xhr)
                {
                    $(box).html("*");
                    $(box).css("background-color", "#DDDDDD");

                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr)
        {
            $(box).html("*");
            $(box).css("background-color", "#DDDDDD");

            alert(xhr.responseText);
        }
    });
};

ujian_confirmFinishUjian = function ()
{
    if (!confirm("Selasai Ujian?"))
        return;

    preventShowSoal = true;
    $("#lbFinishInfo").html("menyimpan hasil ujian ..");
    $("input[name='btUjian']").attr("disabled", true);

    ujian_finishUjian();
};

ujian_finishUjian = function()
{
    var ujianData = ujian_ujianDataToJson();

    $.ajax({
        url: "ujian.ajax.php",
        data: "op=finishujian&ujiandata=" + ujianData,
        type: "post",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                preventShowSoal = false;
                $("#lbFinishInfo").html("");
                $("input[name='btUjian']").attr("disabled", false);

                mainBox.showError(parse.Message, parse.Data, "ujian_finishUjian()");
                return;
            }

            $(window).unbind("beforeunload");

            document.location.href = "finishujian.php";
        },
        error: function (xhr)
        {
            preventShowSoal = false;
            $("#lbFinishInfo").html("");
            $("input[name='btUjian']").attr("disabled", false);

            alert(xhr.responseText);
        }
    });

};

