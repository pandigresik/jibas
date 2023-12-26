changeDept = function () {
    location.href = "infobayar.php?dept=" + $("#dept").val();
};

simpanInfo = function (bagian, elId, elInfo, elStatus)
{
    $(elStatus).html("");

    if ($("#dept option").length === 0)
        return;

    var info = $.trim($(elInfo).val());
    if (info.length < 25)
    {
        alert("Keterangan minimal 25 karakter!")
        $(elInfo).focus();
        return;
    }

    var request = new RequestFactory();
    request.add("op", "3635346456456");
    request.add("dept", $("#dept").val());
    request.add("id", $(elId).val());
    request.add("bagian", bagian);
    request.add("info", info);
    var qs = request.createQs();

    $.ajax({
        url: "infobayar.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            var result = $.parseJSON(json);

            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            $(elId).val(result[2]);
            $(elStatus).html("tersimpan");

            sendToAppServer("datasync");

            setTimeout(function () {
                $(elStatus).html("");
            }, 1500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};