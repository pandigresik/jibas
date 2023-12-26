lihatContoh = function()
{
    var addr = "kodesekolah.contoh.php";
    newWindow(addr, 'ContohKodeSekolah', '820', '720', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

saveSchoolId = function ()
{
    var schoolId = $.trim($("#schoolid").val());
    var dbId = $.trim($("#dbid").val());

    if (schoolId.length < 5)
    {
        $("#schoolid").focus();
        alert("School Id harus 5 digit")
        return;
    }

    if (dbId.length < 5)
    {
        $("#dbid").focus();
        alert("Database Id harus 5 digit")
        return;
    }

    var status = $("#statusschoolid");
    var simpan = $("#btschoolid");

    status.css("color", "#0000FF");
    status.html("menghubungi JIBAS Payment Gateway .. ");
    simpan.attr("disabled", true);

    var data = "op=37489247938247923";
    data += "&schoolid=" + schoolId;
    data += "&dbid=" + dbId;

    $.ajax({
        url: "kodesekolah.ajax.php",
        method: "POST",
        data: data,
        success: function (json)
        {
            var lsResult = $.parseJSON(json);
            var value = parseInt(lsResult[0]);
            var message = lsResult[1];
            var serviceFee = lsResult[2];

            if (value < 0)
            {
                status.css("color", "#FF0000");
                status.html(message);
                simpan.attr("disabled", false);
            }
            else
            {
                status.css("color", "#0000FF");
                status.html("tersimpan");
                simpan.attr("disabled", false);

                $("#servicefee").val(serviceFee);
            }
        }
    })
};

refreshServiceFee = function ()
{
    var schoolId = $.trim($("#schoolid").val());
    var dbId = $.trim($("#dbid").val());

    if (schoolId.length < 5)
    {
        $("#schoolid").focus();
        alert("School Id harus 5 digit")
        return;
    }

    if (dbId.length < 5)
    {
        $("#dbid").focus();
        alert("Database Id harus 5 digit")
        return;
    }

    var status = $("#statusservicefee");
    var simpan = $("#btrefresh");

    status.css("color", "#0000FF");
    status.html("menghubungi JIBAS Payment Gateway .. ");
    simpan.attr("disabled", true);

    var data = "op=23897498324732";
    data += "&schoolid=" + schoolId;
    data += "&dbid=" + dbId;

    $.ajax({
        url: "kodesekolah.ajax.php",
        method: "POST",
        data: data,
        success: function (json)
        {
            var lsResult = $.parseJSON(json);
            var value = parseInt(lsResult[0]);
            var message = lsResult[1];
            var serviceFee = lsResult[2];

            if (value < 0)
            {
                status.css("color", "#FF0000");
                status.html(message);
                simpan.attr("disabled", false);
            }
            else
            {
                status.css("color", "#0000FF");
                status.html("tersimpan");
                simpan.attr("disabled", false);

                $("#servicefee").val(serviceFee);
            }
        }
    })
};