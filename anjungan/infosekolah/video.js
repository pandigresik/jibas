var $vid_jQueryParent = parent.jQuery;

var vid_DivList = ["vidinp_content", "vidlst_content", "vidvw_content", "vided_content", "vididx_content"];
var vid_VideoFrame = $vid_jQueryParent('#ifse_video_content');
var vid_LastGalleryListScrollPos = 0;
var vid_LastGalleryIndexScrollPos = 0;

vid_SetVisible = function(divname) {
	
	for(var i = 0; i < vid_DivList.length; i++)
	{
		if (vid_DivList[i] == divname)
			$("#" + vid_DivList[i]).css('visibility', 'visible');
		else
			$("#" + vid_DivList[i]).css('visibility', 'hidden');
	}
}

vid_ShowVideoList_LastPos = function() {
	
	vid_SetVisible('vidlst_content');
	vid_VideoFrame.contents().scrollTop(vid_LastGalleryListScrollPos);
	
}

vid_ShowVideoList_TopPos = function() {
	
	vid_SetVisible('vidlst_content');
	vid_VideoFrame.contents().scrollTop(0);
	vid_LastGalleryListScrollPos = 0;
	
}

vid_ShowVideoView = function() {
	
	vid_LastGalleryIndexScrollPos = vid_VideoFrame.contents().scrollTop();
	vid_LastGalleryListScrollPos = vid_VideoFrame.contents().scrollTop();
	vid_SetVisible('vidvw_content');
	
}

vid_ShowVideoInput = function() {
	
	vid_SetVisible('vidinp_content');
	
}

vid_ShowVideoEdit = function() {
	
	vid_SetVisible('vided_content');
	
}

vid_ShowVideoIndex_TopPos = function() {
	
	vid_SetVisible('vididx_content');
	vid_VideoFrame.contents().scrollTop(0);
	vid_LastGalleryListScrollPos = 0;
	
}

vid_ShowVideoIndex_LastPos = function() {
	
	vid_SetVisible('vididx_content');
	vid_VideoFrame.contents().scrollTop(vid_LastGalleryListScrollPos);
	
}

vid_ScrollTopMadingPanel = function() {
	
	vid_VideoFrame.contents().scrollTop(0);
	
}

vid_GetDepartemen = function() {
	
	return parent.document.getElementById('ifse_departemen').value;

}

$( document ).ready(function() {

	//vidlst_GetVideoList();	
	
});