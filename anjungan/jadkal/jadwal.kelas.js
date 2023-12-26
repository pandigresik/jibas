jk_ChangeDept = function() {
    var dept = $('#jk_departemen').val();
    $.ajax({
        url : 'jadkal/jadwal.kelas.select.php?dept='+dept,
        type: 'get',
        success : function(html) {
            $('#jk_content').html('');
            $('#jk_selection').html(html);
        }
    })
}

jk_ChangeTingkat = function() {
    var dept = $('#jk_departemen').val();
    var tingkat = $('#jk_tingkat').val();
    $.ajax({
        url : 'jadkal/jadwal.kelas.select.php?dept='+dept+'&tingkat='+tingkat,
        type: 'get',
        success : function(html) {
            $('#jk_content').html('');
            $('#jk_selection').html(html);
        }
    })
}

jk_ChangeKelas = function() {
    $('#jk_content').html('');
}

jk_ChangeJadwal = function() {
    $('#jk_content').html('');
}

jk_ShowContent = function() {
    var dept = $('#jk_departemen').val();
    var tingkat = $('#jk_tingkat').val();
    var kelas = $('#jk_kelas').val();
    var jadwal = $('#jk_jadwal').val();
    
    $.ajax({
        url : 'jadkal/jadwal.kelas.content.php?departemen='+dept+'&kelas='+kelas+'&info='+jadwal,
        type: 'get',
        success : function(html) {
            $('#jk_content').html(html);
        }
    })
}