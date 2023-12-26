simpanBank = function () {

    var bankReplid = $("#bankreplid").val();
    var dept = $("#dept").val();

    var bank = $.trim($("#bank").val());
    if (bank.length < 3)
    {
        alert("Nama bank minimal 3 karater");
        $("#bank").focus();
        return;
    }

    var bankLoc = $.trim($("#bankloc").val());
    if (bankLoc.length < 5)
    {
        alert("Lokasi bank minimal 5 karater");
        $("#bankloc").focus();
        return;
    }

    var bankNo = $.trim($("#bankno").val());
    if (bankNo.length < 5)
    {
        alert("Nomor rekening minimal 5 karater");
        $("#bankno").focus();
        return;
    }

    var bankName = $.trim($("#bankname").val());
    if (bankName.length < 5)
    {
        alert("Nama pemilik rekening minimal 5 karater");
        $("#bankname").focus();
        return;
    }

    if ($("#rekkas").find("option").length === 0)
    {
        alert("Belum ada data rekening kas");
        $("#rekkas").focus();
        return;
    }

    if ($("#rekpendapatan").find("option").length === 0)
    {
        alert("Belum ada data rekening pendapatan");
        $("#rekpendapatan").focus();
        return;
    }

    var keterangan = $.trim($("#keterangan").val());

    var request = new RequestFactory();
    request.add("op", "3276897493284732894");
    request.add("bankreplid", bankReplid);
    request.add("dept", dept);
    request.add("bank", bank);
    request.add("bankloc", bankLoc);
    request.add("bankno", bankNo);
    request.add("bankname", bankName);
    request.add("keterangan", keterangan);
    request.add("rekkas", $("#rekkas").val());
    request.add("rekpendapatan", $("#rekpendapatan").val());
    var qs = request.createQs();

    $.ajax({
        url: "bank.dialog.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);

            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            sendToAppServer("datasync");

            opener.location.reload();
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};