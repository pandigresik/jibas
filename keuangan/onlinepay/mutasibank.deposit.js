showDaftarDeposit = function ()
{
    var dvSubContent = $("#dvSubContent");
    dvSubContent.html("memuat ..");

    var qs = "departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    $.ajax({
        url: "mutasibank.deposit.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvSubContent.html(result);

            if ($("#tabDaftarDeposit").length)
                Tables('tabDaftarDeposit', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

tambahDeposit = function ()
{
    var qs = "iddeposit=0";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    var addr = "mutasibank.deposit.dialog.php?" + qs;
    newWindow(addr, 'TambahDeposit', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

editDeposit = function (idDeposit)
{
    var qs = "iddeposit=" + idDeposit;
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    var addr = "mutasibank.deposit.dialog.php?" + qs;
    newWindow(addr, 'TambahDeposit', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusDeposit = function (idDeposit)
{
    if (!confirm("Hapus data deposit ini?"))
        return;

    var qs = "op=734652387462834";
    qs += "&iddeposit=" + idDeposit;

    $.ajax({
        url: "mutasibank.deposit.ajax.php",
        method: "POST",
        data: qs,
        success: function (jsonResult)
        {
            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) === 1)
                refreshDaftarDeposit();
            else
                alert(lsResult[1]);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};


refreshDaftarDeposit = function ()
{
    showDaftarDeposit();
};

setDepositAktif = function (no, idDeposit, newAktif)
{
    var qs = "op=23483247832478324";
    qs += "&iddeposit=" + idDeposit;
    qs += "&newaktif=" + newAktif;

    $.ajax({
        url: "mutasibank.deposit.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvDepositAktif-" + no).html(result);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};