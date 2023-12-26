ka_ChangeDept = function() {
    var dept = $('#ka_departemen').val();
    $.ajax({
        url : 'jadkal/kalender.select.php?dept='+dept,
        type: 'get',
        success : function(html) {
            $('#ka_content').html('');
            $('#ka_selection').html(html);
        }
    })
}

ka_ChangeKalender = function() {
    $('#ka_content').html('');
}

ka_ShowContent = function() {
    var dept = $('#ka_departemen').val();
    var kalender = $('#ka_kalender').val();
    
    $.ajax({
        url : 'jadkal/kalender.content.php?kalender='+kalender,
        type: 'get',
        success : function(html) {
            $('#ka_content').html(html);
        }
    })
}

ka_GoToNextMonth = function () {	
	var kalender = document.getElementById('kalender').value;
	var bln = document.getElementById('nextbln').value;
	var thn = document.getElementById('nextthn').value;
	var prevbln = document.getElementById('nowbln').value;
	var prevthn = document.getElementById('nowthn').value;
	var last = document.getElementById('last').value;
	var next = document.getElementById('next').value;
   
   $.ajax({
        url : "jadkal/kalender.content.php?kalender="+kalender+"&bln="+bln+"&thn="+thn+"&next=1&prevbln="+prevbln+"&prevthn="+prevthn+"&last="+last,
        type: 'get',
        success : function(html) {
            $('#ka_content').html(html);
        }
    })
}

ka_GoToPrevMonth = function () {	
	var kalender = document.getElementById('kalender').value;
	var bln = document.getElementById('prevbln').value;
	var thn = document.getElementById('prevthn').value;
	var last = document.getElementById('last').value;	
	var next = document.getElementById('next').value;
	
   $.ajax({
        url : "jadkal/kalender.content.php?kalender="+kalender+"&bln="+bln+"&thn="+thn+"&next="+next+"&last="+last,
        type: 'get',
        success : function(html) {
            $('#ka_content').html(html);
        }
    })
}