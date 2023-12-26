cbe_getPelajaran = function()
{
    var bulan = $("#cbe_cbBulan").val();
    var tahun = $("#cbe_cbTahun").val();
    var jenis = $("#cbe_cbJenisUjian").val();

    $("#cbe_divRekapUjian").html("");
    $("#cbe_spCbPelajaran").html("memuat ...");

    $.ajax({
        url: "infosiswa/infosiswa.cbe.ajax.php",
        data: "op=getpelajaran&bulan="+bulan+"&tahun="+tahun+"&jenis="+jenis,
        success: function(html)
        {
            $("#cbe_spCbPelajaran").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

cbe_changePelajaran = function()
{
    $("#cbe_divRekapUjian").html("");
};

cbe_showRekapUjian = function()
{
    if ($("#cbe_cbPelajaran").length === 0)
        return;

    if ($("#cbe_cbPelajaran option").length === 0)
    {
        alert("Tidak ada data pelajaran!");
        return;
    }

    var bulan = $("#cbe_cbBulan").val();
    var tahun = $("#cbe_cbTahun").val();
    var jenis = $("#cbe_cbJenisUjian").val();
    var idpelajaran = $("#cbe_cbPelajaran").val();

    $("#cbe_divRekapUjian").html("memuat ...");

    $.ajax({
        url: "infosiswa/infosiswa.cbe.ajax.php",
        data: "op=gethasil&bulan="+bulan+"&tahun="+tahun+"&jenis="+jenis+"&idpelajaran="+idpelajaran,
        success: function(html)
        {
            $("#cbe_divRekapUjian").html(html);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });

};