var ifse_DivList = ['ifse_notes_content', 'ifse_galeri_content', 'ifse_video_content'];
var ifse_MadingPanel = $("#content-pane-j");
var ifse_LastListScrollPos = 0;
var ifse_ActiveDept = "";

ifse_SetVisible = function(divname) {
	
	for(var i = 0; i < ifse_DivList.length; i++)
	{
		if (ifse_DivList[i] == divname)
			$("#" + ifse_DivList[i]).css('visibility', 'visible');
		else
			$("#" + ifse_DivList[i]).css('visibility', 'hidden');
	}
}

ifse_ShowNotes = function() {
	
	ifse_SetVisible('ifse_notes_content');
	
	var currdept = $("#ifse_departemen").val();
	if (currdept != ifse_ActiveDept)
	{
		$('#ifse_notes_content').attr('src', 'infosekolah/notes.php');
		ifse_ActiveDept = currdept;
	}
	
}

ifse_ShowGaleri = function(src) {
	
	ifse_SetVisible('ifse_galeri_content');
	
	var currdept = $("#ifse_departemen").val();
	if (currdept != ifse_ActiveDept)
	{
		$('#ifse_galeri_content').attr('src', 'infosekolah/galeri.php');
		ifse_ActiveDept = currdept;
	}
	
}

ifse_ShowVideo = function() {
	
	ifse_SetVisible('ifse_video_content');
	
	var currdept = $("#ifse_departemen").val();
	if (currdept != ifse_ActiveDept)
	{
		$('#ifse_video_content').attr('src', 'infosekolah/video.php');
		ifse_ActiveDept = currdept;
	}
	
}

ifse_ResizeIFrame = function() {
	
	var docHeight = $(document).height();
	var iframeHeight = docHeight - 220;
	
	$('#ifse_notes_content').css('height', iframeHeight);
	$('#ifse_galeri_content').css('height', iframeHeight);
	$('#ifse_video_content').css('height', iframeHeight);
}

ifse_InitAllContent = function() {
	
	$('#ifse_notes_content').attr('src', 'infosekolah/notes.php');
	$('#ifse_galeri_content').attr('src', 'infosekolah/galeri.php');
	$('#ifse_video_content').attr('src', 'infosekolah/video.php');
	
}

ifse_InitInfoSekolah = function() {
	
	ifse_ActiveDept = $("#ifse_departemen").val();
	ifse_ResizeIFrame();
	ifse_InitAllContent();
	
}

// ON WINDOW RESIZED
$(window).resize(function() {
	
    ifse_ResizeIFrame();
	
});
