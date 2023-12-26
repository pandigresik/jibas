var mainBox = null;

$(document).ready(function ()
{
    pingSvc_Start(".");

    ujian_initModal();

    ujian_secureWindow();
});

window.onpopstate = function(e)
{
    if (e.state === null)
        return;

    var idSoal = e.state.idSoal;
    var noSoal = e.state.noSoal;

    ujian_showSoal(idSoal, noSoal, false);
};

ujian_initModal = function()
{
    mainBox = new DialogBox("#divDialog", 420, 180);

    $("#btCloseTimeLeftInfo").click(function () {
        $("#dlgTimeLeftInfo").css("visibility", "hidden");
    });

    $("#btCloseFinishUjianInfo").click(function () {
        $("#dlgFinishUjianInfo").css("visibility", "hidden");
        $("#divBlock").css({"z-Index" : -1, "visibility" : "hidden"});
    });
};

ujian_showTimeLeftDialog = function(text, bgcolor, fgcolor)
{
    $("#txTextTimeLeftInfo").css("color", fgcolor).html(text);
    $("#dlgTimeLeftInfo").css({"background-color" : bgcolor, "visibility" : "visible"}).hide().fadeIn(500);
};

ujian_showFinishUjianDialog = function ()
{
    $("#divBlock").css({"z-Index" : 998, "visibility" : "visible"});
    $("#txTextFinishUjianInfo").html("UJIAN TELAH BERAKHIR");
    $("#dlgFinishUjianInfo").css({"z-Index" : 999, "visibility" : "visible"}).hide().fadeIn(500);
};

ujian_secureWindow = function ()
{
    $(document).on("keydown", function (e)
    {
        if (e.keyCode === 116 || (e.ctrlKey && e.keyCode === 82))
            e.preventDefault();
    });

    $(window).bind("beforeunload", function(e) {
        return false;
    });
};

ujian_showElapsedTimeInfo = function()
{
    $("#spElapsed").html("Waktu Ujian:&nbsp;&nbsp;" + ujianData.Info.Elapsed + " menit");
};

ujian_showTimeLeftInfo = function(timeLeft)
{
    var text = timeLeft === -1 ? "-" : timeLeft + " menit";
    $("#spTimeLeft").html("Sisa Waktu:&nbsp;&nbsp;" + text);

    for(var i = 0; i < lsTimeLeft.length; i++)
    {
        var info = lsTimeLeft[i];
        if (info.TimeLeft !== timeLeft)
            continue;

        text = "Waktu tersisa " + info.TimeLeft + " menit lagi";
        var bgColor = info.BgColor;
        var fgColor = info.FgColor;

        ujian_showTimeLeftDialog(text, bgColor, fgColor);
    }
};

ujian_showInformation = function ()
{
    $("#spPelajaran").html(ujianData.Info.Pengujian + "&nbsp;-&nbsp;" + ujianData.Info.Pelajaran);

    var durasi = parseInt(ujianData.Info.Durasi) === 0 ? "tidak ada durasi" : ujianData.Info.Durasi + " menit";
    $("#spDurasi").html("Durasi:&nbsp;&nbsp;&nbsp;" + durasi);
};

