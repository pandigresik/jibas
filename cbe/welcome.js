var pageLastUjian = 1;

$(document).ready(function ()
{
    welcome_getCurrentJadwal();
});

welcome_getCurrentJadwal = function ()
{
    $("#welcome_divJadwalUjian").html("memuat..");

    $.ajax({
        url: "jadwal.ajax.php",
        data: "op=getcurrjadwalujian",
        success: function(json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "welcome_getCurrentJadwal()");
                return;
            }

            var result = parse.Data;
            //var result = $.parseJSON(json);
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "welcome_getCurrentJadwal");
                return;
            }

            var table = urldecode(result.Data);
            $("#welcome_divJadwalUjian").html(table);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

welcome_getLastUjian = function()
{
    pageLastUjian += 1;

    $.ajax({
        type: "POST",
        url: "welcome.ajax.php",
        data: "op=getlastujian&page="+pageLastUjian,
        success: function(data) {
            $('#tabCbeLastUjian > tbody:last').append(data);
            $('#divCbeLastUjian').animate({ scrollTop: $('#divCbeLastUjian')[0].scrollHeight}, 1500);
        },
        error: function(xhr) {
            alert(xhr.responseText);
        }
    });
};