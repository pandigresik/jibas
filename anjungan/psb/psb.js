// ON STARTUP
$(document).ready(function () {
        
    
});

// ON WINDOW RESIZED
$(window).resize(function() {
    
	psb_ResizeDaftarDiv();
	psb_ResizeStatusDiv();
	
});

psb_ResizeDaftarDiv = function() {
	
	var docHeight = $(document).height();
	var psbDaftarHeight = docHeight - 240 - 100;
	
	$("#psb_divDaftar").height(psbDaftarHeight);
	
}

psb_ResizeStatusDiv = function() {
	
	var docHeight = $(document).height();
	var psbStatusHeight = docHeight - 240 - 90;
	
	$("#psb_divStatus").height(psbStatusHeight);
	
}

psb_InputPsbClicked = function() {
	
	$.ajax({
        url : 'psb/psb.input.php',
        type: 'get',
        success : function(html) {
            $('#psb_content').html(html);
        }
    })
	
}

psb_DaftarPsbClicked = function() {
	
	setTimeout(psb_ResizeDaftarDiv, 100);
	
	$.ajax({
        url : 'psb/psb.daftar.php',
        type: 'get',
        success : function(html) {
            $('#psb_content').html(html);
        }
    })
	
}

psb_StatusPsbClicked = function() {
	
	setTimeout(psb_ResizeStatusDiv, 100);
	
	$.ajax({
        url : 'psb/psb.status.php',
        type: 'get',
        success : function(html) {
            $('#psb_content').html(html);
        }
    })
	
}