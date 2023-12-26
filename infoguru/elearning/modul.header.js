function tampilModul()
{
    if ($("#departemen").has('option').length === 0)
        return;

    if ($("#pelajaran").has('option').length === 0)
        return;

    if ($("#channel").has('option').length === 0)
        return;

    var idChannel = $("#channel").val();
    var departemen = $("#departemen").val();
    var idPelajaran = $("#pelajaran").val();

    parent.content.location.href = "modul.content.php?departemen="+departemen+"&idPelajaran="+idPelajaran+"&idChannel=" + idChannel;
}