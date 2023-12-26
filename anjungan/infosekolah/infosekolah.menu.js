var ifse_ActiveTab = 'notes';
var ifse_VideoFirstAccess = true;
var ifse_GalleryFirstAccess = true;

ifse_MenuNotesClicked = function() {
	
	if (ifse_ActiveTab == 'notes')
		return;
	
	$('#ifse_menu_notes_image').attr("src", "images/tab-aktif.png");
	$('#ifse_menu_galeri_image').attr("src", "images/tab-pasif.png");
	$('#ifse_menu_video_image').attr("src", "images/tab-pasif.png");
	
	ifse_ShowNotes();
	ifse_ActiveTab = 'notes';
	
}

ifse_MenuGaleriClicked = function() {
	
	if (ifse_ActiveTab == 'galeri')
		return;
	
	$('#ifse_menu_notes_image').attr("src", "images/tab-pasif.png");
	$('#ifse_menu_galeri_image').attr("src", "images/tab-aktif.png");
	$('#ifse_menu_video_image').attr("src", "images/tab-pasif.png");

	ifse_ShowGaleri();
	ifse_ActiveTab = 'galeri';
	
	if (ifse_GalleryFirstAccess)
	{
		top.frames['ifse_galeri_content'].gallst_GetGalleryList();
		ifse_GalleryFirstAccess = false;
	}

}

ifse_MenuVideoClicked = function() {
	
	if (ifse_ActiveTab == 'video')
		return;
	
	$('#ifse_menu_notes_image').attr("src", "images/tab-pasif.png");
	$('#ifse_menu_galeri_image').attr("src", "images/tab-pasif.png");
	$('#ifse_menu_video_image').attr("src", "images/tab-aktif.png");
	
	ifse_ShowVideo();
	ifse_ActiveTab = 'video';
	
	if (ifse_VideoFirstAccess)
	{
		top.frames['ifse_video_content'].vidlst_GetVideoList();
		ifse_VideoFirstAccess = false;
	}
	
}

ifse_DepartemenChanged = function() {
	
	if (ifse_ActiveTab == "notes")
		$('#ifse_notes_content').attr('src', 'infosekolah/notes.php');
	else if (ifse_ActiveTab == "notes")
		$('#ifse_galeri_content').attr('src', 'infosekolah/galeri.php');
	else if (ifse_ActiveTab == "notes")
		$('#ifse_video_content').attr('src', 'infosekolah/video.php');
		
}