simpanPetugasVendor = function()
{
    if (!$("#petugas").val())
        return;

    var vendorId = $("#vendorid").val();
    var userId = $("#petugas").val();
    var tingkat = $("#tingkat").val();

    var req = new RequestFactory();
    req.add("op", "762384762387423");
    req.add("vendorid", vendorId);
    req.add("userid", userId);
    req.add("tingkat", tingkat);
    req.debug();

    $.ajax({
        url: "vendor.user.dialog.ajax.php",
        method: "POST",
        data: req.createQs(),
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            opener.refreshDaftarPetugas(vendorId);
            window.close();
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

};