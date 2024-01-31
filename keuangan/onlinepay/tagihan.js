$(document).ready(function () {
    if ($("#tabIuran").length)
        Tables('tabIuran', 1, 0);
});

checkKelas = function(state)
{
    if (!$("#nkelas").length)
        return;

    var nKelas = $("#nkelas").val();
    var checked = state === 1;
    for(var i = 1; i <= nKelas; i++)
    {
        $("#chkelas" + i).prop("checked", checked);
    }
};

checkIuran = function(state)
{
    if (!$("#niuran").length)
        return;

    var nIuran = $("#niuran").val();
    var checked = state === 1;
    for(var i = 1; i <= nIuran; i++)
    {
        $("#chiuran" + i).prop("checked", checked);
    }
};

changeDep = function ()
{
    var dept = $("#departemen").val();
    fetchTahunBuku(dept);
    fetchTingkat(dept);
    fetchIuran(dept);
};

changeTingkat = function ()
{
    var dept = $("#departemen").val();
    var idTingkat = $("#tingkat").val();

    fetchKelas(dept, idTingkat);
};

fetchTahunBuku = function (dept)
{
    $.ajax({
        url: "tagihan.ajax.php",
        method: "POST",
        data: "op=fetchtahunbuku&dept=" + dept,
        success: function (data)
        {
            $("#divtahunbuku").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

fetchIuran = function (dept)
{
    $.ajax({
        url: "tagihan.ajax.php",
        method: "POST",
        data: "op=fetchiuran&dept=" + dept,
        success: function (data)
        {
            $("#diviuran").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

fetchTingkat = function (dept)
{
    $.ajax({
        url: "tagihan.ajax.php",
        method: "POST",
        data: "op=fetchtingkat&dept=" + dept,
        success: function (data)
        {
            $("#divtingkat").html(data);

            var idTingkat = $("#tingkat").val();
            fetchKelas(dept, idTingkat);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

fetchKelas = function (dept, idTingkat)
{
    $.ajax({
        url: "tagihan.ajax.php",
        method: "POST",
        data: "op=fetchkelas&dept="+dept+"&idtingkat="+idTingkat,
        success: function (data)
        {
            $("#divkelas").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

createInvoice = function ()
{
    if (!$("#departemen").length) return;
    if (!$("#tahunbuku").length) return;
    if (!$("#tingkat").length) return;
    if (!$("#nkelas").length) return;
    if ($("#nkelas").val() === 0) return;
    if (!$("#niuran").length) return;
    if ($("#niuran").val() === 0) return;

    var nKelas = $("#nkelas").val();
    var stIdKelas = "";
    var stKelas = "";
    for(var i = 1; i <= nKelas; i++)
    {
        if (!$("#chkelas" + i).is(":checked"))
            continue;

        var idKelas = $("#idkelas" + i).val();
        if (stIdKelas !== "") stIdKelas += ",";
        stIdKelas += idKelas;

        var kelas = $("#kelas" + i).val();
        if (stKelas !== "") stKelas += ", ";
        stKelas += kelas;
    }
    if (stIdKelas === "")
    {
        alert("Pilih minimal satu kelas");
        return;
    }

    var nIuran = $("#niuran").val();
    var stIdIuran = "";
    var stIuran = "";
    var invalidDiskon = 0;
    var stDiskon = "";
    for(i = 1; i <= nIuran && invalidDiskon === 0; i++)
    {
        if (!$("#chiuran" + i).is(":checked"))
            continue;

        var idIuran = $("#idiuran" + i).val();
        if (stIdIuran !== "") stIdIuran += ",";
        stIdIuran += idIuran;

        var iuran = $("#iuran" + i).val();
        if (stIuran !== "") stIuran += ", ";
        stIuran += iuran;

        var diskon = rupiahToNumber($("#diskon" + i).val());
        if (isNaN(parseInt(diskon)))
            invalidDiskon = i;

        if (stDiskon !== "") stDiskon += ",";
        stDiskon += diskon;
    }

    if (invalidDiskon !== 0)
    {
        alert("Diskon harus berupa angka");
        $("#diskon" + invalidDiskon).focus();
        return;
    }

    if (stIdIuran === "")
    {
        alert("Pilih minimal satu iuran");
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    var skipAlreadyPaid = $("#skipinvoice").is(":checked") ? 1 : 0;

    var request = new RequestFactory();
    request.add("op", "createinvoice");
    request.add("dept", $("#departemen").val());
    request.add("idtahunbuku", $("#tahunbuku").val());
    request.add("tahunbuku", $("#tahunbuku option:selected").text());
    request.add("idtingkat", $("#tingkat").val());
    request.add("tingkat", $("#tingkat option:selected").text());
    request.add("idkelas", stIdKelas);
    request.add("kelas", stKelas);
    request.add("skiplist", $.trim($("#skiplist").val()));
    request.add("idiuran", stIdIuran);
    request.add("iuran", stIuran);
    request.add("diskon", stDiskon);
    request.add("bulan", $("#bulan").val());
    request.add("tahun", $("#tahun").val());
    request.add("keterangan", $("#keterangan").val());
    request.add("sendnotif", $("#chnotif").prop("checked") ? 1 : 0);
    request.add("skipalreadypaid", skipAlreadyPaid);
    var qs = request.createQs();

    $("#btBuatTagihan").prop("disabled", true);
    $("#spBuatTagihan").html("menyimpan ..");

    $.ajax({
        url: "tagihan.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            //console.log(json);

            var result = $.parseJSON(json);

            var color = "#0000FF";
            if (result[0] === -1)
                color = "#FF0000";
            else if (result[0] === 0)
                color = "#9932CC";

            if (result[0] === 1)
                sendToAppServer("datasync");

            $("#btBuatTagihan").prop("disabled", false);
            $("#spBuatTagihan").css("color", color);
            $("#spBuatTagihan").html(result[1]);

            checkKelas(false);
            checkIuran(false);
            $("#keterangan").val("");

            alert(result[1]);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })

    //console.log("valid");
    //console.log(qs);
};

onCheckIuran = function (no)
{
    var isChecked = $("#chiuran" + no).prop("checked");
    $("#diskon" + no).prop("disabled", !isChecked);

    if (isChecked)
        $("#diskon" + no).focus();
};