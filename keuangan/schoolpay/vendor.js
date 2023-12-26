$(document).ready(function() {
    setTimeout(function () {
        setTimeout(setTableStyle, 100);
    }, 300);
});

setTableStyle = function () {
    Tables('table', 1, 0);
};

setVendorAktif = function(vendorReplid, newAktif)
{
    var req = new RequestFactory();
    req.add("op", "568789673945");
    req.add("replid", vendorReplid);
    req.add("newaktif", newAktif);

    $.ajax({
        url: "vendor.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function () {
            if (newAktif === 1)
                $("#spAktif" + vendorReplid).html("<a href='#' onclick='setVendorAktif(" + vendorReplid + ", 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>");
            else
                $("#spAktif" + vendorReplid).html("<a href='#' onclick='setVendorAktif(" + vendorReplid + ", 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

hapusVendorUser = function (rowId, vendorId, userId)
{
    if (!confirm("Hapus petugas ini?"))
        return;

    var req = new RequestFactory();
    req.add("op", "2876328746237462");
    req.add("vendorid", vendorId);
    req.add("userid", userId);

    $.ajax({
        url: "vendor.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function () {
            $("#rowVendorUser" + rowId).remove();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

tambahPetugas = function(vendorId)
{
    var addr = "vendor.user.dialog.php?vendorid=" + vendorId;
    newWindow(addr, 'AddVendorUser', '550', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

refreshDaftarPetugas = function (vendorId)
{
    var req = new RequestFactory();
    req.add("op", "98789769");
    req.add("vendorid", vendorId);

    $.ajax({
        url: "vendor.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (html)
        {
            $("#spDaftarPetugas" + vendorId).html(html);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
};

tambahVendor = function()
{
    var addr = "vendor.dialog.php?replid=0";
    newWindow(addr, 'AddVendor', '550', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

editVendor = function(vendorReplid)
{
    var addr = "vendor.dialog.php?replid=" + vendorReplid;
    newWindow(addr, 'EditVendor', '550', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusVendor = function (vendorId)
{
    if (!confirm("Hapus vendor ini?"))
        return;

    var req = new RequestFactory();
    req.add("op", "387298378923");
    req.add("vendorid", vendorId);

    $.ajax({
        url: "vendor.ajax.php",
        method: "POST",
        data: req.createQs(),
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