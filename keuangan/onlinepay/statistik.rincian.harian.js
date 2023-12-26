changeReportPage = function ()
{
    var stIdPgTrans = $("#stidpgtrans").val();
    var nData = $("#ndata").val();
    var page = $("#page").val();

    var qs = "stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&ndata=" + encodeURIComponent(nData);
    qs += "&page=" + page;

    var dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    $.ajax({
        url: "statistik.rincian.harian.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result);

            if ($("#tabReport").length)
                Tables('tabReport', 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};