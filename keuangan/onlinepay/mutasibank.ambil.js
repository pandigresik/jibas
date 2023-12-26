showMutasiAmbil = function ()
{
    var dvSubContent = $("#dvSubContent");
    dvSubContent.html("memuat ..");

    var qs = "departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bank=" + encodeURIComponent($("#bank").val());
    qs += "&bankno=" + encodeURIComponent($("#bankno").val());

    $.ajax({
        url: "mutasibank.ambil.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvSubContent.html(result);

            if ($("#tabMutasiAmbilRincian").length)
                Tables('tabMutasiAmbilRincian', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

calcJumAmbil = function ()
{
    var nData = parseInt($("#ndata").val());

    var errPen = "";
    var totalJum = 0;
    for(var i = 1; i <= nData; i++)
    {
        var el = "#jum-" + i;
        var jum = tryParseInt(rupiahToNumber($(el).val()), 0);

        el = "#saldo-" + i;
        var saldo = parseInt($(el).val());

        if (jum > saldo)
        {
            el = "#pen-" + i;
            var pen = $(el).val();

            if (errPen !== "") errPen += ", ";
            errPen += "<b>" + pen.toUpperCase() + "</b>";
        }

        totalJum += jum;
    }

    $("#spTotal").html(numberToRupiah(totalJum));

    if (errPen !== "")
    {
        $("#spInfo").css("color", "#ff0000");
        $("#spInfo").html("Pengambilan " + errPen + " tidak boleh melebihi jumlah saldonya");
        $("#jumvalid").val(-1);
    }
    else
    {
        $("#spInfo").html("");
        $("#jumvalid").val(1);
    }
};

validateInputsAmbil = function ()
{
    var tanggal = $.trim($("#tanggal").val());
    if (tanggal.length === 0)
    {
        alert("Tanggal mutasi belum ditentukan");
        return false;
    }

    var nomorTransfer = $.trim($("#nomortransfer").val());
    if (nomorTransfer.length < 5)
    {
        alert("Nomor bukti pengmabilan minimal 5 karakter");
        return false;
    }

    var buktiValid = parseInt($("#buktitransfervalid").val());
    /*
    if (buktiValid === 0)
    {
        alert("File bukti pengambilan belum disertakan!");
        return false;
    }
    */
    if (buktiValid === -1)
    {
        alert("File bukti pengambilan tidak benar!");
        return false;
    }

    var totalJum = 0;
    var nData = parseInt($("#ndata").val());
    for(var i = 1; i <= nData; i++)
    {
        var el = $("#jum-" + i);

        var rp = el.val();
        var test = removeRpSymbol(rp);
        if (isNaN(test))
        {
            alert("Jumleh pengambilan harus bilangan");
            el.focus();
            return false;
        }

        var jumlah = tryParseInt(rupiahToNumber(rp), 0);
        if (jumlah < 0)
        {
            alert("Jumlah pengambilan harus positif");
            el.focus();
            return false;
        }

        totalJum += jumlah;
    }

    if (totalJum === 0)
    {
        alert("Tentukan minimal satu jumlah pemgambilan!");
        return false;
    }

    var jumValid = parseInt($("#jumvalid").val());
    if (jumValid !== 1)
    {
        alert("Ada jumlah pengambilan yang belum benar!")
        return false;
    }

    return true;
};

simpanMutasiAmbil = function ()
{
    if (!validateInputsAmbil())
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

        el = "#kate-" + no;
        var kate = $(el).val();

        el = "#idpen-" + no;
        var idpen = $(el).val();

        el = "#pen-" + no;
        var pen = $(el).val();

        el = "#ket-" + no;
        var keterangan = $(el).val();

        ix += 1;
        formData.append("kate-" + ix, kate);
        formData.append("idpen-" + ix, idpen);
        formData.append("pen-" + ix, pen);
        formData.append("jum-" + ix, jum);
        formData.append("ket-" + ix, keterangan);
    }
    formData.append("ndata", ix);

    $("#btMutasiAmbil").prop('disabled', true);

    $.ajax({
        url: "mutasibank.ambil.ajax.php",
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
                alert("Berhasil menyimpan mutasi pengambilan dana!");
                $("#dvSubContent").html("");

                showBankSaldo();
            }
            else
            {
                alert("Gagal menyimpan data, " + lsResult[2]);
                $("#btMutasiAmbil").prop('disabled', false);
            }
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};