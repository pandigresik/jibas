changeDept = function()
{
    if ($("#departemen").has('option').length === 0)
        return;

    var departemen = $("#departemen").val();
    $("#divStatistik").empty();

    $.ajax({
        url: "statistik.ajax.php",
        data: "op=getContent&departemen=" + departemen,
        success: function (html) {
            $("#divStatistik").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

reloadStatistik = function ()
{
    changeDept();
};