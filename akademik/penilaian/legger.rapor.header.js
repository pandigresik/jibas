function change_dept()
{
    var departemen = document.main.departemen.value;
    document.location.href = "legger.rapor.header.php?departemen="+departemen;
    parent.footer.location.href = "legger.rapor.blank.php";
}

function change_ta()
{
    var departemen = document.main.departemen.value;
    var tahunajaran = document.main.tahunajaran.value;
    document.location.href = "legger.rapor.header.php?departemen="+departemen+"&tahunajaran="+tahunajaran;
    parent.footer.location.href = "legger.rapor.blank.php";
}

function change_tingkat()
{
    var departemen = document.main.departemen.value;
    var tahunajaran = document.main.tahunajaran.value;
    var tingkat = document.main.tingkat.value;

    document.location.href = "legger.rapor.header.php?tingkat="+tingkat+"&departemen="+departemen+"&tahunajaran="+tahunajaran;
    parent.footer.location.href = "legger.rapor.blank.php";
}

function change_kelas()
{
    var departemen = document.main.departemen.value;
    var tahunajaran = document.main.tahunajaran.value;
    var tingkat = document.main.tingkat.value;
    var kelas = document.main.kelas.value;

    document.location.href = "legger.rapor.header.php?tingkat="+tingkat+"&kelas="+kelas+"&departemen="+departemen+"&tahunajaran="+tahunajaran;
    parent.footer.location.href = "legger.rapor.blank.php";
}

function change_semester()
{
    var departemen = document.main.departemen.value;
    var tahunajaran = document.main.tahunajaran.value;
    var tingkat = document.main.tingkat.value;
    var kelas = document.main.kelas.value;
    var semester = document.main.semester.value;

    document.location.href = "legger.rapor.header.php?tingkat="+tingkat+"&kelas="+kelas+"&departemen="+departemen+"&tahunajaran="+tahunajaran+"&semester="+semester;
    parent.footer.location.href = "legger.rapor.blank.php";
}

function change_pel()
{
    parent.footer.location.href = "legger.rapor.blank.php";
}

function show()
{
    var departemen = document.main.departemen.value;
    var tingkat = document.main.tingkat.value;
    var tahun = document.main.tahunajaran.value;
    var semester = document.main.semester.value;
    var kelas = document.main.kelas.value;
    var pelajaran = document.main.pelajaran.value;

    if(departemen.length == 0)
    {
        alert("Departemen tidak boleh kosong!");
        document.main.departemen.focus();
        return false;
    }
    else if(tingkat.length == 0)
    {
        alert("Tingkat tidak boleh kosong!");
        document.main.tingkat.focus();
        return false;
    }
    else if(tahun.length == 0)
    {
        alert("Tahun Ajaran tidak boleh kosong!");
        document.main.tahun.focus();
        return false;
    }
    else if(semester.length == 0)
    {
        alert("Semester tidak boleh kosong!");
        document.main.semester.focus();
        return false;
    }
    else if(kelas.length == 0)
    {
        alert("Kelas tidak boleh kosong!");
        document.main.kelas.focus();
        return false;
    }
    else if(pelajaran.length == 0)
    {
        alert("Pelajaran tidak boleh kosong!");
        document.main.pelajaran.focus();
        return false;
    }

    var page = pelajaran == 0 ?  "legger.rapor.content.all.php" : "legger.rapor.content.php";

    parent.footer.location.href = page + "?departemen="+departemen+"&tingkat="+tingkat+"&semester="+semester+"&kelas="+kelas+"&pelajaran="+pelajaran+"&tahunajaran="+tahun;

    return true;
}

function focusNext(elemName, evt)
{
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13)
    {
        document.getElementById(elemName).focus();
        if (elemName == 'tabel')
            show();
        return false;
    }
    return true;
}