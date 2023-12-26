var pageInfo = 0;

$(document).ready(function () {
    if ($("#tabTagihanSet").length)
        Tables('tabTagihanSet', 0, 0);
});

checkSiswa = function (state)
{
    var nSiswa = parseInt($("#nsiswa").val());
    var checked = state === 1;
    for(var i = 1; i <= nSiswa; i++)
    {
        $("#chsiswa-" + i).prop("checked", checked);
    }
};

prepareBatchNotif = function ()
{
    var stNis = "";
    var nSiswa = parseInt($("#nsiswa").val());
    for(var i = 1; i <= nSiswa; i++)
    {
        if (!$("#chsiswa-" + i).is(":checked"))
            continue;

        if (stNis !== "") stNis += ",";
        stNis += "'" + $("#nis-" + i).val() + "'";
    }

    if (stNis === "")
    {
        alert("Pilih minimal satu siswa!");
        return;
    }

    var data = "op=7856875634875";
    data += "&departemen=" + encodeURIComponent($("#departemen").val());
    data += "&stnis=" + encodeURIComponent(stNis);
    data += "&idtagihanset=" + $("#idtagihanset").val();

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function(result)
        {
            $("#dvTagihanData").html(result);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

sendNotif = function ()
{
    var pesan = $.trim($("#ps_pesan").val());
    if (pesan.length === 0)
        return;

    if (!confirm("Pesan sudah benar?"))
        return;

    var data = "op=8374628746238746728346";
    data += "&dept=" + encodeURIComponent($("#departemen").val());
    data += "&nis=" + encodeURIComponent($("#nis").val());
    data += "&nama=" + encodeURIComponent($("#nama").val());
    data += "&pesan=" + encodeURIComponent(pesan);

    $("#ps_kirim").prop("disabled", true);
    $("#ps_info").html("menyiapkan pesan ..");

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (jsonResult)
        {
            //console.log(jsonResult);

            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) < 0)
            {
                $("#ps_info").css("color", "#ff0000");
                $("#ps_info").html("GAGAL " + lsResult[1]);
                return;
            }

            $("#ps_info").html(lsResult[1]);
        },
        error: function (xhr)
        {
            $("#ps_kirim").prop("disabled", false);
            $("#ps_info").css("color", "#ff0000");
            $("#ps_info").html("GAGAL " + xhr.responseText);
        }
    });
};

sendBatchNotif = function ()
{
    var pesan = $.trim($("#pt_pesan").val());
    if (pesan.length === 0)
        return;

    if (!confirm("Pesan sudah benar?"))
        return;

    var data = "op=8273468874356743723468324";
    data += "&stnis=" + encodeURIComponent($("#pt_stnis").val());
    data += "&idtagihanset=" + $("#pt_idtagihanset").val();
    data += "&pesan=" + encodeURIComponent(pesan);
    data += "&dept=" + encodeURIComponent($("#pt_dept").val());

    $("#pt_kirim").prop("disabled", true);
    $("#pt_info").html("menyiapkan pesan ..");

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (jsonResult)
        {
            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) < 0)
            {
                $("#pt_info").css("color", "#ff0000");
                $("#pt_info").html("GAGAL " + lsResult[1]);
                return;
            }

            $("#pt_info").html(lsResult[1]);
        },
        error: function (xhr)
        {
            $("#pt_kirim").prop("disabled", false);
            $("#pt_info").css("color", "#ff0000");
            $("#pt_info").html("GAGAL " + xhr.responseText);
        }
    });
};

showTagihanSet = function ()
{
    $("#dvTagihanData").html("");
    $("#trTagihanInfo").hide();
    $("#trTagihanSet").fadeIn();
};

