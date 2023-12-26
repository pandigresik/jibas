var rekap_preventShow;

rekap_getPelajaran = function()
{
    $("#rekap_divRekapUjian").html(".");

    var bulan = $("#rekap_cbBulan").val();
    var tahun = $("#rekap_cbTahun").val();
    var jenisujian = $("#rekap_cbJenisUjian").val();

    $.ajax({
        url: "rekap.ajax.php",
        data: "op=getpelajaran&bulan=" + bulan + "&tahun=" + tahun + "&jenisujian=" + jenisujian,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, "", "rekap_getPelajaran()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                mainBox.showError(result.Message, "", "rekap_getPelajaran()");
                return;
            }

            var select = urldecode(result.Data);
            $("#rekap_spCbPelajaran").html(select);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

rekap_changeCbPelajaran = function()
{
    $("#rekap_divRekapUjian").html(".");
};

rekap_showRekapUjian = function()
{
    if (!$("#rekap_cbPelajaran").val())
    {
        alert("Tidak ada data pelajaran!")
        return;
    }

    if (rekap_preventShow)
        return;

    rekap_preventShow = true;
    $("#rekap_divRekapUjian").html("memuat..");

    var bulan = $("#rekap_cbBulan").val();
    var tahun = $("#rekap_cbTahun").val();
    var jenisujian = $("#rekap_cbJenisUjian").val();
    var idPelajaran = $("#rekap_cbPelajaran").val();

    $.ajax({
        url: "rekap.ajax.php",
        data: "op=getrekapujian&bulan=" + bulan + "&tahun=" + tahun + "&jenisujian=" + jenisujian + "&idpelajaran=" + idPelajaran,
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                rekap_preventShow = false;
                $("#rekap_divRekapUjian").html("");

                mainBox.showError(parse.Message, "", "rekap_showRekapUjian()");
                return;
            }

            //var result = $.parseJSON(json);
            var result = parse.Data;
            if (parseInt(result.Code) < 0)
            {
                rekap_preventShow = false;
                $("#rekap_divRekapUjian").html("");

                mainBox.showError(result.Message, "", "rekap_showRekapUjian()");
                return;
            }

            var table = urldecode(result.Data);
            $("#rekap_divRekapUjian").html(table);
            rekap_preventShow = false;
        },
        error: function (xhr)
        {
            rekap_preventShow = false;
            $("#rekap_divRekapUjian").html("");

            alert(xhr.responseText);
        }
    });
};

rekap_showDetail = function(no, idUjianSerta)
{
    //var divElement = "#divDetail-" + no;
    var divContentElement = "#divDetailContent-" + no;
    var btElement = "#btDetail-" + no;
    var trElement = "#trDetail-" + no;
    var visible = parseInt($(trElement).attr("data-visible"));

    $(divContentElement).html("memuat..");

    if (visible === 0)
    {
        $(btElement).attr("value", "Tutup");
        $(trElement).attr("data-visible", 1);
        $(trElement).css({"visibility" : "visible", "height" : "300px", "display" : "table-row"});
        $(divContentElement).load("hasilujian.php?ShowInfo=0&IdUjianSerta=" + idUjianSerta);
    }
    else
    {
        $(btElement).attr("value", "Lihat");
        $(trElement).attr("data-visible", 0);
        $(trElement).css({"visibility" : "hidden", "height" : "300px", "display" : "none"});
        $(divContentElement).html("");
    }

};

rekap_showNewWindow = function(no, idUjianSerta)
{
    newWindow("rekap.hasil.php?ShowInfo=1&idujianserta=" + idUjianSerta,
        'RekapHasilUjian', '950','700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};