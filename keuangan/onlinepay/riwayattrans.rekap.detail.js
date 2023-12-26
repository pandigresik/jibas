$(document).ready(function () {
    if ($("#tabReport").length)
        Tables('tabReport', 1, 0);
});

changePage = function ()
{
    var stIdPgTrans = $("#stidpgtrans").val();
    var jsonPen = $("#jsonpen").val();
    var jsonTgl = $("#jsontgl").val();
    var page = $("#page").val();

    var qs = "stidpgtrans=" + encodeURIComponent(stIdPgTrans);
    qs += "&jsonpen=" + encodeURIComponent(jsonPen);
    qs += "&jsontgl=" + encodeURIComponent(jsonTgl);
    qs += "&page=" + page;

    document.location.href = "riwayattrans.rekap.detail.php?" + qs;
};