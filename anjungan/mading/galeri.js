var $gal_jQueryParent = parent.jQuery;

var gal_DivList = ["galinp_content", "gallst_content", "galvw_content", "galed_content", "galidx_content"];
var gal_NotesFrame = $gal_jQueryParent('#mad_galeri_content');
var gal_LastGalleryListScrollPos = 0;
var gal_LastGalleryIndexScrollPos = 0;

gal_SetVisible = function(divname) {
	
	for(var i = 0; i < gal_DivList.length; i++)
	{
		if (gal_DivList[i] == divname)
			$("#" + gal_DivList[i]).css('visibility', 'visible');
		else
			$("#" + gal_DivList[i]).css('visibility', 'hidden');
	}
}

gal_ShowGalleryList_LastPos = function() {
	
	gal_SetVisible('gallst_content');
	gal_NotesFrame.contents().scrollTop(gal_LastGalleryListScrollPos);
	
}

gal_ShowGalleryList_TopPos = function() {
	
	gal_SetVisible('gallst_content');
	gal_NotesFrame.contents().scrollTop(0);
	gal_LastGalleryListScrollPos = 0;
	
}

gal_ShowGalleryView = function() {
	
	gal_LastGalleryIndexScrollPos = gal_NotesFrame.contents().scrollTop();
	gal_LastGalleryListScrollPos = gal_NotesFrame.contents().scrollTop();
	gal_SetVisible('galvw_content');
	
}

gal_ShowGalleryInput = function() {
	
	gal_SetVisible('galinp_content');
	
}

gal_ShowGalleryEdit = function() {
	
	gal_SetVisible('galed_content');
	
}

gal_ShowGalleryIndex_TopPos = function() {
	
	gal_SetVisible('galidx_content');
	gal_NotesFrame.contents().scrollTop(0);
	gal_LastGalleryListScrollPos = 0;
	
}

gal_ShowGalleryIndex_LastPos = function() {
	
	gal_SetVisible('galidx_content');
	gal_NotesFrame.contents().scrollTop(gal_LastGalleryListScrollPos);
	
}

gal_ScrollTopMadingPanel = function() {
	
	gal_NotesFrame.contents().scrollTop(0);
	
}

gal_GetDepartemen = function() {
	
	return parent.document.getElementById('mad_departemen').value;

}

$( document ).ready(function() {

	//gallst_GetGalleryList();
	
});