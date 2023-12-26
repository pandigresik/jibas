

showDatePicker = function ()
{
    var selDate = $("#dttanggal").val();
    $("#tanggal").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal").val(date);
            $("#tanggal").val(dateutil_formatInaDate(date));
        }
    }).focus();
};


showMutasiSimpan = function ()
{
    var dvSubContent = $("#dvSubContent");
    dvSubContent.html("memuat ..");

    var qs = "departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    $.ajax({
        url: "mutasibank.simpan.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvSubContent.html(result);

            if ($("#tabMutasiSimpan").length)
                Tables('tabMutasiSimpan', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

calcJumSimpan = function ()
{
    var nData = parseInt($("#ndata").val());

    var totalJum = 0;
    for(var i = 1; i <= nData; i++)
    {
        var el = "#jum-" + i;
        var jum = tryParseInt(rupiahToNumber($(el).val()), 0);

        totalJum += jum;
    }

    $("#spTotal").html(numberToRupiah(totalJum));
};

previewImageFile = function ()
{
    var imageFile = $("#buktitransfer")[0].files[0];
    if (imageFile)
    {
        const reader = new FileReader();
        reader.onload = function(e)
        {
            var image = new Image();
            image.onload = function()
            {
                $("#spFileInfo").css("color", "#0000ff");
                $("#spFileInfo").html(image.width + " x " + image.height);
            };
            image.src = e.target.result;

            $('#imagePreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(imageFile);
    }
};

validateBuktiTransfer = function ()
{
    var file = ($("#buktitransfer"))[0].files[0];
    if (file.size >= G_MAX_FILE_SIZE)
    {
        $("#spFileInfo").css("color", "#ff0000");
        $("#spFileInfo").html("Ukuran file tidak boleh melebihi " + G_MAX_FILE_SIZE + " B");

        $("#buktitransfervalid").val(-1);
    }
    else
    {
        var fileName = $("#buktitransfer").val();
        var fileExt = fileName.split(".").pop().toLowerCase();

        if (LS_IMAGE_EXT.includes(fileExt))
        {
            $("#spFileInfo").html("");
            $("#buktitransfervalid").val(1);

            previewImageFile();
        }
        else
        {
            $("#spFileInfo").css("color", "#ff0000");
            $("#spFileInfo").html("Diperlukan file gambar");

            $("#buktitransfervalid").val(-1);
        }
    }
};

validateInputsSimpan = function ()
{
    var tanggal = $.trim($("#tanggal").val());
    if (tanggal.length === 0)
    {
        alert("tanggal mutasi belum ditentukan");
        return false;
    }

    var buktiValid = parseInt($("#buktitranfervalid").val());
    /*
    if (buktiValid === 0)
    {
        alert("File bukti transfer belum disertakan!");
        return false;
    }
    */

    if (buktiValid === -1)
    {
        alert("File bukti transfer tidak valid!");
        return false;
    }

    var nData = parseInt($("#ndata").val());

    var totalJum = 0;
    for(var i = 1; i <= nData; i++)
    {
        var el = $("#jum-" + i);
        var rp = el.val();

        var jumlah = tryParseInt(rupiahToNumber(rp), 0);
        if (jumlah < 0)
        {
            alert("Jumlah penyimpanan harus positif!");
            el.focus();
            return false;
        }

        totalJum += jumlah;
    }

    if (totalJum === 0)
    {
        alert("Tentukan minimal satu jumlah penyimpanan!");
        return false;
    }

    return true;
};

simpanMutasiSimpan = function ()
{
    if (!validateInputsSimpan())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    var formData = new FormData();
    formData.append("departemen", $("#departemen").val());
    formData.append("bankno", $("#bankno").val());
    formData.append("tglmutasi", $("#dttanggal").val());
    formData.append("nomortransfer", $("#nomortransfer").val());
    var buktiValid = parseInt($("#buktitransfervalid").val());
    formData.append("buktivalid", buktiValid);
    if (buktiValid === 1)
        formData.append("buktitransfer", $("#buktitransfer")[0].files[0]);
    formData.append("keterangan", $("#keterangan").val());

    var nData = parseInt($("#ndata").val());
    var ix = 0;
    for(var no = 1; no <= nData; no++)
    {
        var el = "#jum-" + no;
        var jum = tryParseInt(rupiahToNumber($(el).val()), 0);
        if (jum === 0)
            continue;

        el = "#iddeposit-" + no;
        var idDeposit = $(el).val();

        el = "#deposit-" + no;
        var deposit = $(el).val();

        el = "#ket-" + no;
        var keterangan = $(el).val();

        ix += 1;
        formData.append("iddeposit-" + ix, idDeposit);
        formData.append("deposit-" + ix, deposit);
        formData.append("jum-" + ix, jum);
        formData.append("ket-" + ix, keterangan);
    }
    formData.append("ndata", ix);

    $("#btMutasiSimpan").prop('disabled', true);

    $.ajax({
        url: "mutasibank.simpan.ajax.php",
        method: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            //console.log(result);

            var lsResult = $.parseJSON(result);
            if (parseInt(lsResult[0]) === 1)
            {
                alert("Berhasil menyimpan mutasi penyimpanan dana!");
                $("#dvSubContent").html("");

                showBankSaldo();
            }
            else
            {
                alert("Gagal menyimpan data, " + lsResult[2]);
                $("#btMutasiSimpan").prop('disabled', false);
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })

    /*
    var iterator = formData.entries();
    var pair;

    // Iterate over the FormData object
    while ((pair = iterator.next().value)) {
        console.log(pair[0] + ": " + pair[1]);
    }
    */
};
