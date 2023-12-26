var ujianData = {Info: "", LsSoalUjian: [], DcSoalUjian: [], DcJawaban: []};
var colorSure = "#87cefa";
var colorDoubt = "#ffd700";
var colorBlank = "#dddddd";
var zoomFactor = 1.0;
var origImageWidth = 0;
var currSoalInfo = null;
var lsTimeLeft = [
    {TimeLeft:10, BgColor: "#0000FF", FgColor: "#FFFFFF"},
    {TimeLeft:5, BgColor: "#FF8C00", FgColor: "#FFFFFF"},
    {TimeLeft:3, BgColor: "#FF0000", FgColor: "#FFFF00"},
    {TimeLeft:2, BgColor: "#FF0000", FgColor: "#FFFF00"},
    {TimeLeft:1, BgColor: "#FF0000", FgColor: "#FFFF00"}
];

var ixDownloadSoal = 0;
var intervalDownloadSoal = 30 * 1000; // 30 detik

$(document).ready(function()
{
    ujian_getUjianData();
});

$(window).resize(function ()
{
    ujian_showListSoal();
});

ujian_getUjianData = function()
{
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=getujiandata",
        type: "post",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "ujian_getUjianData");
                return;
            }

            var gnrt = parse.Data; // Data hasil parse json ke GenericReturn
            if (parseInt(gnrt.Code) === 1)
            {
                ujian_processUjianData(gnrt.Data);
            }
            else
            {
                mainBox.showError(gnrt.Message, gnrt.Data, "ujian_getUjianData");
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

ujian_processUjianData = function (json)
{
    try
    {
        ujian_parseUjianData(json);
        ujian_showListSoal();

        ujian_getElapsedTime();
        ujian_showInformation();

        ujian_startAutoDownloadSoal();
        ujian_showFirstSoal();
    }
    catch (err)
    {
        mainBox.showError(err.message, "", "ujian_processUjianData");
    }
};

ujian_showFirstSoal = function ()
{
    if (ujianData.LsSoalUjian.length === 0)
        return;

    var idSoal = ujianData.LsSoalUjian[0];
    ujian_showSoal(idSoal, 1, true);
};

ujian_showListSoal = function()
{
    var boxes = "";

    var width = $("#divDaftar").width();
    var nBoxPerLine = Math.round(width / 60) - 1;
    if (nBoxPerLine < 0) nBoxPerLine = 1;

    var nBox = ujianData.LsSoalUjian.length;
    var top = 0;
    var left = 0;
    var count = 0;
    for(var n = 0; n < nBox; n++)
    {
        var idSoal = ujianData.LsSoalUjian[n];

        var jawaban = "";

        if (idSoal in ujianData.DcJawaban)
        {
            var jwbTipe = ujianData.DcJawaban[idSoal].TipeData;
            var jwbArr = ujianData.DcJawaban[idSoal].Jawaban;

            if (jwbArr.length > 0)
            {
                if (parseInt(jwbTipe) === 0)
                    jawaban = jwbArr[0]; // PILIHAN
                else
                    jawaban = "{..}"; //ESSAY
            }
        }

        boxes += ujian_createBoxJawaban(idSoal, top, left, n + 1, jawaban);

        count += 1;
        if (count < nBoxPerLine)
        {
            left += 65;
        }
        else
        {
            top += 65;
            left = 0;
            count = 0;
        }
    }

    // SHOW IN UI
    $("#divDaftar").html(boxes);

    // APPLY COLOR
    for(n = 0; n < nBox; n++)
    {
        idSoal = ujianData.LsSoalUjian[n];
        var idBox = "#spBoxJwb-" + idSoal;

        jawaban = $(idBox).html();
        if (jawaban.length === 0)
            continue;

        $(idBox).css("background-color", colorSure);
    }
};

ujian_parseUjianData = function(json)
{
    var parse = jsonutil_tryParseJson(json);
    if (parseInt(parse.Code) < 0)
    {
        mainBox.showError(parse.Message, parse.Data, "ujian_parseUjianData");
        return;
    }

    var result = parse.Data;
    $.each(result, function (key, value) {
        if (key === "Info")
        {
            ujianData.Info = value;
        }
        else if (key === "LsSoalUjian")
        {
            ujianData.LsSoalUjian = value;
        }
        else if (key === "DcSoalUjian")
        {
            $.each(value, function(key2, value2) {
                ujianData.DcSoalUjian[key2] = value2;
            });
        }
        else if (key === "DcJawaban")
        {
            $.each(value, function(key2, value2) {
                ujianData.DcJawaban[key2] = value2;
            });
        }
    });
};

ujian_ujianDataToJson = function()
{
    var ujianDataStr = "";

    ujianDataStr += "\"Info\" : " + JSON.stringify(ujianData.Info) + ", ";
    ujianDataStr += "\"LsSoalUjian\" : " + JSON.stringify(ujianData.LsSoalUjian) + ", ";

    var dcSoalUjianStr = "";
    $.each(ujianData.LsSoalUjian, function(ix, key) {
        if (dcSoalUjianStr !== "") dcSoalUjianStr += ", ";
        dcSoalUjianStr += "\"" + key + "\" : " + JSON.stringify(ujianData.DcSoalUjian[key]);
    });
    ujianDataStr += "\"DcSoalUjian\" : {" + dcSoalUjianStr + "}" + ", ";

    var dcJawabanStr = "";
    $.each(ujianData.LsSoalUjian, function(ix, key) {
        if (dcJawabanStr !== "") dcJawabanStr += ", ";
        dcJawabanStr += "\"" + key + "\" : " + JSON.stringify(ujianData.DcJawaban[key]);
    });
    ujianDataStr += "\"DcJawaban\" : {" + dcJawabanStr + "}" + ", ";

    ujianDataStr += "\"DcKunci\" : {} ";

    ujianDataStr = "{" + ujianDataStr + "}";

    return ujianDataStr;
};

ujian_startAutoDownloadSoal = function()
{
    ixDownloadSoal = 0;
    setTimeout(ujian_autoDownloadSoal, intervalDownloadSoal);
};

ujian_autoDownloadSoal = function()
{
    if (ixDownloadSoal >= ujianData.LsSoalUjian.length)
        return;

    var idSoal = ujianData.LsSoalUjian[ixDownloadSoal];

    $.ajax({
        url: "ujian.ajax.php",
        data: "op=downloadsoal&idsoal="+idSoal,
        type: "post",
        success: function(json)
        {

        },
        error: function (xhr)
        {

        }
    });

    ixDownloadSoal += 1;
    setTimeout(ujian_autoDownloadSoal, intervalDownloadSoal);
};