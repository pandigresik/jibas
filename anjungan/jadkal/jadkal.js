jadkal_ShowKalender = function() {
	
	$.ajax({
        url : 'jadkal/kalender.php',
        type: 'get',
        success : function(html) {
            $('#jadkal_content').html(html);
        }
    })
	
}

jadkal_ShowJadwalKelas = function() {
	
	$.ajax({
        url : 'jadkal/jadwal.kelas.php',
        type: 'get',
        success : function(html) {
            $('#jadkal_content').html(html);
        }
    })
	
}

jadkal_ShowJadwalGuru = function() {
	
	$.ajax({
        url : 'jadkal/jadwal.guru.php',
        type: 'get',
        success : function(html) {
            $('#jadkal_content').html(html);
        }
    })
	
}