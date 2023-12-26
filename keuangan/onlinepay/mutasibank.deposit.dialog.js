simpanDeposit = function ()
{
    var deposit = $.trim($("#deposit").val());
    if (deposit.length < 5)
    {
        alert("Nama simpanan minimal 5 karakter!")
        $("#deposit").focus();
        return;
    }

    var keterangan = $.trim($("#keterangan").val());

    var qs = "iddeposit=" + $("#iddeposit").val();
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());
    qs += "&deposit=" + encodeURIComponent(deposit);
    qs += "&keterangan=" + encodeURIComponent(keterangan);

    $.ajax({
        url: "mutasibank.deposit.dialog.ajax.php",
        method: "POST",
        data: qs,
        success: function ()
        {
            opener.refreshDaftarDeposit();
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};