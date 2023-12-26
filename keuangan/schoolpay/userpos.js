$(document).ready(function() {
    setTimeout(function () {
        setTimeout(setTableStyle, 100);
    }, 300);
});

setTableStyle = function () {
    Tables('table', 1, 0);
};

tambahUser = function ()
{
    var addr = "userpos.dialog.php?replid=0";
    newWindow(addr, 'AddUserPos', '550', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

editUser = function(userReplid)
{
    var addr = "userpos.dialog.php?replid=" + userReplid;
    newWindow(addr, 'EditUserPos', '550', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusUser = function (userId)
{
    if (!confirm("Hapus petugas ini?"))
        return;

    $.ajax({
        url: "userpos.ajax.php",
        method: "POST",
        data: "op=847293847324&userid=" + userId,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result) < 0)
            {
                alert(result[1]);
                return;
            }

            location.reload();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

setUserAktif = function(userReplid, newAktif)
{
    $.ajax({
        url: "userpos.ajax.php",
        data: "op=895723984732984732&replid=" + userReplid + "&newaktif=" + newAktif,
        success: function () {
            if (newAktif === 1)
                $("#spAktif" + userReplid).html("<a href='#' onclick='setUserAktif(" + userReplid + ", 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>");
            else
                $("#spAktif" + userReplid).html("<a href='#' onclick='setUserAktif(" + userReplid + ", 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};