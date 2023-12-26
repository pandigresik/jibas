saveSetting = function()
{
    if (!validateInput())
        return;

    var maxVideo = $.trim($("#maxVideo").val());
    var maxFile = $.trim($("#maxFile").val());

    $.ajax({
        url: "schooltube.ajax.php",
        data: "op=saveSetting&maxVideo="+maxVideo+"&maxFile="+maxFile,
        success: function () {
            alert("Pengaturan telah disimpan");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

validateInput = function ()
{
    var valx = new ValidatorX();

    return valx.EmptyText("maxVideo", "Ukuran maksimal video") &&
           valx.IsInteger("maxVideo", "Ukuran maksimal video") &&
           valx.EmptyText("maxFile", "Ukuran maksimal file lampiran") &&
           valx.IsInteger("maxFile", "Ukuran maksimal file lampiran");
};