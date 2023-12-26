var G_MAX_FILE_SIZE = 100 * 1024;
var LS_IMAGE_EXT = ["jpg", "jpeg", "png", "gif"];

showDatePickerTf = function (ix)
{
    var selDate = $("#dttanggal" + ix).val();
    $("#tanggaltf" + ix).datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggaltf" + ix).val(date);
            $("#tanggaltf" + ix).val(dateutil_formatInaDate(date));
        }
    }).focus();
};

previewImageFile = function (ix)
{
    var imageFile = $("#buktitf" + ix)[0].files[0];
    if (imageFile)
    {
        const reader = new FileReader();
        reader.onload = function(e)
        {
            var image = new Image();
            image.onload = function()
            {
                $("#spFileInfo" + ix).css("color", "#0000ff");
                $("#spFileInfo" + ix).html(image.width + " x " + image.height);
            };
            image.src = e.target.result;

            $("#imagePreview" + ix).attr('src', e.target.result);
        };
        reader.readAsDataURL(imageFile);
    }
};

removeBuktiTf = function (ix)
{
    $("#buktitf" + ix).val("");
    $("#spFileInfo" + ix).html("");
    $("#buktitfvalid" + ix).val(0);
    $("#imagePreview" + ix).removeAttr("src");
};

validateBuktiTf = function (ix)
{
    var file = ($("#buktitf" + ix))[0].files[0];
    if (file.size >= G_MAX_FILE_SIZE)
    {
        $("#spFileInfo" + ix).css("color", "#ff0000");
        $("#spFileInfo" + ix).html("Ukuran file tidak boleh melebihi " + G_MAX_FILE_SIZE + " B");

        $("#buktitfvalid" + ix).val(-1);
    }
    else
    {
        var fileName = $("#buktitf" + ix).val();
        var fileExt = fileName.split(".").pop().toLowerCase();

        if (LS_IMAGE_EXT.includes(fileExt))
        {
            $("#spFileInfo" + ix).html("");
            $("#buktitfvalid" + ix).val(1);

            previewImageFile(ix);
        }
        else
        {
            $("#spFileInfo" + ix).css("color", "#ff0000");
            $("#spFileInfo" + ix).html("Diperlukan file gambar");

            $("#buktitfvalid" + ix).val(-1);
        }
    }
};

changeSelProses = function ()
{
    var proses = parseInt($("#proses").val());
    if (proses === 1)
    {
        $("#dvSimpanTabungan").css("display", "block");
        $("#dvTransferBalik").css("display", "none");
    }
    else
    {
        $("#dvSimpanTabungan").css("display", "none");
        $("#dvTransferBalik").css("display", "block");
    }
};

simpanTransfer = function ()
{
    if ($.trim($("#tanggaltf2").val()).length === 0)
    {
        alert("Tanggal belum dipilih");
        return;
    }

    var buktiValid = parseInt($("#buktitfvalid2").val());
    if (buktiValid === -1)
    {
        alert("File bukti transfer tidak benar!");
        return false;
    }

    if (!confirm("Data sudan benar?"))
        return;

    var formData = new FormData();
    formData.append("op", "5488745683756348756738");
    formData.append("idpgtranslebih", $("#idpgtranslebih").val());
    formData.append("proses", "2");
    formData.append("tanggal", $("#dttanggaltf2").val());
    formData.append("keterangan", $("#keterangan2").val());
    formData.append("nomortf", $("#nomortf2").val());
    formData.append("buktitfvalid", buktiValid);
    if (buktiValid === 1)
        formData.append("buktitf", $("#buktitf2")[0].files[0]);

    var btSimpanTransfer = $("#btSimpanTransfer");
    var btTutupTransfer = $("#btTutupTransfer");
    var spSimpanTransfer = $("#spSimpanTransfer");

    btSimpanTransfer.prop("disabled", true);
    btTutupTransfer.prop("disabled", true);
    spSimpanTransfer.css("color", "#000000");
    spSimpanTransfer.html("menyimpan ..");

    $.ajax({
        url: "lebihtrans.proses.ajax.php",
        method: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            //console.log(result);

            var lsRes = $.parseJSON(result);
            if (parseInt(lsRes[0]) !== 1)
            {
                btSimpanTransfer.prop("disabled", false);
                btTutupTransfer.prop("disabled", false);
                spSimpanTransfer.css("color", "#ff0000");
                spSimpanTransfer.html(lsRes[1]);
                return;
            }

            sendToAppServer("datasync");

            spSimpanTransfer.css("color", "#0000ff");
            spSimpanTransfer.html("Berhasil menyimpan data");
            setTimeout(function ()
            {
                window.opener.refreshLebihTrans();
                window.close();
            }, 500);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })

};

simpanTabungan = function ()
{
    if ($("#seltabungan option").length === 0)
    {
        alert("Belum ada data tabungan siswa");
        return;
    }

    var buktiValid = parseInt($("#buktitfvalid1").val());
    if (buktiValid === -1)
    {
        alert("File bukti transfer tidak benar!");
        return false;
    }

    if ($.trim($("#tanggaltf1").val()).length === 0)
    {
        alert("Tanggal belum dipilih");
        return;
    }

    if (!confirm("Data sudan benar?"))
        return;

    var formData = new FormData();
    formData.append("op", "7463832746829347328");
    formData.append("idpgtranslebih", $("#idpgtranslebih").val());
    formData.append("proses", "1");
    formData.append("idtabungan", $("#seltabungan").val());
    formData.append("tanggal", $("#dttanggaltf1").val());
    formData.append("keterangan", $("#keterangan1").val());
    formData.append("nomortf", $("#nomortf1").val());
    formData.append("buktitfvalid", buktiValid);
    if (buktiValid === 1)
        formData.append("buktitf", $("#buktitf1")[0].files[0]);

    var btSimpanTabungan = $("#btSimpanTabungan");
    var btTutupTabungan = $("#btTutupTabungan");
    var spSimpanTabungan = $("#spSimpanTabungan");

    btSimpanTabungan.prop("disabled", true);
    btTutupTabungan.prop("disabled", true);
    spSimpanTabungan.css("color", "#000000");
    spSimpanTabungan.html("menyimpan ..");

    $.ajax({
        url: "lebihtrans.proses.ajax.php",
        method: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            //console.log(result);

            var lsRes = $.parseJSON(result);
            if (parseInt(lsRes[0]) !== 1)
            {
                btSimpanTabungan.prop("disabled", false);
                btTutupTabungan.prop("disabled", false);
                spSimpanTabungan.css("color", "#ff0000");
                spSimpanTabungan.html(lsRes[1]);
                return;
            }

            spSimpanTabungan.css("color", "#0000ff");
            spSimpanTabungan.html("Berhasil menyimpan data");
            setTimeout(function ()
            {
                window.opener.refreshLebihTrans();
                window.close();
            }, 500);
        }
    })
};
