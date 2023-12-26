var tc_Interval = 5 * 1000;
var tc_Repetition = 10;
var tc_DataSize = 32;
var tc_Started = false;
var tc_Count = 0;
var tc_Success = 0;
var tc_Failed = 0;

tc_Start = function()
{
    $("#btStartTc").attr("disabled", true);
    $("#btStopTc").attr("disabled", false);
    $("#txSuccessTc").val(0);
    $("#txFailedTc").val(0);
    $("#tabBodyTc").empty();

    tc_Interval = parseInt($("#cbIntervalTc").val()) * 1000;
    tc_Repetition = parseInt($("#cbRepetitionTc").val());
    tc_DataSize = parseInt($("#cbDataSizeTc").val());
    tc_Count = 0;
    tc_Success = 0;
    tc_Failed = 0;
    tc_Started = true;

    tc_SendTestConn();
};

tc_Stop = function()
{
    $("#btStartTc").attr("disabled", false);
    $("#btStopTc").attr("disabled", true);
    $("#tabBodyTc").append("<tr><td>--- selesai ---</td></tr>");

    tc_Started = false;
};

tc_SendTestConn = function()
{
    if (!tc_Started)
        return;

    tc_Count += 1;

    if (tc_Count > tc_Repetition)
    {
        $("#btStartTc").attr("disabled", false);
        $("#btStopTc").attr("disabled", true);
        $("#tabBodyTc").append("<tr><td>--- selesai ---</td></tr>");

        tc_Count = 0;
        tc_Started = false;

        return;
    }


    $.ajax({
        url: "testconn.ajax.php",
        data: "op=testconn&datasize=" + tc_DataSize,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                //TODO: Display On Table
                $("#tabBodyTc").append("<tr><td>GAGAL MENERIMA DATA DARI CBE SERVER</td></tr>");

                tc_Failed += 1;
                $("#txFailedTc").val(tc_Failed);

                return;
            }

            var gnrt = parse.Data; // Data hasil parse json ke GenericReturn
            if (parseInt(gnrt.Code) === 1)
            {
                //TODO: Display On Table
                $("#tabBodyTc").append("<tr><td>Berhasil koneksi ke CBE Server dalam waktu " + gnrt.Data + " ms</td></tr>");

                tc_Success += 1;
                $("#txSuccessTc").val(tc_Success);

                setTimeout(tc_SendTestConn, tc_Interval);
            }
            else if (parseInt(gnrt.Code) === 100)
            {
                $("#btStartTc").attr("disabled", false);
                $("#btStopTc").attr("disabled", true);

                alert(gnrt.Message);
            }
            else
            {
                //TODO: Display On Table
                $("#tabBodyTc").append("<tr><td>GAGAL KONEKSI KE CBE SERVER</td></tr>");

                tc_Failed += 1;
                $("#txFailedTc").val(tc_Failed);

                setTimeout(tc_SendTestConn, tc_Interval);
            }
        },
        error: function (xhr, response, error)
        {
            $("#tabBodyTc").append("<tr><td>GAGAL KONEKSI KE CBE SERVER</td></tr>");

            tc_Failed += 1;
            $("#txFailedTc").val(tc_Failed);

            setTimeout(tc_SendTestConn, tc_Interval);
        }
    });
};