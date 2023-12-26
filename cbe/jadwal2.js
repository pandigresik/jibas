var jadwal_preventShow = false;

jadwal_getRuangan = function()
{
    $.ajax({
        url: "jadwal.ajax.php",
        data: "op=getdataruangan",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "jadwal_getRuangan()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "jadwal_getRuangan()");
                return;
            }

            var select = urldecode(result.Data);
            $("#jadwal_spCbRuangan").html(select);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

jadwal_changeCbRuangan = function()
{
    $('#jadwal_divJadwalUjian').html(".");
};

jadwal_showJadwalUjian = function()
{
    if ($("#jadwal_cbRuangan").has('option').length === 0)
        return;

    if (jadwal_preventShow)
        return;

    jadwal_preventShow = true;
    $("#jadwal_divJadwalUjian").html("memuat ..");

    var idRuangan = $("#jadwal_cbRuangan").val();
    var bulan = $("#jadwal_cbBulan").val();
    var tahun = $("#jadwal_cbTahun").val();

    $.ajax({
        url: "jadwal.ajax.php",
        data: "op=getjadwalujian&idruangan=" + idRuangan + "&bulan=" + bulan + "&tahun=" + tahun,
        success: function (json)
        {
            //logToConsole("jadwal_showJadwalUjian", json)
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                jadwal_preventShow = false;
                $("#jadwal_divJadwalUjian").html("");

                mainBox.showError(parse.Message, "", "jadwal_showJadwalUjian()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                jadwal_preventShow = false;
                $("#jadwal_divJadwalUjian").html("");

                mainBox.showError(result.Message, "", "jadwal_showJadwalUjian()");
                return;
            }

            var table = urldecode(result.Data);
            $("#jadwal_divJadwalUjian").html(table);
            jadwal_preventShow = false;
        },
        error: function(xhr)
        {
            jadwal_preventShow = false;
            $("#jadwal_divJadwalUjian").html("");

            alert(xhr.responseText);
        }
    });

};