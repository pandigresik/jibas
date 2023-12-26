$(document).ready(function() {
    setTimeout(function () {
        setTimeout(setTableStyle, 100);
    }, 300);
});

setTableStyle = function () {
    Tables('table', 1, 0);
};

changeDept = function () {
    location.href = "bank.php?dept=" + $("#dept").val();
};

tambahBank = function ()
{
    if ($("#dept option").length === 0)
        return;

    var addr = "bank.dialog.php?replid=0&dept=" + $("#dept").val();
    newWindow(addr, 'AddBank', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

editBank = function(replid)
{
    var addr = "bank.dialog.php?replid=" + replid;
    newWindow(addr, 'EditBank', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusBank = function (replid)
{
    if (!confirm("Hapus bank ini?"))
        return;

    $.ajax({
        url: "bank.ajax.php",
        method: "POST",
        data: "op=847293847324&replid=" + replid,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result) < 0)
            {
                alert(result[1]);
                return;
            }

            sendToAppServer("datasync");

            setTimeout(function () {
                location.reload();
            }, 500)
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

setBankAktif = function(bankReplid, newAktif)
{
    $.ajax({
        url: "bank.ajax.php",
        data: "op=895723984732984732&replid=" + bankReplid + "&newaktif=" + newAktif,
        success: function ()
        {
            if (newAktif === 1)
                $("#spAktif" + bankReplid).html("<a href='#' onclick='setBankAktif(" + bankReplid + ", 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>");
            else
                $("#spAktif" + bankReplid).html("<a href='#' onclick='setBankAktif(" + bankReplid + ", 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>");

            sendToAppServer("datasync");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};