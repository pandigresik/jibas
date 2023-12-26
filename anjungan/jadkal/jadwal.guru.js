jg_ChangeDept = function() {
    var dept = $('#jg_departemen').val();
    $.ajax({
        url : 'jadkal/jadwal.guru.select.php?dept='+dept,
        type: 'get',
        success : function(html) {
            $('#jg_content').html('');
            $('#jg_selection').html(html);
        }
    })
}

jg_ChangeJadwal = function() {
    var dept = $('#jg_departemen').val();
    var jadwal = $('#jg_jadwal').val();
    $.ajax({
        url : 'jadkal/jadwal.guru.select.php?dept='+dept+'&jadwal='+jadwal,
        type: 'get',
        success : function(html) {
            $('#jg_content').html('');
            $('#jg_selection').html(html);
        }
    })
}

jg_ChangeGuru = function() {
    $('#jg_content').html('');
}

jg_ShowContent = function() {
    var dept = $('#jg_departemen').val();
    var jadwal = $('#jg_jadwal').val();
    var tahunajaran = $('#jg_tahunajaran').val();
    var guru = $('#jg_guru').val();
    
    $.ajax({
        url : 'jadkal/jadwal.guru.content.php?departemen='+dept+'&info='+jadwal+'&nip='+guru+'&tahunajaran='+tahunajaran,
        type: 'get',
        success : function(html) {
            $('#jg_content').html(html);
        }
    })
}
