tampilNotes = function()
{
    if ($("#departemen").has('option').length === 0)
        return;

    if ($("#pelajaran").has('option').length === 0)
        return;

    if ($("#channel").has('option').length === 0)
        return;

    var date1 = $("#tahun1").val() + "-" + $("#bulan1").val() + "-" + $("#tanggal1").val();
    var date2 = $("#tahun2").val() + "-" + $("#bulan2").val() + "-" + $("#tanggal2").val();

    var idChannel = $("#channel").val();
    var departemen = $("#departemen").val();
    var idPelajaran = $("#pelajaran").val();

    parent.content.location.href = "notes.content.php?departemen="+departemen+"&idPelajaran="+idPelajaran+"&idChannel=" + idChannel+ "&date1=" + date1 + "&date2=" + date2;
};