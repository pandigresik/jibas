var mad_DivList = ['mad_notes_content', 'mad_galeri_content', 'mad_video_content'];
var mad_MadingPanel = $("#content-pane-j");
var mad_LastListScrollPos = 0;
var mad_ActiveDept = "";

mad_SetVisible = function(divname) {
	
	for(var i = 0; i < mad_DivList.length; i++)
	{
		if (mad_DivList[i] == divname)
			$("#" + mad_DivList[i]).css('visibility', 'visible');
		else
			$("#" + mad_DivList[i]).css('visibility', 'hidden');
	}
}

mad_ShowNotes = function() {
	
	mad_SetVisible('mad_notes_content');
	
	var currdept = $("#mad_departemen").val();
	if (currdept != mad_ActiveDept)
	{
		$('#mad_notes_content').attr('src', 'mading/notes.php');
		mad_ActiveDept = currdept;
	}
	
}

mad_ShowGaleri = function(src) {
	
	mad_SetVisible('mad_galeri_content');
	
	var currdept = $("#mad_departemen").val();
	if (currdept != mad_ActiveDept)
	{
		$('#mad_galeri_content').attr('src', 'mading/galeri.php');
		mad_ActiveDept = currdept;
	}
	
}

mad_ShowVideo = function() {
	
	mad_SetVisible('mad_video_content');
	
	var currdept = $("#mad_departemen").val();
	if (currdept != mad_ActiveDept)
	{
		$('#mad_video_content').attr('src', 'mading/video.php');
		mad_ActiveDept = currdept;
	}
	
}

mad_ResizeIFrame = function() {
	
	var docHeight = $(document).height();
	var iframeHeight = docHeight - 220;
	
	$('#mad_notes_content').css('height', iframeHeight);
	$('#mad_galeri_content').css('height', iframeHeight);
	$('#mad_video_content').css('height', iframeHeight);
}

mad_InitAllContent = function() {
	
	$('#mad_notes_content').attr('src', 'mading/notes.php');
	$('#mad_galeri_content').attr('src', 'mading/galeri.php');
	$('#mad_video_content').attr('src', 'mading/video.php');
	
}

mad_InitMading = function() {
	
	mad_ActiveDept = $("#mad_departemen").val();
	mad_ResizeIFrame();
	mad_InitAllContent();
	
}

// ON WINDOW RESIZED
$(window).resize(function() {
	
    mad_ResizeIFrame();
	
});
