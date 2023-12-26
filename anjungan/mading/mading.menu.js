var mad_ActiveTab = 'notes';
var mad_VideoFirstAccess = true;
var mad_GalleryFirstAccess = true;

mad_MenuNotesClicked = function() {
	
	if (mad_ActiveTab == 'notes')
		return;
	
	$('#mad_menu_notes_image').attr("src", "images/tab-aktif.png");
	$('#mad_menu_galeri_image').attr("src", "images/tab-pasif.png");
	$('#mad_menu_video_image').attr("src", "images/tab-pasif.png");
	
	mad_ShowNotes();
	mad_ActiveTab = 'notes';
	
}

mad_MenuGaleriClicked = function() {
	
	if (mad_ActiveTab == 'galeri')
		return;
	
	$('#mad_menu_notes_image').attr("src", "images/tab-pasif.png");
	$('#mad_menu_galeri_image').attr("src", "images/tab-aktif.png");
	$('#mad_menu_video_image').attr("src", "images/tab-pasif.png");

	mad_ShowGaleri();
	mad_ActiveTab = 'galeri';
	
	if (mad_GalleryFirstAccess)
	{
		top.frames['mad_galeri_content'].gallst_GetGalleryList();
		mad_GalleryFirstAccess = false;
	}

}

mad_MenuVideoClicked = function() {
	
	if (mad_ActiveTab == 'video')
		return;
	
	$('#mad_menu_notes_image').attr("src", "images/tab-pasif.png");
	$('#mad_menu_galeri_image').attr("src", "images/tab-pasif.png");
	$('#mad_menu_video_image').attr("src", "images/tab-aktif.png");
	
	mad_ShowVideo();
	mad_ActiveTab = 'video';
	
	if (mad_VideoFirstAccess)
	{
		top.frames['mad_video_content'].vidlst_GetVideoList();
		mad_VideoFirstAccess = false;
	}
	
}

mad_DepartemenChanged = function() {
	
	if (mad_ActiveTab == "notes")
		$('#mad_notes_content').attr('src', 'mading/notes.php');
	else if (mad_ActiveTab == "notes")
		$('#mad_galeri_content').attr('src', 'mading/galeri.php');
	else if (mad_ActiveTab == "notes")
		$('#mad_video_content').attr('src', 'mading/video.php');
		
}