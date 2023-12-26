simpanVendor = function ()
{
    var vendorReplid = $("#vendorreplid").val();

    var vendorId = $.trim($("#vendorid").val());
    if (vendorId.length < 3)
    {
        alert("Panjang Vendor Id minimal 3 karater");
        $("#vendorid").focus();
        return;
    }

    var vendorName = $.trim($("#vendorname").val());
    if (vendorName.length < 5)
    {
        alert("Panjang Vendor Name minimal 5 karater");
        $("#vendorname").focus();
        return;
    }

    var keterangan = $.trim($("#keterangan").val());

    var checked = $("#terimaiuran").is(":checked");
    var terimaIuran = checked ? 1 : 0;

    checked = $("#kirimpesan").is(":checked");
    var kirimPesan = checked ? 1 : 0;

    var valMethod = $("#valmethod").val(); // 2023-09-25

    var request = new RequestFactory();
    request.add("op", "5748749857485");
    request.add("vendorreplid", vendorReplid);
    request.add("vendorid", vendorId);
    request.add("vendorname", vendorName);
    request.add("terimaiuran", terimaIuran);
    request.add("valmethod", valMethod); // 2023-09-25
    request.add("kirimpesan", kirimPesan);
    request.add("keterangan", keterangan);

    $.ajax({
        url: "vendor.dialog.ajax.php",
        method: "POST",
        data: request.createQs(),
        success: function (json)
        {
            var result = $.parseJSON(json);

            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            opener.location.reload();
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};