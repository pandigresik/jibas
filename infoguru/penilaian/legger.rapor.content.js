function cetak_excel()
{
    var departemen = document.getElementById('departemen').value;
    var tingkat = document.getElementById('tingkat').value;
    var tahunajaran = document.getElementById('tahunajaran').value
    var semester = document.getElementById('semester').value
    var kelas = document.getElementById('kelas').value
    var pelajaran = document.getElementById('pelajaran').value

    var page = "legger.rapor.excel.php?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&kelas="+kelas+"&pelajaran="+pelajaran+"&tahunajaran="+tahunajaran;
    newWindow(page,'LeggerRaporExcel','120','150','resizable=1,scrollbars=1,status=0,toolbar=0');
}