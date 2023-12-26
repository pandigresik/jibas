var serverTime;
var clockCount = 0;

ujian_getElapsedTime = function ()
{
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=getelapsedtime",
        type: "post",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "ujian_getElapsedTime");
                return;
            }

            var gnrt = parse.Data; // Data hasil parse json ke GenericReturn
            if (parseInt(gnrt.Code) < 0)
            {
                mainBox.showError(gnrt.Message, gnrt.Data, "ujian_getElapsedTime");
                return;
            }

            var data = gnrt.Data;
            //logToConsole("GetElapsedTime", data);

            parse = jsonutil_tryParseJson(data);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "ujian_getElapsedTime");
                return;
            }

            var elapsedInfo = parse.Data;

            //var elapsedCookie = parseInt($.cookie("elapsed"));
            //if (elapsedCookie === undefined) elapsedCookie = 0;
            //var elapsedData = parseInt(elapsedInfo.Elapsed);
            //ujianData.Info.Elapsed = elapsedCookie > elapsedData ? elapsedCookie : elapsedData;
            //$.cookie("elapsed", ujianData.Info.Elapsed);

            ujianData.Info.Elapsed = parseInt(elapsedInfo.Elapsed);
            ujian_showElapsedTimeInfo();

            ujianData.Info.ServerTime = elapsedInfo.ServerTime;
            serverTime = edate_parseDate(elapsedInfo.ServerTime);
            clockCount = serverTime.Second;
            ujian_showWaktuServer(ujian_serverTimeToString());

            var timeLeft = ujian_getTimeLeft();
            ujian_showTimeLeftInfo(timeLeft);

            if (parseInt(timeLeft) === 0) ujian_forceDone();

            ujian_startClock();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

ujian_updateElapsedTime = function (elapsed)
{
    $.ajax({
        url: "ujian.ajax.php",
        data: "op=updateelapsed&elapsed=" + elapsed,
        success: function (json) {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "ujian_updateElapsedTime");
                return;
            }

            var gnrt = parse.Data; // Data hasil parse json ke GenericReturn
            if (parseInt(gnrt.Code) < 0)
                mainBox.showError(gnrt.Message, gnrt.Data, "ujian_updateElapsedTime");
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

ujian_startClock = function()
{
    if (ujianEnd)
        return;

    setTimeout(ujian_startClock, 1000);

    ujian_serverTimeTick();
    ujian_showWaktuServer(ujian_serverTimeToString());

    clockCount += 1;
    if (clockCount < 60)
        return;

    clockCount = 0;

    ujianData.Info.Elapsed += 1;
    ujian_showElapsedTimeInfo();

    ujian_updateElapsedTime(ujianData.Info.Elapsed);

    var timeLeft = ujian_getTimeLeft();
    ujian_showTimeLeftInfo(timeLeft);

    if (parseInt(timeLeft) === 0)
        ujian_forceDone();
};

ujian_forceDone = function ()
{
    ujianEnd = true;

    preventShowSoal = true;
    $("#lbFinishInfo").html("menyimpan hasil ujian ..");
    $("input[name='btUjian']").attr("disabled", true);

    ujian_showFinishUjianDialog();

    setTimeout(ujian_finishUjian, 1000);
};

ujian_getTimeLeft = function ()
{
    var durasi = parseInt(ujianData.Info.Durasi);
    var elapsed = parseInt(ujianData.Info.Elapsed);

    if (durasi === 0)
        return -1;

    var durLeft = durasi > elapsed ? durasi - elapsed : 0;

    if (parseInt(ujianData.Info.IdJadwalUjian) === 0)
        return durLeft; // Tidak ada jadwal

    var currMin = dateutil_dateToMinute(
        serverTime.Year, serverTime.Month, serverTime.Date,
        serverTime.Hour, serverTime.Minute);
    var endTime = parseInt(ujianData.Info.EndTime);
    var minDiff = endTime > currMin ? endTime - currMin : 0;

    //logToConsole("TimeLeft currMin", currMin);
    //logToConsole("TimeLeft endTime", endTime);
    //logToConsole("TimeLeft minDiff", minDiff);

    //var result = minDiff < durLeft ? minDiff : durLeft;
    //logToConsole("TimeLeft result", result);

    return minDiff < durLeft ? minDiff : durLeft;
};

ujian_showWaktuServer = function(timeStr)
{
    $("#spWaktuServer").html("Waktu Server:&nbsp;&nbsp;" + timeStr);
};

String.prototype.paddingLeft = function (paddingValue)
{
    return String(paddingValue + this).slice(-paddingValue.length);
};

ujian_serverTimeToString = function ()
{
    //var str = serverTime.Date + " " + serverTime.Month + " " + serverTime.Year;
    //str += " " + serverTime.Hour + ":" + serverTime.Minute + ":" + serverTime.Second;
    var hh = serverTime.Hour.toString().paddingLeft("00");
    var mm = serverTime.Minute.toString().paddingLeft("00");
    var ss = serverTime.Second.toString().paddingLeft("00");

    return hh + ":" + mm + ":" + ss;
};

ujian_serverTimeTick = function()
{
    if (serverTime.Second + 1 <= 59)
    {
        serverTime.Second += 1;
        return;
    }

    serverTime.Second = 0;
    if (serverTime.Minute + 1 <= 59)
    {
        serverTime.Minute += 1;
        return;
    }

    serverTime.Minute = 0;
    if (serverTime.Hour + 1 <= 23)
    {
        serverTime.Hour += 1;
        return;
    }

    var maxDay = dateutil_getMaxDay(serverTime.Year, serverTime.Month);
    serverTime.Hour = 0;
    if (serverTime.Date + 1 <= maxDay)
    {
        serverTime.Date += 1;
        return;
    }

    serverTime.Date = 1;
    if (serverTime.Month + 1 <= 12)
    {
        serverTime.Month += 1;
        return;
    }

    serverTime.Month = 1;
    serverTime.Year += 1;
};