showTagihanInfo = function(no)
{
    var el = "#idtagihanset-" + no;
    var idTagihanSet = $(el).val();

    el = "#tagihanset-" + no;
    var tagihanSet = encodeURIComponent($(el).val());

    el = "#infotagihanset-" + no;
    var infoSet = encodeURIComponent($(el).val());

    pageInfo = 1;

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: "op=8374687346839274&idtagihanset=" + idTagihanSet + "&pageinfo=" + pageInfo + "&status=100&tagihanset=" + tagihanSet + "&infotagihanset=" + infoSet,
        success: function (data)
        {
            $("#dvTagihanInfo").html(data);
            $("#trTagihanInfo").fadeIn();
            $("#trTagihanSet").hide();

            if ($("#tabTagihanInfo").length)
                Tables('tabTagihanInfo', 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

refreshTagihanData = function ()
{
    var nis = $("#nis").val();
    var nama = $("#nama").val();
    var noTagihan = $("#notagihan").val();
    var idTagihanInfo = $("#idtagihaninfo").val();

    showTagihanData(nis, nama, noTagihan, idTagihanInfo);
};

showTagihanData = function (nis, nama, noTagihan, idTagihanInfo)
{
    $("#dvTagihanData").html("memuat ..");

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data : "op=930248032948023948&nis=" + nis + "&nama=" + nama + "&notagihan=" + noTagihan + "&idtagihaninfo=" + idTagihanInfo,
        success: function (data)
        {
            $("#dvTagihanData").html(data);

            if ($("#tabTagihanData").length)
                Tables('tabTagihanData', 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

changePage = function ()
{
    var idTagihanSet = $("#idtagihanset").val();
    var tagihanSet = encodeURIComponent($("#tagihanset").val());
    var page = $("#halaman").val();
    var status = $("#status").val();

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: "op=8374687346839274&idtagihanset=" + idTagihanSet + "&pageinfo=" + page + "&status=" + status + "&tagihanset=" + tagihanSet,
        success: function (data)
        {
            $("#dvTagihanInfo").html(data);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

changeStatus = function ()
{
    var idTagihanSet = $("#idtagihanset").val();
    var tagihanSet = encodeURIComponent($("#tagihanset").val());
    var page = 1;
    var status = $("#status").val();

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: "op=8374687346839274&idtagihanset=" + idTagihanSet + "&pageinfo=" + page + "&status=" + status + "&tagihanset=" + tagihanSet,
        success: function (data)
        {
            $("#dvTagihanInfo").html(data);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

checkInvoiceStatusFromJsServer = function (execFunc, execCode, execData1, execData2)
{
    var qs = "op=modifinvoice";
    qs += "&nomor=" + encodeURIComponent(execData2);

    if (execCode === 4)
    {
        qs = "op=modifinvoiceset";
        qs += "&idts=" + execData2;
    }

    $.ajax({
        url: "appserver.sender.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            var gr = $.parseJSON(json);
            if (parseInt(gr.Value) !== 1)
            {
                if (execCode === 2)
                {
                    $("#spInfo").css("color", "#FF0000");
                    $("#spInfo").html("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
                }
                else if (execCode === 3)
                {
                    $("#btHapusTagihanSiswa").attr("disabled", true);
                    $("#spInfo").css("color", "#FF0000");
                    $("#spInfo").html("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
                }
                else if (execCode === 4)
                {
                    var no = execData1;
                    $("#imHapusTagihanSet-" + no).attr("src", "../images/ico/hapus.png");
                }

                alert("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
                return;
            }

            var grResult = $.parseJSON(gr.Data);
            if (parseInt(grResult.Value) !== 1)
            {
                if (execCode === 2)
                {
                    $("#spInfo").css("color", "#FF0000");
                    $("#spInfo").html("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
                }
                else if (execCode === 3)
                {
                    $("#btHapusTagihanSiswa").attr("disabled", true);
                    $("#spInfo").css("color", "#FF0000");
                    $("#spInfo").html("Maaf, tagihan ini tidak dapat dimodifikasi: " + grResult.Text);
                }
                else if (execCode === 4)
                {
                    var no = execData1;
                    $("#imHapusTagihanSet-" + no).attr("src", "../images/ico/hapus.png");
                }

                alert("Maaf, tagihan ini tidak dapat dimodifikasi: " + grResult.Text);
                return;
            }

            if (execCode === 1)
                execFunc(execData1, execData2);
            else if (execCode === 2)
                execFunc(execData1, execData2);
            else if (execCode === 3)
                execFunc(execData2);
            else if (execCode === 4)
                execFunc(execData1, execData2);
        },
        error: function(xhr)
        {
            if (execCode === 2)
            {
                $("#spInfo").css("color", "#FF0000");
                $("#spInfo").html("Maaf, ada kekeliruan: Tidak dapat menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah (" + gr.Text + ")");
            }
            else if (execCode === 3)
            {
                $("#btHapusTagihanSiswa").attr("disabled", true);
                $("#spInfo").css("color", "#FF0000");
                $("#spInfo").html("Maaf, ada kekeliruan: " + xhr.responseText);
            }
            else if (execCode === 4)
            {
                $("#imHapusTagihanSet").attr("src", "../images/ico/hapus.png");
            }

            alert("Maaf, ada kekeliruan: " + xhr.responseText);
        }
    })
};

editTagihan = function (idTagihanData, noTagihan)
{
    //checkInvoiceStatusFromJsServer(doEditTagihan, 1, idTagihanData, noTagihan);

    var addr = "daftartagihan.edit.php?idtagihandata=" + idTagihanData + "&notagihan=" + encodeURIComponent(noTagihan);
    newWindow(addr, 'EditTagihan', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusTagihan = function (idTagihanData, noTagihan)
{
    $("#spInfo").css("color", "#0000FF");
    $("#spInfo").html("menghapus ..");

    checkInvoiceStatusFromJsServer(doHapusTagihan, 2, idTagihanData, noTagihan);
};

doHapusTagihan = function (idTagihanData, noTagihan)
{
    if (!confirm("Hapus tagihan ini?"))
        return;

    var data = "op=984723846234";
    data += "&idtagihandata=" + idTagihanData;
    data += "&notagihan=" + encodeURIComponent(noTagihan);

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result[0]) === -1)
            {
                alert(result[2]);
                return;
            }

            sendToAppServer("datasync");

            setTimeout(function () {
                refreshTagihanData();
            }, 1000);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hapusTagihanSiswa = function (noTagihan)
{
    $("#btHapusTagihanSiswa").attr("disabled", false);
    $("#spInfo").css("color", "#0000FF");
    $("#spInfo").html("menghapus ..");

    checkInvoiceStatusFromJsServer(doHapusTagihanSiswa, 3, "", noTagihan);
};

doHapusTagihanSiswa = function (noTagihan)
{
    if (!confirm("Hapus tagihan siswa ini?"))
        return;

    var data = "op=49384729847682934";
    data += "&notagihan=" + noTagihan;

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result[0]) === -1)
            {
                alert(result[2]);
                return;
            }

            sendToAppServer("datasync");

            setTimeout(function () {
                $("#dvTagihanData").html("<br><br>Tagihan siswa telah dihapus");
            }, 1000);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

hapusTagihanSet = function (no, idTagihanSet)
{
    var nKonfirmasi = parseInt($("#nkonfirmasi-" + no).val());
    var nSelesai = parseInt($("#nselesai-" + no).val());

    if (nKonfirmasi !== 0 || nSelesai !== 0)
    {
        alert("Tidak bisa menghapus tagihan ini karena ada yang sudah konfirmasi atau selesai pembayarannya!");
        return;
    }

    $("#imHapusTagihanSet-" + no).attr("src", "../images/ico/bullet.gif");
    checkInvoiceStatusFromJsServer(doHapusTagihanSet, 4, no, idTagihanSet);
};

doHapusTagihanSet = function (no, idTagihanSet)
{
    var el = "#nkonfirmasi-" + no;
    var nKonfirmasi = parseInt($(el).val());

    el = "#nselesai-" + no;
    var nSelesai = parseInt($(el).val());

    if (nKonfirmasi !== 0 || nSelesai !== 0)
    {
        alert("Tidak dapat menghapus tagihan ini karena sudah terkonfirmasi!")
        return;
    }

    if (!confirm("Hapus semua tagihan ini?"))
        return;

    var data = "op=36547346837463";
    data += "&idtagihanset=" + idTagihanSet;

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (jsonResult)
        {
            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) !== 1)
            {
                alert(lsResult[1]);
                return;
            }

            changeTagihanSel();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

changeTagihanSel = function ()
{
    $("#dvTagihanSet").html("");
    $("#dvTagihanInfo").html("");
    $("#dvTagihanData").html("");

    var departemen = $("#departemen").val();
    var bulan = $("#bulan").val();
    var tahun = $("#tahun").val();

    var data = "op=23894762874632";
    data += "&departemen=" + departemen;
    data += "&bulan=" + bulan;
    data += "&tahun=" + tahun;

    $.ajax({
        url: "daftartagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (data)
        {
            $("#trTagihanInfo").hide();

            $("#dvTagihanSet").html(data);
            $("#trTagihanSet").fadeIn();

            if ($("#tabTagihanSet").length)
                Tables('tabTagihanSet', 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })

};